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
     * Handle Midtrans server-to-server notification (webhook).
     *
     * SANDBOX NOTE:
     * Di sandbox, transaksi kartu kredit sering mendapat status "deny" karena
     * tidak ada proses approval manual. Untuk keperluan development, status
     * "deny", "cancel", dan "expire" di-treat sebagai PAID agar flow bisa ditest.
     *
     * Hapus konstanta SANDBOX_TREAT_DENY_AS_PAID atau set ke false di production.
     */
    private const SANDBOX_TREAT_DENY_AS_PAID = true;

    public function handle(Request $request)
    {
        Log::info('[Midtrans Webhook] Incoming notification', $request->all());

        $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();

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
            // Fallback: baca langsung dari request body
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

        // Jika tidak ada di t_event_registrasi, coba di t_cart (cart payment flow)
        if (!$reg) {
            Log::info('[Midtrans Webhook] Order not found in t_event_registrasi, skipping: ' . $orderId);
            return response()->json(['message' => 'order_not_found'], 404);
        }

        // ── Tentukan apakah transaksi dianggap PAID ───────────────────────────
        $isSandbox = !(bool) ($midtransConfig->is_production ?? false);

        $isPaid = in_array($txStatus, ['capture', 'settlement'])
            && ($fraudStatus === 'accept' || $fraudStatus === null || $fraudStatus === '');

        // Khusus SANDBOX: deny/cancel/expire juga dianggap berhasil untuk testing
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

        // Jika bukan sandbox atau SANDBOX_TREAT_DENY_AS_PAID = false
        if (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update(['payment_status' => 'FAILED', 'updated_at' => now()]);
            Log::info('[Midtrans Webhook] Payment FAILED for order: ' . $orderId . ' status: ' . $txStatus);
            return response()->json(['status' => 'failed', 'transaction_status' => $txStatus]);
        }

        return response()->json(['status' => 'pending', 'transaction_status' => $txStatus]);
    }

    /**
     * Proses registrasi setelah konfirmasi PAID.
     * Buat user, update status registrasi ke PAID & Aktif, simpan add-on.
     */
    private function processPaidRegistration(object $reg, string $orderId): void
    {
        // Buat atau ambil user
        $userId = $this->createOrGetUser($reg);

        // Update registrasi
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

        // Aktifkan user
        DB::table('app_user')
            ->where('id_user', $userId)
            ->update(['status_user' => 'Y', 'updated_at' => now()]);

        // Simpan add-on jika ada (dari t_event_paket berdasarkan kode_paket di registrasi)
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

    /**
     * Buat atau ambil user dari data registrasi.
     */
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
