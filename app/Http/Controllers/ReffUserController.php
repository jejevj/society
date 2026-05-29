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






class ReffUserController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {   
        $menu_aktif = 'ref-pengguna||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $data = [
            'menu' => 'Users',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1">
											<a class=" text-hover-primary">
												<i class="ki-outline ki-home  fs-3"></i>
											</a>
										</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4  mx-n1"></i>
										</li>
										<li class="breadcrumb-item  fw-bold lh-1">References</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4  mx-n1"></i>
										</li>
										<li class="breadcrumb-item  fw-bold lh-1">Users</li>
									</ul>'
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.pengguna.main', $data);
    }

    public function getTableUser(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-pengguna||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('app_user as a')
                ->selectRaw('*');
                
                if ($request->filled('nama')) {
                    $query->where('a.nama_user', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.id_user', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('foto', function ($row){
                    if ($row->foto_user) {
                        $url = asset('storage/' . $row->foto_user);
                        return '<img src="'.$url.'" width="80" class="img-thumbnail"/>';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row)  use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_user);
                    $infoUrl = route('editUser', $id_hash);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a> ';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-user btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button>';
                    }
                    
                    return $btn;
                })
                ->addColumn('pengguna', function ($row) {
                    $view = '<span class="badge badge-secondary">Nama: '.$row->nama_user.'</span><br>
                            <span class="badge badge-secondary">Username/Email: '.$row->username_user.'</span><br>';
                    return $view;
                })
                
                ->rawColumns(['foto','action','pengguna'])
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        $menu_aktif = 'ref-pengguna||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $organisasi = DB::table('reff_organisasi')->get()->toArray();
        $role = DB::table('reff_role')->get()->toArray();
        $data = [
            'menu' => 'Add Users',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"> <i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">References</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Users</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Add Users</li>
									</ul>',
            'role' => $role,
            'organisasi' => $organisasi
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.pengguna.tambah', $data);

    }

    public function addUserAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-pengguna||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:200',
                'username'   => 'required|email|max:200',
                'role'       => 'required',

                'password'   => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[A-Z]/',        
                    'regex:/[a-z]/',       
                    'regex:/[0-9]/',        
                    'regex:/[@$!%*#?&._-]/' 
                ],

                'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'

            ],[
                'username.email' => 'Username must be in email format.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.regex' => 'Password must contain uppercase letters, lowercase letters, numbers, and special characters.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $cek_email = DB::table('app_user')->where('username_user', $request->username)->exists();
            if ($cek_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email has been registered.'
                ], 422);
            }
            $password = Hash::make($request->password);
            $path = null;
            $filename = null;
            if ($request->hasFile('foto')) {
                $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
                $path = $request->file('foto')->storeAs('user', $filename, 'public');
                
            }
            $data = [
                'nama_user'      => $request->nama,
                'role_id' => $request->role,
                'username_user'                  => $request->username,
                'password_user'               => $password,
                'foto_user'           => $path,
                'status_user'           => 1,
                'created_at'           => now(),
            ];
            $insert = DB::table('app_user')->insert($data);
            if($insert){
                $this->dataService->createLog($request,'addUserAction' ,'Successfully added user data',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully added user data'
                ]);
            }else{
                $this->dataService->createLog($request,'addUserAction' ,'Gagal tambah data pengguna',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add user data'
                ]);
            }
        }    
    }

    public function editUser($id_user, Request $request)
    {
        $menu_aktif = 'ref-pengguna||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $id_user_dec = Crypt::decrypt($id_user);
        $detail = DB::table('app_user')->where('id_user', $id_user_dec)->first();
        $organisasi = DB::table('reff_organisasi')->get()->toArray();
        $role = DB::table('reff_role')->get()->toArray();
        $data = [
            'menu' => 'Edit User',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">References</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">User</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Edit User</li>
									</ul>',
            'id_user' => $id_user,
            'detail' => $detail,
            'organisasi' => $organisasi,
            'role' => $role
        ];
        if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.pengguna.edit', $data);
    }

    
    public function updateUserAction(Request $request)
    {
        if ($request->session()->has('id')) {

            $menu_aktif = 'ref-pengguna||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());

            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }

            $id = Crypt::decrypt($request->key);

            $validator = Validator::make($request->all(), [

                'key' => 'required',
                'nama' => 'required|string|max:200',
                'username' => 'required|email|max:200|unique:app_user,username_user,'.$id.',id_user',
                'role' => 'required',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&._-]/'
                ],

                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'

            ],[
                'username.email' => 'User Email must be in email format.',
                'username.unique' => 'Email has been registered.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.regex' => 'Password must contain uppercase letters, lowercase letters, numbers, and special characters.'

            ]);

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);

            }
            $cek_email = DB::table('app_user')
                ->where('username_user', $request->username)
                ->where('id_user', '!=', $id)
                ->exists();
            if ($cek_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email has been registered.'
                ], 422);
            }

            $updateData = [
                'nama_user' => $request->nama,
                'username_user' => $request->username,
                'role_id' => $request->role,
                'updated_at' => now(),
            ];

            if ($request->hasFile('foto')) {
                $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
                $path = $request->file('foto')->storeAs(
                    'organisasi',
                    $filename,
                    'public'
                );
                $updateData['foto_user'] = $path;
            }

            if (!empty($request->password)) {
                $updateData['password_user'] = Hash::make($request->password);
            }

            $dt_exist = DB::table('app_user')
                ->where('id_user', $id)
                ->first();

            $update = DB::table('app_user')
                ->where('id_user', $id)
                ->update($updateData);

            if($update){

                $this->dataService->createLog(
                    $request,
                    'updateUserAction',
                    'Berhasil ubah data pengguna',
                    json_encode($updateData),
                    json_encode($dt_exist)
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully changed user data'
                ]);

            }else{

                $this->dataService->createLog(
                    $request,
                    'updateUserAction',
                    'Failed to change user data',
                    json_encode($updateData),
                    json_encode($dt_exist)
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to change user data'
                ]);

            }
        }
    }

    public function deleteUserAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-pengguna||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['d']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
            $validator = Validator::make($request->all(), [
                'key' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $id = Crypt::decrypt($request->key);
            $dt_exist = DB::table('app_user')->where('id_user', $id)->first();
            $deleted = DB::table('app_user')->where('id_user', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteUserAction' ,'Successfully deleted user data','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Successfully deleted user data']);
            } else {
                $this->dataService->createLog($request,'deleteUserAction' ,'Failed to delete user data','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Failed to delete user data']);
            }
        }
    }

    public function loginBackend()
    {
        $menu_aktif = '/user||/refference';
        $navbar = '';
        $data = [
            'menu' => 'Login Backend',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => ''
        ];
        return view('admin-panel.referensi.pengguna.login', $data);        
    }

    
    public function loginBackendAction(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('app_user as u')
            ->leftJoin('reff_role as r', 'r.id_role', '=', 'u.role_id')
            ->leftJoin('reff_organisasi as o', 'o.id_organisasi', '=', 'u.organisasi_id')
            ->select(
                'u.*',
                'r.nama_role',
                'r.kode_role',
                'o.nama_organisasi',
                'o.kode_organisasi'
            )
            ->where('u.username_user', $request->username)
            ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User tidak ditemukan']);
        }

        if (!Hash::check($request->password, $user->password_user)) {
            return response()->json(['status' => false, 'message' => 'Password salah']);
        }

        session([
            'id'   => $user->id_user,
            'nama' => $user->nama_user,
            'username'=> $user->username_user,
            'kode_organisasi' => $user->kode_organisasi,
            'kode_role' => $user->kode_role,
            'id_role' => $user->role_id,
            'id_organisasi' => $user->organisasi_id,
        ]);
        $this->dataService->createLog($request,'loginBackendAction' ,'Login successful');
        return response()->json(['status' => true, 'message' => 'Login successful']);
    }

    
    public function logoutBackendAction(Request $request)
    {
        $this->dataService->createLog($request,'logoutBackendAction' ,'Exit Application');
        Session::flush();

        Auth::logout();
        
        return response()->json([
            'status' => true,
            'message' => 'Successfully exited the application'
        ]);
    }


}
