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

        $set   = DB::table('t_setting')->first();
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

        $set   = DB::table('t_setting')->first();
        $event = DB::table('t_event as e')
            ->where('e.kode_event', $data['kode_event'])
            ->where('e.status_event', 'Y')
            ->first();

        $paket = DB::table('t_paket_event')
            ->where('event_kode_paket', $data['kode_event'])
            ->where('status_paket', 'Y')
            ->get();

        return view('web.register-event.addon', compact('set', 'event', 'paket', 'data'));
    }

    // ─────────────────────────────────────────────────
    // STEP 3 – Simpan pilihan Add-on
    // ─────────────────────────────────────────────────
    public function saveAddon(Request $request)
    {
        $data = session('reg_pending');
        if (!$data || empty($data['otp_verified'])) {
            return redirect()->route('register')->with('error', 'Akses tidak valid.');
        }

        $selectedPaket = $request->input('selected_paket', []);

        // Hitung total harga
        $totalHarga = 0;
        if (!empty($selectedPaket)) {
            $paketData = DB::table('t_paket_event')
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

        $set   = DB::table('t_setting')->first();
        $event = DB::table('t_event as e')
            ->where('e.kode_event', $data['kode_event'])
            ->where('e.status_event', 'Y')
            ->first();

        $selectedPaket = [];
        if (!empty($data['selected_paket'])) {
            $selectedPaket = DB::table('t_paket_event')
                ->whereIn('kode_paket', $data['selected_paket'])
                ->get();
        }

        // Midtrans Snap Token
        $snapToken  = null;
        $midtransConfig = DB::table('t_midtrans_config')->first();

        if ($midtransConfig && $data['total_harga'] > 0) {
            \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
            \Midtrans\Config::$isProduction = $midtransConfig->is_production == '1';
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $orderId = 'REG-' . strtoupper(Str::random(10)) . '-' . time();
            session(['reg_pending' => array_merge(session('reg_pending'), ['order_id' => $orderId])]);

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $data['total_harga'],
                ],
                'customer_details' => [
                    'first_name' => $data['nama'],
                    'email'      => $data['email'],
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                // Lanjutkan tanpa Snap; akan ditangani di view
            }
        }

        return view('web.register-event.payment', compact('set', 'event', 'selectedPaket', 'data', 'snapToken', 'midtransConfig'));
    }

    // ─────────────────────────────────────────────────
    // STEP 4 – Proses Payment (fallback non-Midtrans)
    // ─────────────────────────────────────────────────
    public function processPayment(Request $request)
    {
        $data = session('reg_pending');
        if (!$data || empty($data['otp_verified'])) {
            return redirect()->route('register')->with('error', 'Akses tidak valid.');
        }

        // Buat akun user
        $userId = $this->createUserAccount($data);

        // Enroll event
        $this->enrollEvent($data, $userId);

        session()->forget('reg_pending');
        return redirect()->route('register-event.success');
    }

    // ─────────────────────────────────────────────────
    // Midtrans Notification Callback
    // ─────────────────────────────────────────────────
    public function midtransCallback(Request $request)
    {
        $data = session('reg_pending');
        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'Session expired']);
        }

        $transactionStatus = $request->input('transaction_status');
        $orderId           = $request->input('order_id');

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $userId = $this->createUserAccount($data);
            $this->enrollEvent($data, $userId);
            session()->forget('reg_pending');
            return response()->json(['status' => 'success', 'redirect' => route('register-event.success')]);
        }

        return response()->json(['status' => 'pending', 'transaction_status' => $transactionStatus]);
    }

    // ─────────────────────────────────────────────────
    // Halaman Sukses
    // ─────────────────────────────────────────────────
    public function success(Request $request)
    {
        $set = DB::table('t_setting')->first();
        return view('web.register-event.success', compact('set'));
    }

    // ─────────────────────────────────────────────────
    // Helper: Buat akun user dari data sesi
    // ─────────────────────────────────────────────────
    private function createUserAccount(array $data): int
    {
        // Cek jika email sudah ada (registrasi ganda)
        $existing = DB::table('t_user')->where('email_user', $data['email'])->first();
        if ($existing) {
            return $existing->id_user;
        }

        return DB::table('t_user')->insertGetId([
            'nama_user'        => $data['nama'],
            'email_user'       => $data['email'],
            'no_hp_user'       => $data['no_hp']       ?? null,
            'organisasi_user'  => $data['organisasi']  ?? null,
            'jabatan_user'     => $data['jabatan']      ?? null,
            'password_user'    => Hash::make($data['password']),
            'status_user'      => 'Y',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    // ─────────────────────────────────────────────────
    // Helper: Enroll event + paket ke user
    // ─────────────────────────────────────────────────
    private function enrollEvent(array $data, int $userId): void
    {
        $kodeRegistrasi = 'REG' . date('ymd') . strtoupper(Str::random(6));

        $registrasiId = DB::table('t_registrasi_event')->insertGetId([
            'kode_registrasi'       => $kodeRegistrasi,
            'user_kode_registrasi'  => $userId,
            'event_kode_registrasi' => $data['kode_event'],
            'status_registrasi'     => 'Y',
            'total_pembayaran'      => $data['total_harga'] ?? 0,
            'order_id_payment'      => $data['order_id']    ?? null,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        // Simpan paket yang dipilih
        if (!empty($data['selected_paket'])) {
            foreach ($data['selected_paket'] as $kodePaket) {
                DB::table('t_registrasi_paket')->insert([
                    'registrasi_kode' => $registrasiId,
                    'paket_kode'      => $kodePaket,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }
}
