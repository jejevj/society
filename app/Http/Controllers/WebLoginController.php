<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use App\Models\ReffMenu;
use App\Models\ReffStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Mail\AppMail;

class WebLoginController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $data = [
            'menu'       => 'login',
            'menu_aktif' => 'login',
            'set'        => DB::table('app_setting')->where('id_setting', 1)->first(),
        ];
        return view('web.login', $data);
    }

    public function register(Request $request)
    {
        $event = null;
        if ($request->filled('event')) {
            $event = DB::table('t_event as e')
                ->where('e.kode_event', $request->event)
                ->where('e.status_event', 'Y')
                ->first();
            if ($event) {
                $event->kolaborasi = DB::table('t_event_kolaborasi')
                    ->where('event_kode_kolaborasi', $event->kode_event)->get();
                $event->paket = DB::table('t_event_paket')
                    ->where('event_kode_paket', $event->kode_event)->get();
            }
        }

        $midtransConfig = DB::table('app_midtrans_config')
            ->where('id_midtrans', 1)->where('is_active', 'Y')->first();

        $data = [
            'menu'           => 'register',
            'menu_aktif'     => 'register',
            'set'            => DB::table('app_setting')->where('id_setting', 1)->first(),
            'event'          => $event,
            'midtransConfig' => $midtransConfig,
        ];
        return view('web.register', $data);
    }

    private function generateKodeRegistrasi(): string
    {
        do {
            $kode = 'REG-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (DB::table('t_event_registrasi')->where('kode_registrasi', $kode)->exists());
        return $kode;
    }

    public function registrasiAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'            => 'required|string|max:200',
            'email'           => 'required|email|max:200|unique:app_user,username_user',
            'password'        => [
                'required', 'string', 'min:8',
                'regex:/[A-Z]/', 'regex:/[a-z]/',
                'regex:/[0-9]/', 'regex:/[@$!%*#?&._-]/'
            ],
            'identitas'       => 'required',
            'file'            => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pekerjaan'       => 'required',
            'telepon'         => 'required',
            'alamat'          => 'required',
            'organisasi'      => 'required|string|max:255',
            'tipe_organisasi' => 'required|string|max:100',
        ], [
            'email.email'              => 'Invalid email format.',
            'email.unique'             => 'Email is already registered.',
            'password.min'             => 'Password must be at least 8 characters.',
            'password.regex'           => 'Password must contain uppercase, lowercase, number, and special character.',
            'file.image'               => 'File must be an image.',
            'file.mimes'               => 'File format must be jpg, jpeg, or png.',
            'file.max'                 => 'Maximum file size is 2MB.',
            'organisasi.required'      => 'Organization name is required.',
            'tipe_organisasi.required' => 'Organization type is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if ($request->hasFile('file')) {
            $scan = $this->dataService->scanAntivirus($request->file('file'));
            if (!$scan['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $scan['message'],
                    'virus'   => $scan['virus'] ?? null,
                    'error'   => $scan['error'] ?? null,
                ], $scan['code']);
            }
        }

        $path = null;
        if ($request->hasFile('file')) {
            $filename = time() . '_' . $request->file('file')->getClientOriginalName();
            $path     = $request->file('file')->storeAs('user', $filename, 'public');
        }

        $dt_role  = DB::table('reff_role')->where('kode_role', 'PUB')->first();
        $otp_reg  = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $token    = Str::random(64);

        $userId = DB::table('app_user')->insertGetId([
            'nama_user'            => $request->nama,
            'role_id'              => $dt_role->id_role,
            'username_user'        => $request->email,
            'password_user'        => Hash::make($request->password),
            'identitas_user'       => $request->identitas,
            'file_identitas_user'  => $path,
            'status_user'          => 0,
            'telepon_user'         => $request->telepon,
            'pekerjaan_user'       => $request->pekerjaan,
            'alamat_user'          => $request->alamat,
            'organisasi_user'      => $request->organisasi,
            'tipe_organisasi_user' => $request->tipe_organisasi,
            'verify_token'         => $token,
            'otp_user'             => $otp_reg,
            'created_at'           => now(),
        ]);

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Registration failed. Please try again.']);
        }

        if ($request->filled('kode_event')) {
            $ev = DB::table('t_event')
                ->where('kode_event', $request->kode_event)
                ->where('status_event', 'Y')
                ->first();
            if ($ev) {
                DB::table('t_event_registrasi')->insert([
                    'kode_registrasi'   => $this->generateKodeRegistrasi(),
                    'kode_event'        => $ev->kode_event,
                    'id_user'           => $userId,
                    'role_peserta'      => 'participant',
                    'status_registrasi' => 'PENDING_OTP',
                    'created_at'        => now(),
                ]);
            }
        }

        $this->dataService->setMailConfig();
        Mail::to($request->email)->queue(
            new AppMail(
                'web.email-otp-registrasi',
                ['nama' => $request->nama, 'otp' => $otp_reg],
                'OTP Verification - ' . env('APP_NAME', 'Society Event')
            )
        );

        $this->dataService->createLogWeb($request, 'registrasiAction', 'User registered. user_id=' . $userId);

        return response()->json([
            'success'   => true,
            'message'   => 'Account created. Please verify your email.',
            'user_id'   => $userId,
            'has_event' => $request->filled('kode_event'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // VERIFY OTP REGISTRASI
    // Opsi A: status_user = 1 setelah OTP (user langsung bisa login).
    // Jika ada event → registrasi naik ke PENDING_PAYMENT, bayar nanti
    //   via dashboard (retry payment).
    // ─────────────────────────────────────────────────────────────────
    public function verifyOtpRegistrasi(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|integer',
            'otp'       => 'required|string|min:6|max:6',
            'has_event' => 'nullable|boolean',
        ]);

        $user = DB::table('app_user')->where('id_user', $request->user_id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        if ($user->otp_user !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please check your email and try again.']);
        }

        // Opsi A: aktifkan akun langsung, tanpa tergantung status pembayaran
        DB::table('app_user')->where('id_user', $request->user_id)->update([
            'otp_user'     => null,
            'verify_token' => null,
            'status_user'  => 1,
            'updated_at'   => now(),
        ]);

        // Naikkan status registrasi event ke PENDING_PAYMENT jika ada
        DB::table('t_event_registrasi')
            ->where('id_user', $request->user_id)
            ->where('status_registrasi', 'PENDING_OTP')
            ->update(['status_registrasi' => 'PENDING_PAYMENT', 'updated_at' => now()]);

        $this->dataService->createLogWeb($request, 'verifyOtpRegistrasi', 'OTP verified. user_id=' . $request->user_id);

        return response()->json([
            'success'   => true,
            'message'   => 'OTP verified successfully.',
            'activated' => true,
        ]);
    }

    public function resendOtpRegistrasi(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);

        $user = DB::table('app_user')->where('id_user', $request->user_id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $otp_reg = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        DB::table('app_user')->where('id_user', $request->user_id)
            ->update(['otp_user' => $otp_reg, 'updated_at' => now()]);

        $this->dataService->setMailConfig();
        Mail::to($user->username_user)->queue(
            new AppMail(
                'web.email-otp-registrasi',
                ['nama' => $user->nama_user, 'otp' => $otp_reg],
                'OTP Verification (Resend) - ' . env('APP_NAME', 'Society Event')
            )
        );

        return response()->json(['success' => true, 'message' => 'New OTP sent.']);
    }

    public function getEventPackages(Request $request)
    {
        $request->validate(['kode_event' => 'required|string']);

        $packages = DB::table('t_event_paket')
            ->where('event_kode_paket', $request->kode_event)
            ->orderBy('id_event_paket', 'asc')
            ->get();

        return response()->json(['success' => true, 'packages' => $packages]);
    }

    // ─────────────────────────────────────────────────────────────────
    // SNAP TOKEN (dipakai saat register flow maupun retry dari dashboard)
    // ─────────────────────────────────────────────────────────────────
    private function buildSnapToken(array $params): array
    {
        $userId    = $params['user_id'];
        $kodeEvent = $params['kode_event'];
        $packages  = $params['packages'] ?? [];

        $user = DB::table('app_user')->where('id_user', $userId)->first();
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        $totalAmount = 0;
        $itemDetails = [];

        $event      = DB::table('t_event')->where('kode_event', $kodeEvent)->first();
        $hargaEvent = (float) ($event->harga_event ?? 0);
        if ($hargaEvent > 0) {
            $totalAmount  += $hargaEvent;
            $itemDetails[] = [
                'id'       => $kodeEvent,
                'price'    => (int) $hargaEvent,
                'quantity' => 1,
                'name'     => mb_substr($event->judul_event ?? 'Event Registration', 0, 50),
            ];
        }

        $selectedPaketData = [];
        foreach ($packages as $kode_paket) {
            $pkg = DB::table('t_event_paket')->where('kode_paket', $kode_paket)->first();
            if ($pkg) {
                $selectedPaketData[] = $pkg;
                if ($pkg->harga_paket > 0) {
                    $totalAmount  += (float) $pkg->harga_paket;
                    $itemDetails[] = [
                        'id'       => $pkg->kode_paket,
                        'price'    => (int) $pkg->harga_paket,
                        'quantity' => 1,
                        'name'     => mb_substr($pkg->judul_paket, 0, 50),
                    ];
                }
            }
        }

        if ($totalAmount <= 0) {
            return [
                'success'        => true,
                'free'           => true,
                'snap_token'     => null,
                'total_amount'   => 0,
                'harga_event'    => $hargaEvent,
                'selected_paket' => $selectedPaketData,
            ];
        }

        $midtransConfig = DB::table('app_midtrans_config')
            ->where('id_midtrans', 1)->where('is_active', 'Y')->first();

        if (!$midtransConfig || empty($midtransConfig->server_key)) {
            return ['success' => false, 'message' => 'Konfigurasi Midtrans belum diatur. Hubungi administrator.'];
        }

        $isProduction = $midtransConfig->environment === 'production';
        $snapApiUrl   = $isProduction
            ? 'https://api.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $orderId = 'REG-' . $userId . '-' . time();
        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $user->nama_user,
                'email'      => $user->username_user,
                'phone'      => $user->telepon_user ?? '',
            ],
        ];
        if (!empty($itemDetails)) {
            $payload['item_details'] = $itemDetails;
        }
        if (!empty($midtransConfig->payment_types)) {
            $types = json_decode($midtransConfig->payment_types, true);
            if (!empty($types)) {
                $payload['enabled_payments'] = $types;
            }
        }

        try {
            $response = Http::withBasicAuth($midtransConfig->server_key, '')
                ->timeout(30)
                ->post($snapApiUrl, $payload);
            $res = $response->json();

            if ($response->failed() || !isset($res['token'])) {
                $errMsg = isset($res['error_messages'])
                    ? implode(', ', (array) $res['error_messages'])
                    : ($res['message'] ?? 'Gagal mendapatkan SNAP token.');
                return ['success' => false, 'message' => $errMsg];
            }

            $snapToken = $res['token'];

            if (Schema::hasTable('app_midtrans_transaction')) {
                DB::table('app_midtrans_transaction')->insert([
                    'order_id'           => $orderId,
                    'transaction_status' => 'pending',
                    'payment_type'       => 'snap',
                    'gross_amount'       => (float) $totalAmount,
                    'currency'           => 'IDR',
                    'snap_token'         => $snapToken,
                    'redirect_url'       => $res['redirect_url'] ?? null,
                    'status_message'     => 'SNAP token created',
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            return [
                'success'        => true,
                'free'           => false,
                'snap_token'     => $snapToken,
                'order_id'       => $orderId,
                'total_amount'   => $totalAmount,
                'harga_event'    => $hargaEvent,
                'selected_paket' => $selectedPaketData,
                'client_key'     => $midtransConfig->client_key,
                'is_production'  => $isProduction,
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Gagal membuat token: ' . $e->getMessage()];
        }
    }

    public function getRegistrationSnapToken(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|integer',
            'kode_event' => 'required|string',
        ]);
        $result = $this->buildSnapToken([
            'user_id'    => $request->user_id,
            'kode_event' => $request->kode_event,
            'packages'   => $request->packages ?? [],
        ]);
        $status = ($result['success'] ?? false) ? 200 : 422;
        return response()->json($result, $status);
    }

    public function enrollEventFree(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|integer',
            'kode_event' => 'required|string',
        ]);
        $this->enrollUser($request->user_id, $request->kode_event, $request->packages ?? []);
        return response()->json(['success' => true, 'message' => 'Enrolled successfully.']);
    }

    public function paymentRegistrationCallback(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|integer',
            'kode_event' => 'required|string',
        ]);
        $midtransResult = json_decode($request->midtrans_result, true);
        if (!empty($midtransResult['order_id']) && Schema::hasTable('app_midtrans_transaction')) {
            DB::table('app_midtrans_transaction')
                ->where('order_id', $midtransResult['order_id'])
                ->update([
                    'transaction_status' => $midtransResult['transaction_status'] ?? 'settlement',
                    'payment_type'       => $midtransResult['payment_type'] ?? null,
                    'updated_at'         => now(),
                ]);
        }
        $this->enrollUser($request->user_id, $request->kode_event, $request->packages ?? []);
        return response()->json(['success' => true, 'message' => 'Payment confirmed. Enrolled successfully.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // RETRY PAYMENT — user sudah login, bayar ulang dari dashboard
    // ─────────────────────────────────────────────────────────────────
    public function retryPayment(Request $request)
    {
        $request->validate([
            'kode_event' => 'required|string',
            'packages'   => 'nullable|array',
        ]);

        $userId = session('id_user');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Sesi habis. Silakan login kembali.'], 401);
        }

        // Pastikan registrasi masih PENDING_PAYMENT atau PAYMENT_EXPIRED
        $reg = DB::table('t_event_registrasi')
            ->where('id_user', $userId)
            ->where('kode_event', $request->kode_event)
            ->whereIn('status_registrasi', ['PENDING_PAYMENT', 'PAYMENT_EXPIRED'])
            ->first();

        if (!$reg) {
            return response()->json(['success' => false, 'message' => 'Tidak ada tagihan pembayaran yang aktif untuk event ini.'], 422);
        }

        // Ambil paket yang sudah dipilih sebelumnya jika tidak dikirim ulang
        $packages = $request->packages;
        if (empty($packages)) {
            $packages = DB::table('t_event_addon')
                ->where('id_user', $userId)
                ->where('kode_event', $request->kode_event)
                ->pluck('kode_paket')
                ->toArray();
        }

        $result = $this->buildSnapToken([
            'user_id'    => $userId,
            'kode_event' => $request->kode_event,
            'packages'   => $packages,
        ]);

        if (!($result['success'] ?? false)) {
            return response()->json($result, 422);
        }

        // Reset status jadi PENDING_PAYMENT saat retry
        DB::table('t_event_registrasi')
            ->where('id_user', $userId)
            ->where('kode_event', $request->kode_event)
            ->update(['status_registrasi' => 'PENDING_PAYMENT', 'updated_at' => now()]);

        $this->dataService->createLogWeb($request, 'retryPayment', 'Retry payment. user_id=' . $userId . ' event=' . $request->kode_event);

        return response()->json($result);
    }

    public function retryPaymentCallback(Request $request)
    {
        $request->validate([
            'kode_event'     => 'required|string',
            'midtrans_result' => 'nullable|string',
        ]);

        $userId = session('id_user');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Sesi habis. Silakan login kembali.'], 401);
        }

        $midtransResult = json_decode($request->midtrans_result ?? '{}', true);
        if (!empty($midtransResult['order_id']) && Schema::hasTable('app_midtrans_transaction')) {
            DB::table('app_midtrans_transaction')
                ->where('order_id', $midtransResult['order_id'])
                ->update([
                    'transaction_status' => $midtransResult['transaction_status'] ?? 'settlement',
                    'payment_type'       => $midtransResult['payment_type'] ?? null,
                    'updated_at'         => now(),
                ]);
        }

        $packages = DB::table('t_event_addon')
            ->where('id_user', $userId)
            ->where('kode_event', $request->kode_event)
            ->pluck('kode_paket')
            ->toArray();

        $this->enrollUser($userId, $request->kode_event, $packages);

        $this->dataService->createLogWeb($request, 'retryPaymentCallback', 'Retry payment success. user_id=' . $userId);

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil. Anda telah terdaftar di event.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // MIDTRANS WEBHOOK — bypass CSRF, dipanggil server Midtrans
    // ─────────────────────────────────────────────────────────────────
    public function midtransNotification(Request $request)
    {
        $midtransConfig = DB::table('app_midtrans_config')
            ->where('id_midtrans', 1)->where('is_active', 'Y')->first();

        if (!$midtransConfig || empty($midtransConfig->server_key)) {
            return response()->json(['message' => 'Midtrans not configured.'], 500);
        }

        $payload   = $request->all();
        $orderId   = $payload['order_id']   ?? null;
        $status    = $payload['transaction_status'] ?? null;
        $payType   = $payload['payment_type'] ?? null;
        $grossAmt  = $payload['gross_amount'] ?? null;
        $sigKey    = $payload['signature_key'] ?? null;
        $statusCode = $payload['status_code'] ?? null;

        // Verifikasi signature Midtrans
        $expected = hash('sha512', $orderId . $statusCode . $grossAmt . $midtransConfig->server_key);
        if ($sigKey !== $expected) {
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        if (!$orderId) {
            return response()->json(['message' => 'No order_id.'], 400);
        }

        // Update tabel transaksi Midtrans
        if (Schema::hasTable('app_midtrans_transaction')) {
            DB::table('app_midtrans_transaction')
                ->where('order_id', $orderId)
                ->update([
                    'transaction_status' => $status,
                    'payment_type'       => $payType,
                    'updated_at'         => now(),
                ]);
        }

        // Cari registrasi berdasarkan order_id pattern: REG-{userId}-{timestamp}
        // Format: REG-<userId>-<timestamp>
        if (preg_match('/^REG-(\d+)-\d+$/', $orderId, $m)) {
            $userId = (int) $m[1];

            // Cari registrasi PENDING_PAYMENT user ini
            $reg = DB::table('t_event_registrasi')
                ->where('id_user', $userId)
                ->whereIn('status_registrasi', ['PENDING_PAYMENT', 'PAYMENT_EXPIRED'])
                ->orderByDesc('created_at')
                ->first();

            if ($reg) {
                if (in_array($status, ['settlement', 'capture'])) {
                    $packages = DB::table('t_event_addon')
                        ->where('id_user', $userId)
                        ->where('kode_event', $reg->kode_event)
                        ->pluck('kode_paket')
                        ->toArray();
                    $this->enrollUser($userId, $reg->kode_event, $packages);

                } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
                    DB::table('t_event_registrasi')
                        ->where('id_registrasi', $reg->id_registrasi)
                        ->update([
                            'status_registrasi' => 'PAYMENT_EXPIRED',
                            'updated_at'        => now(),
                        ]);
                }
            }
        }

        return response()->json(['message' => 'OK']);
    }

    // ─────────────────────────────────────────────────────────────────
    // INTERNAL: enroll user + update status
    // ─────────────────────────────────────────────────────────────────
    private function enrollUser($userId, $kodeEvent, $packages = [])
    {
        // Akun sudah aktif di Opsi A, tidak perlu update status_user lagi
        // tapi tetap pastikan aktif jika belum
        DB::table('app_user')
            ->where('id_user', $userId)
            ->where('status_user', 0)
            ->update(['status_user' => 1, 'updated_at' => now()]);

        DB::table('t_event_registrasi')
            ->where('id_user', $userId)
            ->where('kode_event', $kodeEvent)
            ->update([
                'status_registrasi' => 'CONFIRMED',
                'confirmed_at'      => now(),
                'updated_at'        => now(),
            ]);

        if (!empty($packages)) {
            foreach ($packages as $kode_paket) {
                $exists = DB::table('t_event_addon')
                    ->where('id_user', $userId)
                    ->where('kode_paket', $kode_paket)
                    ->exists();
                if (!$exists) {
                    DB::table('t_event_addon')->insert([
                        'id_user'    => $userId,
                        'kode_event' => $kodeEvent,
                        'kode_paket' => $kode_paket,
                        'created_at' => now(),
                    ]);
                }
            }
        }
    }

    public function verifikasiAkun($token)
    {
        $user = DB::table('app_user')->where('verify_token', $token)->first();
        if (!$user) {
            return 'Token tidak valid!';
        }
        DB::table('app_user')
            ->where('id_user', $user->id_user)
            ->update(['status_user' => 1, 'verify_token' => null]);
        return redirect()->route('login')->with('success', 'Account verified successfully. Please login.');
    }

    public function loginAction(Request $request)
    {
        $request->validate(['email' => 'required', 'password' => 'required']);

        $user = DB::table('app_user as u')
            ->select('u.*')
            ->where('u.username_user', $request->email)
            ->where('u.role_id', 4)
            ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found.']);
        }
        if (!$user->status_user) {
            return response()->json(['status' => false, 'message' => 'Account is not active. Please verify your email first.']);
        }
        if (!Hash::check($request->password, $user->password_user)) {
            return response()->json(['status' => false, 'message' => 'Incorrect password.']);
        }

        $this->dataService->setMailConfig();
        $otp      = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $id_hash  = Crypt::encrypt($user->id_user);

        DB::table('app_user')->where('id_user', $user->id_user)->update(['otp_user' => $otp]);

        Mail::to($request->email)->queue(
            new AppMail(
                'web.email-otp-login',
                ['nama' => $user->nama_user, 'otp' => $otp],
                'OTP Login - ' . env('APP_NAME', 'Society Event')
            )
        );

        $this->dataService->createLogWeb($request, 'loginAction', 'OTP sent. user_id=' . $user->id_user);

        return response()->json([
            'status'  => true,
            'message' => 'OTP code has been sent to your email.',
            'key'     => $id_hash,
        ]);
    }

    public function otpLogin($key, Request $request)
    {
        $data = [
            'menu'       => 'OTP Login',
            'menu_aktif' => 'otp',
            'key'        => $key,
            'set'        => DB::table('app_setting')->where('id_setting', 1)->first(),
        ];
        return view('web.otp-login', $data);
    }

    public function verifyOtpAction(Request $request)
    {
        $request->validate(['key' => 'required', 'otp' => 'required']);

        $id   = Crypt::decrypt($request->key);
        $user = DB::table('app_user as u')->select('u.*')->where('u.id_user', $id)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found.']);
        }
        if ($request->otp != $user->otp_user) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP code.']);
        }

        session([
            'id_user'             => $user->id_user,
            'nama_user'           => $user->nama_user,
            'email_user'          => $user->username_user,
            'identitas_user'      => $user->identitas_user,
            'file_identitas_user' => $user->file_identitas_user,
            'jenis_user'          => 'publik',
        ]);

        DB::table('app_user')->where('id_user', $user->id_user)->update(['otp_user' => '']);

        $this->dataService->createLogWeb($request, 'verifyOtpAction', 'OTP verified. user_id=' . $user->id_user);

        return response()->json(['status' => true, 'message' => 'OTP verified.']);
    }

    public function lupaPassword(Request $request)
    {
        $data = [
            'menu'       => 'Forgot Password',
            'menu_aktif' => 'lupa password',
            'set'        => DB::table('app_setting')->where('id_setting', 1)->first(),
        ];
        return view('web.lupa-password', $data);
    }

    public function passwordBaru(Request $request, $token)
    {
        $data = [
            'menu'       => 'New Password',
            'menu_aktif' => 'password baru',
            'token'      => $token,
            'set'        => DB::table('app_setting')->where('id_setting', 1)->first(),
        ];
        return view('web.password-baru', $data);
    }

    public function lupaPasswordAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:200',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = DB::table('app_user')->where('username_user', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email is not registered.'], 422);
        }

        $token = Str::random(64);
        DB::table('app_user')->where('username_user', $request->email)->update(['verify_token' => $token]);

        $verificationUrl = url(env('APP_ROUTE') . '/password-baru/' . $token);
        $this->dataService->setMailConfig();
        Mail::to($request->email)->queue(
            new AppMail(
                'web.email-lupa-password',
                ['nama' => $user->nama_user, 'verificationUrl' => $verificationUrl],
                'Forgot Password - ' . env('APP_NAME', 'Society Event')
            )
        );

        $this->dataService->createLogWeb($request, 'lupaPasswordAction', 'Forgot password request.');
        return response()->json(['success' => true, 'message' => 'Please check your email for the reset link.']);
    }

    public function ganitPasswordAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'   => 'required|max:200',
            'konfirmasi' => 'required|max:200',
            'token'      => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = DB::table('app_user')->where('verify_token', $request->token)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid token.'], 422);
        }
        if ($request->password !== $request->konfirmasi) {
            return response()->json(['success' => false, 'message' => 'Password confirmation does not match.'], 422);
        }

        $update = DB::table('app_user')->where('id_user', $user->id_user)->update([
            'verify_token'  => '',
            'password_user' => Hash::make($request->password),
        ]);

        if ($update) {
            $this->dataService->createLogWeb($request, 'ganitPasswordAction', 'Password changed.');
            return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Failed to change password. Please try again.']);
    }
}
