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
        // if (!$request->session()->has('id')) {
        //     return Redirect::to('/login-backend');
        // }
        $menu_aktif = 'login';
        // $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
       
        $data = [
            'menu' => 'login',
            'menu_aktif' => $menu_aktif,
            'set' =>  DB::table('app_setting')->where('id_setting', 1)->first(),
            

        ];

        return view('web.login', $data);

    }

    public function registrasiAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'email' => 'required|email|max:200|unique:app_user,username_user',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',        
                'regex:/[a-z]/',        
                'regex:/[0-9]/',        
                'regex:/[@$!%*#?&._-]/' 
            ],
            'identitas' => 'required',
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pekerjaan' => 'required',
            'telepon' => 'required',
            'alamat' => 'required'
        ], [
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            'file.image' => 'File harus berupa gambar.',
            'file.mimes' => 'Format file harus jpg, jpeg, atau png.',
            'file.max' => 'Ukuran file maksimal 2MB.',
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
            $path = $request->file('file')->storeAs('user', $filename, 'public' );

        }

        $dt_role = DB::table('reff_role')
            ->where('kode_role', 'PUB')
            ->first();
        $token = Str::random(64);
        $insert = DB::table('app_user')->insert([
            'nama_user' => $request->nama,
            'role_id' => $dt_role->id_role,
            'username_user' => $request->email,
            'password_user' => $password,
            'identitas_user' => $request->identitas,
            'file_identitas_user' => $path,
            'status_user' => 0,
            'telepon_user' => $request->telepon,
            'pekerjaan_user' => $request->pekerjaan,
            'alamat_user' => $request->alamat,
            'verify_token' => $token,
            'created_at' => now(),
        ]);

        if($insert){
            $this->dataService->setMailConfig();
            $verificationUrl = url(env('APP_ROUTE') . '/verifikasiAkun/' . $token);
           
            Mail::to($request->email)->queue(
                new AppMail(
                    'web.email-verifikasi-akun',
                    [
                        'nama' => $request->nama,
                        'verificationUrl' => $verificationUrl
                    ],
                    'Verifikasi Akun Satu Data Pertahanan'
                )
            );

            $this->dataService->createLogWeb(
                $request,
                'registrasiAction',
                'Berhasil registrasi akun'
            );
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil, silakan cek email untuk konfirmasi akun'
            ]);

        }else{

            $this->dataService->createLogWeb(
                $request,
                'registrasiAction',
                'Gagal registrasi akun'
            );

            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal'
            ]);

        }

    }

    public function verifikasiAkun($token)
    {
        $user = DB::table('app_user')->where('verify_token', $token)->first();

        if(!$user){
            return "Token tidak valid!";
        }

        DB::table('app_user')
            ->where('id_user', $user->id_user)
            ->update([
                'status_user' => 1, 
                'verify_token' => null
            ]);

            return redirect()->route('login')->with('success', 'Akun berhasil diverifikasi, silakan login.');
    }

    
    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('app_user as u')
            ->select('u.*')
            ->where('u.username_user', $request->email)
            ->where('u.role_id', 4)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        if (!$user->status_user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak aktif, silahkan melakukan verifikasi akun pada saat setelah registrasi'
            ]);
        }

        if (!Hash::check($request->password, $user->password_user)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah'
            ]);
        }

        $this->dataService->setMailConfig();

        $otp = str_pad(
            mt_rand(0, 999999),
            6,
            '0',
            STR_PAD_LEFT
        );

        $upd = DB::table('app_user')
            ->where('id_user', $user->id_user)
            ->update([
                'otp_user' => $otp
            ]);

        $id_hash = Crypt::encrypt($user->id_user);

        $data_log = [
            'email' => $request->email,
            'nama' => $user->nama_user,
            'otp' => $otp
        ];

        if ($upd) {

            Mail::to($request->email)->queue(
                new AppMail(
                    'web.email-otp-login',
                    [
                        'nama' => $user->nama_user,
                        'otp' => $otp
                    ],
                    'OTP Login Satu Data Pertahanan'
                )
            );

            $this->dataService->createLogWeb(
                $request,
                'loginAction',
                'Berhasil mengirimkan kode OTP',
                '',
                json_encode($data_log)
            );

            return response()->json([
                'status' => true,
                'message' => 'Kode OTP berhasil dikirimkan ke email anda',
                'key' => $id_hash
            ]);

        } else {

            $this->dataService->createLogWeb(
                $request,
                'loginAction',
                'Gagal mengirimkan kode OTP',
                '',
                json_encode($data_log)
            );

            return response()->json([
                'status' => false,
                'message' => 'Kode OTP gagal dikirimkan ke email anda, silahkan coba login kembali',
                'key' => ''
            ]);
        }
    }

    public function otpLogin($key, Request $request)
    {
        
        $menu_aktif = 'otp';
       
        $data = [
            'menu' => 'OTP Login',
            'menu_aktif' => $menu_aktif,
            'key' => $key,
            'set' =>  DB::table('app_setting')->where('id_setting', 1)->first(),
            

        ];

        return view('web.otp-login', $data);

    }

    
    public function verifyOtpAction(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'otp' => 'required',
        ]);

        $id = Crypt::decrypt($request->key);

        $user = DB::table('app_user as u')
            ->select('u.*')
            ->where('u.id_user', $id)
            ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User tidak ditemukan']);
        }

        if ($request->otp != $user->otp_user) {
            return response()->json(['status' => false, 'message' => 'Kode OTP salah']);
        }

       
        session([
            'id_user'   => $user->id_user,
            'nama_user' => $user->nama_user,
            'email_user'=> $user->username_user,
            'identitas_user' => $user->identitas_user,
            'file_identitas_user' => $user->file_identitas_user,
            'jenis_user' => 'publik',
        ]);

         $data_log = [
            'email' => $user->username_user,
            'nama' => $user->nama_user,
            'otp' => $request->otp
        ];

        $upd = DB::table('app_user')
            ->where('id_user', $user->id_user)
            ->update([
                'otp_user' => ''
            ]);

        
        if($upd){
            $this->dataService->createLogWeb($request,'verifyOtpAction' ,'Berhasil verifikasi kode OTP','',json_encode($data_log));
            return response()->json(['status' => true, 'message' => 'Kode OTP sesuai']);
        }else{
            $this->dataService->createLogWeb($request,'verifyOtpAction' ,'Gagal mengirimkan kode OTP','',json_encode($data_log));
            return response()->json(['status' => false, 'message' => 'gagal verifikasi OTP, silahkan coba kembali']);
        }
        
    }


    public function lupaPassword(Request $request)
    {
        
        $menu_aktif = 'lupa password';
       
        $data = [
            'menu' => 'Lupa Password',
            'menu_aktif' => $menu_aktif,
            'set' =>  DB::table('app_setting')->where('id_setting', 1)->first(),
            

        ];

        return view('web.lupa-password', $data);

    }

    public function passwordBaru(Request $request, $token)
    {
        
        $menu_aktif = 'password baru';
       
        $data = [
            'menu' => 'Password Baru',
            'menu_aktif' => $menu_aktif,
            'token' => $token,
            'set' =>  DB::table('app_setting')->where('id_setting', 1)->first(),
            

        ];

        return view('web.password-baru', $data);

    }

    public function lupaPasswordAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'  => 'required|email|max:200',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = DB::table('app_user')->where('username_user', $request->email)->count();
        if($user < 1){
             return response()->json([
                'success' => false,
                'message' => 'email tidak terdaftar'
            ], 422);
        }

        $user_dt = DB::table('app_user')->where('username_user', $request->email)->first();
        $token = Str::random(64);
        $insert = DB::table('app_user')
            ->where('username_user', $request->email)
            ->update([
                'verify_token' => $token
            ]);



        if($insert){
            $verificationUrl = url(env('APP_ROUTE') . '/password-baru/' . $token);
            Mail::to($request->email)->queue(
                new AppMail(
                    'web.email-lupa-password',
                    [
                        'nama' => $user_dt->nama_user,
                        'verificationUrl' => $verificationUrl
                    ],
                    'Lupa Passowrd Akun Satu Data Pertahanan'
                )
            );


            $this->dataService->createLogWeb($request,'lupaPasswordAction' ,'Berhasil mengajukan lupa password');
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan lupa password berhasil, Silahkan cek email untuk proses selanjutnya'
            ]);
        }else{
            $this->dataService->createLogWeb($request,'lupaPasswordAction' ,'Gagal mengajukan lupa password');
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan lupa password gagal'
            ]);
        }
        
        
    }

    
    public function ganitPasswordAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password'  => 'required|max:200',
            'konfirmasi'  => 'required|max:200',
            'token' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = DB::table('app_user')->where('verify_token', $request->token)->count();
        if($user < 1){
             return response()->json([
                'success' => false,
                'message' => 'invalid token'
            ], 422);
        }

        if($request->password != $request->konfirmasi){
            return response()->json([
                'success' => false,
                'message' => 'konfirmasi password tidak valid'
            ], 422);
        }

        $det_user = DB::table('app_user')->where('verify_token', $request->token)->first();
        $password = Hash::make($request->password);
        $insert = DB::table('app_user')
            ->where('id_user', $det_user->id_user)
            ->update([
                'verify_token' => '',
                'password_user' => $password
            ]);



        if($insert){
            
            $this->dataService->createLogWeb($request,'ganitPasswordAction' ,'Berhasil ganti password');
            return response()->json([
                'success' => true,
                'message' => 'Berhasil ganti password'
            ]);
        }else{
            $this->dataService->createLogWeb($request,'ganitPasswordAction' ,'Gagal ganti password');
            return response()->json([
                'success' => false,
                'message' => 'Gagal ganti password'
            ]);
        }
        
    }


    


}
