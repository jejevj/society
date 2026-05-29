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



class TautanController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'tautan||konten';
         if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Link Tautan',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'breadcrumb' => '
                           <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1">
											<a class="text-white text-hover-primary">
												<i class="ki-outline ki-home text-white fs-3"></i>
											</a>
										</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Konten Web</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Link Tautan</li>
									</ul>
                            </ul>',
            'organisasi' => ReffOrganisasi::latest()->get()
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.konten-web.link-tautan.main', $data);

        
    }

    public function getTableTautan(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'tautan||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('t_tautan as a')
                ->selectRaw('A.*');
                
                if ($request->filled('nama')) {
                    $query->where('a.nama_tautan', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.urutan_tautan', 'asc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                 ->addColumn('foto', function ($row) {
                    if ($row->gambar_tautan) {
                        $url = asset('storage/' . $row->gambar_tautan);
                        return '<img src="'.$url.'" width="80" class="img-thumbnail"/>';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) use ($cek){
                    $id_hash = Crypt::encrypt($row->id_tautan);
                    $infoUrl = route('editTautan', $id_hash);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a> ';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-tautan btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button>';
                    }
                    
                    return $btn;
                })
                
                ->rawColumns(['foto','action'])
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        $menu_aktif = 'tautan||konten';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Tambah Tautan',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1">
											<a class="text-white text-hover-primary">
												<i class="ki-outline ki-home text-white fs-3"></i>
											</a>
										</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Konten Web</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Link Tautan</li>
                                        <li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Tambah Link Tautan</li>
									</ul>'
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.konten-web.link-tautan.tambah', $data);

    }

    
    public function addTautanAction(Request $request)
    {
        
        if ($request->session()->has('id')) {
            $menu_aktif = 'tautan||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:255',
                'link'  => 'required|string|max:255',
                'urutan'  => 'required',
                'gambar'     => 'required|image|mimes:jpg,jpeg,png,gif|max:5048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $path = null;
            $filename = null;
            if ($request->hasFile('gambar')) {
                $scan = $this->dataService->scanAntivirus($request->file('gambar'));
                if (!$scan['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $scan['message'],
                        'virus'   => $scan['virus'] ?? null,
                        'error'   => $scan['error'] ?? null,
                    ], $scan['code']);
                }
                $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('organisasi', $filename, 'public');
            }

            $insert = DB::table('t_tautan')->insert([
                'nama_tautan'      => $request->nama,
                'link_tautan' => $request->link,
                'urutan_tautan' => $request->urutan,
                'gambar_tautan'           => $path,
                'created_at'           => now(),
            ]);

            if($insert){
                $this->dataService->createLog($request,'addTautanAction' ,'Berhasil tambah data tautan');
                return response()->json([
                    'success' => true,
                    'message' => 'Tautan berhasil disimpan'
                ]);
            }else{
                $this->dataService->createLog($request,'addTautanAction' ,'Gagal tambah data tautan');
                return response()->json([
                    'success' => false,
                    'message' => 'Tautan gagal disimpan'
                ]);
            }
        }
    }

    public function editTautan($id_tautan, Request $request)
    {
        $menu_aktif = 'tautan||konten';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_tautan_dec = Crypt::decrypt($id_tautan);
        $detail = DB::table('t_tautan')->where('id_tautan', $id_tautan_dec)->first();
        $data = [
            'menu' => 'Edit Tautan',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1"><span class="text-white text-hover-primary"><i class="ki-outline ki-home text-white fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Konten</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Tautan</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 text-white mx-n1"></i></li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Edit Tautan</li>
									</ul>',
            'id_tautan' => $id_tautan,
            'detail' => $detail
        ];
        if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.konten-web.link-tautan.edit', $data);
    }

    
    public function updateTautanAction(Request $request)
    {
        
        if ($request->session()->has('id')) {
            $menu_aktif = 'tautan||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
                }
            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:200',
                'key'       => 'required',
                'link'  => 'required|url',
                'urutan'  => 'required',
                'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $updateData = [
                'nama_tautan'       => $request->nama,
                'link_tautan'       => $request->link,
                'urutan_tautan'  => $request->urutan,
                'updated_at'            => now(),
            ];

            if ($request->hasFile('gambar')) {
                $scan = $this->dataService->scanAntivirus($request->file('gambar'));
                if (!$scan['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $scan['message'],
                        'virus'   => $scan['virus'] ?? null,
                        'error'   => $scan['error'] ?? null,
                    ], $scan['code']);
                }
                $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('organisasi', $filename, 'public');

                $updateData['gambar_tautan'] = $path;
            }
            $id =  Crypt::decrypt($request->key);
            $update = DB::table('t_tautan')->where('id_tautan', $id)->update($updateData);


            if($update){
                $this->dataService->createLog($request,'updateTautanAction' ,'Berhasil ubah data tautan');
                return response()->json([
                    'success' => true,
                    'message' => 'Tautan berhasil diperbarui'
                ]);
            }else{
                $this->dataService->createLog($request,'updateTautanAction' ,'Gagal ubah data tautan');
                return response()->json([
                    'success' => false,
                    'message' => 'Tautan gagal diperbarui'
                ]);
            }
        }
    }

    public function deleteTautanAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'tautan||konten';
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
            $deleted = DB::table('t_tautan')->where('id_tautan', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteTautanAction' ,'Berhasil hapus data tautan');
                return response()->json(['success' => true, 'message' => 'Berhasil hapus tautan']);
            } else {
                $this->dataService->createLog($request,'deleteTautanAction' ,'Gagal hapus data tautan');
                return response()->json(['success' => false, 'message' => 'Gagal hapus tautan']);
            }
        }
    }


}
