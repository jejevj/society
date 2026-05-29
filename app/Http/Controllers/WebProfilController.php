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



class webProfilController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return Redirect::to('/login');
        }
        $menu_aktif = 'profil||akun';
        // $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Profil Saya',
            'menu_aktif' => $menu_aktif,
            'navbar' => '',
            'breadcrumb' => '',
            'organisasi' => ReffOrganisasi::latest()->get(),
            'detail' => DB::table('app_user')
                        ->leftJoin('reff_organisasi', 'reff_organisasi.id_organisasi', '=', 'app_user.organisasi_id')
                        ->leftJoin('reff_role', 'reff_role.id_role', '=', 'app_user.role_id')
                        ->where('id_user', session('id_user'))->first(),
        ];
        return view('web.profil-user', $data);        
    }

    
    public function updateProfilUserAction(Request $request)
    {
        $id = session('id_user');
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'username' => 'required|email|max:200|unique:app_user,username_user,'.$id.',id_user',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'username.required' => 'Email wajib diisi.',
            'username.email' => 'Format email tidak valid.',
            'username.unique' => 'Email sudah digunakan.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpg, jpeg, png, atau gif.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);

        }

        $updateData = [
            'nama_user' => $request->nama,
            'username_user' => $request->username,
            'identitas_user' => $request->identitas,
            'telepon_user' => $request->telepon,
            'pekerjaan_user' => $request->pekerjaan,
            'alamat_user' => $request->alamat,
            'updated_at' => now(),

        ];

        if ($request->hasFile('foto')) {
            $scan = $this->dataService->scanAntivirus($request->file('foto'));
                if (!$scan['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $scan['message'],
                        'virus'   => $scan['virus'] ?? null,
                        'error'   => $scan['error'] ?? null,
                    ], $scan['code']);
                }
            $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
            $path = $request->file('foto')->storeAs(
                'organisasi',
                $filename,
                'public'
            );
            $updateData['file_identitas_user'] = $path;

        }

        $dt_exist = DB::table('app_user')
            ->where('id_user', $id)
            ->first();

        $update = DB::table('app_user')
            ->where('id_user', $id)
            ->update($updateData);

        if($update){

            $this->dataService->createLogWeb(
                $request,
                'updateProfilUserAction',
                'Berhasil ubah data profil',
                json_encode($updateData),
                json_encode($dt_exist)
            );

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui'
            ]);

        }else{

            $this->dataService->createLogWeb(
                $request,
                'updateProfilUserAction',
                'Gagal ubah data profil',
                json_encode($updateData),
                json_encode($dt_exist)
            );

            return response()->json([
                'success' => false,
                'message' => 'Profil gagal diperbarui'
            ]);

        }

    }

    

    public function gantiPasswordUser(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return Redirect::to('/login');
        }
        $menu_aktif = 'password||akun';        
        $data = [
            'menu' => 'Ganti Password',
            'menu_aktif' => $menu_aktif,
            'navbar' => '',
            'breadcrumb' => '
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
                            <li class="breadcrumb-item text-muted"><a class="text-muted text-hover-primary">Home</a></li>
							<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
							<li class="breadcrumb-item text-muted">Pengaturan Akun</li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
							<li class="breadcrumb-item text-muted">Ganti Password</li>
                            </ul>',
        ];

        return view('web.ganti-password', $data);

    }

    
    public function updatePasswordUserAction(Request $request)
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
            'password_baru.min' => 'Password minimal 8 karakter.',
            'password_baru.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            'konfirmasi_password_baru.same' => 'Konfirmasi password tidak sesuai.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = DB::table('app_user as u')
            ->where('u.id_user', session('id_user'))
            ->first();

        if (!Hash::check($request->password_lama, $user->password_user)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama salah'
            ], 422);
        }
        if (Hash::check($request->password_baru, $user->password_user)) {
            return response()->json([
                'success' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama'
            ], 422);

        }
        $password = Hash::make($request->password_baru);
        $updateData = [
            'password_user' => $password,
            'updated_at' => now(),
        ];
        $id = session('id_user');
        $dt_exist = DB::table('app_user')
            ->where('id_user', $id)
            ->first();

        $update = DB::table('app_user')
            ->where('id_user', $id)
            ->update($updateData);

        if($update){
            $this->dataService->createLog(
                $request,
                'updatePasswordUserAction',
                'Berhasil ubah password',
                json_encode($updateData),
                json_encode($dt_exist)
            );

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui'
            ]);

        }else{
            $this->dataService->createLog(
                $request,
                'updatePasswordUserAction',
                'Gagal ubah password',
                json_encode($updateData),
                json_encode($dt_exist)
            );

            return response()->json([
                'success' => false,
                'message' => 'Password gagal diperbarui'
            ]);

        }

    }

    
    public function riwayatUser(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return Redirect::to('/login');
        }
        $menu_aktif = 'riwayat||akun';
        $query = DB::table('t_permohonan as a')->leftJoin('reff_status as b', function($join) {
                    $join->on('b.kode_status', '=', 'a.status_permohonan')
                        ->where('b.jenis_status', '=', 'status_permohonan');
                })
                ->leftJoin('t_data as c', 'c.id_data', '=', 'a.data_id_permohonan')
                ->selectRaw('a.*, b.keterangan_status, c.judul_data')
                ->where('a.user_id_permohonan',session('id_user'));
        
        $data = [
            'menu' => 'Profil Saya',
            'menu_aktif' => $menu_aktif,
            'navbar' => '',
            'breadcrumb' => '',
            'organisasi' => ReffOrganisasi::latest()->get(),
            

            'riwayat' => $query->orderBy('a.id_permohonan', 'asc')->paginate(6)->appends($request->all()),
        ];
        // dd($data['riwayat']);
        return view('web.riwayat-user', $data);

        
    }

}
