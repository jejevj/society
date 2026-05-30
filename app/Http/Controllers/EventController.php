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
use Carbon\Carbon;


class EventController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Events',
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
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
									</ul>'
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.event.main', $data);
    }

    public function getTableEvent(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('t_event as a')
                ->selectRaw("
                    a.*,
                    (
                        SELECT COUNT(*)
                        FROM t_event_paket b
                        WHERE b.event_kode_paket = a.kode_event
                    ) as total_paket,
                    (
                        SELECT COUNT(*)
                        FROM t_event_program c
                        WHERE c.kode_event_program = a.kode_event
                    ) as total_program,
                    (
                        SELECT COUNT(*)
                        FROM t_event_kolaborasi d
                        WHERE d.event_kode_kolaborasi = a.kode_event
                    ) as total_kolaborasi
                ");
                
                if ($request->filled('nama')) {
                    $query->where('a.judul_event', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.id_event', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row) use ($cek) {
                    $editUrl = route('editEvent', $row->kode_event);
                    $btn = '';
                    if ($cek['u']) {
                        $btn .= '<a href="' . $editUrl . '" class="btn btn-light-warning btn-sm me-1" title="Edit Event"><span class="fa fa-pencil"></span></a>';
                    }
                    if ($cek['d']) {
                        $btn .= '<button title="Delete" class="btn btn-danger btn-delete-event btn-sm" data-id="' . $row->kode_event . '"><span class="fa fa-trash"></span></button>';
                    }
                    return $btn;
                })

                ->addColumn('info', function ($row) {
                    $paketUrl       = route('paketEvent',     $row->kode_event);
                    $programUrl     = route('programEvent',   $row->kode_event);
                    $kolaborasiUrl  = route('kolaborasiEvent',$row->kode_event);
                    $btn = '
                        <a class="text-dark fs-6" href="' . $paketUrl . '"><i class="fa fa-edit text-dark"></i> Packages: '. $row->total_paket .'</a><br>
                        <a class="text-dark fs-6" href="' . $programUrl . '"><i class="fa fa-edit text-dark"></i> Programs: '. $row->total_program .'</a><br>
                        <a class="text-dark fs-6" href="' . $kolaborasiUrl . '"><i class="fa fa-edit text-dark"></i> Collaborators: '. $row->total_kolaborasi .'</a>
                    ';
                    return $btn;
                })

                ->addColumn('info2', function ($row) {
                        $tglAwal  = Carbon::parse($row->tanggal_awal_event)->translatedFormat('j F Y');
                        $tglAkhir = Carbon::parse($row->tanggal_akhir_event)->translatedFormat('j F Y');
                        return '
                            <span class="text-dark fs-6">Date: '.$tglAwal.' - '.$tglAkhir.'</span><br>
                            <span class="text-dark fs-6">Location: '.$row->lokasi_event.'</span><br>
                        ';
                    })
                
                ->rawColumns(['action','info','info2'])
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Add events',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"> <i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Add Events</li>
									</ul>'
        ];
        if (!$cek['c']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.event.tambah', $data);

    }

    public function addEventAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'judul'       => 'required|string|max:255',
                'sub_judul'       => 'required|string|max:255',
                'awal'       => 'required',
                'akhir'       => 'required',
                'keterangan'       => 'required',
                'lokasi'       => 'required|string|max:255',
                'gambar'     => 'required|image|mimes:jpg,jpeg,png|max:5048',
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
                $path = $request->file('gambar')->storeAs('event', $filename, 'public');
            }

            $kode = 'EV'.date('ymdhis');
            $data = [
                'kode_event'      => $kode,
                'judul_event'      => $request->judul,
                'sub_judul_event'      => $request->sub_judul,
                'keterangan_event'      => $request->keterangan,
                'lokasi_event'      => $request->lokasi,
                'tanggal_awal_event'      => $request->awal,
                'tanggal_akhir_event'      => $request->akhir,
                'background_event'      => $path,
                'status_event'      => 'N',
                'created_by_event'      => session('nama'),
                'created_at_event'            => now(),
            ];
            
            $insert = DB::table('t_event')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addEventAction' ,'Successfully added event data',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully added event data'
                ]);
            }else{
                $this->dataService->createLog($request,'addEventAction' ,'Failed to add event data',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add event data'
                ]);
            }
        }
    }

    public function editEvent($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();
        $data = [
            'menu' => 'Edit Event',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Edit Events</li>
									</ul>',
            'kode_event' => $kode_event,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.event.edit', $data);
    }
    
    public function updateEventAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'key'       => 'required',
                'judul'       => 'required|string|max:255',
                'sub_judul'       => 'required|string|max:255',
                'awal'       => 'required',
                'akhir'       => 'required',
                'keterangan'       => 'required',
                'lokasi'       => 'required|string|max:255',
                'gambar'     => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $updateData = [
                'judul_event'      => $request->judul,
                'sub_judul_event'      => $request->sub_judul,
                'keterangan_event'      => $request->keterangan,
                'lokasi_event'      => $request->lokasi,
                'tanggal_awal_event'      => $request->awal,
                'tanggal_akhir_event'      => $request->akhir,
                'updated_at_event'            => now(),
            ];
            if ($request->hasFile('gambar')) {
                $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('event', $filename, 'public');

                $updateData['background_event'] = $path;
            }

            $id =  $request->key;
            $dt_exist = DB::table('t_event')->where('kode_event', $id)->first();
            $update = DB::table('t_event')->where('kode_event', $id)->update($updateData);


            if($update){
                $this->dataService->createLog($request,'updateEventAction' ,'Event updated successfully',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Event updated successfully'
                ]);
            }else{
                $this->dataService->createLog($request,'updateEventAction' ,'Event failed to update',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Event failed to update'
                ]);
            }
        }
    }

    public function deleteEventAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
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
            $id = $request->key;
            $dt_exist = DB::table('t_event')->where('kode_event', $id)->first();
            $deleted = DB::table('t_event')->where('kode_event', $id)->delete();

            if ($deleted) {
                DB::table('t_event_kolaborasi')->where('event_kode_kolaborasi', $id)->delete();
                DB::table('t_event_paket')->where('event_kode_paket', $id)->delete();
                DB::table('t_event_paket_detail')->where('event_kode', $id)->delete();
                DB::table('t_event_program')->where('event_kode', $id)->delete();
                DB::table('t_event_program_detail')->where('event_kode', $id)->delete();

                $this->dataService->createLog($request,'deleteEventAction' ,'Successfully deleted event data','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Successfully deleted event data']);
            } else {
                $this->dataService->createLog($request,'deleteEventAction' ,'Failed to delete event data','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Failed to delete event data']);
            }
        }    
    }

    public function paketEvent($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();
        $data = [
            'menu' => 'Packages Event',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Packages Event</li>
									</ul>',
            'kode_event' => $kode_event,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.event.paket.main', $data);
    }
    
    public function getTablePaketEvent(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('t_event_paket as a')
                ->selectRaw("a.*");
                if ($request->filled('key')) {
                    $query->where('a.event_kode_paket',  $request->input('key'));
                }
                
           $query->orderBy('a.id_event_paket', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row)  use ($cek) {
                    $infoUrl = route('editPaketEvent', $row->kode_paket);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a> ';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-event btn-sm" data-id="' . $row->kode_paket . '"><span class="fa fa-trash"></span></button> ';
                    }
                    return $btn;
                })

                ->addColumn('gambar', function ($row) {
                    if ($row->gambar_paket) {
                        $url = asset('storage/' . $row->gambar_paket);
                        return '<img src="'.$url.'" width="80" class="img-icon"/>';
                    }
                    return '-';
                })
                ->addColumn('icon', function ($row) {
                    if ($row->icon_paket) {
                        $url = asset('storage/' . $row->icon_paket);
                        return '<img src="'.$url.'" width="80" class="img-icon"/>';
                    }
                    return '-';
                })

                ->addColumn('info2', function ($row) {
                        return '
                            <span class="text-dark fs-6"><b>Location</b>: '.$row->lokasi_paket.'</span><br>
                            <span class="text-dark fs-6"><b>Description</b>: '.$row->keterangan_paket.'</span><br>
                        ';
                    })
                
                ->rawColumns(['action','gambar','icon','info2'])
                ->make(true);
        }
    }

    public function tambahPaketEvent($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();
        $data = [
            'menu' => 'Add Packages Event',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
                                        <li class="breadcrumb-item  fw-bold lh-1">Packages Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Add Packages Event</li>
									</ul>',
            'kode_event' => $kode_event,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.event.paket.tambah', $data);
    }

    public function addPaketEventAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'judul'       => 'required|string|max:255',
                'sub_judul'       => 'required|string|max:255',
                'key'       => 'required',
                'keterangan'       => 'required',
                'lokasi'       => 'required|string|max:255',
                'gambar'     => 'required|image|mimes:jpg,jpeg,png|max:5048',
                'icon'     => 'required|image|mimes:jpg,jpeg,png|max:5048',
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
                $filename = 'pkg_' .time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('event', $filename, 'public');
            }

            $path2 = null;
            $filename2 = null;
            if ($request->hasFile('icon')) {
                $filename2 = 'pki_' .time() . '_' . $request->file('icon')->getClientOriginalName();
                $path2 = $request->file('icon')->storeAs('event', $filename2, 'public');
            }

            $kode = $request->key.date('is');
            $data = [
                'kode_paket'      => $kode,
                'event_kode_paket'      => $request->key,
                'judul_paket'      => $request->judul,
                'sub_judul_paket'      => $request->sub_judul,
                'keterangan_paket'      => $request->keterangan,
                'lokasi_paket'      => $request->lokasi,
                'gambar_paket'      => $path,
                'icon_paket'      => $path2,
                'created_at_paket'            => now(),
            ];
            
            $insert = DB::table('t_event_paket')->insert($data);

            if($insert){
                $this->dataService->createLog($request,'addPaketEventAction' ,'Successfully added package event data',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully added package event data'
                ]);
            }else{
                $this->dataService->createLog($request,'addPaketEventAction' ,'Failed to add package event data',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add package event data'
                ]);
            }
        }
    }
    
    public function editPaketEvent($kode_paket, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $detail = DB::table('t_event_paket')->where('kode_paket', $kode_paket)->first();
        $data = [
            'menu' => 'Edit Packages Event',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
                                        <li class="breadcrumb-item  fw-bold lh-1">Packages Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Edit Packages Event</li>
									</ul>',
            'kode_paket' => $kode_paket,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.event.paket.edit', $data);
    }

    public function editPaketEventAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['u']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'key'       => 'required',
                'judul'       => 'required|string|max:255',
                'sub_judul'       => 'required|string|max:255',
                'keterangan'       => 'required',
                'lokasi'       => 'required|string|max:255',
                'gambar'     => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
                'icon'     => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $updateData = [
                'judul_paket'      => $request->judul,
                'sub_judul_paket'      => $request->sub_judul,
                'keterangan_paket'      => $request->keterangan,
                'lokasi_paket'      => $request->lokasi,
                'updated_at_paket'            => now(),
            ];
            if ($request->hasFile('gambar')) {
                $filename = 'pkg_' .time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('event', $filename, 'public');
                $updateData['gambar_paket'] = $path;
            }

            if ($request->hasFile('icon')) {
                $filename2 = 'pki_' .time() . '_' . $request->file('icon')->getClientOriginalName();
                $path2 = $request->file('icon')->storeAs('event', $filename2, 'public');
                $updateData['icon_paket'] = $path2;
            }

            $id =  $request->key;
            $dt_exist = DB::table('t_event_paket')->where('kode_paket', $id)->first();
            $update = DB::table('t_event_paket')->where('kode_paket', $id)->update($updateData);


            if($update){
                $this->dataService->createLog($request,'editPaketEventAction' ,'Event Package updated successfully',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => true,
                    'message' => 'Event Package updated successfully'
                ]);
            }else{
                $this->dataService->createLog($request,'editPaketEventAction' ,'Event Package failed to update',json_encode($updateData),json_encode($dt_exist));
                return response()->json([
                    'success' => false,
                    'message' => 'Event Package failed to update'
                ]);
            }
        }
    }
    
    public function deletePaketEventAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
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
            $id = $request->key;
            $dt_exist = DB::table('t_event_paket')->where('kode_paket', $id)->first();
            $deleted = DB::table('t_event_paket')->where('kode_paket', $id)->delete();

            if ($deleted) {
                $this->dataService->createLog($request,'deletePaketEventAction' ,'Successfully deleted package event data','',json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Successfully deleted package event data']);
            } else {
                $this->dataService->createLog($request,'deletePaketEventAction' ,'Failed to delete package event data','',json_encode($dt_exist));
                return response()->json(['success' => false, 'message' => 'Failed to delete package event data']);
            }
        }    
    }

    public function programEvent($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();
        $data = [
            'menu' => 'Program Event',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Program Event</li>
									</ul>',
            'kode_event' => $kode_event,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.event.program.main', $data);
    }
    
    public function getTableProgramEvent(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            
            $query = DB::table('t_event_program as a')
                ->selectRaw("a.*");
                if ($request->filled('key')) {
                    $query->where('a.event_kode_program',  $request->input('key'));
                }
                
           $query->orderBy('a.id_event_program', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row)  use ($cek) {
                    $infoUrl = route('editProgramEvent', $row->kode_event_program);
                    $btn = '';
                    if($cek['u']){
                        $btn .= '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a> ';
                    }
                    if($cek['d']){
                        $btn .= '<button title="HAPUS" class="btn btn-danger btn-delete-event btn-sm" data-id="' . $row->kode_event_program . '"><span class="fa fa-trash"></span></button> ';
                    }
                    return $btn;
                })

                ->addColumn('info', function ($row) {
                    $detail = DB::table('t_event_program_detail')
                        ->where('event_program_kode', $row->kode_event_program)
                        ->orderBy('id_event_program_detail', 'asc')
                        ->get();

                    if ($detail->isEmpty()) {
                        return '-';
                    }
                    $html = '<ul class="mb-0 ps-4">';
                    foreach ($detail as $d) {
                        $html .= '<li>[ '.$d->awal_program_detail.' -  '.$d->akhir_program_detail.'] '.$d->sesi_program_detail.'</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })
                
                ->rawColumns(['action','info'])
                ->make(true);
        }
    }

    public function tambahProgramEvent($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();
        $data = [
            'menu' => 'Add Program Event',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'cek_permit' => $cek,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item  fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home  fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
                                        <li class="breadcrumb-item  fw-bold lh-1">Program Events</li>
                                        <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4  mx-n1"></i></li>
										<li class="breadcrumb-item  fw-bold lh-1">Add Program Event</li>
									</ul>',
            'kode_event' => $kode_event,
            'detail' => $detail
        ];
         if (!$cek['u']) {
             return view('admin-panel.error_page.403-page', $data);
        }

        return view('admin-panel.event.program.tambah', $data);
    }

    public function addProgramEventAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if(!$cek['c']){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 422);
            }
                
            $validator = Validator::make($request->all(), [
                'hari'       => 'required|string|max:255',
                'tanggal'       => 'required|string|max:255',
                'key'       => 'required',
                'sesi' => 'required|array|min:1',
                'sesi.*' => 'required|string',
                'keterangan' => 'required|array|min:1',
                'keterangan.*' => 'required|string',
                'jam_awal' => 'required|array|min:1',
                'jam_awal.*' => 'required',
                'jam_akhir' => 'required|array|min:1',
                'jam_akhir.*' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            
            $kode = $request->key.date('is');
            $data = [
                'kode_event_program'      => $kode,
                'event_kode_program'      => $request->key,
                'hari_program'      => $request->hari,
                'tanggal_program'      => $request->tanggal,
                'created_at_program'            => now(),
            ];
            
            $insert = DB::table('t_event_program')->insert($data);

            if($insert){
                for ($i = 0; $i < count($request->sesi); $i++) {

                    DB::table('t_event_program_detail')->insert([
                        'kode_event_program_detail' => $kode . sprintf('%03d', $i + 1),
                        'event_program_kode' => $kode,
                        'event_kode' => $request->key,
                        'sesi_program_detail' => $request->sesi[$i],
                        'keterangan_program_detail' => $request->keterangan[$i],
                        'awal_program_detail' => $request->jam_awal[$i],
                        'akhir_program_detail' => $request->jam_akhir[$i],
                        'created_at_program_detail' => now(),
                    ]);
                }

                $this->dataService->createLog($request,'addProgramEventAction' ,'Successfully added program event data',json_encode($data),'');
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully added program event data'
                ]);
            }else{
                $this->dataService->createLog($request,'addProgramEventAction' ,'Failed to add program event data',json_encode($data),'');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add program event data'
                ]);
            }
        }
    }
}
