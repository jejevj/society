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



class WebHomeController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'about';

        $event = DB::table('t_event')
            ->where('status_event', 'Y')
            ->orderBy('created_at_event', 'asc')
            ->get();

        foreach ($event as $e) {
            $e->paket = DB::table('t_event_paket')
                ->where('event_kode_paket', $e->kode_event)
                ->orderBy('id_event_paket', 'asc')
                ->get();
                
            $e->kolaborasi = DB::table('t_event_kolaborasi')
            ->where('event_kode_kolaborasi', $e->kode_event)
            ->orderBy('id_event_kolaborasi', 'asc')
            ->get();
        }

        $data = [
            'menu' => 'Log Aktivitas',
            'menu_aktif' => $menu_aktif,
            'event' => $event,
            'set' => DB::table('app_setting')
                        ->where('kode', 'SETT')
                        ->first(),
        ];

        return view('web.home.main', $data);
    }

    public function getListData()
    {
        $dataset = DB::table('t_master_data as x')
                ->leftJoin('reff_status as b', function ($join) {
                    $join->on('b.kode_status', '=', 'x.tipe_master')
                        ->where('b.jenis_status', '=', 'tipe_data');
                })
                ->leftJoin('reff_status as c', function ($join) {
                    $join->on('c.kode_status', '=', 'x.kategori_master')
                        ->where('c.jenis_status', '=', 'kategori_data');
                })
                ->leftJoin('reff_status as e', function ($join) {
                    $join->on('e.kode_status', '=', 'x.frekuensi_master')
                        ->where('e.jenis_status', '=', 'frekuensi_data');
                })
                ->leftJoin('reff_organisasi as d', 'd.id_organisasi', '=', 'x.organisasi_master')
                ->selectRaw("x.*, b.keterangan_status as tipe_data_desc, c.keterangan_status as kategori_data_desc,
                             e.keterangan_status as frekuensi_data_desc,
                             d.nama_organisasi,
                            (SELECT COUNT(*) FROM t_permohonan WHERE t_permohonan.kode_data_permohonan = x.kode_data_master AND t_permohonan.status_permohonan = 'Y') AS jumlah_download,
                            (SELECT COUNT(*) FROM t_data_log WHERE t_data_log.data_kode_log = x.kode_data_master ) AS jumlah_lihat ")
            ->where('x.tipe_master', 'DT')
            ->where('x.status_master','Y')
            ->whereIn('x.sifat_master', ['TERBUKA','TERBATAS'])
            ->orderBy('x.id_master_data', 'desc')
            ->limit(4)
            ->get();

        $infografis = DB::table('t_master_data as x')
            ->leftJoin('reff_status as b', function ($join) {
                    $join->on('b.kode_status', '=', 'x.tipe_master')
                        ->where('b.jenis_status', '=', 'tipe_data');
                })
                ->leftJoin('reff_status as c', function ($join) {
                    $join->on('c.kode_status', '=', 'x.kategori_master')
                        ->where('c.jenis_status', '=', 'kategori_data');
                })
                ->leftJoin('reff_status as e', function ($join) {
                    $join->on('e.kode_status', '=', 'x.frekuensi_master')
                        ->where('e.jenis_status', '=', 'frekuensi_data');
                })
                ->leftJoin('reff_organisasi as d', 'd.id_organisasi', '=', 'x.organisasi_master')
                ->selectRaw("x.*, b.keterangan_status as tipe_data_desc, c.keterangan_status as kategori_data_desc,
                             e.keterangan_status as frekuensi_data_desc,
                             d.nama_organisasi,
                            (SELECT COUNT(*) FROM t_permohonan WHERE t_permohonan.kode_data_permohonan = x.kode_data_master AND t_permohonan.status_permohonan = 'Y') AS jumlah_download,
                            (SELECT COUNT(*) FROM t_data_log WHERE t_data_log.data_kode_log = x.kode_data_master ) AS jumlah_lihat ")
            ->where('x.tipe_master', 'IG')
            ->where('x.status_master','Y')
            ->whereIn('x.sifat_master', ['TERBUKA','TERBATAS'])
            ->orderBy('x.id_master_data', 'desc')
            ->limit(4)
            ->get();

        $datasetHtml = view('web.dashboard-list-data', [
            'data' => $dataset,
            'img' => 'satu-data-pertahanan-data.jpeg',
            'carousel_id' => 'datasetCarousel'
        ])->render();

        $infografisHtml = view('web.dashboard-list-data', [
            'data' => $infografis,
            'img' => 'satu-data-pertahanan-data.jpeg',
            'carousel_id' => 'infografisCarousel'
        ])->render();

        return response()->json([
            'dataset_html' => $datasetHtml,
            'infografis_html' => $infografisHtml
        ]);
    }
    
    public function getCountData(Request $request)
    {
        $dataset_count = DB::table('t_master_data')
            ->where('tipe_master', 'DT')
            ->where('status_master', 'Y')
            ->whereIn('sifat_master', ['TERBUKA','TERBATAS'])
            ->count();

        $infografis_count = DB::table('t_master_data')
            ->where('status_master', 'Y')
            ->where('tipe_master', 'IG')
            ->whereIn('sifat_master', ['TERBUKA','TERBATAS'])
            ->count();

        $organisasi_count = DB::table('reff_organisasi')->where('status_organisasi','Y')->count();
        // dd($dataset_count);
        return response()->json([
            'organisasi_count' => $organisasi_count,
            'dataset_count' => $dataset_count,
            'infografis_count' => $infografis_count,
            'total_data' => $dataset_count + $infografis_count
        ]);
    }

    public function getTopik()
    {
        $topik = DB::table('reff_topik')
            ->orderBy('urutan_topik', 'asc')
            ->get();

        $html = view('web.dashboard-topik', compact('topik'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function getTautan()
    {
        $tautan = DB::table('t_tautan')
            ->orderBy('urutan_tautan','asc')
            ->get();

        $chunks = $tautan->chunk(4);

        $html = '';

        foreach ($chunks as $key => $chunk) {
            $active = $key == 0 ? 'active' : '';

            $html .= '<div class="carousel-item '.$active.'">';
            $html .= '<div class="row text-center">';

            foreach ($chunk as $item) {
                $url = $item->link_tautan;
                $img = asset('storage/'.$item->gambar_tautan);
                $nama = $item->nama_tautan ?? 'Tautan';

                $html .= '
                <div class="col-3 my-2">
                    <a target="_blank" href="'.$url.'">
                        <img src="'.$img.'" 
                            class="d-block mx-auto img-fluid"
                            style="height:100px; object-fit:contain;"
                            alt="'.$nama.'">
                    </a>
                </div>';
            }

            $html .= '</div></div>';
        }

        return response()->json([
            'html' => $html
        ]);
    }
    public function getSlider()
    {
        $slider = DB::table('app_slider')
            ->where('jenis_slider','gambar')
            ->orderBy('urutan_slider','asc')
            ->get();

        $html = '';

        foreach ($slider as $i => $d) {
            $active = $i == 0 ? 'active' : '';
            $img = asset('storage/'.$d->gambar_slider);

            $html .= '
            <div class="carousel-item '.$active.'">
                <div class="slider-wrapper">
                    <img src="'.$img.'" class="img-fluid slider-img" alt="Slider">
                </div>
            </div>';
        }

        return response()->json([
            'html' => $html
        ]);
    }

    public function getOrganisasi()
    {
        $organisasi = DB::table('reff_organisasi')
            ->orderBy('id_organisasi','asc')
            ->get();

        $html = '';

        foreach ($organisasi as $t) {
            $html .= '
            <div class="col-6 col-md-4 col-lg-4 mb-3">
                <a href="'.$t->web_organisasi.'" target="_blank" class="footer-link">
                    <i class="fa fa-globe"></i>
                    <span>'.$t->nama_organisasi.'</span>
                </a>
            </div>';
        }

        return response()->json([
            'html' => $html
        ]);
    }

    public function getTableLog(Request $request)
    {
        if ($request->ajax()) {
            
            $query = DB::table('app_log_aktivitas as a')
                ->selectRaw('*');
                
                if ($request->filled('nama')) {
                    $query->where('a.deskripsi_log', 'ILIKE', '%' . $request->input('nama') . '%');
                }
                
           $query->orderBy('a.id_log', 'desc')->get();

            return DataTables::of($query)
                ->addIndexColumn()  
                ->addColumn('action', function ($row) {
                    $id_hash = Crypt::encrypt($row->id_log);
                    $infoUrl = route('editTopik', $id_hash);
                    $btn = '<a href=' . $infoUrl . ' class="btn btn-light-warning btn-sm"><span class="fa fa-pencil"></span></a> 
                            <button title="HAPUS" class="btn btn-danger btn-delete-topik btn-sm" data-id="' . $id_hash . '"><span class="fa fa-trash"></span></button> ';
                    return $btn;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tujuan'   => 'required|string|max:255',
            'keberhasilan'  => 'required|string|max:10',
            'saran'    => 'string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }else{
            if($request->tujuan == 'Lainnya' && empty($request->tujuan_lainnya)){
                return response()->json([
                    'status' => false,
                    'message' => 'Tujuan lainnya harus diisi'
                ]);
            }
            $save = DB::table('t_survey_kepuasan')->insert([
                'tujuan' => $request->tujuan,
                'tujuan_lainnya' => $request->tujuan_lainnya ?? '',
                'keberhasilan' => $request->keberhasilan,
                'saran' => $request->saran,
                'created_at' => now()
            ]);

            if($save){
                return response()->json([
                    'status' => true,
                    'message' => 'Terima kasih atas Penilaian dan Feedback yang telah Anda berikan'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan dalam menyimpan feedback'
                ]);
            }
            
        }

        
    }


}
