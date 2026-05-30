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



class ReffMenuController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'ref-menu||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Menu Aplikasi',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'menu_count' => DB::table('reff_menu')->count(),
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
										<li class="breadcrumb-item text-white fw-bold lh-1">Menu</li>
									</ul>'
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.menu.main', $data);
    }

    public function getTableMenu(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-menu||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('reff_menu as a')
                ->leftJoin('reff_status as b', 'a.jenis_menu', '=', 'b.kode_status')
                ->select(
                    'a.id_menu',
                    'a.nama_menu',
                    'a.jenis_menu',
                    'a.kode_menu',
                    'a.icon_menu',
                    'a.parent_menu',
                    'a.urutan_menu',
                    'a.deskripsi_menu',
                    'a.created_at',
                    'a.updated_at',
                    'b.nama_status'
                );
                
            if ($request->filled('nama')) {
                $query->where('a.nama_menu', 'like', '%' . $request->input('nama') . '%');
            }
                
            $query->orderBy('a.id_menu', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row) use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_menu);
                    $infoUrl = route('editMenu', $id_hash);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a>';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-menu btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button> ';
                    }
                    
                    return $btn;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        $menu_aktif = 'ref-menu||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Tambah Menu',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1"><span class="text-white text-hover-primary"> <i class="ki-outline ki-home text-white fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Referensi</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Menu</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Tambah Menu</li>
									</ul>',
            'status_menu' => ReffStatus::where('jenis_status', 'menu')->orderBy('urutan_status', 'ASC')->get(),
            'master_menu' => ReffMenu::where('jenis_menu', 'M')->orderBy('urutan_menu', 'ASC')->get()
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.menu.tambah', $data);

    }

    
    public function addMenuAction(Request $request)
    {

        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-menu||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'nama'  => 'required|string|max:255',
                'kode'  => 'required|string|max:100',
                'jenis' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if($request->jenis == 'D'){
                if(empty($request->parent)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Master menu harus diisi jika pilih jenis sub menu'
                    ], 422);
                }
                $parent = $request->parent;
                
            }else{
                $parent = 0;
            }

            $data = [
                'nama_menu'      => $request->nama,
                'jenis_menu'     => $request->jenis,
                'kode_menu'      => $request->kode,
                'icon_menu'      => $request->icon,
                'parent_menu'    => $parent,
                'urutan_menu'    => $request->urutan,
                'deskripsi_menu' => $request->deskripsi,
                'created_at'     => now(),
            ];

            $insert = DB::table('reff_menu')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addMenuAction' ,'Berhasil tambah data menu',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Menu berhasil disimpan'
                ]);
            }else{
                $this->dataService->createLog($request,'addMenuAction' ,'Gagal tambah data menu',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Menu gagal disimpan'
                ]);
            }
        }
    }

    public function editMenu($id_menu, Request $request)
    {
        $menu_aktif = 'ref-menu||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_menu_dec = Crypt::decrypt($id_menu);
        $detail = DB::table('reff_menu')->where('id_menu', $id_menu_dec)->first();
        $data = [
            'menu' => 'Edit Menu',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
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
            'status_menu' => ReffStatus::where('jenis_status', 'menu')->orderBy('urutan_status', 'ASC')->get(),
            'master_menu' => ReffMenu::where('jenis_menu', 'M')->orderBy('urutan_menu', 'ASC')->get()
        ];

        if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.menu.edit', $data);
    }

    
    public function updateMenuAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-menu||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'key'   => 'required',            
                'nama'  => 'required|string|max:255',
                'kode'  => 'required|string|max:100',
                'jenis' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if($request->jenis == 'D'){
                if(empty($request->parent)){
                    return response()->json([
                    'success' => false,
                    'message' => 'Master menu harus diisi jika pilih jenis sub menu'
                ], 422);
                }
                $parent = $request->parent;
                
            }else{
                $parent = 0;
            }

            $updateData = [
                'nama_menu'      => $request->nama,
                'jenis_menu'     => $request->jenis,
                'kode_menu'      => $request->kode,
                'icon_menu'      => $request->icon,
                'parent_menu'    => $parent,
                'urutan_menu'    => $request->urutan,
                'deskripsi_menu' => $request->deskripsi,
                'updated_at'     => now(),
            ];

            $id = Crypt::decrypt($request->key);
            $dt_exist = DB::table('reff_menu')->where('id_menu', $id)->first();
            $update = DB::table('reff_menu')->where('id_menu', $id)->update($updateData);

            if($update){
                $this->dataService->createLog($request,'updateMenuAction' ,'Berhasil ubah data menu',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Menu berhasil diperbarui'
                ]);
            }else{
                $this->dataService->createLog($request,'updateMenuAction' ,'Gagal ubah data menu',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Menu gagal diperbarui'
                ]);
            }
        }
    }

    public function deleteMenuAction(Request $request)
    {

        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-menu||referensi';
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
            $dt_exist = DB::table('reff_menu')->where('id_menu', $id)->first();
            $deleted = DB::table('reff_menu')->where('id_menu', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteMenuAction' ,'Berhasil hapus data menu','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Berhasil hapus menu']);
            } else {
                $this->dataService->createLog($request,'deleteMenuAction' ,'Gagal hapus data menu','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Gagal hapus menu']);
            }
        }
    }


}
