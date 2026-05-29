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



class SettingController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        
        $menu_aktif = 'setting||konten';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Setting',
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
										<li class="breadcrumb-item text-white fw-bold lh-1">Pengaturan Umum</li>
									</ul>',
            'organisasi' => ReffOrganisasi::latest()->get(),
            'detail' =>  DB::table('app_setting')->where('id_setting', 1)->first()
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.konten-web.general-setting.main', $data);

        
    }

    public function getTableSlider(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'setting||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('app_slider as a')
                ->selectRaw('A.*')
                ->where('jenis_slider', 'gambar');
                
                if ($request->filled('nama')) {
                    $query->where('a.judul_slider', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.urutan_slider', 'asc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                 ->addColumn('foto', function ($row) {
                    if ($row->gambar_slider) {
                        $url = asset('storage/' . $row->gambar_slider);
                        return '<img src="'.$url.'" width="80" class="img-thumbnail"/>';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_slider);
                    $infoUrl = route('editSlider', $id_hash);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a>';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-slider btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button>';
                    }
                    
                    return $btn;
                })
                
                ->rawColumns(['foto','action'])
                ->make(true);
        }
    }

    
    public function getTableSliderText(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'setting||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('app_slider as a')
                ->selectRaw('A.*')
                ->where('jenis_slider', 'text');
                
                if ($request->filled('nama')) {
                    $query->where('a.judul_slider', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.urutan_slider', 'asc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                
                ->addColumn('action', function ($row) use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_slider);
                    $infoUrl = route('editSlider', $id_hash);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a>';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-slider btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button>';
                    }
                    
                    return $btn;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function editSlider($id_slider, Request $request)
    {
        $menu_aktif = 'setting||konten';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $id_slider_dec = Crypt::decrypt($id_slider);
        $detail = DB::table('app_slider')->where('id_slider', $id_slider_dec)->first();
        // dd($detail);
        $data = [
            'menu' => 'Edit Slider',
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
										<li class="breadcrumb-item text-white fw-bold lh-1">Pengaturan Umum</li>
                                        <li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Edit Slider</li>
									</ul>',
            'id_slider' => $id_slider,
            'detail' => $detail
        ];
        if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.konten-web.general-setting.edit', $data);
    }

    
    public function updateSliderAction(Request $request)
    {
        if ($request->session()->has('id')) {

            $menu_aktif = 'setting||konten';

            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());

            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            if($request->jenis == 'gambar'){

                $validator = Validator::make($request->all(), [
                    'judul'   => 'required|string|max:255',
                    'key'     => 'required',
                    'urutan'  => 'required',
                    'gambar'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                ], [
                    'gambar.image' => 'File harus berupa gambar',
                    'gambar.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
                    'gambar.max'   => 'Ukuran gambar maksimal 2 MB',
                ]);
                $updateData = [
                    'judul_slider'   => $request->judul,
                    'urutan_slider'  => $request->urutan,
                    'updated_at'     => now(),
                ];
            } else {
                $validator = Validator::make($request->all(), [
                    'judul'       => 'required|string|max:255',
                    'key'         => 'required',
                    'urutan'      => 'required',
                    'deskripsi'   => 'required',
                ]);

                $updateData = [
                    'judul_slider'       => $request->judul,
                    'urutan_slider'      => $request->urutan,
                    'deskripsi_slider'   => $request->deskripsi,
                    'updated_at'         => now(),
                ];
            }

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if ($request->jenis == 'gambar' && $request->hasFile('gambar')) {
                $file_post = $request->file('gambar');
                $scan = $this->dataService->scanAntivirus($file_post);
                if (!$scan['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $scan['message'],
                        'virus'   => $scan['virus'] ?? null,
                        'error'   => $scan['error'] ?? null,
                    ], $scan['code']);
                }

                $filename = time() . '_' . $file_post->getClientOriginalName();
                $path = $file_post->storeAs('slider', $filename, 'public');
                $updateData['gambar_slider'] = $path;
            }

            $id = Crypt::decrypt($request->key);
            $dt_exist = DB::table('app_slider')->where('id_slider', $id)->first();

            $update = DB::table('app_slider')->where('id_slider', $id)->update($updateData);
            if($update){
                $this->dataService->createLog(
                    $request,'updateSliderAction','Berhasil ubah data slider',json_encode($updateData),json_encode($dt_exist)
                );
                return response()->json([
                    'success' => true,
                    'message' => 'Slider berhasil diperbarui'
                ]);
            } else {
                $this->dataService->createLog(
                    $request,
                    'updateSliderAction',
                    'Gagal ubah data slider',
                    json_encode($updateData),
                    json_encode($dt_exist)
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Slider gagal diperbarui'
                ]);
            }
        }
    }

    
    public function deleteSliderAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'setting||konten';
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
            $dt_exist = DB::table('app_slider')->where('id_slider', $id)->first();
            $deleted = DB::table('app_slider')->where('id_slider', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deleteSliderAction' ,'Berhasil hapus data slider','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Berhasil hapus slider']);
            } else {
                $this->dataService->createLog($request,'deleteSliderAction' ,'Gagal hapus data slider','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Gagal hapus slider']);
            }
        }
    }

    
    public function addSliderAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'setting||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
                }
            $validator = Validator::make($request->all(), [
                'judul'       => 'required|string|max:255',
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
                $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('slider', $filename, 'public');
            }
            $data = [
                'judul_slider'      => $request->judul,
                'urutan_slider' => $request->urutan,
                'gambar_slider'           => $path,
                'jenis_slider'           => 'gambar',
                'created_at'           => now(),
            ];
            $insert = DB::table('app_slider')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addSliderAction' ,'Berhasil tambah data slider',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Slider berhasil disimpan'
                ]);
            }else{
                $this->dataService->createLog($request,'addSliderAction' ,'Gagal tambah data slider',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Slider gagal disimpan'
                ]);
            }
        }
    }

    public function addSliderTextAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'setting||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
                }
            $validator = Validator::make($request->all(), [
                'judul'       => 'required|string|max:255',
                'urutan'  => 'required',
                'deskripsi'  => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $data = [
                'judul_slider'      => $request->judul,
                'urutan_slider' => $request->urutan,
                'deskripsi_slider'           => $request->deskripsi,
                'jenis_slider'           => 'text',
                'created_at'           => now(),
            ];
            $insert = DB::table('app_slider')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addSliderTextAction' ,'Berhasil tambah data slider',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Slider berhasil disimpan'
                ]);
            }else{
                $this->dataService->createLog($request,'addSliderTextAction' ,'Gagal tambah data slider',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Slider gagal disimpan'
                ]);
            }
        }
    }

    
    public function updateSettingAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'setting||konten';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                
                'logo'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $updateData = [
                'deskripsi_topik'       => $request->deskripsi_topik,
                'deskripsi_organisasi'       => $request->deskripsi_organisasi,
                'deskripsi_permohonan'  => $request->deskripsi_permohonan,
                'deskripsi_hubungi'       => $request->deskripsi_hubungi,
                'deskripsi_tentang'       => $request->deskripsi_tentang,
                'deskripsi_login'  => $request->deskripsi_login,
                'url_facebook'  => $request->url_facebook,
                'url_twitter'  => $request->url_twitter,
                'url_instagram'  => $request->url_instagram,
                'url_youtube'  => $request->url_youtube,
                'url_linkedin'  => $request->url_linkedin,
                'updated_at'            => now(),
            ];

            if ($request->hasFile('logo')) {
                $filename = time() . '_' . $request->file('logo')->getClientOriginalName();
                $path = $request->file('logo')->storeAs('image_setting', $filename, 'public');
                $updateData['logo'] = $path;
            }

            if ($request->hasFile('gambar_dashboard')) {
                $filename2 = time() . '_' . $request->file('gambar_dashboard')->getClientOriginalName();
                $path2 = $request->file('gambar_dashboard')->storeAs('image_setting', $filename2, 'public');
                $updateData['gambar_dashboard'] = $path2;
            }

            if ($request->hasFile('gambar_topik')) {
                $filename3 = time() . '_' . $request->file('gambar_topik')->getClientOriginalName();
                $path3 = $request->file('gambar_topik')->storeAs('image_setting', $filename3, 'public');
                $updateData['gambar_topik'] = $path3;
            }

            if ($request->hasFile('gambar_organisasi')) {
                $filename4 = time() . '_' . $request->file('gambar_organisasi')->getClientOriginalName();
                $path4 = $request->file('gambar_organisasi')->storeAs('image_setting', $filename4, 'public');
                $updateData['gambar_organisasi'] = $path4;
            }

            if ($request->hasFile('gambar_permohonan')) {
                $filename5 = time() . '_' . $request->file('gambar_permohonan')->getClientOriginalName();
                $path5 = $request->file('gambar_permohonan')->storeAs('image_setting', $filename5, 'public');
                $updateData['gambar_permohonan'] = $path5;
            }

            if ($request->hasFile('gambar2_permohonan')) {
                $filename6 = time() . '_' . $request->file('gambar2_permohonan')->getClientOriginalName();
                $path6 = $request->file('gambar2_permohonan')->storeAs('image_setting', $filename6, 'public');
                $updateData['gambar2_permohonan'] = $path6;
            }

            if ($request->hasFile('gambar_hubungi')) {
                $filename7 = time() . '_' . $request->file('gambar_hubungi')->getClientOriginalName();
                $path7 = $request->file('gambar_hubungi')->storeAs('image_setting', $filename7, 'public');
                $updateData['gambar_hubungi'] = $path7;
            }

            if ($request->hasFile('gambar2_hubungi')) {
                $filename8 = time() . '_' . $request->file('gambar2_hubungi')->getClientOriginalName();
                $path8 = $request->file('gambar2_hubungi')->storeAs('image_setting', $filename8, 'public');
                $updateData['gambar2_hubungi'] = $path8;
            }

            if ($request->hasFile('gambar_tentang')) {
                $filename9 = time() . '_' . $request->file('gambar_tentang')->getClientOriginalName();
                $path9 = $request->file('gambar_tentang')->storeAs('image_setting', $filename9, 'public');
                $updateData['gambar_tentang'] = $path9;
            }

            if ($request->hasFile('gambar2_tentang')) {
                $filename10 = time() . '_' . $request->file('gambar2_tentang')->getClientOriginalName();
                $path10 = $request->file('gambar2_tentang')->storeAs('image_setting', $filename10, 'public');
                $updateData['gambar2_tentang'] = $path10;
            }

            if ($request->hasFile('gambar_login')) {
                $filename11 = time() . '_' . $request->file('gambar_login')->getClientOriginalName();
                $path11 = $request->file('gambar_login')->storeAs('image_setting', $filename11, 'public');
                $updateData['gambar_login'] = $path11;
            }

            if ($request->hasFile('gambar2_login')) {
                $filename11 = time() . '_' . $request->file('gambar2_login')->getClientOriginalName();
                $path11 = $request->file('gambar2_login')->storeAs('image_setting', $filename11, 'public');
                $updateData['gambar2_login'] = $path11;
            }


            $id =  1;
            $dt_exist = DB::table('app_setting')->where('id_setting', $id)->first();
            $update = DB::table('app_setting')->where('id_setting', $id)->update($updateData);


            if($update){
                $this->dataService->createLog($request,'updateSettingAction' ,'Berhasil ubah data setting',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Pengaturan berhasil diperbarui'
                ]);
            }else{
                $this->dataService->createLog($request,'updateSettingAction' ,'Gagal ubah data setting',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Pengaturan gagal diperbarui'
                ]);
            }
        }
    }



}
