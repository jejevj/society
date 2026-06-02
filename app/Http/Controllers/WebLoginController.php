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
use Illuminate\Support\Facades\Log;
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
        $menu_aktif = 'login';
        $data = [
            'menu'       => 'login',
            'menu_aktif' => $menu_aktif,
            'set'        => DB::table('app_setting')->where('id_setting', 1)->first(),
        ];
        return view('web.login', $data);
    }

    public function register(Request $request)
    {
        $menu_aktif = 'register';

        $event = null;
        if ($request->filled('event')) {
            $event = DB::table('t_event as e')
                ->where('e.kode_event', $request->event)
                ->where('e.status_event', 'Y')
                ->first();

            if ($event) {
                $event->kolaborasi = DB::table('t_event_kolaborasi')
                    ->where('event_kode_kolaborasi', $event->kode_event)
                    ->get();
                $event->paket = DB::table('t_event_paket')
                    ->where('event_kode_paket', $event->kode_event)
                    ->get();
            }
        }

        $midtransConfig = DB::table('app_midtrans_config')
            ->where('id_midtrans', 1)
            ->where('is_active', 'Y')
            ->first();

        $data = [
            'menu'           => 'register',
            'menu_aktif'     => $menu_aktif,
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

        $password = Hash::make($request->password);

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
            $path = $request->file('file')->storeAs('user', $filename, 'public');
        }

        $dt_role = DB::table('reff_role')->where('kode_role', 'PUB')->first();
        $otp_reg = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $token   = Str::random(64);

        $userId = DB::table('app_user')->insertGetId([
            'nama_user'            => $request->nama,
            'role_id'              => $dt_role->id_role,
            'username_user'        => $request->email,
            'password_user'        => $password,
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
            $ev = DB::table('t_event')->where('kode_event', $request->kode_event)->where('status_event', 'Y')->first();
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

        $this->dataService->createLogWeb($request, 'registrasiAction', 'User registered, OTP sent. user_id=' . $userId);

        return response()->json([
            'success'   => true,
            'message'   => 'Account created. Please verify your email with the OTP we sent.',
            'user_id'   => $userId,
            'has_event' => $request->filled('kode_event') ? true : false,
        ]);
    }

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

        // Opsi A: selalu aktifkan akun setelah OTP verified.
        // Jika ada event, status registrasi = PENDING_PAYMENT dan user bisa login
        // lalu retry payment dari halaman riwayat.
        DB::table('app_user')->where('id_user', $request->user_id)->update([
            'otp_user'     => null,
            'verify_token' => null,
            'status_user'  => 1,
            'updated_at'   => now(),
        ]);

        DB::table('t_event_registrasi')
            ->where('id_user', $request->user_id)
            ->where('status_registrasi', 'PENDING_OTP')
            ->update(['status_registrasi' => 'PENDING_PAYMENT', 'updated_at' => now()]);

        $this->dataService->createLogWeb($request, 'verifyOtpRegistrasi', 'OTP verified for user_id=' . $request->user_id);

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
        DB::table('app_user')->where('id_user', $request->user_id)->update(['otp_user' => $otp_reg, 'updated_at' => now()]);

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

    private function buildSnapToken(object $user, string $kodeEvent, array $packages): array
    {
        $totalAmount = 0;
        $itemDetails = [];

        $event      = DB::table('t_event')->where('kode_event', $kodeEvent)->first();
        $hargaEvent = (float) ($event->harga_event ?? 0);
        if ($hargaEvent > 0) {
            $totalAmount += $hargaEvent;
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
                    $totalAmount += (float) $pkg->harga_paket;
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
            ->where('id_midtrans', 1)
            ->where('is_active', 'Y')
            ->first();

        if (!$midtransConfig || empty($midtransConfig->server_key)) {
            return ['success' => false, 'message' => 'Konfigurasi Midtrans belum diatur. Hubungi administrator.'];
        }

        $isProduction = $midtransConfig->environment === 'production';
        $snapApiUrl   = $isProduction
            ? 'https://api.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $orderId = 'REG-' . $user->id_user . '-' . time();

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
                    : ($res['message'] ?? 'Gagal mendapatkan SNAP token dari Midtrans.');
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
                    'status_message'     => 'SNAP token created - event registration',
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
            return ['success' => false, 'message' => 'Gagal membuat token pembayaran: ' . $e->getMessage()];
        }
    }

    public function getRegistrationSnapToken(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|integer',
            'kode_event' => 'required|string',
        ]);

        $user = DB::table('app_user')->where('id_user', $request->user_id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $packages = $request->packages ?? [];
        $result   = $this->buildSnapToken($user, $request->kode_event, $packages);

        return response()->json($result);
    }

    /**
     * Retry payment - dipanggil dari halaman riwayat (user sudah login).
     */
    public function retryPayment(Request $request)
    {
        $request->validate([
            'kode_event' => 'required|string',
            'packages'   => 'nullable|array',
        ]);

        $userId = session('id_user');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please login again.'], 401);
        }

        $user = DB::table('app_user')->where('id_user', $userId)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        // Pastikan registrasi yang bisa di-retry: PENDING_PAYMENT atau PAYMENT_EXPIRED
        $reg = DB::table('t_event_registrasi')
            ->where('id_user', $userId)
            ->where('kode_event', $request->kode_event)
            ->whereIn('status_registrasi', ['PENDING_PAYMENT', 'PAYMENT_EXPIRED'])
            ->first();

        if (!$reg) {
            return response()->json(['success' => false, 'message' => 'No pending registration found for this event.']);
        }

        // Ambil paket yang sudah dipilih sebelumnya jika tidak dikirim ulang
        $packages = $request->packages ?? [];
        if (empty($packages)) {
            $addonKodes = DB::table('t_event_addon')
                ->where('id_user', $userId)
                ->where('kode_event', $request->kode_event)
                ->pluck('kode_paket')
                ->toArray();
            $packages = $addonKodes;
        }

        // Reset status ke PENDING_PAYMENT agar konsisten
        DB::table('t_event_registrasi')
            ->where('id_user', $userId)
            ->where('kode_event', $request->kode_event)
            ->update(['status_registrasi' => 'PENDING_PAYMENT', 'updated_at' => now()]);

        $result = $this->buildSnapToken($user, $request->kode_event, $packages);

        return response()->json($result);
    }

    /**
     * Callback setelah retry payment sukses (dipanggil dari JS Snap onSuccess).
     */
    public function retryPaymentCallback(Request $request)
    {
        $request->validate([
            'kode_event' => 'required|string',
        ]);

        $userId = session('id_user');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
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

        $packages = $request->packages ?? [];
        $this->enrollUser($userId, $request->kode_event, $packages);

        return response()->json(['success' => true, 'message' => 'Payment confirmed. You are now enrolled.']);
    }

    /**
     * Midtrans server-to-server notification webhook.
     * Daftarkan URL ini di dashboard Midtrans: /society-event/midtrans-notification
     * Route ini bypass CSRF.
     */
    public function midtransNotification(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification', $payload);

        $orderId           = $payload['order_id']           ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status']       ?? null;
        $grossAmount       = $payload['gross_amount']       ?? 0;
        $paymentType  