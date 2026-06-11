<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class WebPaperController extends Controller
{
    private function setting(): ?object
    {
        return DB::table('app_setting')->where('kode', 'SETT')->first();
    }

    /** Halaman utama paper user — list event yang terdaftar */
    public function index(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return redirect()->route('login');
        }

        $idUser = $request->session()->get('id_user');

        // Ambil semua event yang terdaftar oleh user ini
        $events = DB::table('t_event_registrasi as r')
            ->join('t_event as e', 'e.kode_event', '=', 'r.kode_event')
            ->where('r.id_user', $idUser)
            ->where('r.payment_status', 'PAID')
            ->select(
                'e.kode_event',
                'e.judul_event',
                'e.lokasi_event',
                'e.tanggal_awal_event',
                'e.tanggal_akhir_event',
                'r.kode_registrasi',
                'r.nama_peserta',
                'r.email_peserta'
            )
            ->orderBy('e.tanggal_awal_event', 'desc')
            ->get();

        // Cek per event apakah user sudah upload paper
        foreach ($events as $ev) {
            $ev->has_paper = DB::table('t_paper')
                ->where('kode_registrasi', $ev->kode_registrasi)
                ->exists();
        }

        return view('web.home.paper', [
            'events'     => $events,
            'menu_aktif' => 'paper',
            'set'        => $this->setting(),
        ]);
    }

    /** Datatable paper per event (kode_event) — tampilkan semua paper di event tersebut */
    public function getPaperByEvent(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $kodeEvent = $request->input('kode_event');

        $query = DB::table('t_paper as p')
            ->join('t_event_registrasi as r', 'r.kode_registrasi', '=', 'p.kode_registrasi')
            ->where('p.kode_event', $kodeEvent)
            ->select(
                'p.id_paper',
                'p.kode_paper',
                'p.judul_paper',
                'p.file_paper',
                'p.tipe_file_paper',
                'p.status_paper',
                'p.catatan_paper',
                'p.created_at',
                'r.nama_peserta',
                'r.email_peserta',
                'r.instansi_peserta'
            )
            ->orderBy('p.id_paper', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('peserta_info', function ($row) {
                return '<div class="fw-bold">' . e($row->nama_peserta) . '</div>'
                     . '<small class="text-muted">' . e($row->email_peserta) . '</small>'
                     . ($row->instansi_peserta ? '<br><small class="text-muted">' . e($row->instansi_peserta) . '</small>' : '');
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status_paper === 'A') {
                    return '<span class="badge badge-light-success">Approved</span>';
                } elseif ($row->status_paper === 'R') {
                    return '<span class="badge badge-light-danger">Rejected</span>';
                }
                return '<span class="badge badge-light-warning">Pending</span>';
            })
            ->addColumn('file_col', function ($row) {
                if ($row->file_paper) {
                    $url  = asset('storage/' . $row->file_paper);
                    $ext  = strtoupper($row->tipe_file_paper);
                    $icon = $row->tipe_file_paper === 'pdf' ? 'fa-file-pdf-o text-danger' : 'fa-file-powerpoint-o text-warning';
                    return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-light-primary py-1 px-3">'
                         . '<i class="fa ' . $icon . ' me-1"></i>' . $ext . '</a>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->rawColumns(['peserta_info', 'status_badge', 'file_col'])
            ->make(true);
    }

    /** Upload paper baru ke event tertentu */
    public function uploadPaper(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $idUser = $request->session()->get('id_user');

        $validator = Validator::make($request->all(), [
            'kode_registrasi' => 'required|string',
            'judul_paper'     => 'required|string|max:255',
            'file_paper'      => 'required|file|mimes:pdf,ppt,pptx|max:20480',
        ], [
            'file_paper.mimes' => 'File harus berformat PDF, PPT, atau PPTX.',
            'file_paper.max'   => 'Ukuran file maksimal 20MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Pastikan registrasi ini milik user
        $reg = DB::table('t_event_registrasi')
            ->where('kode_registrasi', $request->kode_registrasi)
            ->where('id_user', $idUser)
            ->first();

        if (!$reg) {
            return response()->json(['success' => false, 'message' => 'Registrasi tidak ditemukan.'], 403);
        }

        // Upload file
        $file     = $request->file('file_paper');
        $ext      = strtolower($file->getClientOriginalExtension());
        $fileName = 'paper_' . time() . '_' . Str::random(8) . '.' . $ext;
        $filePath = $file->storeAs('paper', $fileName, 'public');

        // Simpan ke t_paper
        DB::table('t_paper')->insert([
            'kode_paper'       => 'PPR' . date('ymdHis') . strtoupper(Str::random(4)),
            'kode_registrasi'  => $request->kode_registrasi,
            'kode_event'       => $reg->kode_event,
            'judul_paper'      => $request->judul_paper,
            'file_paper'       => $filePath,
            'tipe_file_paper'  => $ext,
            'status_paper'     => 'P',  // Pending — menunggu review admin
            'created_by_paper' => $idUser,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Paper berhasil diupload dan menunggu review admin.']);
    }
}
