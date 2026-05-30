<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use App\Models\ReffRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;       
use Illuminate\Support\Facades\Crypt;   
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class ReffRoleController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        
        $menu_aktif = 'ref-role||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Role Aplikasi',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'role_count' => DB::table('reff_role')->count(),
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1">
											<a class="text-white text-hover-primary">
												<i class="ki-outline ki-home text-white fs-3"></i>
											</a>
										</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Referensi</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Role</li>
									</ul>'
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.role.main', $data);

        
    }

    public function getTableRole(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('reff_role as a')
                ->selectRaw('a.*');
                
                if ($request->filled('nama')) {
                    $query->where('a.nama_role', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.id_role', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row) use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_role);
                    $infoUrl = route('editRole', $id_hash);
                    $menuUrl = route('menuRole', $id_hash);
                    $btn = '';
                    if($cek['c']){
                        $btn .= '<a href=' . $menuUrl . ' class="btn btn-light-success btn-sm"><span class="fa fa-gear"></span></a> ';
                    }
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a> ';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-role btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button> ';
                    }

                  
                    return $btn;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
      
        $menu_aktif = 'ref-role||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Tambah Role',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1"><span class="text-white text-hover-primary"> <i class="ki-outline ki-home text-white fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Referensi</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Role</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Tambah Role</li>
									</ul>'
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.role.tambah', $data);

    }

    
    public function addRoleAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:100',
                'deskripsi'  => 'required|string',
                'kode'  => 'required|string|max:100',
                'all_data'  => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $data = [
                'nama_role'      => $request->nama,
                'kode_role' => $request->kode,
                'deskripsi_role' => $request->deskripsi,
                'all_data_role' => $request->all_data,
                'created_at'           => now(),
            ];
            $insert = DB::table('reff_role')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addRoleAction' ,'Berhasil tambah data role baru',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Role berhasil disimpan'
                ]);
            }else{
                $this->dataService->createLog($request,'addRoleAction' ,'Gagal Tambah data role baru',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Role gagal disimpan'
                ]);
            }
        }
    }

    public function editRole($id_role, Request $request)
    {
        $menu_aktif = 'ref-role||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_role_dec = Crypt::decrypt($id_role);
        $detail = DB::table('reff_role')->where('id_role', $id_role_dec)->first();
        $data = [
            'menu' => 'Edit Role',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1"><span class="text-white text-hover-primary"><i class="ki-outline ki-home text-white fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Referensi</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Role </li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Edit Role </li>
									</ul>',
            'id_role' => $id_role,
            'detail' => $detail
        ];
        if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.role.edit', $data);
    }

    
    public function updateRoleAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:100',
                'key'       => 'required',
                'deskripsi'  => 'required|string',
                'kode'  => 'required|string|max:100',
                'all_data'  => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $updateData = [
                'nama_role'       => $request->nama,
                'kode_role'       => $request->kode,
                'deskripsi_role'  => $request->deskripsi,
                'all_data_role' => $request->all_data,
                'updated_at'            => now(),
            ];

            
            $id =  Crypt::decrypt($request->key);
            $dt_exist = DB::table('reff_role')->where('id_role', $id)->first();
            $update = DB::table('reff_role')->where('id_role', $id)->update($updateData);


            if($update){
                $this->dataService->createLog($request,'updateRoleAction' ,'Berhasil ubah data role',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Role berhasil diperbarui'
                ]);
            }else{
                $this->dataService->createLog($request,'updateRoleAction' ,'Gagal ubah data role',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Role gagal diperbarui'
                ]);
            }
        }
    }

    public function deleteRoleAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['d']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'key' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $id = Crypt::decrypt($request->key);
            $dt_exist = DB::table('reff_role')->where('id_role', $id)->first();
            $deleted = DB::table('reff_role')->where('id_role', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteRoleAction' ,'Berhasil hapus data role','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Berhasil hapus role']);
            } else {
                $this->dataService->createLog($request,'deleteRoleAction' ,'Berhasil hapus data role','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Gagal hapus role']);
            }
        }
    }

    public function menuRole($id_role, Request $request)
    {
        $menu_aktif = 'ref-role||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());

        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_role_dec = Crypt::decrypt($id_role);
        $detail = DB::table('reff_role')->where('id_role', $id_role_dec)->first();
        $menu = DB::table('reff_menu')
                    ->whereNotIn('id_menu', function($q) use ($id_role_dec) {
                        $q->select('menu_id')->from('reff_akses_menu')->where('role_id',$id_role_dec);
                    })->get()->toArray();

        $data = [
            'menu' => 'Akses Menu Role',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1"><span class="text-white text-hover-primary"><i class="ki-outline ki-home text-white fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Referensi</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Role </li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Akses Menu Role </li>
									</ul>',
            'id_role' => $id_role,
            'detail' => $detail,
            'cek_permit' => $cek,
            'list_menu' => $menu
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.role.menu', $data);
    }

    public function editAksesMenu($id_menu, Request $request)
    {
        $menu_aktif = 'ref-role||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_menu_dec = Crypt::decrypt($id_menu);
        $detail = DB::table('reff_akses_menu as a')
            ->join('reff_menu as b', 'a.menu_id', '=', 'b.id_menu')
            ->join('reff_role as c', 'a.role_id', '=', 'c.id_role')
            ->select(
                'a.*',
                'b.nama_menu',
                'c.nama_role'
            )
            ->where('a.id_akses_menu', $id_menu_dec)
            ->first();
        $data = [
            'menu' => 'Edit Akses Menu',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'id_akses_menu' => $id_menu,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1"><span class="text-white text-hover-primary"><i class="ki-outline ki-home text-white fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Referensi</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Menu</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Edit Menu</li>
									</ul>',
            'id_menu' => $id_menu,
            'detail' => $detail,
            'detail_role' => DB::table('reff_role')->where('id_role', $detail->role_id)->first(),
        ];

        if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.role.edit-akses-menu', $data);
    }
    
    public function updateAksesMenuAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'key'       => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $updateData = [
                'permit_c' => $request->has('permit_c') ? 1 : 0,
                'permit_r' => $request->has('permit_r') ? 1 : 0,
                'permit_u' => $request->has('permit_u') ? 1 : 0,
                'permit_d' => $request->has('permit_d') ? 1 : 0,
            ];
            
            $id =  Crypt::decrypt($request->key);
            $dt_exist = DB::table('reff_akses_menu')->where('id_akses_menu', $id)->first();
            $update = DB::table('reff_akses_menu')->where('id_akses_menu', $id)->update($updateData);

            if($update){
                $this->dataService->createLog($request,'updateAksesMenuAction' ,'Berhasil ubah data akses menu',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Akses Menu berhasil diperbarui'
                ]);
            }else{
                $this->dataService->createLog($request,'updateAksesMenuAction' ,'Gagal ubah data akses menu',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Akses Menu gagal diperbarui'
                ]);
            }
        }
    }

    
    public function addAksesMenuAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'key'       => 'required',
                'menu_id'  => 'required',
            ]);
            

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $id_role = Crypt::decrypt($request->key);
            $data = [
                'role_id'      => $id_role,
                'menu_id' => $request->menu_id,
                'permit_c' => $request->has('permit_c'),
                'permit_r' => $request->has('permit_r'),
                'permit_u' => $request->has('permit_u'),
                'permit_d' => $request->has('permit_d'),
                'created_at'           => now(),
            ];
            $insert = DB::table('reff_akses_menu')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addAksesMenuAction' ,'Berhasil tambah data akses menu role',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Akses Menu berhasil disimpan'
                ]);
            }else{
                $this->dataService->createLog($request,'addAksesMenuAction' ,'Gagal tambah data akses menu role',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Akses Menu gagal disimpan'
                ]);
            }
        }
    }

    
    public function getTableMenuRole(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $id_role = Crypt::decrypt($request->input('key'));
            $query = DB::table('reff_akses_menu as a')
                ->select(
                    'a.id_akses_menu',
                    'a.role_id',
                    'a.menu_id',
                    'a.permit_r',
                    'a.permit_c',
                    'a.permit_u',
                    'a.permit_d',
                    'a.created_at',
                    'a.updated_at',
                    'b.nama_menu',
                    'b.kode_menu',
                    'b.jenis_menu'
                )
                ->leftJoin('reff_menu as b', 'b.id_menu', '=', 'a.menu_id')
                ->where('a.role_id', $id_role);
                
                if ($request->filled('nama')) {
                    $query->where('b.nama_menu', 'like', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.id_akses_menu', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row) use ($cek){
                    $id_hash = Crypt::encrypt($row->id_akses_menu);
                    $infoUrl = route('editAksesMenu', $id_hash);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a>';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-menu-role btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button>';
                    }

                    return $btn;
                })
                ->addColumn('permit', function ($row) {

                    $create = $row->permit_c 
                        ? '<span class="badge bg-success text-white">Create ✔</span>' 
                        : '<span class="badge bg-danger text-white">Create ✖</span>';

                    $read = $row->permit_r 
                        ? '<span class="badge bg-success text-white">Read ✔</span>' 
                        : '<span class="badge bg-danger text-white">Read ✖</span>';

                    $update = $row->permit_u 
                        ? '<span class="badge bg-success text-white">Update ✔</span>' 
                        : '<span class="badge bg-danger text-white">Update ✖</span>';

                    $delete = $row->permit_d 
                        ? '<span class="badge bg-success text-white">Delete ✔</span>' 
                        : '<span class="badge bg-danger text-white">Delete ✖</span>';

                    return '
                        <div class="d-flex flex-wrap gap-2">
                            '.$create.'
                            '.$read.'
                            '.$update.'
                            '.$delete.'
                        </div>
                    ';
                })
                
                ->rawColumns(['action','permit'])
                ->make(true);
        }
    }

    public function deleteMenuRoleAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-role||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['d']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'key' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $id = Crypt::decrypt($request->key);
            $dt_exist = DB::table('reff_akses_menu')->where('id_akses_menu', $id)->first();
            $deleted = DB::table('reff_akses_menu')->where('id_akses_menu', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteMenuRoleAction' ,'Berhasil hapus data akses menu role','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Berhasil hapus askes menu']);
            } else {
                $this->dataService->createLog($request,'deleteMenuRoleAction' ,'Berhasil hapus data akses menu role','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Gagal hapus askes menu']);
            }
        }
    }

}
