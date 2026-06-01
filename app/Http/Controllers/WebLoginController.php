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
            'menu' => 'login',
            'menu_aktif' => $menu_aktif,
            'set' =>  DB::table('app_setting')->where('id_setting', 1)->first(),
        ];

        return view('web.login', $data);
    }

    /**
     * Show register page.
     * If ?event={kode_event} is passed, load event context for the left panel.
     */
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

        $data = [
            'menu'       => 'register',
            'menu_aktif' => $menu_aktif,
            'set'        => DB::table('app_setting')->where('id_setting', 1)->first(),
            'event'      => $event,
        ];

        return view('web.register', $data);
    }

    /**
     * Generate a unique kode_registrasi.
     * Format: REG-{YmdHis}-{random4}
     */
    private function generateKodeRegistrasi(): string
    {
        do {
            $kode = 'REG-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (DB::table('t_event_registrasi')->where('kode_registrasi', $kode)->exists());

        return $kode;
    }

    /**
     * STEP 1: Create user account + send OTP registration code.
     * Returns user_id on success so frontend can proceed to OTP step.
     */
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

        // Generate OTP for registration verification
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

        // Save role_event to t_event_registrasi (pending, before payment)
        if ($request->filled('kode_event')) {
            $ev = DB::table('t_event')->where('kode_event', $request->kode_event)->where('status_event', 'Y')->first();
            if ($ev) {
                DB::table('t_event_registrasi')->insert([
                    'kode_registrasi'   => $this->generateKodeRegistrasi(),
                    'kode_event'        => $ev->kode_event,
                    'id_user'           => $userId,
                    'role_peserta'      => $request->role_event ?? 'participant',
                    'status_registrasi' => 'PENDING_OTP',
                    'created_at'        => now(),
                ]);
            }
        }

        // Send OTP email
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
            'success' => true,
            'message' => 'Account created. Please verify your email with the OTP we sent.',
            'user_id' => $userId,
        ]);
    }

    /**
     * STEP 2: Verify OTP entered by user during registration.
     */
    public function verifyOtpRegistrasi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'otp'     => 'required|string|min:6|max:6',
        ]);

        $user = DB::table('app_user')->where('id_user', $request->user_id)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        if ($user->otp_user !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please check your email and try again.']);
        }

        // Mark OTP verified, clear otp field
        DB::table('app_user')->where('id_user', $request->user_id)->update([
            'otp_user'     => null,
            'verify_token' => null,
            'status_user'  => 0,
            'updated_at'   => now(),
        ]);

        // Update registrasi status
        DB::table('t_event_registrasi')
            ->where('id_user', $request->user_id)
            ->where('status_registrasi', 'PENDING_OTP')
            ->update(['status_registrasi' => 'PENDING_PAYMENT', 'updated_at' => now()]);

        $this->dataService->createLogWeb($request, 'verifyOtpRegistrasi', 'OTP verified for user_id=' . $request->user_id);

        return response()->json(['success' => true, 'message' => 'OTP verified successfully.']);
    }

    /**
     * Resend registration OTP.
     */
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

    /**
     * STEP 3: Return available packages for an event.
     */
    public function getEventPackages(Request $request)
    {
        $request->validate(['kode_event' => 'required|string']);

        $packages = DB::table('t_event_paket')
            ->where('event_kode_paket', $request->kode_event)
            ->orderBy('id_event_paket', 'asc')
            ->get();

        return response()->json(['success' => true, 'packages' => $packages]);
    }

    /**
     * STEP 4a: Generate Midtrans Snap Token for registration payment.
     */
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

        $packages    = $request->packages ?? [];
        $totalAmount = 0;
        $itemDetails = [];

        foreach ($packages as $kode_paket) {
            $pkg = DB::table('t_event_paket')->where('kode_paket', $kode_paket)->first();
            if ($pkg && $pkg->harga_paket > 0) {
                $totalAmount += $pkg->harga_paket;
                $itemDetails[] = [
                    'id'       => $pkg->kode_paket,
                    'price'    => (int) $pkg->harga_paket,
                    'quantity' => 1,
                    'name'     => substr($pkg->judul_paket, 0, 50),
                ];
            }
        }

        if ($totalAmount <= 0) {
            return response()->json(['success' => false, 'message' => 'No paid packages selected.', 'snap_token' => null]);
        }

        $orderId = 'REG-' . $request->user_id . '-' . time();

        $midtransConfig = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $user->nama_user,
                'email'      => $user->username_user,
                'phone'      => $user->telepon_user,
            ],
            'item_details' => $itemDetails,
        ];

        \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($midtransConfig);

            DB::table('app_midtrans_transaction')->insert([
                'order_id'     => $orderId,
                'user_id'      => $request->user_id,
                'gross_amount' => $totalAmount,
                'status'       => 'pending',
                'snap_token'   => $snapToken,
                'kode_event'   => $request->kode_event,
                'created_at'   => now(),
            ]);

            return response()->json(['success' => true, 'snap_token' => $snapToken, 'order_id' => $orderId]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create payment: ' . $e->getMessage()]);
        }
    }

    /**
     * STEP 4b: Handle free enrollment (no packages or all free).
     */
    public function enrollEventFree(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|integer',
            'kode_event' => 'required|string',
        ]);

        $this->enrollUser($request->user_id, $request->kode_event, $request->packages ?? []);

        return response()->json(['success' => true, 'message' => 'Enrolled successfully.']);
    }

    /**
     * STEP 4c: Midtrans payment callback after successful payment.
     */
    public function paymentRegistrationCallback(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|integer',
            'kode_event' => 'required|string',
        ]);

        $midtransResult = json_decode($request->midtrans_result, true);

        if (!empty($midtransResult['order_id'])) {
            DB::table('app_midtrans_transaction')
                ->where('order_id', $midtransResult['order_id'])
                ->update([
                    'status'       => $midtransResult['transaction_status'] ?? 'settlement',
                    'payment_type' => $midtransResult['payment_type'] ?? null,
                    'updated_at'   => now(),
                ]);
        }

        $this->enrollUser($request->user_id, $request->kode_event, $request->packages ?? []);

        return response()->json(['success' => true, 'message' => 'Payment confirmed. Enrolled successfully.']);
    }

    /**
     * Internal: enroll user into event + activate account.
     */
    private function enrollUser($userId, $kodeEvent, $packages = [])
    {
        DB::table('app_user')->where('id_user', $userId)->update([
            'status_user' => 1,
            'updated_at'  => now(),
        ]);

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
                $existing = DB::table('t_event_addon')
                    ->where('id_user', $userId)
                    ->where('kode_paket', $kode_paket)
                    ->exists();
                if (!$existing) {
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
            return "Token tidak valid!";
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

        $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $upd = DB::table('app_user')->where('id_user', $user->id_user)->update(['otp_user' => $otp]);

        $id_hash  = Crypt::encrypt($user->id_user);
        $data_log = ['email' => $request->email, 'nama' => $user->nama_user, 'otp' => $otp];

        if ($upd) {
            Mail::to($request->email)->queue(
                new AppMail(
                    'web.email-otp-login',
                    ['nama' => $user->nama_user, 'otp' => $otp],
                    'OTP Login - ' . env('APP_NAME', 'Society Event')
                )
            );

            $this->dataService->createLogWeb($request, 'loginAction', 'OTP sent successfully', '', json_encode($data_log));

            return response()->json([
                'status'  => true,
                'message' => 'OTP code has been sent to your email.',
                'key'     => $id_hash
            ]);
        } else {
            $this->dataService->createLogWeb($request, 'loginAction', 'Failed to send OTP', '', json_encode($data_log));
            return response()->json(['status' => false, 'message' => 'Failed to send OTP. Please try again.', 'key' => '']);
        }
    }

    public function otpLogin($key, Request $request)
    {
        $menu_aktif = 'otp';
       
        $data = [
            'menu'       => 'OTP Login',
            'menu_aktif' => $menu_aktif,
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

        $data_log = ['email' => $user->username_user, 'nama' => $user->nama_user, 'otp' => $request->otp];

        $upd = DB::table('app_user')->where('id_user', $user->id_user)->update(['otp_user' => '']);

        if ($upd) {
            $this->dataService->createLogWeb($request, 'verifyOtpAction', 'OTP verified successfully', '', json_encode($data_log));
            return response()->json(['status' => true, 'message' => 'OTP verified.']);
        } else {
            $this->dataService->createLogWeb($request, 'verifyOtpAction', 'OTP verification failed', '', json_encode($data_log));
            return response()->json(['status' => false, 'message' => 'OTP verification failed. Please try again.']);
        }
    }

    public function lupaPassword(Request $request)
    {
        $menu_aktif = 'lupa password';
        $data = [
            'menu'       => 'Forgot Password',
            'menu_aktif' => $menu_aktif,
            'set'        => DB::table('app_setting')->where('id_setting', 1)->first(),
        ];
        return view('web.lupa-password', $data);
    }

    public function passwordBaru(Request $request, $token)
    {
        $menu_aktif = 'password baru';
        $data = [
            'menu'       => 'New Password',
            'menu_aktif' => $menu_aktif,
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

        $user = DB::table('app_user')->where('username_user', $request->email)->count();
        if ($user < 1) {
            return response()->json(['success' => false, 'message' => 'Email is not registered.'], 422);
        }

        $user_dt = DB::table('app_user')->where('username_user', $request->email)->first();
        $token   = Str::random(64);

        DB::table('app_user')->where('username_user', $request->email)->update(['verify_token' => $token]);

        $verificationUrl = url(env('APP_ROUTE') . '/password-baru/' . $token);
        Mail::to($request->email)->queue(
            new AppMail(
                'web.email-lupa-password',
                ['nama' => $user_dt->nama_user, 'verificationUrl' => $verificationUrl],
                'Forgot Password - ' . env('APP_NAME', 'Society Event')
            )
        );

        $this->dataService->createLogWeb($request, 'lupaPasswordAction', 'Forgot password request submitted.');
        return response()->json(['success' => true, 'message' => 'Request submitted. Please check your email for the reset link.']);
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

        $user = DB::table('app_user')->where('verify_token', $request->token)->count();
        if ($user < 1) {
            return response()->json(['success' => false, 'message' => 'Invalid token.'], 422);
        }

        if ($request->password != $request->konfirmasi) {
            return response()->json(['success' => false, 'message' => 'Password confirmation does not match.'], 422);
        }

        $det_user = DB::table('app_user')->where('verify_token', $request->token)->first();
        $password = Hash::make($request->password);

        $update = DB::table('app_user')->where('id_user', $det_user->id_user)->update([
            'verify_token'  => '',
            'password_user' => $password
        ]);

        if ($update) {
            $this->dataService->createLogWeb($request, 'ganitPasswordAction', 'Password changed successfully.');
            return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
        } else {
            $this->dataService->createLogWeb($request, 'ganitPasswordAction', 'Failed to change password.');
            return response()->json(['success' => false, 'message' => 'Failed to change password. Please try again.']);
        }
    }
}
