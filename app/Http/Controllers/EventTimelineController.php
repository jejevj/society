<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventTimelineController extends Controller
{
    public function index()
    {
        $events = DB::table('t_event')->where('status_event', 'Y')->orderBy('tanggal_awal_event')->get();
        return view('admin-panel.event.timeline.main', compact('events'));
    }

    public function getTableTimeline(Request $request)
    {
        $kode_event    = $request->kode_event ?? '';
        $judul_sesi    = $request->judul_sesi ?? '';
        $hari_ke       = $request->hari_ke ?? '';
        $status        = $request->status ?? '';

        $query = DB::table('t_event_timeline as tl')
            ->leftJoin('t_event as ev', 'tl.kode_event', '=', 'ev.kode_event')
            ->select(
                'tl.*',
                'ev.judul_event'
            );

        if ($kode_event)  $query->where('tl.kode_event', $kode_event);
        if ($judul_sesi)  $query->where('tl.judul_sesi', 'like', "%$judul_sesi%");
        if ($hari_ke !== '') $query->where('tl.hari_ke', $hari_ke);
        if ($status !== '') $query->where('tl.status_timeline', $status);

        $query->orderBy('tl.tanggal_timeline')->orderBy('tl.jam_mulai');

        $totalData    = $query->count();
        $totalFiltered = $totalData;

        $start  = $request->start  ?? 0;
        $length = $request->length ?? 10;
        $data   = $query->skip($start)->take($length)->get();

        $rows = [];
        foreach ($data as $i => $row) {
            $statusBadge = $row->status_timeline === 'Y'
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Nonaktif</span>';

            $rows[] = [
                'no'              => $start + $i + 1,
                'kode_timeline'   => $row->kode_timeline,
                'judul_event'     => $row->judul_event ?? '-',
                'hari_ke'         => 'Hari ke-' . $row->hari_ke,
                'tanggal'         => $row->tanggal_timeline ? date('d M Y', strtotime($row->tanggal_timeline)) : '-',
                'waktu'           => substr($row->jam_mulai, 0, 5) . ' - ' . substr($row->jam_selesai, 0, 5),
                'judul_sesi'      => $row->judul_sesi,
                'deskripsi_sesi'  => $row->deskripsi_sesi,
                'status'          => $statusBadge,
                'aksi'            => '
                    <button class="btn btn-sm btn-warning btn-edit-timeline"
                        data-id="'      . $row->id_timeline      . '"
                        data-kode="'    . $row->kode_timeline     . '"
                        data-event="'   . $row->kode_event        . '"
                        data-hari="'    . $row->hari_ke           . '"
                        data-tanggal="' . $row->tanggal_timeline  . '"
                        data-mulai="'   . substr($row->jam_mulai, 0, 5)   . '"
                        data-selesai="' . substr($row->jam_selesai, 0, 5) . '"
                        data-judul="'   . htmlspecialchars($row->judul_sesi, ENT_QUOTES) . '"
                        data-deskripsi="' . htmlspecialchars($row->deskripsi_sesi ?? '', ENT_QUOTES) . '"
                        data-status="'  . $row->status_timeline   . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete-timeline"
                        data-kode="' . $row->kode_timeline . '">
                        <i class="fas fa-trash"></i>
                    </button>',
            ];
        }

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $rows,
        ]);
    }

    public function addTimelineAction(Request $request)
    {
        $request->validate([
            'kode_event'      => 'required',
            'hari_ke'         => 'required|integer|min:1',
            'tanggal_timeline'=> 'required|date',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required',
            'judul_sesi'      => 'required|string|max:255',
        ]);

        $kode = 'TL-' . strtoupper(substr($request->kode_event, 0, 8)) . '-' . date('YmdHis');

        DB::table('t_event_timeline')->insert([
            'kode_timeline'       => $kode,
            'kode_event'          => $request->kode_event,
            'hari_ke'             => $request->hari_ke,
            'tanggal_timeline'    => $request->tanggal_timeline,
            'jam_mulai'           => $request->jam_mulai,
            'jam_selesai'         => $request->jam_selesai,
            'judul_sesi'          => $request->judul_sesi,
            'deskripsi_sesi'      => $request->deskripsi_sesi,
            'status_timeline'     => $request->status_timeline ?? 'Y',
            'created_by_timeline' => Auth::user()->username_user ?? 'admin',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Sesi timeline berhasil ditambahkan.']);
    }

    public function updateTimelineAction(Request $request)
    {
        $request->validate([
            'kode_timeline'   => 'required',
            'kode_event'      => 'required',
            'hari_ke'         => 'required|integer|min:1',
            'tanggal_timeline'=> 'required|date',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required',
            'judul_sesi'      => 'required|string|max:255',
        ]);

        DB::table('t_event_timeline')
            ->where('kode_timeline', $request->kode_timeline)
            ->update([
                'kode_event'       => $request->kode_event,
                'hari_ke'          => $request->hari_ke,
                'tanggal_timeline' => $request->tanggal_timeline,
                'jam_mulai'        => $request->jam_mulai,
                'jam_selesai'      => $request->jam_selesai,
                'judul_sesi'       => $request->judul_sesi,
                'deskripsi_sesi'   => $request->deskripsi_sesi,
                'status_timeline'  => $request->status_timeline ?? 'Y',
                'updated_at'       => now(),
            ]);

        return response()->json(['status' => 'success', 'message' => 'Sesi timeline berhasil diperbarui.']);
    }

    public function deleteTimelineAction(Request $request)
    {
        $request->validate(['kode_timeline' => 'required']);

        DB::table('t_event_timeline')
            ->where('kode_timeline', $request->kode_timeline)
            ->delete();

        return response()->json(['status' => 'success', 'message' => 'Sesi timeline berhasil dihapus.']);
    }
}
