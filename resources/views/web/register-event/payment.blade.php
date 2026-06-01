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
.step-label.done { color:#22c55e; } .step-label.active { color:#fff; } .step-label.idle { color:rgba(255,255,255,0.35); }
.step-connector { flex:1;height:2px;background:rgba(255,255,255,0.12);margin:0 -1px;margin-top:-22px;position:relative;z-index:0; }
.step-connector.done { background:#22c55e; }

.reg-card { background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.35);padding:36px 32px; }
.reg-card h2 { font-size:1.2rem;font-weight:800;color:#1a1a1a;margin-bottom:4px; }
.reg-card .subtitle { color:#999;font-size:0.82rem;margin-bottom:20px; }

.order-row { display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f0f0f0;font-size:0.88rem; }
.order-row:last-child { border-bottom:none; }
.order-row .ok { color:#888; }
.order-row .ov { font-weight:700;color:#1a1a1a; }
.order-total { background:#1a1a2e;border-radius:12px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;margin:16px 0; }
.order-total .tl { color:rgba(255,255,255,0.7);font-size:0.9rem;font-weight:600; }
.order-total .tr { color:#f8ee93;font-size:1.25rem;font-weight:800; }

.btn-pay { background:#E62020;color:#fff;border:none;border-radius:10px;padding:14px;font-weight:700;font-size:1rem;width:100%;cursor:pointer;transition:background 0.2s;display:flex;align-items:center;justify-content:center;gap:8px; }
.btn-pay:hover { background:#c41a1a; }

.free-note { background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:14px;color:#166534;font-size:0.88rem;margin-bottom:16px;display:flex;align-items:center;gap:10px; }

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
        @php $steps = [['done'=>true,'label'=>'Data Diri'],['done'=>true,'label'=>'Verifikasi OTP'],['done'=>true,'label'=>'Add-on'],['active'=>true,'label'=>'Pembayaran']]; @endphp
        <div class="step-bar">
            @foreach($steps as $i => $s)
                @if($i > 0)<div class="step-connector done"></div>@endif
                <div class="step-item">
                    <div class="step-circle {{ ($s['done'] ?? false) ? 'done' : 'active' }}">
                        @if($s['done'] ?? false)<i class="fa-solid fa-check" style="font-size:0.9rem;"></i>@else{{ $i+1 }}@endif
                    </div>
                    <span class="step-label {{ ($s['done'] ?? false) ? 'done' : 'active' }}">{{ $s['label'] }}</span>
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
            <h2>Konfirmasi Pembayaran</h2>
            <p class="subtitle">Tinjau pesanan Anda sebelum melanjutkan</p>

            {{-- Order summary --}}
            <div style="background:#f8f8fb;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div class="order-row"><span class="ok">Nama</span><span class="ov">{{ $data['nama'] }}</span></div>
                <div class="order-row"><span class="ok">Email</span><span class="ov">{{ $data['email'] }}</span></div>
                @if(isset($event))
                <div class="order-row"><span class="ok">Event</span><span class="ov">{{ $event->judul_event }}</span></div>
                @endif
                @if($selectedPaket && count($selectedPaket))
                    <div style="margin-top:8px;font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#aaa;margin-bottom:4px;">Paket Add-on</div>
                    @foreach($selectedPaket as $p)
                    <div class="order-row">
                        <span class="ok">{{ $p->judul_paket }}</span>
                        <span class="ov" style="color:#E62020;">Rp {{ number_format($p->harga_paket ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                @endif
            </div>

            <div class="order-total">
                <span class="tl"><i class="fa-solid fa-receipt me-1"></i>Total Pembayaran</span>
                <span class="tr">Rp {{ number_format($data['total_harga'] ?? 0, 0, ',', '.') }}</span>
            </div>

            @if(($data['total_harga'] ?? 0) == 0)
                {{-- Gratis: langsung enroll --}}
                <div class="free-note">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Event ini <strong>gratis</strong>. Klik konfirmasi untuk menyelesaikan pendaftaran.</span>
                </div>
                <form action="{{ route('register-event.process-payment') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-pay">
                        <i class="fa-solid fa-check-circle"></i> Konfirmasi & Daftar Sekarang
                    </button>
                </form>
            @elseif($snapToken)
                {{-- Midtrans Snap --}}
                <button id="btnPay" class="btn-pay">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                </button>
                <script src="https://app.{{ ($midtransConfig->is_production ?? '0') == '1' ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
                        data-client-key="{{ $midtransConfig->client_key ?? '' }}"></script>
                <script>
                document.getElementById('btnPay').addEventListener('click', function () {
                    snap.pay('{{ $snapToken }}', {
                        onSuccess: function (result) {
                            fetch('{{ route("register-event.midtrans-callback") }}', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                body: JSON.stringify(result)
                            })
                            .then(r => r.json())
                            .then(res => { if (res.redirect) window.location.href = res.redirect; });
                        },
                        onPending: function (result) { alert('Pembayaran pending. Cek email untuk instruksi selanjutnya.'); },
                        onError:   function (result) { alert('Pembayaran gagal. Silakan coba lagi.'); },
                        onClose:   function ()       { /* user menutup popup */ }
                    });
                });
                </script>
            @else
                {{-- Fallback jika Midtrans tidak terkonfigurasi --}}
                <div class="alert alert-warning" style="font-size:0.85rem;">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    Gateway pembayaran belum dikonfiguras