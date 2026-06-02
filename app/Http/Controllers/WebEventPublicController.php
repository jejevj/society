<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebEventPublicController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $query = DB::table('t_event as e')
            ->where('e.status_event', 'Y');

        // Filter: search keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('e.judul_event', 'LIKE', "%{$q}%")
                    ->orWhere('e.sub_judul_event', 'LIKE', "%{$q}%")
                    ->orWhere('e.lokasi_event', 'LIKE', "%{$q}%");
            });
        }

        // Filter: status pendaftaran
        if ($request->filled('status')) {
            if ($request->status === 'open') {
                $query->where('e.tanggal_akhir_event', '>=', now()->toDateString());
            } elseif ($request->status === 'closed') {
                $query->where('e.tanggal_akhir_event', '<', now()->toDateString());
            }
        }

        // Filter: rentang harga
        if ($request->filled('harga_min')) {
            $query->where('e.harga_event', '>=', (float) $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('e.harga_event', '<=', (float) $request->harga_max);
        }

        // Urutan
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'termurah'  => $query->orderBy('e.harga_event', 'asc'),
            'termahal'  => $query->orderBy('e.harga_event', 'desc'),
            'terdekat'  => $query->orderBy('e.tanggal_awal_event', 'asc'),
            default     => $query->orderByDesc('e.created_at_event'),
        };

        $events = $query->select(
            'e.kode_event',
            'e.judul_event',
            'e.sub_judul_event',
            'e.lokasi_event',
            'e.tanggal_awal_event',
            'e.tanggal_akhir_event',
            'e.harga_event',
            'e.background_event',
            'e.keterangan_event'
        )->paginate(9)->appends($request->all());

        // Attach paket & kolaborasi per event
        foreach ($events as $e) {
            $e->paket = DB::table('t_event_paket')
                ->where('event_kode_paket', $e->kode_event)
                ->orderBy('id_event_paket', 'asc')
                ->get();

            $e->kolaborasi = DB::table('t_event_kolaborasi')
                ->where('event_kode_kolaborasi', $e->kode_event)
                ->orderBy('id_event_kolaborasi', 'asc')
                ->get();

            $e->is_open = $e->tanggal_akhir_event >= now()->toDateString();
            $e->jumlah_peserta = DB::table('t_event_registrasi')
                ->where('kode_event', $e->kode_event)
                ->where('status_registrasi', 'CONFIRMED')
                ->count();
        }

        // Harga min/max untuk range slider
        $hargaRange = DB::table('t_event')
            ->where('status_event', 'Y')
            ->selectRaw('MIN(harga_event) as min_harga, MAX(harga_event) as max_harga')
            ->first();

        $data = [
            'menu'       => 'Event',
            'menu_aktif' => 'event',
            'events'     => $events,
            'hargaRange' => $hargaRange,
            'set'        => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.event.index', $data);
    }
}
