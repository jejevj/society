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

        // Ambil semua paket milik event ini — tanpa filter status_paket
        // karena kolom status_paket mungkin belum ada pada semua record lama
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

        // Ambil harga event langsung dari DB — jangan andalkan session lama
        $event = DB::table('t_event')
            ->where('kode_event', $data['kode_event'])
            ->first();
        $hargaEvent = (float) ($event->harga_event ?? 0);

        // Total = harga event (base) + harga paket yang dipilih dan berbayar
        $totalHarga  = $hargaEvent;
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

        // Update session dengan data lengkap sebelum apapun dilakukan
        $updatedData = array_merge($data, [
            'selected_paket' => $selectedPaket,
            'harga_event'    => $hargaEvent,
            'total_harga'    => $totalHarga,
        ]);
        session(['reg_pending' => $updatedData]);

        // Jika total 0: skip payment, langsung enroll dan aktifkan user
        if ($totalHarga <= 0) {
            $userId = $this->createUserAccount($updatedData);
            $this->enrollEvent($updatedData, $userId);
            session()->forget('reg_pending');
            return redirect()->route('register-event.success');
        }

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

        // Guard: jika total 0 (user manipulasi URL), langsung enroll
        if (($data['total_harga'] ?? 0) <= 0) {
            $userId = $this->createUserAccount($data);
            $this->enrollEvent($data, $userId);
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

        // ── Midtrans Snap Token ──
        $snapToken      = null;
        $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();

        if ($midtransConfig && ($data['total_harga'] ?? 0) > 0) {
            \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
            \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $orderId = 'REG-' . strtoupper(Str::random(8)) . '-' . time();

            // item_details — harga event sebagai item pertama
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
                // Simpan order_id dan snap_token ke session SETELAH berhasil
                session(['reg_pending' => array_merge(session('reg_pending'), [
                    'order_id'   => $orderId,
                    'snap_token' => $snapToken,
                ])]);
            } catch (\Exception $e) {
                // Lanjutkan — view akan tampilkan fallback manual
                \Log::error('Midtrans getSnapToken error: ' . $e->getMessage());
            }
        }

        return view('web.register-event.payment', compact(
            'set', 'event', 'selectedPaket', 'data', 'snapToken', 'midtransConfig'
        ));
    }

    // ─────────────────────────────────────────────────
    // STEP 4 – Proses Payment gratis / manual / fallback
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
        $transactionStatus = $request->input('transaction_status');
        $orderId           = $request->input('order_id');
        $fraudStatus       = $request->input('fraud_status');

        $isPaid = in_array($transactionStatus, ['capture', 'settlement'])
            && ($fraudStatus === 'accept' || $fraudStatus === null);

        if ($isPaid) {
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update([
                    'payment_status'    => 'PAID',
                    'status_registrasi' => 'A',
                    'paid_at'           => now(),
                    'updated_at'        => now(),
                ]);

            // Aktifkan user
            $reg = DB::table('t_event_registrasi')->where('midtrans_order_id', $orderId)->first();
            if ($reg) {
                DB::table('app_user')
                    ->where('id_user', $reg->id_user)
                    ->update(['status_user' => 'Y', 'updated_at' => now()]);
            }

            return response()->json(['status' => 'success']);
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update(['payment_status' => 'FAILED', 'updated_at' => now()]);
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
    // Helper: Buat akun user
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
    // Helper: Enroll event + simpan paket add-on
    // ─────────────────────────────────────────────────
    private function enrollEvent(array $data, int $userId, ?string $orderId = null, string $paymentStatus = 'UNPAID'): void
    {
        $kodeRegistrasi = 'REG' . date('ymdHis') . strtoupper(Str::random(4));
        $usedOrderId    = $orderId ?? ($data['order_id'] ?? null);
        $totalHarga     = (float) ($data['total_harga'] ?? 0);

        if ($totalHarga <= 0) {
            $paymentStatus = 'FREE';
        }

        $statusRegistrasi = in_array($paymentStatus, ['PAID', 'FREE']) ? 'A' : 'P';
        $confirmedAt      = ($statusRegistrasi === 'A') ? now() : null;

        $kodePaket = !empty($data['selected_paket']) ? $data['selected_paket'][0] : null;

        // Cegah duplikasi registrasi
        $alreadyRegistered = DB::table('t_event_registrasi')
            ->where('kode_event', $data['kode_event'])
            ->where('id_user', $userId)
            ->exists();

        if (!$alreadyRegistered) {
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
                'midtrans_order_id' => $usedOrderId,
                'payment_status'    => $paymentStatus,
                'status_registrasi' => $statusRegistrasi,
                'confirmed_at'      => $confirmedAt,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        } else {
            // Ambil kode_registrasi yang sudah ada agar addon tetap bisa disimpan
            $existing = DB::table('t_event_registrasi')
                ->where('kode_event', $data['kode_event'])
                ->where('id_user', $userId)
                ->first();
            $kodeRegistrasi = $existing->kode_registrasi ?? $kodeRegistrasi;
        }

        // Simpan semua paket berbayar yang dipilih ke t_event_addon
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
