<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\OtpRegistrasiMail;

class WebRegisterEventController extends Controller
{
    // ─────────────────────────────────────────────────
    // STEP 2 – Tampilkan form OTP
    // ─────────────────────────────────────────────────
    public function showOtp(Request $request)
    {
        $data = session('reg_pending');
        if (!$data) {
            return redirect()->route('register')->with('error', 'Sesi registrasi tidak ditemukan. Silakan ulangi.');
        }

        $set   = DB::table('app_setting')->first();
        $event = null;
        if (!empty($data['kode_event'])) {
            $event = DB::table('t_event as e')
                ->where('e.kode_event', $data['kode_event'])
                ->where('e.status_event', 'Y')
                ->first();
        }

        return view('web.register-event.otp', compact('set', 'event', 'data'));
    }

    // ─────────────────────────────────────────────────
    // STEP 2 – Verifikasi OTP
    // ─────────────────────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $data = session('reg_pending');
        if (!$data) {
            return back()->with('error', 'Sesi habis. Silakan registrasi ulang.');
        }

        if ($data['otp'] !== $request->otp) {
            return back()->with('error', 'Kode OTP tidak valid. Periksa email Anda.');
        }

        if (now()->gt($data['otp_expires_at'])) {
            return back()->with('error', 'Kode OTP sudah kedaluwarsa. Klik Kirim Ulang OTP.');
        }

        // Tandai OTP sudah terverifikasi
        session(['reg_pending' => array_merge($data, ['otp_verified' => true])]);

        // Jika tidak ada event, langsung buat akun dan selesai
        if (empty($data['kode_event'])) {
            $this->createUserAccount($data);
            session()->forget('reg_pending');
            return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan masuk.');
        }

        return redirect()->route('register-event.addon');
    }

    // ─────────────────────────────────────────────────
    // Kirim Ulang OTP
    // ─────────────────────────────────────────────────
    public function resendOtp(Request $request)
    {
        $data = session('reg_pending');
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan.']);
        }

        $otp     = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = now()->addMinutes(10);

        session(['reg_pending' => array_merge($data, [
            'otp'            => $otp,
            'otp_expires_at' => $expires,
        ])]);

        try {
            Mail::to($data['email'])->send(new OtpRegistrasiMail($otp, $data['nama']));
            return response()->json(['success' => true, 'message' => 'OTP baru telah dikirim ke ' . $data['email']]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim OTP: ' . $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────
    // STEP 3 – Tampilkan pilihan Add-on / Paket
    // ─────────────────────────────────────────────────
    public function showAddon(Request $request)
    {
        $data = session('reg_pending');
        if (!$data || empty($data['otp_verified'])) {
            return redirect()->route('register')->with('error', 'Akses tidak valid.');
        }

        $set   = DB::table('app_setting')->first();
        $event = DB::table('t_event as e')
            ->where('e.kode_event', $data['kode_event'])
            ->where('e.status_event', 'Y')
            ->first();

        // Paket yang tersedia untuk event ini
        // t_event_paket kolom: kode_paket, event_kode_paket, nama_paket, harga_paket, status_paket
        $paket = DB::table('t_event_paket')
            ->where('event_kode_paket', $data['kode_event'])
            ->where('status_paket', 'Y')
            ->get();

        return view('web.register-event.addon', compact('set', 'event', 'paket', 'data'));
    }

    // ─────────────────────────────────────────────────
    // STEP 3 – Simpan pilihan Add-on / Paket
    // ─────────────────────────────────────────────────
    public function saveAddon(Request $request)
    {
        $data = session('reg_pending');
        if (!$data || empty($data['otp_verified'])) {
            return redirect()->route('register')->with('error', 'Akses tidak valid.');
        }

        $selectedPaket = $request->input('selected_paket', []);

        // Hitung total harga dari paket yang dipilih
        $totalHarga = 0;
        if (!empty($selectedPaket)) {
            $paketData = DB::table('t_event_paket')
                ->whereIn('kode_paket', $selectedPaket)
                ->where('event_kode_paket', $data['kode_event'])
                ->get();

            foreach ($paketData as $p) {
                $totalHarga += (float) ($p->harga_paket ?? 0);
            }
        }

        session(['reg_pending' => array_merge($data, [
            'selected_paket' => $selectedPaket,
            'total_harga'    => $totalHarga,
        ])]);

        return redirect()->route('register-event.payment');
    }

    // ─────────────────────────────────────────────────
    // STEP 4 – Tampilkan Payment
    // ─────────────────────────────────────────────────
    public function showPayment(Request $request)
    {
        $data = session('reg_pending');
        if (!$data || empty($data['otp_verified'])) {
            return redirect()->route('register')->with('error', 'Akses tidak valid.');
        }

        $set   = DB::table('app_setting')->first();
        $event = DB::table('t_event as e')
            ->where('e.kode_event', $data['kode_event'])
            ->where('e.status_event', 'Y')
            ->first();

        $selectedPaket = collect();
        if (!empty($data['selected_paket'])) {
            $selectedPaket = DB::table('t_event_paket')
                ->whereIn('kode_paket', $data['selected_paket'])
                ->get();
        }

        // Midtrans Snap Token
        $snapToken      = null;
        $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();

        if ($midtransConfig && ($data['total_harga'] ?? 0) > 0) {
            \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
            \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $orderId = 'REG-' . strtoupper(Str::random(8)) . '-' . time();
            session(['reg_pending' => array_merge(session('reg_pending'), ['order_id' => $orderId])]);

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $data['total_harga'],
                ],
                'customer_details' => [
                    'first_name' => $data['nama'],
                    'email'      => $data['email'],
                    'phone'      => $data['no_hp'] ?? '',
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                session(['reg_pending' => array_merge(session('reg_pending'), ['snap_token' => $snapToken])]);
            } catch (\Exception $e) {
                // Lanjutkan tanpa Snap — tampilan view akan handle fallback
            }
        }

        return view('web.register-event.payment', compact('set', 'event', 'selectedPaket', 'data', 'snapToken', 'midtransConfig'));
    }

    // ─────────────────────────────────────────────────
    // STEP 4 – Proses Payment gratis / manual
    // ─────────────────────────────────────────────────
    public function processPayment(Request $request)
    {
        $data = session('reg_pending');
        if (!$data || empty($data['otp_verified'])) {
            return redirect()->route('register')->with('error', 'Akses tidak valid.');
        }

        $userId = $this->createUserAccount($data);
        $this->enrollEvent($data, $userId);

        session()->forget('reg_pending');
        return redirect()->route('register-event.success');
    }

    // ─────────────────────────────────────────────────
    // Midtrans Notification Callback (server-to-server)
    // ─────────────────────────────────────────────────
    public function midtransCallback(Request $request)
    {
        // Callback dari Midtrans tidak punya session — gunakan order_id dari payload
        $transactionStatus = $request->input('transaction_status');
        $orderId           = $request->input('order_id');
        $fraudStatus       = $request->input('fraud_status');

        $isPaid = in_array($transactionStatus, ['capture', 'settlement'])
            && ($fraudStatus === 'accept' || $fraudStatus === null);

        if ($isPaid) {
            // Update payment_status di t_event_registrasi berdasarkan order_id
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update([
                    'payment_status' => 'PAID',
                    'status_registrasi' => 'A',
                    'paid_at'        => now(),
                    'updated_at'     => now(),
                ]);

            return response()->json(['status' => 'success']);
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update([
                    'payment_status' => 'FAILED',
                    'updated_at'     => now(),
                ]);
        }

        return response()->json(['status' => 'pending', 'transaction_status' => $transactionStatus]);
    }

    // ─────────────────────────────────────────────────
    // Midtrans Finish Redirect (dari browser user)
    // ─────────────────────────────────────────────────
    public function midtransFinish(Request $request)
    {
        $data = session('reg_pending');

        if ($data && !empty($data['otp_verified'])) {
            $userId = $this->createUserAccount($data);
            $this->enrollEvent($data, $userId, $request->input('order_id'), 'PAID');
            session()->forget('reg_pending');
        }

        return redirect()->route('register-event.success');
    }

    // ─────────────────────────────────────────────────
    // Halaman Sukses
    // ─────────────────────────────────────────────────
    public function success(Request $request)
    {
        $set = DB::table('app_setting')->first();
        return view('web.register-event.success', compact('set'));
    }

    // ─────────────────────────────────────────────────
    // Helper: Buat akun user dari data sesi
    // ─────────────────────────────────────────────────
    private function createUserAccount(array $data): int
    {
        // Cek jika email sudah terdaftar
        $existing = DB::table('app_user')->where('email_user', $data['email'])->first();
        if ($existing) {
            return (int) $existing->id_user;
        }

        return (int) DB::table('app_user')->insertGetId([
            'nama_user'             => $data['nama'],
            'email_user'            => $data['email'],
            'no_hp_user'            => $data['no_hp']           ?? null,
            'organisasi_user'       => $data['organisasi']       ?? null,
            'tipe_organisasi_user'  => $data['tipe_organisasi']  ?? null,
            'jabatan_user'          => $data['jabatan']          ?? null,
            'identitas_user'        => $data['no_identitas']     ?? null,
            'password_user'         => Hash::make($data['password']),
            'status_user'           => 'Y',
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }

    // ─────────────────────────────────────────────────
    // Helper: Enroll event + simpan paket
    // ─────────────────────────────────────────────────
    private function enrollEvent(array $data, int $userId, ?string $orderId = null, string $paymentStatus = 'UNPAID'): void
    {
        $kodeRegistrasi = 'REG' . date('ymdHis') . strtoupper(Str::random(4));
        $usedOrderId    = $orderId ?? ($data['order_id'] ?? null);
        $totalHarga     = (float) ($data['total_harga'] ?? 0);

        // Jika total 0, langsung approved
        if ($totalHarga <= 0) {
            $paymentStatus = 'FREE';
        }

        // Ambil kode_paket pertama jika ada
        $kodePaket = !empty($data['selected_paket']) ? $data['selected_paket'][0] : null;

        DB::table('t_event_registrasi')->insert([
            'kode_registrasi'       => $kodeRegistrasi,
            'kode_event'            => $data['kode_event'],
            'id_user'               => $userId,
            'nama_peserta'          => $data['nama'],
            'email_peserta'         => $data['email'],
            'instansi_peserta'      => $data['organisasi']  ?? null,
            'no_hp_peserta'         => $data['no_hp']       ?? null,
            'kode_paket'            => $kodePaket,
            'total_bayar'           => $totalHarga,
            'midtrans_order_id'     => $usedOrderId,
            'payment_status'        => $paymentStatus,
            'status_registrasi'     => ($paymentStatus === 'PAID' || $paymentStatus === 'FREE') ? 'A' : 'P',
            'confirmed_at'          => ($paymentStatus === 'PAID' || $paymentStatus === 'FREE') ? now() : null,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        // Simpan semua paket yang dipilih ke t_event_addon
        if (!empty($data['selected_paket'])) {
            foreach ($data['selected_paket'] as $kPaket) {
                $paket = DB::table('t_event_paket')->where('kode_paket', $kPaket)->first();
                if (!$paket) continue;

                DB::table('t_event_addon')->insert([
                    'id_user'       => $userId,
                    'kode_event'    => $data['kode_event'],
                    'kode_registrasi' => $kodeRegistrasi,
                    'kode_paket'    => $kPaket,
                    'nama_addon'    => $paket->nama_paket ?? $kPaket,
                    'harga_addon'   => (float) ($paket->harga_paket ?? 0),
                    'qty'           => 1,
                    'subtotal'      => (float) ($paket->harga_paket ?? 0),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
