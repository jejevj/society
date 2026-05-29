<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use App\Models\ReffUser;
use App\Models\ReffOrganisasi;
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






class LoginController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }


    public function index()
    {
        $menu_aktif = '/user||/refference';
        $navbar = '';
        $data = [
            'menu' => 'Login Backend',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => ''
        ];
        return view('admin-panel.login.main', $data);
    }

    
    public function loginBackendAction(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('app_user as u')
            ->leftJoin('reff_role as r', 'r.id_role', '=', 'u.role_id')
            ->select(
                'u.*',
                'r.nama_role',
                'r.kode_role',
                'r.all_data_role'
            )
            ->where('u.username_user', $request->username)
            ->where('r.kode_role', '<>', 'PUB')
            ->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        if (!Hash::check($request->password, $user->password_user)) {
            return response()->json(['status' => false, 'message' => 'Incorrect password']);
        }

        session([
            'id'   => $user->id_user,
            'nama' => $user->nama_user,
            'username'=> $user->username_user,
            'all_data'=> $user->all_data_role,
            'kode_role' => $user->kode_role,
            'id_role' => $user->role_id,
        ]);

        $upd = true;
        if($upd){
            $this->dataService->createLog($request,'verifyOtpAdminPanelAction' ,'Login Successfully','','');
             return response()->json([
                'status' => true,
                'message' => 'Login Successfully',
                'redirect' => url(env('APP_ROUTE') . '/dashboard') 
            ]);
        }else{
            $this->dataService->createLog($request,'verifyOtpAdminPanelAction' ,'Login Failed','',json_encode($data_log));
            return response()->json(['status' => false, 'message' => 'Login failed, please try again', 'redirect' => '']);
        }

    }

    
    public function otpAdminPanelLogin($key, Request $request)
    {
        
        $menu_aktif = 'otp';
       
        $data = [
            'menu' => 'OTP Admin Panel Login',
            'menu_aktif' => $menu_aktif,
            'key' => $key
            

        ];

        return view('admin-panel.login.otp-login', $data);

    }

    
    public function verifyOtpAdminPanelAction(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'otp' => 'required',
        ]);

        $id = Crypt::decrypt($request->key);

        $user = DB::table('app_user as u')
            ->leftJoin('reff_role as r', 'r.id_role', '=', 'u.role_id')
            ->leftJoin('reff_organisasi as o', 'o.id_organisasi', '=', 'u.organisasi_id')
            ->select(
                'u.*',
                'r.nama_role',
                'r.kode_role',
                'r.all_data_role',
                'o.nama_organisasi',
                'o.kode_organisasi'
            )
            ->where('u.id_user', $id)
            ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User tidak ditemukan']);
        }

        if ($request->otp != $user->otp_user) {
            return response()->json(['status' => false, 'message' => 'Kode OTP salah']);
        }

       
        session([
            'id'   => $user->id_user,
            'nama' => $user->nama_user,
            'username'=> $user->username_user,
            'all_data'=> $user->all_data_role,
            'kode_organisasi' => $user->kode_organisasi,
            'kode_role' => $user->kode_role,
            'id_role' => $user->role_id,
            'id_organisasi' => $user->organisasi_id,
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
            $this->dataService->createLog($request,'verifyOtpAdminPanelAction' ,'Berhasil verifikasi kode OTP','',json_encode($data_log));
            return response()->json(['status' => true, 'message' => 'Kode OTP sesuai']);
        }else{
            $this->dataService->createLog($request,'verifyOtpAdminPanelAction' ,'Gagal mengirimkan kode OTP','',json_encode($data_log));
            return response()->json(['status' => false, 'message' => 'gagal verifikasi OTP, silahkan coba kembali']);
        }
        
    }

    
    public function logoutBackendAction(Request $request)
    {
        $data_log = [
            'username' => session('username'),
            'nama' => session('nama')
        ];
        $this->dataService->createLog($request,'logoutBackendAction' ,'Successfully logged out','',json_encode($data_log));
        Session::flush();

        Auth::logout();
        
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }


}
