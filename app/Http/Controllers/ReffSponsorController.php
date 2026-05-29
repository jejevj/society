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



class ReffSponsorController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'ref-sponsor||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Sponsor',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'topik_count' => DB::table('t_sponsor')->count(),
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item fw-bold lh-1">
											<a class= text-hover-primary">
												<i class="ki-outline ki-home fs-3"></i>
											</a>
										</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 mx-n1"></i>
										</li>
										<li class="breadcrumb-item fw-bold lh-1">Reference</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 mx-n1"></i>
										</li>
										<li class="breadcrumb-item fw-bold lh-1">Sponsor</li>
									</ul>'
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.sponsor.main', $data);

        
    }

    public function getTableSponsor(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-sponsor||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('t_sponsor as a')
                ->selectRaw('*');
                
                if ($request->filled('nama')) {
                    $query->where('a.nama', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.id_sponsor', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('logo', function ($row) {
                    if ($row->logo) {
                        $url = asset('storage/' . $row->logo); 
                        return '<img src="'.$url.'" width="100" class="img-thumbnail">';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row)  use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_sponsor);
                    $infoUrl = route('editSponsor', $id_hash);
                    $btn = '';

                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm">
                                    <span class="fa fa-pencil"></span>
                                </a> ';
                    }

                    if($cek['d']){
                        $btn .= '<button title="Delete" 
                                    class="btn btn-danger btn-delete-topik btn-sm" 
                                    data-id="' . $id_hash . '">
                                    <span class="fa fa-trash"></span>
                                </button> ';
                    }
                    return $btn;
                })

                ->rawColumns(['logo', 'action'])
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        $menu_aktif = 'ref-sponsor||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Add Sponsor',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item fw-bold lh-1"><span class= text-hover-primary"> <i class="ki-outline ki-home fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Reference</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Sponsor</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Add Sponsor</li>
									</ul>'
            
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.referensi.sponsor.tambah', $data);

    }

    public function addSponsorAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-sponsor||referensi';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'nama'       => 'required|string|max:200',
                'urutan'        => 'required',
                'gambar'     => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
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
                $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('sponsor', $filename, 'public');
            }

            $data = [
                'nama'      => $request->nama,
                'urutan'      => $request->urutan,
                'logo'      => $path,
                'created_at'            => now(),
            ];
            
            $insert = DB::table('t_sponsor')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addSponsorAction' ,'Berhasil tambah data sponsor',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Sponsor successfully saved'
                ]);
            }else{
                $this->dataService->createLog($request,'addSponsorAction' ,'Gagal tambah data topik',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Sponsor failed to save'
                ]);
            }
        }
    }

    public function editSponsor($id_sponsor, Request $request)
    {
        $menu_aktif = 'ref-sponsor||referensi';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_sponsor_dec = Crypt::decrypt($id_sponsor);
        $detail = DB::table('t_sponsor')->where('id_sponsor', $id_sponsor_dec)->first();
        $data = [
            'menu' => 'Edit Sponsor',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item fw-bold lh-1"><span class="text-hover-primary"><i class="ki-outline ki-home fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Reference</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Sponsor</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Edit Sponsor</li>
									</ul>',
            'id_sponsor' => $id_sponsor,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.referensi.sponsor.edit', $data);
    }

    
    public function updateSponsorAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-sponsor||referensi';
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
                'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $updateData = [
                'nama'       => $request->nama,
                'urutan'  => $request->urutan,
                'updated_at'            => now(),
            ];

            if ($request->hasFile('gambar')) {
                $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('sponsor', $filename, 'public');

                $updateData['logo'] = $path;
                
            }
            $id =  Crypt::decrypt($request->key);
            $dt_exist = DB::table('t_sponsor')->where('id_sponsor', $id)->first();
            $update = DB::table('t_sponsor')->where('id_sponsor', $id)->update($updateData);


            if($update){
                $this->dataService->createLog($request,'updateSponsorAction' ,'Successfully changed sponsor data',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Sponsor successfully renewed'
                ]);
            }else{
                $this->dataService->createLog($request,'updateSponsorAction' ,'Failed to change sponsor data',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Sponsor failed to renew'
                ]);
            }
        }
    }

    public function deleteSponsorAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'ref-sponsor||referensi';
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
            $dt_exist = DB::table('t_sponsor')->where('id_sponsor', $id)->first();
            $deleted = DB::table('t_sponsor')->where('id_sponsor', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteSponsorAction' ,'Successfully deleted sponsor data','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Successfully deleted sponsor data']);
            } else {
                $this->dataService->createLog($request,'deleteSponsorAction' ,'Failed to delete sponsor data','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Failed to delete sponsor data']);
            }
        }    
    }


}
