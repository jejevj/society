<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
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



class ProfilController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        if (!$request->session()->has('id')) {
            return Redirect::to('/login-backend');
        }
        $menu_aktif = 'profil||akun';
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Profile',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
                            <li class="breadcrumb-item "><a class=" text-hover-primary">Home</a></li>
							<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
							<li class="breadcrumb-item ">Settings</li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
							<li class="breadcrumb-item ">Profile</li>
                            </ul>',
            'detail' => DB::table('app_user')
                        ->leftJoin('reff_role', 'reff_role.id_role', '=', 'app_user.role_id')
                        ->where('id_user', session('id'))->first(),
        ];

        return view('admin-panel.pengaturan-akun.profil', $data);

        
    }

    
    public function updateProfilAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama'       => 'required|string|max:200',
            'username'       => 'required',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

         $updateData = [
            'nama_user'       => $request->nama,
            'username_user'       => $request->username,
            'updated_at'            => now(),
        ];

        if ($request->hasFile('foto')) {
            $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
            $path = $request->file('foto')->storeAs('organisasi', $filename, 'public');

            $updateData['foto_user'] = $path;
        }
        $id =  session('id');
        $dt_exist = DB::table('app_user')->where('id_user', $id)->first();

        $cek_duplikat_username = DB::table('app_user')->where('id_user','!=', $id)->where('username_user', $request->username)->count();
        if($cek_duplikat_username > 0){
            $this->dataService->createLog($request,'updateProfilAction' ,'Failed to change profile data',json_encode($updateData),json_encode($dt_exist));
            return response()->json([
                'success' => false,
                'message' => 'Email has been registered'
            ]);
        }
        $update = DB::table('app_user')->where('id_user', $id)->update($updateData);


        if($update){
            $this->dataService->createLog($request,'updateProfilAction' ,'Profile updated successfully',json_encode($updateData),json_encode($dt_exist));
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);
        }else{
            $this->dataService->createLog($request,'updateProfilAction' ,'Profile failed to update',json_encode($updateData),json_encode($dt_exist));
            return response()->json([
                'success' => false,
                'message' => 'Profile failed to update'
            ]);
        }
        
    }

    

    public function gantiPassword(Request $request)
    {
        if (!$request->session()->has('id')) {
            return Redirect::to('/login-backend');
        }
        $menu_aktif = 'password||akun';
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Change Password',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
                            <li class="breadcrumb-item "><a class=" text-hover-primary">Home</a></li>
							<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
							<li class="breadcrumb-item ">Settings</li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
							<li class="breadcrumb-item ">Change Password</li>
                            </ul>',
        ];
        return view('admin-panel.pengaturan-akun.ganti-password', $data);
    }

    
    public function updatePasswordAction(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'password_lama' => 'required|string|max:200',

            'password_baru' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',        
                'regex:/[a-z]/',        
                'regex:/[0-9]/',        
                'regex:/[@$!%*#?&._-]/' 
            ],

            'konfirmasi_password_baru' => 'required|same:password_baru',

        ], [
            'password_baru.min' => 'New password must be at least 8 characters.',
            'password_baru.regex' => 'The new password must contain uppercase letters, lowercase letters, numbers, and special characters.',
            'konfirmasi_password_baru.same' => 'Password confirmation is incorrect.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = DB::table('app_user as u')
            ->where('u.id_user', session('id'))
            ->first();

        if (!Hash::check($request->password_lama, $user->password_user)) {
            return response()->json([
                'success' => false,
                'message' => 'The old password is wrong'
            ], 422);
        }

        if (Hash::check($request->password_baru, $user->password_user)) {
            return response()->json([
                'success' => false,
                'message' => 'The new password cannot be the same as the old password'
            ], 422);
        }

        $password = Hash::make($request->password_baru);
        $updateData = [
            'password_user' => $password,
            'updated_at' => now(),
        ];

        $id = session('id');

        $dt_exist = DB::table('app_user')
            ->where('id_user', $id)
            ->first();

        $update = DB::table('app_user')
            ->where('id_user', $id)
            ->update($updateData);

        if($update){
            $this->dataService->createLog(
                $request,
                'updatePasswordAction',
                'Successfully changed password',
                json_encode($updateData),
                json_encode($dt_exist)
            );
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);

        }else{
            $this->dataService->createLog(
                $request,
                'updatePasswordAction',
                'Failed to change password',
                json_encode($updateData),
                json_encode($dt_exist)
            );
            return response()->json([
                'success' => false,
                'message' => 'Password failed to update'
            ]);

        }

    }

}
