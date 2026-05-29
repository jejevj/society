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



class ReffTopikController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'ref-topik||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Tag',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'topik_count' => DB::table('reff_topik')->count(),
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
										<li class="breadcrumb-item  fw-bold lh-1">Tag</li>
									</ul>'
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.topik.main', $data);
    }

    public function getTableTopik(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-topik||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('reff_topik as a')
                ->selectRaw('*');
                
                if ($request->filled('nama')) {
                    $query->where('a.nama_menu', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.id_topik', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row)  use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_topik);
                    $infoUrl = route('editTopik', $id_hash);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a> ';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-topik btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button> ';
                    }

                    return $btn;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        $menu_aktif = 'ref-topik||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Add Tag',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"> <i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">References</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Tag</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Add Tag</li>
									</ul>',
            'status_menu' => ReffStatus::where('jenis_status', 'menu')->orderBy('urutan_status', 'ASC')->get(),
            'master_menu' => ReffMenu::where('jenis_menu', 'M')->orderBy('urutan_menu', 'ASC')->get()
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.topik.tambah', $data);

    }

    public function addTopikAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-topik||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:200',
                'urutan'        => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $kode = 'TG'.date('ymdhis');
            $data = [
                'nama_topik'      => $request->nama,
                'kode_topik'      => $kode,
                'urutan_topik'      => $request->urutan,
                'created_at'            => now(),
            ];
            
            $insert = DB::table('reff_topik')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addMenuAction' ,'Successfully added tag data',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully added tag data'
                ]);
            }else{
                $this->dataService->createLog($request,'addMenuAction' ,'Failed to add tag data',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add tag data'
                ]);
            }
        }
    }

    

    public function editTopik($id_topik, Request $request)
    {
        $menu_aktif = 'ref-topik||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_topik_dec = Crypt::decrypt($id_topik);
        $detail = DB::table('reff_topik')->where('id_topik', $id_topik_dec)->first();
        $data = [
            'menu' => 'Edit Tag',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">References</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Tag</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Edit Tag</li>
									</ul>',
            'id_topik' => $id_topik,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.referensi.topik.edit', $data);
    }

    
    public function updateTopikAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-topik||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:255',
                'key'       => 'required',
                'urutan'  => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $updateData = [
                'nama_topik'       => $request->nama,
                'urutan_topik'  => $request->urutan,
                'updated_at'            => now(),
            ];

            $id =  Crypt::decrypt($request->key);
            $dt_exist = DB::table('reff_topik')->where('id_topik', $id)->first();
            $update = DB::table('reff_topik')->where('id_topik', $id)->update($updateData);


            if($update){
                $this->dataService->createLog($request,'updateTopikAction' ,'Tag updated successfully',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Tag updated successfully'
                ]);
            }else{
                $this->dataService->createLog($request,'updateTopikAction' ,'Tag failed to update',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Tag failed to update'
                ]);
            }
        }
    }

    public function deleteTopikAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-topik||referensi';
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
            $dt_exist = DB::table('reff_topik')->where('id_topik', $id)->first();
            $deleted = DB::table('reff_topik')->where('id_topik', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteTopikAction' ,'Successfully deleted tag data','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Successfully deleted tag data']);
            } else {
                $this->dataService->createLog($request,'deleteTopikAction' ,'Failed to delete tag data','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Failed to delete tag data']);
            }
        }    
    }


}
