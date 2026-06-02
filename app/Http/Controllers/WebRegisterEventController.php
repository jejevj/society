<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
            $event = DB::table('t_event')
                ->where('kode_event', $data['kode_event'])
                ->where('status_event', 'Y')
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

        session(['reg_pending' => array_merge($data, ['otp_verified' => true])]);

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

        $set = DB::table('app_setting')->first();

        $event = DB::table('t_event')
            ->where('kode_event', $data['kode_event'])
            ->where('status_event', 'Y')
            ->first();

        $paket = DB::table('t_event_paket')
            ->where('event_kode_paket', $data['kode_event'])
            ->orderBy('urutan_paket')
            ->orderBy('id_event_paket')
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

        $event      = DB::table('t_event')->where('kode_event', $data['kode_event'])->first();
        $hargaEvent = (float) ($event->harga_event ?? 0);
        $totalHarga = $hargaEvent;

        $paketTerpilih = collect();
        if (!empty($selectedPaket)) {
            $paketTerpilih = DB::table('t_event_paket')
                ->whereIn('kode_paket', $selectedPaket)
                ->where('event_kode_paket', $data['kode_event'])
                ->get();

            foreach ($paketTerpilih as $p) {
                $totalHarga += (float) ($p->harga_paket ?? 0);
            }
        }

        $updatedData = array_merge($data, [
            'selected_paket' => $selectedPaket,
            'harga_event'    => $hargaEvent,
            'total_harga'    => $totalHarga,
        ]);
        session(['reg_pending' => $updatedData]);

        // Total 0 → enroll gratis langsung
        if ($totalHarga <= 0) {
            $userId = $this->createUserAccount($updatedData);
            $this->enrollEvent($updatedData, $userId, null, 'FREE');
            session()->forget('reg_pending');
            return redirect()->route('register-event.success');
        }

        return redirect()->route('register-event.payment');
    }

    // ─────────────────────────────────────────────────
    // STEP 4 – Tampilkan halaman Payment
    // Generate snap token + simpan pending registration ke DB
    // ─────────────────────────────────────────────────
    public function showPayment(Request $request)
    {
        $data = session('reg_pending');
        if (!$data || empty($data['otp_verified'])) {
            return redirect()->route('register')->with('error', 'Akses tidak valid.');
        }

        // Guard: manipulasi URL saat total 0
        if (($data['total_harga'] ?? 0) <= 0) {
            $userId = $this->createUserAccount($data);
            $this->enrollEvent($data, $userId, null, 'FREE');
            session()->forget('reg_pending');
            return redirect()->route('register-event.success');
        }

        $set = DB::table('app_setting')->first();

        $event = DB::table('t_event')
            ->where('kode_event', $data['kode_event'])
            ->where('status_event', 'Y')
            ->first();

        $selectedPaket = collect();
        if (!empty($data['selected_paket'])) {
            $selectedPaket = DB::table('t_event_paket')
                ->whereIn('kode_paket', $data['selected_paket'])
                ->get();
        }

        $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();
        $snapToken      = null;
        $orderId        = $data['order_id'] ?? null;

        if ($midtransConfig) {
            // Buat order_id baru jika belum ada di session
            if (!$orderId) {
                $orderId = 'REG-' . strtoupper(Str::random(8)) . '-' . time();
            }

            // Generate snap token
            \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
            \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $itemDetails = [];
            if (($data['harga_event'] ?? 0) > 0) {
                $itemDetails[] = [
                    'id'       => $data['kode_event'],
                    'price'    => (int) $data['harga_event'],
                    'quantity' => 1,
                    'name'     => mb_substr($event->judul_event ?? 'Event Registration', 0, 50),
                ];
            }
            foreach ($selectedPaket as $p) {
                if (($p->harga_paket ?? 0) > 0) {
                    $itemDetails[] = [
                        'id'       => $p->kode_paket,
                        'price'    => (int) $p->harga_paket,
                        'quantity' => 1,
                        'name'     => mb_substr($p->judul_paket ?? $p->kode_paket, 0, 50),
                    ];
                }
            }

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
            if (!empty($itemDetails)) {
                $params['item_details'] = $itemDetails;
            }

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                Log::error('Midtrans getSnapToken error: ' . $e->getMessage());
            }

            // Simpan pending registration ke DB (status PENDING)
            // sehingga callback bisa update tanpa butuh session
            $alreadyPending = DB::table('t_event_registrasi')
                ->where('kode_event', $data['kode_event'])
                ->where('email_peserta', $data['email'])
                ->where('payment_status', 'PENDING')
                ->exists();

            if (!$alreadyPending) {
                $kodeRegistrasi = 'REG' . date('ymdHis') . strtoupper(Str::random(4));
                $kodePaket      = !empty($data['selected_paket']) ? $data['selected_paket'][0] : null;

                DB::table('t_event_registrasi')->insert([
                    'kode_registrasi'   => $kodeRegistrasi,
                    'kode_event'        => $data['kode_event'],
                    'id_user'           => 0, // belum ada user, akan di-update setelah bayar
                    'nama_peserta'      => $data['nama'],
                    'email_peserta'     => $data['email'],
                    'instansi_peserta'  => $data['organisasi'] ?? null,
                    'no_hp_peserta'     => $data['no_hp']      ?? null,
                    'kode_paket'        => $kodePaket,
                    'total_bayar'       => (float) $data['total_harga'],
                    'midtrans_order_id' => $orderId,
                    'payment_status'    => 'PENDING',
                    'status_registrasi' => 'P',
                    'confirmed_at'      => null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                // Simpan selected_paket ke session data bersama order_id
                session(['reg_pending' => array_merge(session('reg_pending'), [
                    'order_id'        => $orderId,
                    'kode_registrasi' => $kodeRegistrasi,
                ])]);
            } else {
                // Pending sudah ada, ambil order_id-nya
                $pending = DB::table('t_event_registrasi')
                    ->where('kode_event', $data['kode_event'])
                    ->where('email_peserta', $data['email'])
                    ->where('payment_status', 'PENDING')
                    ->first();
                $orderId = $pending->midtrans_order_id ?? $orderId;
                session(['reg_pending' => array_merge(session('reg_pending'), [
                    'order_id'        => $orderId,
                    'kode_registrasi' => $pending->kode_registrasi ?? null,
                ])]);
            }
        }

        return view('web.register-event.payment', compact(
            'set', 'event', 'selectedPaket', 'data', 'snapToken', 'midtransConfig', 'orderId'
        ));
    }

    // ─────────────────────────────────────────────────
    // AJAX: Cek status pembayaran (polling dari browser)
    // ─────────────────────────────────────────────────
    public function checkPaymentStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json(['status' => 'error', 'message' => 'Order ID tidak ditemukan.']);
        }

        $reg = DB::table('t_event_registrasi')
            ->where('midtrans_order_id', $orderId)
            ->first();

        if (!$reg) {
            return response()->json(['status' => 'not_found']);
        }

        if ($reg->payment_status === 'PAID' && $reg->status_registrasi === 'A') {
            return response()->json(['status' => 'paid']);
        }

        if (in_array($reg->payment_status, ['FAILED', 'CANCEL', 'EXPIRE'])) {
            return response()->json(['status' => 'failed', 'payment_status' => $reg->payment_status]);
        }

        // Cek ke Midtrans langsung jika masih PENDING
        $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();
        if ($midtransConfig) {
            try {
                \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
                \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);

                $status = \Midtrans\Transaction::status($orderId);
                $txStatus    = $status->transaction_status ?? '';
                $fraudStatus = $status->fraud_status ?? null;

                if (in_array($txStatus, ['capture', 'settlement'])
                    && ($fraudStatus === 'accept' || $fraudStatus === null)) {
                    // Bayar — proses enroll
                    $this->processPaidRegistration($reg, $orderId);
                    return response()->json(['status' => 'paid']);
                }

                if (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
                    DB::table('t_event_registrasi')
                        ->where('midtrans_order_id', $orderId)
                        ->update(['payment_status' => 'FAILED', 'updated_at' => now()]);
                    return response()->json(['status' => 'failed', 'payment_status' => $txStatus]);
                }
            } catch (\Exception $e) {
                Log::error('checkPaymentStatus error: ' . $e->getMessage());
            }
        }

        return response()->json(['status' => 'pending']);
    }

    // ─────────────────────────────────────────────────
    // Midtrans Notification Callback (server-to-server)
    // Dipanggil oleh server Midtrans, bukan browser user
    // ─────────────────────────────────────────────────
    public function midtransCallback(Request $request)
    {
        // Coba ambil notif dari Midtrans secara aman
        try {
            $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();
            if ($midtransConfig) {
                \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
                \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);
            }

            $notif           = new \Midtrans\Notification();
            $txStatus        = $notif->transaction_status;
            $orderId         = $notif->order_id;
            $fraudStatus     = $notif->fraud_status;
        } catch (\Exception $e) {
            // Fallback: baca dari request body (untuk callback dari snap.pay onSuccess)
            $txStatus    = $request->input('transaction_status');
            $orderId     = $request->input('order_id');
            $fraudStatus = $request->input('fraud_status');
        }

        $isPaid = in_array($txStatus, ['capture', 'settlement'])
            && ($fraudStatus === 'accept' || $fraudStatus === null || $fraudStatus === '');

        $reg = DB::table('t_event_registrasi')
            ->where('midtrans_order_id', $orderId)
            ->first();

        if (!$reg) {
            return response()->json(['status' => 'order_not_found'], 404);
        }

        if ($isPaid && $reg->payment_status !== 'PAID') {
            $this->processPaidRegistration($reg, $orderId);
            return response()->json(['status' => 'success', 'redirect' => route('register-event.success')]);
        }

        if (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update(['payment_status' => 'FAILED', 'updated_at' => now()]);
        }

        return response()->json(['status' => 'pending', 'transaction_status' => $txStatus]);
    }

    // ─────────────────────────────────────────────────
    // Helper: Proses registrasi setelah konfirmasi PAID
    // Buat user, enroll event, update status
    // ─────────────────────────────────────────────────
    private function processPaidRegistration(object $reg, string $orderId): void
    {
        // Ambil session jika masih ada (flow browser)
        $sessionData = session('reg_pending');

        // Buat atau update user
        $userId = $this->createUserAccountFromReg($reg, $sessionData);

        // Update pending registration: set PAID + id_user
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

        // Simpan add-on yang dipilih ke t_event_addon
        $selectedPaket = $sessionData['selected_paket'] ?? [];
        $kodeRegistrasi = $reg->kode_registrasi;

        foreach ($selectedPaket as $kPaket) {
            $paket = DB::table('t_event_paket')->where('kode_paket', $kPaket)->first();
            if (!$paket) continue;

            $addonExists = DB::table('t_event_addon')
                ->where('kode_registrasi', $kodeRegistrasi)
                ->where('kode_paket', $kPaket)
                ->exists();

            if (!$addonExists) {
                DB::table('t_event_addon')->insert([
                    'id_user'         => $userId,
                    'kode_event'      => $reg->kode_event,
                    'kode_registrasi' => $kodeRegistrasi,
                    'kode_paket'      => $kPaket,
                    'nama_addon'      => $paket->judul_paket ?? $kPaket,
                    'harga_addon'     => (float) ($paket->harga_paket ?? 0),
                    'qty'             => 1,
                    'subtotal'        => (float) ($paket->harga_paket ?? 0),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }

        // Bersihkan session
        session()->forget('reg_pending');
    }

    // ─────────────────────────────────────────────────
    // Helper: Buat atau ambil user dari data registrasi
    // ─────────────────────────────────────────────────
    private function createUserAccountFromReg(object $reg, ?array $sessionData): int
    {
        $existing = DB::table('app_user')->where('email_user', $reg->email_peserta)->first();
        if ($existing) {
            return (int) $existing->id_user;
        }

        // Password dari session jika ada, fallback random
        $password = $sessionData['password'] ?? Str::random(12);

        return (int) DB::table('app_user')->insertGetId([
            'nama_user'            => $reg->nama_peserta,
            'email_user'           => $reg->email_peserta,
            'no_hp_user'           => $reg->no_hp_peserta       ?? null,
            'organisasi_user'      => $reg->instansi_peserta     ?? null,
            'tipe_organisasi_user' => $sessionData['tipe_organisasi'] ?? null,
            'jabatan_user'         => $sessionData['jabatan']    ?? null,
            'identitas_user'       => $sessionData['no_identitas'] ?? null,
            'password_user'        => Hash::make($password),
            'status_user'          => 'Y',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
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
    // Helper: Buat akun user (untuk alur gratis)
    // ─────────────────────────────────────────────────
    private function createUserAccount(array $data): int
    {
        $existing = DB::table('app_user')->where('email_user', $data['email'])->first();
        if ($existing) {
            if (($existing->status_user ?? '') !== 'Y') {
                DB::table('app_user')
                    ->where('id_user', $existing->id_user)
                    ->update(['status_user' => 'Y', 'updated_at' => now()]);
            }
            return (int) $existing->id_user;
        }

        return (int) DB::table('app_user')->insertGetId([
            'nama_user'            => $data['nama'],
            'email_user'           => $data['email'],
            'no_hp_user'           => $data['no_hp']          ?? null,
            'organisasi_user'      => $data['organisasi']      ?? null,
            'tipe_organisasi_user' => $data['tipe_organisasi'] ?? null,
            'jabatan_user'         => $data['jabatan']         ?? null,
            'identitas_user'       => $data['no_identitas']    ?? null,
            'password_user'        => Hash::make($data['password']),
            'status_user'          => 'Y',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
    }

    // ─────────────────────────────────────────────────
    // Helper: Enroll event + simpan add-on (untuk alur gratis)
    // ─────────────────────────────────────────────────
    private function enrollEvent(array $data, int $userId, ?string $orderId = null, string $paymentStatus = 'FREE'): void
    {
        $kodeRegistrasi = 'REG' . date('ymdHis') . strtoupper(Str::random(4));
        $totalHarga     = (float) ($data['total_harga'] ?? 0);
        $statusReg      = 'A';
        $confirmedAt    = now();
        $kodePaket      = !empty($data['selected_paket']) ? $data['selected_paket'][0] : null;

        $alreadyReg = DB::table('t_event_registrasi')
            ->where('kode_event', $data['kode_event'])
            ->where('id_user', $userId)
            ->exists();

        if (!$alreadyReg) {
            DB::table('t_event_registrasi')->insert([
                'kode_registrasi'   => $kodeRegistrasi,
                'kode_event'        => $data['kode_event'],
                'id_user'           => $userId,
                'nama_peserta'      => $data['nama'],
                'email_peserta'     => $data['email'],
                'instansi_peserta'  => $data['organisasi'] ?? null,
                'no_hp_peserta'     => $data['no_hp']      ?? null,
                'kode_paket'        => $kodePaket,
                'total_bayar'       => $totalHarga,
                'midtrans_order_id' => $orderId,
                'payment_status'    => $paymentStatus,
                'status_registrasi' => $statusReg,
                'confirmed_at'      => $confirmedAt,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        } else {
            $existing = DB::table('t_event_registrasi')
                ->where('kode_event', $data['kode_event'])
                ->where('id_user', $userId)
                ->first();
            $kodeRegistrasi = $existing->kode_registrasi ?? $kodeRegistrasi;
        }

        if (!empty($data['selected_paket'])) {
            foreach ($data['selected_paket'] as $kPaket) {
                $paket = DB::table('t_event_paket')->where('kode_paket', $kPaket)->first();
                if (!$paket) continue;

                $addonExists = DB::table('t_event_addon')
                    ->where('kode_registrasi', $kodeRegistrasi)
                    ->where('kode_paket', $kPaket)
                    ->exists();

                if (!$addonExists) {
                    DB::table('t_event_addon')->insert([
                        'id_user'         => $userId,
                        'kode_event'      => $data['kode_event'],
                        'kode_registrasi' => $kodeRegistrasi,
                        'kode_paket'      => $kPaket,
                        'nama_addon'      => $paket->judul_paket ?? $kPaket,
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
}
