<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class EventRegistrasiController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'event-registrasi||event-menu';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());

        $events = DB::table('t_event')->orderBy('id_event', 'desc')->get();

        $data = [
            'menu'         => 'Registrasi Peserta',
            'menu_aktif'   => $menu_aktif,
            'navbar'       => $navbar,
            'cek_permit'   => $cek,
            'events'       => $events,
            'breadcrumb'   => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Event</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Registrasi Peserta</li>
									</ul>',
        ];
        if (!$cek['r']) {
            return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.event.registrasi.main', $data);
    }

    public function getTableRegistrasi(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event-registrasi||event-menu';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());

            $query = DB::table('t_event_registrasi as a')
                ->select(
                    'a.id_registrasi',
                    'a.kode_registrasi',
                    'a.kode_event',
                    'a.nama_peserta',
                    'a.email_peserta',
                    'a.instansi_peserta',
                    'a.no_hp_peserta',
                    'a.status_registrasi',
                    'a.catatan_registrasi',
                    'a.created_at',
                    'b.judul_event',
                    DB::raw('(SELECT COUNT(*) FROM t_paper p WHERE p.kode_registrasi = a.kode_registrasi) as total_paper')
                )
                ->leftJoin('t_event as b', 'b.kode_event', '=', 'a.kode_event');

            if ($request->filled('kode_event')) {
                $query->where('a.kode_event', $request->input('kode_event'));
            }
            if ($request->filled('nama')) {
                $query->where('a.nama_peserta', 'like', '%' . $request->input('nama') . '%');
            }
            if ($request->filled('status')) {
                $query->where('a.status_registrasi', $request->input('status'));
            }

            $query->orderBy('a.id_registrasi', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($row) {
                    if ($row->status_registrasi === 'A') {
                        return '<span class="badge bg-success">Approved</span>';
                    } elseif ($row->status_registrasi === 'R') {
                        return '<span class="badge bg-danger">Rejected</span>';
                    }
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                })
                ->addColumn('paper_info', function ($row) {
                    $color = $row->total_paper > 0 ? 'success' : 'secondary';
                    return '<span class="badge bg-' . $color . '">' . $row->total_paper . ' Paper</span>';
                })
                ->addColumn('action', function ($row) use ($cek) {
                    $id_hash = Crypt::encrypt($row->id_registrasi);
                    $paperUrl = route('detailPaperRegistrasi', $id_hash);
                    $btn = '';
                    if ($cek['u']) {
                        $btn .= '<button class="btn btn-light-success btn-sm btn-approve-registrasi" data-id="' . $id_hash . '" title="Approve"><span class="fa fa-check"></span></button> ';
                        $btn .= '<button class="btn btn-light-danger btn-sm btn-reject-registrasi" data-id="' . $id_hash . '" title="Reject"><span class="fa fa-times"></span></button> ';
                        $btn .= '<a href="' . $paperUrl . '" class="btn btn-light-info btn-sm" title="Lihat Paper"><span class="fa fa-file"></span></a> ';
                    }
                    if ($cek['d']) {
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete-registrasi" data-id="' . $id_hash . '" title="Hapus"><span class="fa fa-trash"></span></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['status_badge', 'paper_info', 'action'])
                ->make(true);
        }
    }

    public function updateStatusRegistrasiAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event-registrasi||event-menu';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if (!$cek['u']) {
                return response()->json(['success' => false, 'message' => 'Tidak memiliki akses'], 422);
            }

            $validator = Validator::make($request->all(), [
                'key'    => 'required',
                'status' => 'required|in:A,R,P',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $id = Crypt::decrypt($request->key);
            $updateData = [
                'status_registrasi' => $request->status,
                'catatan_registrasi' => $request->catatan ?? null,
                'updated_at' => now(),
            ];
            $dt_exist = DB::table('t_event_registrasi')->where('id_registrasi', $id)->first();
            $update = DB::table('t_event_registrasi')->where('id_registrasi', $id)->update($updateData);

            if ($update) {
                $this->dataService->createLog($request, 'updateStatusRegistrasiAction', 'Berhasil ubah status registrasi', json_encode($updateData), json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Status registrasi berhasil diperbarui']);
            }
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status registrasi']);
        }
    }

    public function deleteRegistrasiAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'event-registrasi||event-menu';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if (!$cek['d']) {
                return response()->json(['success' => false, 'message' => 'Tidak memiliki akses'], 422);
            }

            $validator = Validator::make($request->all(), ['key' => 'required']);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $id = Crypt::decrypt($request->key);
            $dt_exist = DB::table('t_event_registrasi')->where('id_registrasi', $id)->first();
            $deleted = DB::table('t_event_registrasi')->where('id_registrasi', $id)->delete();

            if ($deleted) {
                DB::table('t_paper')->where('kode_registrasi', $dt_exist->kode_registrasi)->delete();
                $this->dataService->createLog($request, 'deleteRegistrasiAction', 'Berhasil hapus registrasi', '', json_encode($dt_exist));
                return response()->json(['success' => true, 'message' => 'Registrasi berhasil dihapus']);
            }
            return response()->json(['success' => false, 'message' => 'Gagal menghapus registrasi']);
        }
    }

    public function detailPaperRegistrasi($id_registrasi, Request $request)
    {
        $menu_aktif = 'event-registrasi||event-menu';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());

        $id = Crypt::decrypt($id_registrasi);
        $registrasi = DB::table('t_event_registrasi as a')
            ->select('a.*', 'b.judul_event')
            ->leftJoin('t_event as b', 'b.kode_event', '=', 'a.kode_event')
            ->where('a.id_registrasi', $id)
            ->first();

        $data = [
            'menu'          => 'Detail Paper Peserta',
            'menu_aktif'    => $menu_aktif,
            'navbar'        => $navbar,
            'cek_permit'    => $cek,
            'registrasi'    => $registrasi,
            'id_registrasi' => $id_registrasi,
            'breadcrumb'    => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item fw-bold lh-1"><span class=" text-hover-primary"><i class="ki-outline ki-home fs-3"></i></span></li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Event</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Registrasi Peserta</li>
										<li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
										<li class="breadcrumb-item fw-bold lh-1">Detail Paper</li>
									</ul>',
        ];
        if (!$cek['r']) {
            return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.event.registrasi.detail-paper', $data);
    }
}
