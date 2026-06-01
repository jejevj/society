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
     * If ?event={kode_event} is passed in the URL, load event data
     * so the register view can show event context on the left panel.
     */
    public function register(Request $request)
    {
        $menu_aktif = 'register';

        // Load event data when kode_event query param is present
        $event = null;
        if ($request->filled('event')) {
            $event = DB::table('t_event as e')
                ->where('e.kode_event', $request->event)
                ->where('e.status', 'Y')
                ->first();

            if ($event) {
                // Attach kolaborasi
                $event->kolaborasi = DB::table('t_event_kolaborasi')
                    ->where('kode_event', $event->kode_event)
                    ->get();

                // Attach paket
                $event->paket = DB::table('t_event_paket')
                    ->where('kode_event', $event->kode_event)
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

    public function registrasiAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama'         => 'required|string|max:200',
            'email'        => 'required|email|max:200|unique:app_user,username_user',
            'password'     => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&._-]/'
            ],
            'identitas'      => 'required',
            'file'           => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pekerjaan'      => 'required',
            'telepon'        => 'required',
            'alamat'         => 'required',
            'organisasi'     => 'required|string|max:255',
            'tipe_organisasi' => 'required|string|max:100',
        ], [
            'email.email'     => 'Invalid email format.',
            'email.unique'    => 'Email is already registered.',
            'password.min'    => 'Password must be at least 8 characters.',
            'password.regex'  => 'Password must contain uppercase, lowercase, number, and special character.',
            'file.image'      => 'File must be an image.',
            'file.mimes'      => 'File format must be jpg, jpeg, or png.',
            'file.max'        => 'Maximum file size is 2MB.',
            'organisasi.required'      => 'Organization name is required.',
            'tipe_organisasi.required' => 'Organization type is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $password = Hash::make($request->password);

        if ($request->hasFile('file')) {
            $file_post = $request->file('file');
            $scan = $this->dataService->scanAntivirus($file_post);
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

        $dt_role = DB::table('reff_role')
            ->where('kode_role', 'PUB')
            ->first();

        $token  = Str::random(64);
        $insert = DB::table('app_user')->insertGetId([
            'nama_user'           => $request->nama,
            'role_id'             => $dt_role->id_role,
            'username_user'       => $request->email,
            'password_user'       => $password,
            'identitas_user'      => $request->identitas,
            'file_identitas_user' => $path,
            'status_user'         => 0,
            'telepon_user'        => $request->telepon,
            'pekerjaan_user'      => $request->pekerjaan,
            'alamat_user'         => $request->alamat,
            'organisasi_user'     => $request->organisasi,
            'tipe_organisasi_user' => $request->tipe_organisasi,
            'verify_token'        => $token,
            'created_at'          => now(),
        ]);

        if ($insert) {

            // If registering for a specific event, insert into t_event_registrasi
            if ($request->filled('kode_event') && $request->filled('role_event')) {
                $eventData = DB::table('t_event')
                    ->where('kode_event', $request->kode_event)
                    ->where('status', 'Y')
                    ->first();

                if ($eventData) {
                    DB::table('t_event_registrasi')->insert([
                        'kode_event'        => $eventData->kode_event,
                        'id_user'           => $insert,
                        'role_peserta'      => $request->role_event,
                        'status_registrasi' => 'PENDING',
                        'created_at'        => now(),
                    ]);
                }
            }

            $this->dataService->setMailConfig();
            $verificationUrl = url(env('APP_ROUTE') . '/verifikasiAkun/' . $token);

            Mail::to($request->email)->queue(
                new AppMail(
                    'web.email-verifikasi-akun',
                    [
                        'nama'            => $request->nama,
                        'verificationUrl' => $verificationUrl
                    ],
                    'Account Verification - ' . ($request->filled('kode_event') ? 'Event Registration' : env('APP_NAME', 'Society Event'))
                )
            );

            $this->dataService->createLogWeb(
                $request,
                'registrasiAction',
                'Registration successful' . ($request->filled('kode_event') ? ' for event ' . $request->kode_event : '')
            );

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Please check your email to verify your account.'
            ]);

        } else {

            $this->dataService->createLogWeb(
                $request,
                'registrasiAction',
                'Registration failed'
            );

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ]);
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
            ->update([
                'status_user'  => 1,
                'verify_token' => null
            ]);

        return redirect()->route('login')->with('success', 'Account verified successfully. Please login.');
    }

    
    public function loginAction(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('app_user as u')
            ->select('u.*')
            ->where('u.username_user', $request->email)
            ->where('u.role_id', 4)
            ->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.'
            ]);
        }

        if (!$user->status_user) {
            return response()->json([
                'status'  => false,
                'message' => 'Account is not active. Please verify your email first.'
            ]);
        }

        if (!Hash::check($request->password, $user->password_user)) {
            return response()->json([
                'status'  => false,
                'message' => 'Incorrect password.'
            ]);
        }

        $this->dataService->setMailConfig();

        $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $upd = DB::table('app_user')
            ->where('id_user', $user->id_user)
            ->update(['otp_user' => $otp]);

        $id_hash  = Crypt::encrypt($user->id_user);
        $data_log = [
            'email' => $request->email,
            'nama'  => $user->nama_user,
            'otp'   => $otp
        ];

        if ($upd) {
            Mail::to($request->email)->queue(
                new AppMail(
                    'web.email-otp-login',
                    [
                        'nama' => $user->nama_user,
                        'otp'  => $otp
                    ],
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

            return response()->json([
                'status'  => false,
                'message' => 'Failed to send OTP. Please try again.',
                'key'     => ''
            ]);
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
        $request->validate([
            'key' => 'required',
            'otp' => 'required',
        ]);

        $id   = Crypt::decrypt($request->key);
        $user = DB::table('app_user as u')
            ->select('u.*')
            ->where('u.id_user', $id)
            ->first();

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

        $data_log = [
            'email' => $user->username_user,
            'nama'  => $user->nama_user,
            'otp'   => $request->otp
        ];

        $upd = DB::table('app_user')
            ->where('id_user', $user->id_user)
            ->update(['otp_user' => '']);

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
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = DB::table('app_user')->where('username_user', $request->email)->count();
        if ($user < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Email is not registered.'
            ], 422);
        }

        $user_dt = DB::table('app_user')->where('username_user', $request->email)->first();
        $token   = Str::random(64);

        $insert = DB::table('app_user')
            ->where('username_user', $request->email)
            ->update(['verify_token' => $token]);

        if ($insert) {
            $verificationUrl = url(env('APP_ROUTE') . '/password-baru/' . $token);
            Mail::to($request->email)->queue(
                new AppMail(
                    'web.email-lupa-password',
                    [
                        'nama'            => $user_dt->nama_user,
                        'verificationUrl' => $verificationUrl
                    ],
                    'Forgot Password - ' . env('APP_NAME', 'Society Event')
                )
            );

            $this->dataService->createLogWeb($request, 'lupaPasswordAction', 'Forgot password request submitted.');
            return response()->json([
                'success' => true,
                'message' => 'Request submitted. Please check your email for the reset link.'
            ]);
        } else {
            $this->dataService->createLogWeb($request, 'lupaPasswordAction', 'Forgot password request failed.');
            return response()->json([
                'success' => false,
                'message' => 'Request failed. Please try again.'
            ]);
        }
    }

    
    public function ganitPasswordAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'   => 'required|max:200',
            'konfirmasi' => 'required|max:200',
            'token'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = DB::table('app_user')->where('verify_token', $request->token)->count();
        if ($user < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token.'
            ], 422);
        }

        if ($request->password != $request->konfirmasi) {
            return response()->json([
                'success' => false,
                'message' => 'Password confirmation does not match.'
            ], 422);
        }

        $det_user = DB::table('app_user')->where('verify_token', $request->token)->first();
        $password = Hash::make($request->password);

        $update = DB::table('app_user')
            ->where('id_user', $det_user->id_user)
            ->update([
                'verify_token' => '',
                'password_user' => $password
            ]);

        if ($update) {
            $this->dataService->createLogWeb($request, 'ganitPasswordAction', 'Password changed successfully.');
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully.'
            ]);
        } else {
            $this->dataService->createLogWeb($request, 'ganitPasswordAction', 'Failed to change password.');
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password. Please try again.'
            ]);
        }
    }
}
