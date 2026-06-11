<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MidtransWebhookController extends Controller
{
    /**
     * SANDBOX NOTE:
     * Di sandbox, transaksi kartu kredit sering mendapat status "deny" karena
     * tidak ada proses approval manual. Status "deny", "cancel", dan "expire"
     * di-treat sebagai PAID agar flow bisa ditest.
     * Set ke false di production.
     */
    private const SANDBOX_TREAT_DENY_AS_PAID = true;

    // ── Helper: ambil config Midtrans aktif ───────────────────────────────────
    private function getMidtransConfig(): ?object
    {
        return DB::table('app_midtrans_config')
            ->where('id_midtrans', 1)
            ->where('is_active', 'Y')
            ->first();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Webhook Handler (POST /midtrans/webhook)
    // Dipanggil oleh server Midtrans, bukan browser user
    // ─────────────────────────────────────────────────────────────────────────
    public function handle(Request $request)
    {
        Log::info('[Midtrans Webhook] Incoming notification', $request->all());

        $midtransConfig = $this->getMidtransConfig();

        if (!$midtransConfig) {
            Log::warning('[Midtrans Webhook] No active Midtrans config found.');
            return response()->json(['message' => 'No active config'], 500);
        }

        // ── Ambil payload ─────────────────────────────────────────────────────
        try {
            \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
            \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);

            $notif       = new \Midtrans\Notification();
            $txStatus    = $notif->transaction_status;
            $orderId     = $notif->order_id;
            $fraudStatus = $notif->fraud_status ?? null;
            $statusCode  = $notif->status_code  ?? null;
            $grossAmount = $notif->gross_amount  ?? null;
        } catch (\Exception $e) {
            Log::warning('[Midtrans Webhook] Midtrans\\Notification() failed, using raw request: ' . $e->getMessage());
            $txStatus    = $request->input('transaction_status');
            $orderId     = $request->input('order_id');
            $fraudStatus = $request->input('fraud_status');
            $statusCode  = $request->input('status_code');
            $grossAmount = $request->input('gross_amount');
        }

        // ── Verifikasi signature key ──────────────────────────────────────────
        $incomingSignature = $request->input('signature_key');
        if ($incomingSignature) {
            $expectedSignature = hash('sha512',
                $orderId . $statusCode . $grossAmount . $midtransConfig->server_key
            );
            if ($incomingSignature !== $expectedSignature) {
                Log::warning('[Midtrans Webhook] Invalid signature for order: ' . $orderId);
                return response()->json(['message' => 'Invalid signature'], 403);
            }
        }

        Log::info("[Midtrans Webhook] order_id={$orderId} tx_status={$txStatus} fraud={$fraudStatus}");

        // ── Cari registrasi berdasarkan order_id ──────────────────────────────
        $reg = DB::table('t_event_registrasi')
            ->where('midtrans_order_id', $orderId)
            ->first();

        if (!$reg) {
            Log::info('[Midtrans Webhook] Order not found in t_event_registrasi, skipping: ' . $orderId);
            return response()->json(['message' => 'order_not_found'], 404);
        }

        // ── Tentukan apakah transaksi dianggap PAID ───────────────────────────
        $isSandbox = !(bool) ($midtransConfig->is_production ?? false);

        $isPaid = in_array($txStatus, ['capture', 'settlement'])
            && ($fraudStatus === 'accept' || $fraudStatus === null || $fraudStatus === '');

        if (!$isPaid && $isSandbox && self::SANDBOX_TREAT_DENY_AS_PAID) {
            if (in_array($txStatus, ['deny', 'cancel', 'expire'])) {
                Log::info("[Midtrans Webhook] SANDBOX MODE: treating '{$txStatus}' as PAID for order: {$orderId}");
                $isPaid = true;
            }
        }

        // ── Proses berdasarkan status ─────────────────────────────────────────
        if ($isPaid && $reg->payment_status !== 'PAID') {
            $this->processPaidRegistration($reg, $orderId);
            Log::info('[Midtrans Webhook] Registration PAID processed for order: ' . $orderId);
            return response()->json(['status' => 'success', 'message' => 'Payment processed']);
        }

        if ($isPaid && $reg->payment_status === 'PAID') {
            Log::info('[Midtrans Webhook] Already PAID, skipping: ' . $orderId);
            return response()->json(['status' => 'already_paid']);
        }

        if (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update(['payment_status' => 'FAILED', 'updated_at' => now()]);
            Log::info('[Midtrans Webhook] Payment FAILED for order: ' . $orderId . ' status: ' . $txStatus);
            return response()->json(['status' => 'failed', 'transaction_status' => $txStatus]);
        }

        return response()->json(['status' => 'pending', 'transaction_status' => $txStatus]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Finish Redirect URL (GET /payment/success)
    // Dipanggil oleh browser user setelah popup Midtrans selesai
    // Midtrans append: ?order_id=...&status_code=...&transaction_status=...
    // ─────────────────────────────────────────────────────────────────────────
    public function paymentSuccess(Request $request)
    {
        $orderId  = $request->query('order_id');
        $txStatus = $request->query('transaction_status');

        $reg       = null;
        $isSandbox = false;

        $midtransConfig = $this->getMidtransConfig();
        if ($midtransConfig) {
            $isSandbox = !(bool) ($midtransConfig->is_production ?? false);
        }

        if ($orderId) {
            $reg = DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->first();

            // Jika webhook belum memproses (race condition), proses di sini
            if ($reg && $reg->payment_status !== 'PAID') {
                $shouldPay = in_array($txStatus, ['capture', 'settlement']);

                if (!$shouldPay && $isSandbox && self::SANDBOX_TREAT_DENY_AS_PAID) {
                    if (in_array($txStatus, ['deny', 'cancel', 'expire'])) {
                        $shouldPay = true;
                        Log::info("[Payment Success Page] SANDBOX: treating '{$txStatus}' as PAID for order: {$orderId}");
                    }
                }

                if ($shouldPay) {
                    $this->processPaidRegistration($reg, $orderId);
                    $reg = DB::table('t_event_registrasi')
                        ->where('midtrans_order_id', $orderId)
                        ->first();
                }
            }
        }

        return view('web.register-event.payment-success', compact('reg', 'isSandbox'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: Proses registrasi setelah konfirmasi PAID
    // ─────────────────────────────────────────────────────────────────────────
    private function processPaidRegistration(object $reg, string $orderId): void
    {
        $userId = $this->createOrGetUser($reg);

        DB::table('t_event_registrasi')
            ->where('midtrans_order_id', $orderId)
            ->update([
                'id_user'           => $userId,
                'payment_status'    => 'PAID',
                'status_registrasi' => 'A',
                'paid_at'           => now(),
                'confirmed_at'      => now(),
                'updated_at'        => now(),
            ]);

        DB::table('app_user')
            ->where('id_user', $userId)
            ->update(['status_user' => 'Y', 'updated_at' => now()]);

        if (!empty($reg->kode_paket)) {
            $paket = DB::table('t_event_paket')->where('kode_paket', $reg->kode_paket)->first();
            if ($paket) {
                $addonExists = DB::table('t_event_addon')
                    ->where('kode_registrasi', $reg->kode_registrasi)
                    ->where('kode_paket', $reg->kode_paket)
                    ->exists();

                if (!$addonExists) {
                    DB::table('t_event_addon')->insert([
                        'id_user'         => $userId,
                        'kode_event'      => $reg->kode_event,
                        'kode_registrasi' => $reg->kode_registrasi,
                        'kode_paket'      => $reg->kode_paket,
                        'nama_addon'      => $paket->judul_paket ?? $reg->kode_paket,
                        'harga_addon'     => (float) ($paket->harga_paket ?? 0),
                        'qty'             => 1,
                        'subtotal'        => (float) ($paket->harga_paket ?? 0),
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: Buat atau ambil user dari data registrasi
    // ─────────────────────────────────────────────────────────────────────────
    private function createOrGetUser(object $reg): int
    {
        $existing = DB::table('app_user')->where('email_user', $reg->email_peserta)->first();
        if ($existing) {
            return (int) $existing->id_user;
        }

        return (int) DB::table('app_user')->insertGetId([
            'nama_user'       => $reg->nama_peserta,
            'email_user'      => $reg->email_peserta,
            'no_hp_user'      => $reg->no_hp_peserta    ?? null,
            'organisasi_user' => $reg->instansi_peserta ?? null,
            'password_user'   => Hash::make(Str::random(12)),
            'status_user'     => 'Y',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}
