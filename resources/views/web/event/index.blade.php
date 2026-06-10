@include('layouts.header-v2')

<style>
/* ─── PAGE ─── */
.ev-page { padding-top: 30px; padding-bottom: 70px; min-height: 100vh; background: #f4f6f9; }
.ev-page-title { font-size: 1.7rem; font-weight: 900; color: #1a1a1a; }
.ev-page-sub   { font-size: 0.92rem; color: #777; }

/* ─── FILTER SIDEBAR ─── */
.ev-filter {
    background: #fff;
    border-radius: 16px;
    padding: 22px 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    position: sticky;
    top: 90px;
}
.ev-filter-title { font-size: 0.78rem; font-weight: 800; color: #999; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 14px; }
.ev-filter label  { font-size: 0.83rem; font-weight: 600; color: #333; margin-bottom: 4px; display: block; }
.ev-filter .form-control,
.ev-filter .form-select { font-size: 0.83rem; border-radius: 8px; border: 1.5px solid #e5e7eb; }
.ev-filter .form-control:focus,
.ev-filter .form-select:focus { border-color: #E62020; box-shadow: 0 0 0 3px rgba(230,32,32,0.1); }
.btn-filter-apply {
    width: 100%;
    background: #E62020;
    color: #fff;
    border: none;
    border-radius: 9px;
    padding: 9px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 4px;
}
.btn-filter-apply:hover { background: #c41a1a; }
.btn-filter-reset {
    width: 100%;
    background: #f3f4f6;
    color: #374151;
    border: none;
    border-radius: 9px;
    padding: 8px;
    font-size: 0.83rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 6px;
    transition: background 0.2s;
}
.btn-filter-reset:hover { background: #e5e7eb; }

/* ─── SEARCH BAR ─── */
.ev-search-wrap { position: relative; }
.ev-search-wrap input {
    border-radius: 30px;
    padding-left: 42px;
    border: 1.5px solid #e5e7eb;
    font-size: 0.88rem;
    height: 42px;
}
.ev-search-wrap input:focus { border-color: #E62020; box-shadow: 0 0 0 3px rgba(230,32,32,0.08); }
.ev-search-wrap .search-ico {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 0.85rem;
    pointer-events: none;
}

/* ─── EVENT CARD ─── */
.ev-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(0,0,0,0.07);
    border: 1.5px solid #f0f0f0;
    transition: transform 0.22s, box-shadow 0.22s;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.ev-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.12);
}
.ev-card-thumb {
    position: relative;
    height: 180px;
    overflow: hidden;
    background: #e9ecef;
    flex-shrink: 0;
}
.ev-card-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.ev-card:hover .ev-card-thumb img { transform: scale(1.05); }
.ev-card-thumb .ev-status {
    position: absolute;
    top: 12px;
    left: 12px;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.8px;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
}
.ev-status.open       { background: #d1fae5; color: #065f46; }
.ev-status.closed     { background: #fee2e2; color: #991b1b; }
.ev-status.registered { background: #dbeafe; color: #1d4ed8; }
.ev-card-thumb .ev-peserta {
    position: absolute;
    bottom: 10px;
    right: 12px;
    font-size: 0.68rem;
    font-weight: 700;
    color: #fff;
    background: rgba(0,0,0,0.55);
    padding: 3px 9px;
    border-radius: 20px;
    backdrop-filter: blur(3px);
}

.ev-card-body { padding: 16px 18px; flex: 1; display: flex; flex-direction: column; }
.ev-card-kol { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px; }
.ev-kol-badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 8px;
    background: #f0f4ff;
    color: #3730a3;
    border-radius: 20px;
    white-space: nowrap;
}
.ev-card-title {
    font-size: 0.98rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 3px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ev-card-sub {
    font-size: 0.78rem;
    color: #888;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ev-card-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.78rem;
    color: #555;
    margin-bottom: 12px;
}
.ev-card-meta span i { width: 14px; color: #E62020; margin-right: 4px; }

.ev-price-wrap { margin-bottom: 10px; }
.ev-price-label { font-size: 0.7rem; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px; }
.ev-price-val { font-size: 1.1rem; font-weight: 900; color: #E62020; }
.ev-price-val.free { color: #059669; }

.ev-paket-wrap { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 14px; }
.ev-paket-pill {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 0.72rem;
    color: #374151;
    font-weight: 600;
    white-space: nowrap;
}
.ev-paket-pill img { width: 18px; height: 18px; object-fit: contain; }
.ev-paket-pill .pill-price { font-weight: 800; color: #E62020; font-size: 0.68rem; }
.ev-paket-pill .pill-price.free { color: #059669; }

.ev-btn-register {
    display: block;
    text-align: center;
    background: #E62020;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 9px;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s;
    margin-top: auto;
}
.ev-btn-register:hover { background: #c41a1a; color: #fff; }
.ev-btn-register.disabled {
    background: #9ca3af;
    cursor: not-allowed;
    pointer-events: none;
}
.ev-btn-register.registered {
    background: #1d4ed8;
    cursor: not-allowed;
    pointer-events: none;
    opacity: 0.85;
}
.ev-btn-detail {
    display: block;
    text-align: center;
    background: #fff;
    color: #E62020;
    border: 2px solid #E62020;
    border-radius: 10px;
    padding: 8px;
    font-size: 0.83rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
    margin-top: 6px;
}
.ev-btn-detail:hover { background: #fff0f0; color: #c41a1a; border-color: #c41a1a; }

.ev-empty { text-align: center; padding: 60px 20px; background: #fff; border-radius: 18px; border: 1.5px solid #f0f0f0; }

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #fff0f0;
    color: #E62020;
    border: 1px solid #fca5a5;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 0.75rem;
    font-weight: 700;
    margin: 0 3px 4px 0;
}
</style>

<div class="ev-page">
<div class="container-fluid px-5">

    <div class="row align-items-center mb-4">
        <div class="col">
            <div class="ev-page-title">Daftar Event</div>
            <div class="ev-page-sub">Temukan event yang sesuai dan daftarkan dirimu sekarang</div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ─── SIDEBAR FILTER ─── --}}
        <div class="col-lg-3 col-xl-2">
            <form method="GET" action="{{ route('list-event') }}" id="filterForm">
                <div class="ev-filter">
                    <div class="ev-filter-title">Filter</div>

                    <div class="mb-3">
                        <label>Kata Kunci</label>
                        <div class="ev-search-wrap">
                            <i class="fa-solid fa-magnifying-glass search-ico"></i>
                            <input type="text" name="q" class="form-control"
                                placeholder="Cari event..."
                                value="{{ request('q') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Status Pendaftaran</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="open"   {{ request('status') === 'open'   ? 'selected' : '' }}>Masih Buka</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Sudah Tutup</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Harga Minimum (Rp)</label>
                        <input type="number" name="harga_min" class="form-control"
                            placeholder="0"
                            value="{{ request('harga_min') }}"
                            min="0"
                            max="{{ $hargaRange->max_harga ?? 99999999 }}">
                    </div>
                    <div class="mb-3">
                        <label>Harga Maksimum (Rp)</label>
                        <input type="number" name="harga_max" class="form-control"
                            placeholder="{{ number_format($hargaRange->max_harga ?? 0, 0, ',', '.') }}"
                            value="{{ request('harga_max') }}"
                            min="0"
                            max="{{ $hargaRange->max_harga ?? 99999999 }}">
                    </div>

                    <div class="mb-4">
                        <label>Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="terbaru"  {{ request('sort','terbaru') === 'terbaru'  ? 'selected' : '' }}>Terbaru</option>
                            <option value="terdekat" {{ request('sort') === 'terdekat' ? 'selected' : '' }}>Paling Dekat</option>
                            <option value="termurah" {{ request('sort') === 'termurah' ? 'selected' : '' }}>Termurah</option>
                            <option value="termahal" {{ request('sort') === 'termahal' ? 'selected' : '' }}>Termahal</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter-apply">
                        <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('list-event') }}" class="btn-filter-reset d-block text-center text-decoration-none">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ─── MAIN CONTENT ─── --}}
        <div class="col-lg-9 col-xl-10">

            <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                <span style="font-size:0.82rem;color:#666;font-weight:600;">
                    {{ $events->total() }} event ditemukan
                </span>
                @if(request('q'))
                    <span class="filter-chip">"{{ request('q') }}" <a href="{{ route('list-event', array_filter(request()->except(['q']), fn($v) => $v !== '')) }}"><i class="fa-solid fa-xmark"></i></a></span>
                @endif
                @if(request('status'))
                    <span class="filter-chip">{{ request('status') === 'open' ? 'Masih Buka' : 'Sudah Tutup' }} <a href="{{ route('list-event', array_filter(request()->except(['status']), fn($v) => $v !== '')) }}"><i class="fa-solid fa-xmark"></i></a></span>
                @endif
                @if(request('harga_min') || request('harga_max'))
                    <span class="filter-chip">Rp {{ number_format(request('harga_min',0),0,',','.') }} &ndash; Rp {{ number_format(request('harga_max', $hargaRange->max_harga ?? 0),0,',','.') }} <a href="{{ route('list-event', array_filter(request()->except(['harga_min','harga_max']), fn($v) => $v !== '')) }}"><i class="fa-solid fa-xmark"></i></a></span>
                @endif
            </div>

            @if($events->isEmpty())
            <div class="ev-empty">
                <i class="fa-solid fa-calendar-xmark fa-3x mb-3" style="color:#e5e7eb;"></i>
                <div style="font-size:1rem;font-weight:700;color:#374151;">Tidak ada event yang sesuai filter</div>
                <div style="font-size:0.85rem;color:#9ca3af;margin-top:4px;">Coba ubah filter atau hapus beberapa kriteria.</div>
                <a href="{{ route('list-event') }}" class="btn btn-sm mt-3" style="background:#E62020;color:#fff;border-radius:8px;">Lihat Semua Event</a>
            </div>
            @else

            <div class="row g-4">
            @foreach($events as $e)
            <div class="col-xl-4 col-md-6">
                <div class="ev-card">

                    <div class="ev-card-thumb">
                        @if($e->background_event)
                            <img src="{{ asset('storage/' . $e->background_event) }}" alt="{{ $e->judul_event }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background:#f1f5f9;">
                                <i class="fa-solid fa-calendar-days fa-3x" style="color:#cbd5e1;"></i>
                            </div>
                        @endif

                        {{-- Badge: Terdaftar > Pendaftaran Buka/Tutup --}}
                        @if($e->is_registered)
                            <span class="ev-status registered">
                                <i class="fa-solid fa-circle-check me-1"></i> Terdaftar
                            </span>
                        @else
                            <span class="ev-status {{ $e->is_open ? 'open' : 'closed' }}">
                                {{ $e->is_open ? 'Pendaftaran Buka' : 'Pendaftaran Tutup' }}
                            </span>
                        @endif

                        @if($e->jumlah_peserta > 0)
                        <span class="ev-peserta">
                            <i class="fa-solid fa-users me-1"></i>{{ $e->jumlah_peserta }} peserta
                        </span>
                        @endif
                    </div>

                    <div class="ev-card-body">

                        @if($e->kolaborasi->count() > 0)
                        <div class="ev-card-kol">
                            @foreach($e->kolaborasi->take(3) as $kol)
                                <span class="ev-kol-badge">{{ $kol->nama_kolaborasi }}</span>
                            @endforeach
                            @if($e->kolaborasi->count() > 3)
                                <span class="ev-kol-badge">+{{ $e->kolaborasi->count() - 3 }}</span>
                            @endif
                        </div>
                        @endif

                        <div class="ev-card-title">{{ $e->judul_event }}</div>
                        <div class="ev-card-sub">{{ $e->sub_judul_event }}</div>

                        <div class="ev-card-meta">
                            <span><i class="fa-solid fa-location-dot"></i>{{ $e->lokasi_event }}</span>
                            <span><i class="fa-solid fa-calendar-days"></i>
                                {{ date('d M Y', strtotime($e->tanggal_awal_event)) }}
                                &ndash;
                                {{ date('d M Y', strtotime($e->tanggal_akhir_event)) }}
                            </span>
                        </div>

                        <div class="ev-price-wrap">
                            <div class="ev-price-label">Harga Dasar</div>
                            <div class="ev-price-val {{ ($e->harga_event ?? 0) <= 0 ? 'free' : '' }}">
                                @if(($e->harga_event ?? 0) <= 0)
                                    GRATIS
                                @else
                                    Rp {{ number_format($e->harga_event, 0, ',', '.') }}
                                @endif
                            </div>
                        </div>

                        @if($e->paket->count() > 0)
                        <div style="font-size:0.7rem;font-weight:700;color:#aaa;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Paket Tambahan</div>
                        <div class="ev-paket-wrap">
                            @foreach($e->paket as $p)
                            <div class="ev-paket-pill" title="{{ $p->keterangan_paket ?? $p->judul_paket }}">
                                @if($p->icon_paket)
                                    <img src="{{ asset('storage/' . $p->icon_paket) }}" alt="">
                                @else
                                    <i class="fa-solid fa-box-open" style="font-size:11px;color:#6366f1;"></i>
                                @endif
                                <span>{{ $p->judul_paket }}</span>
                                <span class="pill-price {{ $p->harga_paket <= 0 ? 'free' : '' }}">
                                    @if($p->harga_paket <= 0) Gratis
                                    @else +Rp {{ number_format($p->harga_paket, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- CTA Button --}}
                        @if($e->is_registered)
                            {{-- Sudah terdaftar: tombol disabled --}}
                            <span class="ev-btn-register registered">
                                <i class="fa-solid fa-circle-check me-1"></i> Sudah Terdaftar
                            </span>
                        @elseif($e->is_open)
                            @if(session('id_user'))
                                <a href="{{ route('detailEvent', ['key' => $e->kode_event]) }}" class="ev-btn-register">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Daftar / Beli Tiket
                                </a>
                            @else
                                <a href="{{ route('register') }}?event={{ $e->kode_event }}" class="ev-btn-register">
                                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Daftar Sekarang
                                </a>
                            @endif
                        @else
                            <span class="ev-btn-register disabled">
                                <i class="fa-solid fa-lock me-1"></i> Pendaftaran Ditutup
                            </span>
                        @endif

                        <a href="{{ route('detailEvent', ['key' => $e->kode_event]) }}" class="ev-btn-detail">
                            <i class="fa-solid fa-circle-info me-1"></i> Lihat Detail
                        </a>

                    </div>
                </div>
            </div>
            @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $events->links() }}
            </div>

            @endif
        </div>
    </div>
</div>
</div>

<script>
document.querySelector('select[name="sort"]')?.addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>

@include('layouts.footer-v2')
