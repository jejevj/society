<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\EventAddon;
use App\Models\EventAddonRegistrasi;
use Carbon\Carbon;

class EventAddonController extends Controller
{
    // -------------------------------------------------------
    // ADMIN: List add-ons per event
    // -------------------------------------------------------
    public function index($kode_event)
    {
        $cek_permit = $this->cekPermit();
        $detail     = DB::table('t_event')->where('kode_event', $kode_event)->first();

        if (!$detail) abort(404);

        $breadcrumb = '
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
                <li class="breadcrumb-item text-muted"><a href="' . route('event') . '" class="text-muted text-hover-primary">Event</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Add-On</li>
            </ul>';

        return view('admin-panel.event.addon.main', [
            'menu'        => 'Event Add-On',
            'breadcrumb'  => $breadcrumb,
            'detail'      => $detail,
            'kode_event'  => $kode_event,
            'cek_permit'  => $cek_permit,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: DataTable source
    // -------------------------------------------------------
    public function getTableAddon(Request $request)
    {
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

    // -------------------------------------------------------
    // ADMIN: Show add form
    // -------------------------------------------------------
    public function tambah($kode_event)
    {
        $cek_permit = $this->cekPermit();
        $detail     = DB::table('t_event')->where('kode_event', $kode_event)->first();
        if (!$detail) abort(404);

        $breadcrumb = '
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
                <li class="breadcrumb-item text-muted"><a href="' . route('event') . '" class="text-muted text-hover-primary">Event</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="' . route('addonEvent', $kode_event) . '" class="text-muted text-hover-primary">Add-On</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Add</li>
            </ul>';

        return view('admin-panel.event.addon.tambah', [
            'menu'       => 'Add Add-On',
            'breadcrumb' => $breadcrumb,
            'detail'     => $detail,
            'cek_permit' => $cek_permit,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: Show edit form
    // -------------------------------------------------------
    public function edit($kode_addon)
    {
        $cek_permit = $this->cekPermit();
        $detail     = DB::table('event_addon')->where('kode_addon', $kode_addon)->first();
        if (!$detail) abort(404);

        $event = DB::table('t_event')->where('kode_event', $detail->kode_event)->first();

        $breadcrumb = '
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
                <li class="breadcrumb-item text-muted"><a href="' . route('event') . '" class="text-muted text-hover-primary">Event</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="' . route('addonEvent', $detail->kode_event) . '" class="text-muted text-hover-primary">Add-On</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>';

        return view('admin-panel.event.addon.edit', [
            'menu'       => 'Edit Add-On',
            'breadcrumb' => $breadcrumb,
            'detail'     => $detail,
            'event'      => $event,
            'cek_permit' => $cek_permit,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: Store new add-on
    // -------------------------------------------------------
    public function addAddonAction(Request $request)
    {
        $request->validate([
            'kode_event'     => 'required|string',
            'nama_addon'     => 'required|string|max:255',
            'deskripsi_addon'=> 'nullable|string',
            'harga_addon'    => 'nullable|numeric|min:0',
            'gambar_addon'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status_addon'   => 'required|in:A,N',
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

        return response()->json(['message' => 'Add-On added successfully.']);
    }

    // -------------------------------------------------------
    // ADMIN: Update add-on
    // -------------------------------------------------------
    public function updateAddonAction(Request $request)
    {
        $request->validate([
            'key'            => 'required|string',
            'nama_addon'     => 'required|string|max:255',
            'deskripsi_addon'=> 'nullable|string',
            'harga_addon'    => 'nullable|numeric|min:0',
            'gambar_addon'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status_addon'   => 'required|in:A,N',
        ]);

        $addon = DB::table('event_addon')->where('kode_addon', $request->key)->first();
        if (!$addon) return response()->json(['message' => 'Data not found.'], 404);

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

        return response()->json(['message' => 'Add-On updated successfully.']);
    }

    // -------------------------------------------------------
    // ADMIN: Delete add-on
    // -------------------------------------------------------
    public function deleteAddonAction(Request $request)
    {
        $addon = DB::table('event_addon')->where('kode_addon', $request->key)->first();
        if (!$addon) return response()->json(['message' => 'Data not found.'], 404);

        if ($addon->gambar_addon && Storage::disk('public')->exists($addon->gambar_addon)) {
            Storage::disk('public')->delete($addon->gambar_addon);
        }

        DB::table('event_addon_registrasi')->where('kode_addon', $request->key)->delete();
        DB::table('event_addon')->where('kode_addon', $request->key)->delete();

        return response()->json(['message' => 'Add-On deleted successfully.']);
    }

    // -------------------------------------------------------
    // ADMIN: List registrant add-on selections
    // -------------------------------------------------------
    public function addonRegistrasi($kode_event)
    {
        $cek_permit = $this->cekPermit();
        $detail     = DB::table('t_event')->where('kode_event', $kode_event)->first();
        if (!$detail) abort(404);

        return view('admin-panel.event.addon.registrasi', [
            'menu'       => 'Add-On Registrations',
            'detail'     => $detail,
            'kode_event' => $kode_event,
            'cek_permit' => $cek_permit,
        ]);
    }

    // -------------------------------------------------------
    // ADMIN: DataTable for add-on registrations
    // -------------------------------------------------------
    public function getTableAddonRegistrasi(Request $request)
    {
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

    // -------------------------------------------------------
    // ADMIN: Update add-on registration status
    // -------------------------------------------------------
    public function updateStatusAddonRegistrasiAction(Request $request)
    {
        $reg = DB::table('event_addon_registrasi')->where('kode_addon_reg', $request->key)->first();
        if (!$reg) return response()->json(['message' => 'Data not found.'], 404);

        DB::table('event_addon_registrasi')->where('kode_addon_reg', $request->key)->update([
            'status'     => $request->status,
            'catatan'    => $request->catatan,
            'updated_at' => Carbon::now(),
        ]);

        $label = $request->status === 'A' ? 'approved' : 'rejected';
        return response()->json(['message' => "Add-On registration $label successfully."]);
    }

    // -------------------------------------------------------
    // Helper: permission check (mirrors other controllers)
    // -------------------------------------------------------
    private function cekPermit()
    {
        return ['c' => true, 'r' => true, 'u' => true, 'd' => true];
    }
}
