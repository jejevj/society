@include('layouts.header-v2')

<style>
.reg-fullscreen { position:relative;min-height:100vh; }
.reg-fullscreen::before { content:'';position:fixed;inset:0;z-index:-2;background-image:url('{{ asset("storage/" . ($set->gambar_login ?? "")) }}');background-size:cover;background-position:center; }
.reg-fullscreen::after  { content:'';position:fixed;inset:0;z-index:-1;background:rgba(10,10,30,0.82); }
.reg-content { position:relative;z-index:1;display:flex;align-items:flex-start;padding-top:120px;padding-bottom:60px;min-height:100vh; }

.step-bar { display:flex;align-items:center;gap:0;margin-bottom:28px; }
.step-item { display:flex;flex-direction:column;align-items:center;flex:1;position:relative; }
.step-circle { width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.88rem;border:2.5px solid;transition:all 0.3s;position:relative;z-index:1; }
.step-circle.done   { background:#22c55e;border-color:#22c55e;color:#fff; }
.step-circle.active { background:#E62020;border-color:#E62020;color:#fff; }
.step-circle.idle   { background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.25);color:rgba(255,255,255,0.5); }
.step-label { font-size:0.68rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-top:6px;text-align:center; }
.step-label.done   { color:#22c55e; }
.step-label.active { color:#fff; }
.step-label.idle   { color:rgba(255,255,255,0.35); }
.step-connector { flex:1;height:2px;background:rgba(255,255,255,0.12);margin:0 -1px;margin-top:-22px;position:relative;z-index:0; }
.step-connector.done { background:#22c55e; }

.reg-card { background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.35);padding:36px 32px; }
.reg-card h2 { font-size:1.2rem;font-weight:800;color:#1a1a1a;margin-bottom:4px; }
.reg-card .subtitle { color:#999;font-size:0.82rem;margin-bottom:20px; }

/* Baris harga event */
.event-fee-row { display:flex;justify-content:space-between;align-items:center;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:0.88rem; }
.event-fee-row.paid { background:#fff5f5;border-color:#fca5a5; }
.event-fee-label { color:#555;font-weight:600; }
.event-fee-value { font-weight:800;color:#E62020; }
.event-fee-value.free { color:#16a34a; }

.paket-card {
    border:2px solid #e8e8e8; border-radius:14px; padding:16px; cursor:pointer;
    transition:border-color 0.2s, box-shadow 0.2s, background 0.2s; position:relative; margin-bottom:12px;
}
.paket-card:hover { border-color:#E62020; box-shadow:0 4px 16px rgba(230,32,32,0.08); }
.paket-card.selected { border-color:#E62020; background:#fff5f5; box-shadow:0 4px 16px rgba(230,32,32,0.12); }
.paket-card .check-icon { position:absolute;top:12px;right:12px;width:24px;height:24px;background:#E62020;border-radius:50%;color:#fff;display:none;align-items:center;justify-content:center;font-size:0.75rem; }
.paket-card.selected .check-icon { display:flex; }
.paket-card .paket-name { font-weight:700;font-size:0.95rem;color:#1a1a1a;margin-bottom:4px; }
.paket-card .paket-desc { font-size:0.82rem;color:#777;margin-bottom:8px; }
.paket-card .paket-price { font-size:1.1rem;font-weight:800;color:#E62020; }
.paket-card .paket-img { width:48px;height:48px;object-fit:contain;margin-bottom:10px; }

.total-bar { background:#1a1a2e;border-radius:12px;padding:14px 18px;margin:16px 0;display:flex;justify-content:space-between;align-items:center; }
.total-bar .tl { color:rgba(255,255,255,0.7);font-size:0.85rem;font-weight:600; }
.total-bar .tr { color:#f8ee93;font-size:1.15rem;font-weight:800; }

.btn-primary-red { background:#E62020;color:#fff;border:none;border-radius:10px;padding:12px;font-weight:700;font-size:0.94rem;width:100%;cursor:pointer;transition:background 0.2s;display:flex;align-items:center;justify-content:center;gap:8px; }
.btn-primary-red:hover { background:#c41a1a;color:#fff; }
.btn-skip { background:transparent;border:1.5px solid #e0e0e0;color:#888;border-radius:10px;padding:11px;font-size:0.88rem;font-weight:600;width:100%;cursor:pointer;transition:all 0.2s;margin-top:8px; }
.btn-skip:hover { border-color:#aaa;color:#555; }

@media (max-width:767.98px) { .reg-content{padding-top:80px;} .reg-card{padding:22px 14px;} .left-col{display:none;} }
</style>

<div class="reg-fullscreen">
<div class="reg-content">
<div class="container">
<div class="row justify-content-center align-items-start g-5">

    <div class="col-md-6 col-lg-5 left-col">
        @if(isset($event) && $event)
            <span style="display:inline-block;background:rgba(255,255,255,0.12);color:#f8ee93;font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 14px;border-radius:20px;border:1px solid rgba(255,255,255,0.25);margin-bottom:18px;">Mendaftar untuk</span>
            <h1 style="font-size:2rem;font-weight:800;color:#fff;line-height:1.25;margin-bottom:6px;">{{ $event->judul_event }}</h1>
            <div style="color:rgba(255,255,255,0.6);font-size:0.95rem;margin-bottom:24px;">{{ $event->sub_judul_event }}</div>
        @endif
        <div style="border-top:1px solid rgba(255,255,255,0.12);padding-top:24px;">
        @php $steps = [['done'=>true,'label'=>'Data Diri'],['done'=>true,'label'=>'Verifikasi OTP'],['active'=>true,'label'=>'Add-on'],['idle'=>true,'label'=>'Pembayaran']]; @endphp
        <div class="step-bar">
            @foreach($steps as $i => $s)
                @if($i > 0)<div class="step-connector {{ ($s['done'] ?? false) ? 'done' : '' }}"></div>@endif
                <div class="step-item">
                    <div class="step-circle {{ ($s['done'] ?? false) ? 'done' : (($s['active'] ?? false) ? 'active' : 'idle') }}">
                        @if($s['done'] ?? false)<i class="fa-solid fa-check" style="font-size:0.9rem;"></i>@else{{ $i+1 }}@endif
                    </div>
                    <span class="step-label {{ ($s['done'] ?? false) ? 'done' : (($s['active'] ?? false) ? 'active' : 'idle') }}">{{ $s['label'] }}</span>
                </div>
            @endforeach
        </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-5">
        <div class="reg-card">
            @if($set && $set->logo_app)
                <img src="{{ asset('storage/' . $set->logo_app) }}" alt="Logo" style="width:44px;height:44px;object-fit:contain;margin-bottom:12px;">
            @endif
            <h2>Pilih Add-on Paket</h2>
            <p class="subtitle">Tambahkan paket aktivitas yang ingin Anda ikuti (opsional)</p>

            {{-- Baris harga dasar event --}}
            @php $hargaEventBase = (float) ($event->harga_event ?? 0); @endphp
            <div class="event-fee-row {{ $hargaEventBase > 0 ? 'paid' : '' }}">
                <span class="event-fee-label"><i class="fa-solid fa-ticket me-1"></i>Biaya Pendaftaran Event</span>
                @if($hargaEventBase > 0)
                    <span class="event-fee-value">Rp {{ number_format($hargaEventBase, 0, ',', '.') }}</span>
                @else
                    <span class="event-fee-value free"><i class="fa-solid fa-circle-check me-1"></i>Gratis</span>
                @endif
            </div>

            <form action="{{ route('register-event.save-addon') }}" method="POST" id="formAddon">
                @csrf

                @if($paket && $paket->count())
                    <div style="font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#aaa;margin-bottom:10px;">Paket Add-on (opsional)</div>
                    @foreach($paket as $p)
                    @php $hargaPaket = (float) ($p->harga_paket ?? 0); @endphp
                    <div class="paket-card" data-kode="{{ $p->kode_paket }}" data-harga="{{ $hargaPaket }}" onclick="togglePaket(this)">
                        <div class="check-icon"><i class="fa-solid fa-check"></i></div>
                        @if(!empty($p->icon_paket))
                            <img src="{{ asset('storage/' . $p->icon_paket) }}" alt="" class="paket-img">
                        @endif
                        <div class="paket-name">{{ $p->judul_paket }}</div>
                        <div class="paket-desc">{{ $p->keterangan_paket ?? '' }}</div>
                        <div class="paket-price">
                            @if($hargaPaket > 0)
                                Rp {{ number_format($hargaPaket, 0, ',', '.') }}
                            @else
                                <span style="color:#22c55e;">Gratis</span>
                            @endif
                        </div>
                        <input type="checkbox" name="selected_paket[]" value="{{ $p->kode_paket }}" style="display:none;" class="paket-check">
                    </div>
                    @endforeach
                @endif

                {{-- Total: harga event + add-on yang dipilih --}}
                <div class="total-bar">
                    <span class="tl"><i class="fa-solid fa-receipt me-1"></i>Total Pembayaran</span>
                    <span class="tr" id="totalHarga">{{ $hargaEventBase > 0 ? 'Rp ' . number_format($hargaEventBase, 0, ',', '.') : 'Rp 0' }}</span>
                </div>

                <button type="submit" class="btn-primary-red mt-2">
                    <i class="fa-solid fa-arrow-right"></i> Lanjut ke Pembayaran
                </button>
                @if($paket && $paket->count())
                <button type="button" class="btn-skip" onclick="skipAddon()">
                    Lewati, lanjut tanpa add-on
                </button>
                @endif
            </form>
        </div>
    </div>

</div>
</div>
</div>
</div>

<script>
var BASE_HARGA_EVENT = {{ (float) ($event->harga_event ?? 0) }};

function formatRp(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
}

function togglePaket(card) {
    card.classList.toggle('selected');
    const chk = card.querySelector('.paket-check');
    chk.checked = card.classList.contains('selected');
    updateTotal();
}

function updateTotal() {
    let totalAddon = 0;
    document.querySelectorAll('.paket-card.selected').forEach(function(c) {
        totalAddon += parseFloat(c.dataset.harga || 0);
    });
    var grand = BASE_HARGA_EVENT + totalAddon;
    document.getElementById('totalHarga').textContent = formatRp(grand);
}

function skipAddon() {
    document.querySelectorAll('.paket-check').forEach(function(c) { c.checked = false; });
    document.querySelectorAll('.paket-card').forEach(function(c) { c.classList.remove('selected'); });
    document.getElementById('formAddon').submit();
}
</script>

@include('layouts.footer-v2')
