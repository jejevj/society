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
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Vish4395\LaravelFileViewer\LaravelFileViewer;
use App\Mail\AppMail;





class WebDataController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        if($tipe = $request->query('tipe') == 'DT'){
            $menu_aktif = 'dataset';
            $nama_menu_list = 'List Dataset';
        }else if($tipe = $request->query('tipe') == 'IG'){
            $menu_aktif = 'infografis';
            $nama_menu_list = 'List Infografis';
        }else{
            $menu_aktif = 'data';
            $nama_menu_list = 'List Data';
        }
        

        

        $data = [
            'menu' => 'data',
            'menu_aktif' => $menu_aktif,
            'nama_menu_list' => $nama_menu_list,
            'list_topik' => DB::table('reff_topik')->orderBy('urutan_topik', 'asc')->get()->toArray(),
            'list_organisasi' => DB::table('reff_organisasi')->get()->toArray(),
            'list_tipe' => DB::table('reff_status')->where('jenis_status','tipe_data')->get()->toArray(),
            'f_tipe' => $request->query('tipe', []),
            'f_topik' => $request->query('topik', []),
            'f_organisasi' => $request->query('org', []),
            'f_status' => $request->query('status', []),
            'set' =>  DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.data-list', $data);
    }

    public function getDataList(Request $request)
    {
        $query = DB::table('t_master_data as x')            
            ->leftJoin('reff_status as c', function($join) {
                $join->on('x.tipe_master', '=', 'c.kode_status')
                    ->where('c.jenis_status', '=', 'tipe_data');
            })
            ->leftJoin('reff_organisasi as d', 'x.organisasi_master', '=', 'd.id_organisasi')
            ->leftJoin('reff_status as e', function($join) {
                $join->on('x.kategori_master', '=', 'e.kode_status')
                    ->where('e.jenis_status', '=', 'kategori_data');
            })
            ->leftJoin('t_data_tag as f', 'x.kode_data_master', '=', 'f.kode_data_tag')
            ->selectRaw('x.*,
                    c.keterangan_status,
                    d.nama_organisasi,
                    e.keterangan_status as keterangan_status_kategori,
                    (SELECT COUNT(*) FROM t_permohonan WHERE kode_data_permohonan = x.kode_data_master AND status_permohonan = \'Y\') AS jumlah_download,
                    (SELECT COUNT(*) FROM t_data_log WHERE data_kode_log = x.kode_data_master) AS jumlah_lihat')
                
            ->whereIn('x.sifat_master', ['TERBUKA','TERBATAS'])
            ->where('x.status_master', 'Y');

        if ($request->filled('topik')) {
            $query->whereIn('f.kode_tag', (array)$request->topik);
        }

        if ($request->filled('org')) {
            $query->whereIn('x.organisasi_master', (array)$request->org);
        }

        if ($request->filled('tipe')) {
            $query->whereIn('x.tipe_master', (array)$request->tipe);
        }

        if ($request->filled('status')) {
            $query->whereIn('x.sifat_master', (array)$request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('x.judul_master', 'ILIKE', "%{$request->search}%")
                ->orWhere('x.deskripsi_master', 'ILIKE', "%{$request->search}%");
            });
        }

        $query->groupBy(
            'x.id_master_data',
            'x.kode_data_master',
            'c.keterangan_status',
            'd.nama_organisasi',
            'e.keterangan_status'
        );

        $data = $query->orderBy('id_master_data', 'desc')->paginate(12);
        $html = view('web.data-list-content', compact('data'))->render();

        return response()->json([
            'html' => $html
        ]);
    }


    public function unduhTerbuka(Request $request, $kode)
    {
        // Pisahkan kode dan decrypt id
        $p_kode = explode('~', $kode);
        $id = Crypt::decryptString($p_kode[1]);

        // Ambil detail file
        $detail = DB::table('t_data as a')
            ->join('t_master_data as x','x.kode_data_master','=','a.kode_data')
            ->where('a.kode_data', $p_kode[0])
            ->where('a.id_data', $id)
            ->where('x.sifat_master', 'TERBUKA')
            ->first();

        if (!$detail) {
            abort(404, 'Data tidak ditemukan');
        }

        $disk   = 'sftp_storage_terbuka'; 
        $folder = 'DATA_TERBUKA';

        $filename = basename(ltrim($detail->file_data, '/'));
        $fullPath = $folder . '/' . $filename;

        if (!Storage::disk($disk)->exists($fullPath)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        // Simpan log permohonan
        DB::table('t_permohonan')->insert([
            'data_id_permohonan' => $detail->id_data,
            'kode_data_permohonan' => $detail->kode_data,
            'status_permohonan' => 'Y',
            'user_id_permohonan' => session('id_user') ?? null,
            'nama_permohonan' => session('nama_user') ?? 'Guest',
            'email_permohonan' => session('email_user') ?? '-',
            'created_at' => now(),
        ]);

        $this->dataService->createLogWeb(
            $request,
            'unduhTerbuka',
            'Mengunduh data terbuka (' . $detail->judul_data . ')'
        );

        $stream = Storage::disk($disk)->readStream($fullPath);
        return response()->streamDownload(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, $filename);
    }

    public function filePreview($fileName){
        $fileName = urldecode($fileName);
        $filePath = $fileName;
        $disk = 'public';
        $fileUrl = asset('storage/' . $fileName);
        $fileData = [
            [
                'label' => __('Label'),
                'value' => "Value"
            ]
        ];
        return LaravelFileViewer::show($fileName, $filePath, $fileUrl, $disk, $fileData);
    }


    public function dataDcat(Request $request, $kode): JsonResponse
    {   
        $p_kode = explode('~', $kode);
        $kode = $p_kode[0];
        $id = Crypt::decryptString($p_kode[1]);
        
        $detail = DB::table('t_data as a')
            ->leftJoin('reff_status as c', function($join) {
                $join->on('a.tipe_data', '=', 'c.kode_status')
                    ->where('c.jenis_status', '=', 'tipe_data');
            })
            ->join('t_master_data as x', 'x.kode_data_master' , '=', 'a.kode_data')
            ->leftJoin('reff_organisasi as d', 'x.organisasi_master', '=', 'd.id_organisasi')
            ->leftJoin('reff_status as e', function($join) {
                $join->on('x.kategori_master', '=', 'e.kode_status')
                    ->where('e.jenis_status', '=', 'kategori_data');
            })
            ->leftJoin('reff_status as f', function($join) {
                $join->on('x.frekuensi_master', '=', 'f.kode_status')
                    ->where('f.jenis_status', '=', 'frekuensi_data');
            })
            ->selectRaw('a.*,x.*, c.keterangan_status, d.nama_organisasi, e.keterangan_status as keterangan_status_kategori, f.keterangan_status as keterangan_status_frekuensi')
            ->where('a.kode_data', $kode)
            ->where('a.id_data', $id)
            ->first();
        
        if (!$detail) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        
        $dt_tag = DB::table('t_data_tag as a')
            ->leftJoin('reff_topik as b', DB::raw('a.kode_tag::bigint'), '=', 'b.id_topik')
            ->where('a.kode_data_tag', $kode)
            ->pluck('b.nama_topik')
            ->toArray();
        
        $response = [
            "@context" => "https://project-open-data.cio.gov/v1.1/schema/catalog.jsonld",
            "@id" => config('app.url'),
            "@type" => "dcat:Catalog",
            "conformsTo" => "https://project-open-data.cio.gov/v1.1/schema",
            "describedBy" => "https://project-open-data.cio.gov/v1.1/schema/catalog.json",
            "dataset" => [
                [
                    "@type" => "dcat:Dataset",
                    "accessLevel" => "public",
                    "contactPoint" => [
                        "fn" => "Pusat Data dan Informasi Kementrian Pertahanan Republik Indonesia",
                        "hasEmail" => "mailto:pusdatin@kemhan.go.id"
                    ],
                    "distribution" => [
                        [
                            "@type" => "dcat:Distribution",
                            "downloadURL" => isset($detail->file_data) 
                            ? route('file-preview-show', [
                                'sifat' => $detail->sifat_master,
                                'file' => urlencode(ltrim($detail->file_data, '/'))
                            ])
                            : '',
                            "mediaType" => "text/".$detail->tipe_file_data,
                            "format" => $detail->tipe_file_data,
                            "title" => $detail->judul_data ?? 'Dataset'
                        ]
                    ],
                    "identifier" => $detail->kode_data,
                    "issued" => $detail->created_at ?? '',
                    "landingPage" => url('/detail-data/'.$detail->kode_data),
                    "modified" => $detail->updated_at ?? '',
                    "accrualPeriodicity" => $detail->keterangan_status_frekuensi ?? 'Tahunan',
                    "publisher" => [
                        "@type" => "org:Organization",
                        "name" => $detail->nama_organisasi ?? ''
                    ],
                    "title" => $detail->judul_data ?? '',
                    "description" => isset($detail->deskripsi_data) ? strip_tags($detail->deskripsi_data) : '',
                    "keyword" => $dt_tag
                ]
            ]
        ];
        // dd($detail);
        return response()
            ->json($response, 200, [
                'Content-Type' => 'application/ld+json'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }


    public function detailData($url_data, Request $request)
    {
       
        $menu_aktif = 'data';
        // $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $detail = DB::table('t_master_data as x')
            
            ->leftJoin('reff_status as c', function($join) {
                $join->on('x.tipe_master', '=', 'c.kode_status')
                    ->where('c.jenis_status', '=', 'tipe_data');
            })
            ->leftJoin('reff_organisasi as d', 'x.organisasi_master', '=', 'd.id_organisasi')
            ->leftJoin('reff_status as e', function($join) {
                $join->on('x.kategori_master', '=', 'e.kode_status')
                    ->where('e.jenis_status', '=', 'kategori_data');
            })
            ->leftJoin('reff_status as f', function($join) {
                $join->on('x.frekuensi_master', '=', 'f.kode_status')
                    ->where('f.jenis_status', '=', 'frekuensi_data');
            })
            ->selectRaw("
                x.*,
                c.keterangan_status,
                d.nama_organisasi,
                d.tmp_foto_organisasi,
                e.keterangan_status as keterangan_status_kategori,
                f.keterangan_status as keterangan_status_frekuensi,

                (SELECT COUNT(*) 
                FROM t_permohonan 
                WHERE t_permohonan.kode_data_permohonan = x.kode_data_master 
                AND t_permohonan.status_permohonan = 'Y'
                ) AS jumlah_download,

                (SELECT COUNT(*) 
                FROM t_data_log 
                WHERE t_data_log.data_kode_log = x.kode_data_master
                ) AS jumlah_lihat
            ")
            ->where('x.kode_data_master', $url_data)
            ->first();
            
        if(empty($detail)){
            abort(404);
        }

        $dt_file = DB::table('t_data as a')
                ->leftJoin('reff_image_file as c', 'a.tipe_file_data', '=', 'c.ekstensi_image_file')
                ->selectRaw('a.*, c.path_image_file, (SELECT COUNT(*) FROM t_permohonan WHERE t_permohonan.data_id_permohonan = a.id_data AND t_permohonan.status_permohonan = \'Y\') AS jumlah_download,
                            (SELECT COUNT(*) FROM t_data_log WHERE t_data_log.data_id_log = a.id_data) AS jumlah_lihat')
                ->where('a.kode_data', $url_data)
                ->get()
                ->map(function ($item) {
                        $item->encrypted_id = Crypt::encryptString($item->id_data);
                        return $item;
                    })
                ->toArray();
        // dd($dt_file);

        $dt_tag = DB::table('t_data_tag')
            ->where('kode_data_tag', $url_data)
            ->pluck('kode_tag') 
            ->toArray();

        $dt_tag = DB::table('t_data_tag')
            ->where('kode_data_tag', $url_data)
            ->pluck('kode_tag') 
            ->toArray();

        $dt_terkait = DB::table('t_master_data as x')
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
            ->leftJoin('t_data_tag as f', 'x.kode_data_master', '=', 'f.kode_data_tag')
            ->leftJoin('reff_organisasi as d', 'd.id_organisasi', '=', 'x.organisasi_master')
            ->selectRaw("
                DISTINCT ON (x.kode_data_master)
                x.*, 
                b.keterangan_status as tipe_data_desc, 
                c.keterangan_status as kategori_data_desc,
                e.keterangan_status as frekuensi_data_desc,
                d.nama_organisasi,
                (SELECT COUNT(*) FROM t_permohonan 
                    WHERE t_permohonan.kode_data_permohonan = x.kode_data_master 
                    AND t_permohonan.status_permohonan = 'Y') AS jumlah_download,
                (SELECT COUNT(*) FROM t_data_log 
                    WHERE t_data_log.data_kode_log = x.kode_data_master) AS jumlah_lihat
            ")
            ->whereIn('f.kode_tag', $dt_tag)
            ->where('x.status_master','Y')
            ->where('x.kode_data_master','<>',$url_data)
            ->whereIn('x.sifat_master', ['TERBUKA','TERBATAS'])
            ->orderBy('x.kode_data_master')
            ->orderBy('x.id_master_data', 'desc')
            ->limit(4)
            ->get();
        
        $data = [
            'menu' => 'Data',
            'menu_aktif' => $menu_aktif,
            'dt' => $detail,
            'dt_terkait' => $dt_terkait,
            'dt_file' => $dt_file,
            'set' =>  DB::table('app_setting')->where('kode', 'SETT')->first(),

        ];

        return view('web.data-detail', $data);

        
    }

    public function previewCsv($kode, $id)
    {
        $detail = DB::table('t_data')
            ->where('kode_data', $kode)
            ->where('id_data', $id)
            ->first();

        if (!$detail) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        if (empty($detail->json_data)) {
            return response()->json(['error' => 'JSON belum tersedia'], 404);
        }

        // decode JSON dari DB
        $data = json_decode($detail->json_data, true);

        if (!$data) {
            return response()->json(['error' => 'Format JSON tidak valid'], 500);
        }

        return response()->json($data);
    }

    // public function filePreviewShow($sifat, $file)
    // {
    //     $disk = $sifat == 'TERBUKA' ? 'sftp_storage_terbuka' : 'sftp_storage';
    //     $folder = $sifat == 'TERBUKA' ? 'DATA_TERBUKA' : 'DATA_TERTUTUP';

    //     $filename = basename(ltrim($file, '/'));
    //     $fullPath = $folder . '/' . $filename;

    //     if (!Storage::disk($disk)->exists($fullPath)) {
    //         abort(404, 'File tidak ditemukan');
    //     }

    //     $stream = Storage::disk($disk)->readStream($fullPath);

    //     if (!$stream) {
    //         abort(500, 'Gagal baca file');
    //     }

    //     $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    //     $mimeTypes = [
    //         'pdf' => 'application/pdf',
    //         'xls' => 'application/vnd.ms-excel',
    //         'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //         'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    //         'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    //         'jpg' => 'image/jpeg',
    //         'png' => 'image/png',
    //         'mp4' => 'video/mp4',
    //         'mp3' => 'audio/mpeg',
    //         'csv' => 'text/csv',
    //     ];

    //     $mime = $mimeTypes[$ext] ?? 'application/octet-stream';
    //     // dd($stream);
    //     return response()->stream(function () use ($stream) {
    //         fpassthru($stream);
    //         fclose($stream);
    //     }, 200, [
    //         'Content-Type' => $mime,
    //         'Content-Disposition' => 'inline; filename="'.$filename.'"'
    //     ]);
    // }

    public function filePreviewShow($sifat, $file)
    {
        $disk = $sifat == 'TERBUKA'
            ? 'sftp_storage_terbuka'
            : 'sftp_storage';

        $folder = $sifat == 'TERBUKA'
            ? 'DATA_TERBUKA'
            : 'DATA_TERTUTUP';

        $filename = basename($file);

        $fullPath = $folder . '/' . $filename;

        if (!Storage::disk($disk)->exists($fullPath)) {
            abort(404);
        }
        $stream = Storage::disk($disk)->readStream($fullPath);
        $mime = Storage::disk($disk)->mimeType($fullPath);
        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }

    public function logPreview(Request $request)
    {
        DB::table('t_data_log')->insert([
            'data_kode_log' => $request->kode,
            'data_id_log'   => $request->id,
            'user_id_log'   => session('id_user'),
            'user_nama_log' => session('nama_user'),
            'ip_log'        => $request->ip(),
            'created_at'    => now(),
        ]);

        return response()->json(['status' => true]);
    }

    
    // public function dataJson($url_data)
    // {
    //     $p_kode = explode('~', $url_data);
    //     $id = Crypt::decryptString($p_kode[1]);

    //     $detail = DB::table('t_data')
    //         ->join('t_master_data','t_master_data.kode_data_master','=','t_data.kode_data')
    //         ->where('kode_data', $p_kode[0])
    //         ->where('id_data', $id)
    //         ->first();

    //     if (!$detail) {
    //         return response()->json(['error' => 'Data tidak ditemukan'], 404);
    //     }

    //     $disk = $detail->sifat_master == 'TERBUKA' ? 'sftp_storage_terbuka' : 'sftp_storage';
    //     $folder = $detail->sifat_master == 'TERBUKA' ? 'DATA_TERBUKA' : 'DATA_TERTUTUP';

    //     $filename = basename(ltrim($detail->file_data, '/'));
    //     $fullPath = $folder . '/' . $filename;
        
    //     if (!Storage::disk($disk)->exists($fullPath)) {
    //         return response()->json(['error' => 'File tidak ditemukan di storage'], 404);
    //     }

    //     $stream = Storage::disk($disk)->readStream($fullPath);

    //     if (!$stream) {
    //         return response()->json(['error' => 'Gagal membaca file'], 500);
    //     }

    //     $rows = [];

    //     while (($line = fgets($stream)) !== false) {
    //         $rows[] = str_getcsv($line, ';'); // delimiter kamu ;
    //     }

    //     fclose($stream);

    //     if (empty($rows)) {
    //         return response()->json([]);
    //     }

    //     $header = array_map('trim', array_shift($rows));

    //     $json = array_map(function ($row) use ($header) {
    //         return array_combine($header, $row);
    //     }, $rows);

    //     return response()->json($json, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // }

    public function dataJson($url_data)
    {
        $p_kode = explode('~', $url_data);
        $id = Crypt::decryptString($p_kode[1]);

        $detail = DB::table('t_data')
            ->where('kode_data', $p_kode[0])
            ->where('id_data', $id)
            ->first();

        if (!$detail) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        if (empty($detail->json_data)) {
            return response()->json(['error' => 'JSON belum tersedia'], 404);
        }

        // decode JSON dari database
        $json = json_decode($detail->json_data, true);

        if (!$json) {
            return response()->json(['error' => 'Format JSON tidak valid'], 500);
        }

        return response()->json(
            $json,
            200,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }


    
    public function permohonanAction(Request $request)
    {

        $rules = [
            'nama'       => 'required|string|max:200',
            'email'      => 'required|email|max:200',
            'identitas'  => 'required',
            'pekerjaan'  => 'required',
            'telepon'    => 'required',
            'alamat'     => 'required',
            'pengambilan_data' => 'required',
            'dokumen'    => 'required|mimes:pdf',
        ];

        if (empty($request->file_identitas_old)) {
            $rules['file_identitas'] = 'required|file|mimes:jpg,jpeg,png,pdf';
        }

        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }


        if(empty($request->tujuan)){
            return response()->json([
                'success' => false,
                'message' => 'Tujuan permintaan data harus diisi'
            ], 422);
        }

        if (empty($request->file_identitas_old) && !$request->hasFile('file_identitas')) {
            return response()->json([
                'success' => false,
                'message' => 'File identitas harus diisi'
            ], 422);
        }
        
        
        $path = $request->file_identitas_old;
        $filename = null;
        if ($request->hasFile('file_identitas')) {
            $file_post = $request->file('file_identitas');
            $scan = $this->dataService->scanAntivirus($file_post);
            if (!$scan['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $scan['message'],
                    'virus'   => $scan['virus'] ?? null,
                    'error'   => $scan['error'] ?? null,
                ], $scan['code']);
            }
        }
        if ($request->hasFile('file_identitas')) {
            $filename = time() . '_' . $request->file('file_identitas')->getClientOriginalName();
            $path = $request->file('file_identitas')->storeAs('user', $filename, 'public');
        }

        $path2 = null;
        $filename2 = null;
        if ($request->hasFile('dokumen')) {
            $file_post = $request->file('dokumen');
            $scan = $this->dataService->scanAntivirus($file_post);
            if (!$scan['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $scan['message'],
                    'virus'   => $scan['virus'] ?? null,
                    'error'   => $scan['error'] ?? null,
                ], $scan['code']);
            }
        }
        if ($request->hasFile('dokumen')) {
            $filename2 = time() . '_' . $request->file('dokumen')->getClientOriginalName();
            $path2 = $request->file('dokumen')->storeAs('dokumen', $filename2, 'public');
        }
        
        do {
            $kode = date('ymdh').str_pad(mt_rand(0, 99), 2, '0', STR_PAD_LEFT);
        } while (
            DB::table('t_permohonan')
                ->where('kode_permohonan', $kode)
                ->exists()
        );
       

        $insert = DB::table('t_permohonan')->insert([
            'kode_data_permohonan' => $request->kode,
            'data_id_permohonan' => $request->id_data,
            'kode_permohonan' => $kode,
            'user_id_permohonan' => session('id_user'),
            'nama_permohonan'      => $request->nama,
            'email_permohonan' => $request->email,
            'identitas_permohonan'      => $request->identitas,
            'file_identitas_permohonan'      => $path,
            'telepon_permohonan' => $request->telepon,
            'pekerjaan_permohonan' => $request->pekerjaan,
            'alamat_permohonan' => $request->alamat,
            'status_permohonan' => 'P',
            'tujuan_permohonan' => $request->tujuan,
            'dokumen_permohonan' => $path2,
            'pengambilan_permohonan' => $request->pengambilan_data,
            'created_at'           => now(),
        ]);

        if($insert){
            $detail = DB::table('t_data')->where('id_data', $request->id_data)->first();
            $fileName = $detail->judul_data;
            Mail::to($request->email)->queue(
                new \App\Mail\AppMail(
                    'emails.email-permohonan',
                    [
                        'nama' => $request->nama,
                        'kode' => $kode,
                        'fileName' => $fileName
                    ],
                    'Permohonan Data Satu Data Pertahanan, Kode '.$kode
                )
            );

            $this->dataService->createLogWeb($request,'permohonanAction' ,'Berhasil mengajukan permohonan');
           
            return response()->json([
                'success' => true,
                'message' => 'Permohonan anda berhasil diajukan. Silakan cek email anda untuk informasi lebih lengkap.',
                'kode' => $kode
            ]);
        }else{
            $this->dataService->createLogWeb($request,'permohonanAction' ,'Gagal mengajukan permohonan');
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan permohonan'
            ]);
        }
        
    }


    public function hubungiKamiAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama'       => 'required|string|max:200',
            'email'  => 'required|email|max:200',
            'ktp'  => 'required|max:16',
            'pesan'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        if($request->operator == 'x'){
            $val = $request->angka1 * $request->angka2;
        }else if($request->operator == '+'){
            $val = $request->angka1 + $request->angka2;
        }else{
            $val = $request->angka1 - $request->angka2;
        }

        // dd($val);

        if($val != $request->validasi){
            return response()->json([
                'success' => false,
                'message' => 'Kode validasi salah'
            ]);
        }

        $insert = DB::table('t_pengaduan')->insert([
            'user_id_pengaduan'      => session('id_user') ?? NULL,
            'nama_pengaduan' => $request->nama,
            'email_pengaduan' => $request->email,
            'nik_pengaduan'                  => $request->ktp,
            'ip_pengaduan'               => $request->ip(),
            'status_pengaduan'           => 'P',
            'capcha_pengaduan'           => $request->angka1.' '.$request->operator.' '.$request->angka2,
            'validasi_pengaduan'                  => $request->validasi,
            'created_at'           => now(),
        ]);

        if($insert){
            $this->dataService->createLogWeb($request,'hubungiKamiAction' ,'Berhasil mengirim pesan pengaduan');
            return response()->json([
                'success' => true,
                'message' => 'Pengaduan berhasil disampaikan'
            ]);
        }else{
            $this->dataService->createLogWeb($request,'hubungiKamiAction' ,'Gagal mengirim pesan pengaduan');
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan gagal disampaikan'
            ]);
        }
        
    }

    

    

    


}
