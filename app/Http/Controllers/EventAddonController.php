<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\EventAddon;
use App\Models\EventAddonRegistrasi;
use App\Services\DataService;
use Carbon\Carbon;

class EventAddonController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    // -------------------------------------------------------
    // ADMIN: List add-ons per event
    // -------------------------------------------------------
    public function index($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek    = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();

        if (!$detail) abort(404);

        $breadcrumb = '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
            <li class="breadcrumb-item fw-bold lh-1"><span class="text-hover-primary"><i class="ki-outline ki-home fs-3"></i></span></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1"><a href="' . route('event') . '" class="text-hover-primary">Events</a></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1">Add-On</li>
        </ul>';

        return view('admin-panel.event.addon.main', [
            'menu'        => 'Event Add-On',
            'menu_aktif'  => $menu_aktif,
            'navbar'      => $navbar,
            'breadcrumb'  => $breadcrumb,
            'detail'      => $detail,
            'kode_event'  => $kode_event,
            'cek_permit'  => $cek,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: DataTable source
    // -------------------------------------------------------
    public function getTableAddon(Request $request)
    {
        if ($request->session()->has('id')) {
            $kode_event = $request->key;
            $query = DB::table('event_addon')
                ->where('kode_event', $kode_event)
                ->select('*');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('gambar', function ($row) {
                    if ($row->gambar_addon) {
                        return '<img src="' . asset('storage/' . $row->gambar_addon) . '" style="height:60px;width:90px;object-fit:cover;border-radius:6px;">';
                    }
                    return '<span class="text-muted">No image</span>';
                })
                ->addColumn('harga', function ($row) {
                    return 'Rp ' . number_format($row->harga_addon, 0, ',', '.');
                })
                ->addColumn('status_badge', function ($row) {
                    return $row->status_addon === 'A'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $edit   = route('editAddonEvent', $row->kode_addon);
                    $btn  = '<a href="' . $edit . '" class="btn btn-sm btn-warning me-1"><i class="fa fa-edit"></i></a>';
                    $btn .= '<button class="btn btn-sm btn-danger btn-delete-addon" data-id="' . $row->kode_addon . '"><i class="fa fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['gambar', 'status_badge', 'action'])
                ->make(true);
        }
    }

    // -------------------------------------------------------
    // ADMIN: Show add form
    // -------------------------------------------------------
    public function tambah($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek    = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();
        if (!$detail) abort(404);

        $breadcrumb = '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
            <li class="breadcrumb-item fw-bold lh-1"><span class="text-hover-primary"><i class="ki-outline ki-home fs-3"></i></span></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1"><a href="' . route('event') . '" class="text-hover-primary">Events</a></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1"><a href="' . route('addonEvent', $kode_event) . '" class="text-hover-primary">Add-On</a></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1">Add</li>
        </ul>';

        return view('admin-panel.event.addon.tambah', [
            'menu'       => 'Add Add-On',
            'menu_aktif' => $menu_aktif,
            'navbar'     => $navbar,
            'breadcrumb' => $breadcrumb,
            'detail'     => $detail,
            'kode_event' => $kode_event,
            'cek_permit' => $cek,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: Show edit form
    // -------------------------------------------------------
    public function edit($kode_addon, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek    = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $detail = DB::table('event_addon')->where('kode_addon', $kode_addon)->first();
        if (!$detail) abort(404);

        $event = DB::table('t_event')->where('kode_event', $detail->kode_event)->first();

        $breadcrumb = '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
            <li class="breadcrumb-item fw-bold lh-1"><span class="text-hover-primary"><i class="ki-outline ki-home fs-3"></i></span></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1"><a href="' . route('event') . '" class="text-hover-primary">Events</a></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1"><a href="' . route('addonEvent', $detail->kode_event) . '" class="text-hover-primary">Add-On</a></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1">Edit</li>
        </ul>';

        return view('admin-panel.event.addon.edit', [
            'menu'       => 'Edit Add-On',
            'menu_aktif' => $menu_aktif,
            'navbar'     => $navbar,
            'breadcrumb' => $breadcrumb,
            'detail'     => $detail,
            'event'      => $event,
            'cek_permit' => $cek,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: Store new add-on
    // -------------------------------------------------------
    public function addAddonAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $request->validate([
                'kode_event'      => 'required|string',
                'nama_addon'      => 'required|string|max:255',
                'deskripsi_addon' => 'nullable|string',
                'harga_addon'     => 'nullable|numeric|min:0',
                'gambar_addon'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'status_addon'    => 'required|in:A,N',
            ]);

            $kode_addon = 'ADDON-' . strtoupper(uniqid());
            $gambarPath = null;

            if ($request->hasFile('gambar_addon')) {
                $gambarPath = $request->file('gambar_addon')->store('event/addon', 'public');
            }

            DB::table('event_addon')->insert([
                'kode_addon'      => $kode_addon,
                'kode_event'      => $request->kode_event,
                'nama_addon'      => $request->nama_addon,
                'deskripsi_addon' => $request->deskripsi_addon,
                'gambar_addon'    => $gambarPath,
                'harga_addon'     => $request->harga_addon ?? 0,
                'status_addon'    => $request->status_addon,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Add-On added successfully.']);
        }
    }

    // -------------------------------------------------------
    // ADMIN: Update add-on
    // -------------------------------------------------------
    public function updateAddonAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $request->validate([
                'key'             => 'required|string',
                'nama_addon'      => 'required|string|max:255',
                'deskripsi_addon' => 'nullable|string',
                'harga_addon'     => 'nullable|numeric|min:0',
                'gambar_addon'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'status_addon'    => 'required|in:A,N',
            ]);

            $addon = DB::table('event_addon')->where('kode_addon', $request->key)->first();
            if (!$addon) return response()->json(['success' => false, 'message' => 'Data not found.'], 404);

            $gambarPath = $addon->gambar_addon;

            if ($request->hasFile('gambar_addon')) {
                if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
                    Storage::disk('public')->delete($gambarPath);
                }
                $gambarPath = $request->file('gambar_addon')->store('event/addon', 'public');
            }

            DB::table('event_addon')->where('kode_addon', $request->key)->update([
                'nama_addon'      => $request->nama_addon,
                'deskripsi_addon' => $request->deskripsi_addon,
                'gambar_addon'    => $gambarPath,
                'harga_addon'     => $request->harga_addon ?? 0,
                'status_addon'    => $request->status_addon,
                'updated_at'      => Carbon::now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Add-On updated successfully.']);
        }
    }

    // -------------------------------------------------------
    // ADMIN: Delete add-on
    // -------------------------------------------------------
    public function deleteAddonAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $addon = DB::table('event_addon')->where('kode_addon', $request->key)->first();
            if (!$addon) return response()->json(['success' => false, 'message' => 'Data not found.'], 404);

            if ($addon->gambar_addon && Storage::disk('public')->exists($addon->gambar_addon)) {
                Storage::disk('public')->delete($addon->gambar_addon);
            }

            DB::table('event_addon_registrasi')->where('kode_addon', $request->key)->delete();
            DB::table('event_addon')->where('kode_addon', $request->key)->delete();

            return response()->json(['success' => true, 'message' => 'Add-On deleted successfully.']);
        }
    }

    // -------------------------------------------------------
    // ADMIN: List registrant add-on selections
    // -------------------------------------------------------
    public function addonRegistrasi($kode_event, Request $request)
    {
        $menu_aktif = 'event||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek    = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        $detail = DB::table('t_event')->where('kode_event', $kode_event)->first();
        if (!$detail) abort(404);

        $breadcrumb = '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
            <li class="breadcrumb-item fw-bold lh-1"><span class="text-hover-primary"><i class="ki-outline ki-home fs-3"></i></span></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1"><a href="' . route('event') . '" class="text-hover-primary">Events</a></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1"><a href="' . route('addonEvent', $kode_event) . '" class="text-hover-primary">Add-On</a></li>
            <li class="breadcrumb-item"><i class="ki-outline ki-right fs-4 mx-n1"></i></li>
            <li class="breadcrumb-item fw-bold lh-1">Registrations</li>
        </ul>';

        return view('admin-panel.event.addon.registrasi', [
            'menu'       => 'Add-On Registrations',
            'menu_aktif' => $menu_aktif,
            'navbar'     => $navbar,
            'breadcrumb' => $breadcrumb,
            'detail'     => $detail,
            'kode_event' => $kode_event,
            'cek_permit' => $cek,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: DataTable for add-on registrations
    // -------------------------------------------------------
    public function getTableAddonRegistrasi(Request $request)
    {
        if ($request->session()->has('id')) {
            $kode_event = $request->kode_event;
            $nama       = $request->nama;
            $status     = $request->status;

            $query = DB::table('event_addon_registrasi as ear')
                ->join('event_addon as ea', 'ea.kode_addon', '=', 'ear.kode_addon')
                ->join('event_registrasi as er', 'er.kode_registrasi', '=', 'ear.kode_registrasi')
                ->where('ea.kode_event', $kode_event)
                ->when($nama, fn($q) => $q->where('er.nama_peserta', 'like', "%$nama%"))
                ->when($status, fn($q) => $q->where('ear.status', $status))
                ->select(
                    'ear.*',
                    'ea.nama_addon',
                    'ea.harga_addon',
                    'er.nama_peserta',
                    'er.email_peserta',
                    'er.instansi_peserta'
                );

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($row) {
                    return match($row->status) {
                        'A' => '<span class="badge bg-success">Approved</span>',
                        'R' => '<span class="badge bg-danger">Rejected</span>',
                        default => '<span class="badge bg-warning text-dark">Pending</span>',
                    };
                })
                ->addColumn('harga', function ($row) {
                    return 'Rp ' . number_format($row->harga_addon, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->status === 'P') {
                        $btn .= '<button class="btn btn-sm btn-success btn-approve-addon me-1" data-id="' . $row->kode_addon_reg . '"><i class="fa fa-check"></i> Approve</button>';
                        $btn .= '<button class="btn btn-sm btn-danger btn-reject-addon" data-id="' . $row->kode_addon_reg . '"><i class="fa fa-times"></i> Reject</button>';
                    }
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }
    }

    // -------------------------------------------------------
    // ADMIN: Update add-on registration status
    // -------------------------------------------------------
    public function updateStatusAddonRegistrasiAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $reg = DB::table('event_addon_registrasi')->where('kode_addon_reg', $request->key)->first();
            if (!$reg) return response()->json(['success' => false, 'message' => 'Data not found.'], 404);

            DB::table('event_addon_registrasi')->where('kode_addon_reg', $request->key)->update([
                'status'     => $request->status,
                'catatan'    => $request->catatan,
                'updated_at' => Carbon::now(),
            ]);

            $label = $request->status === 'A' ? 'approved' : 'rejected';
            return response()->json(['success' => true, 'message' => "Add-On registration $label successfully."]);
        }
    }
}
