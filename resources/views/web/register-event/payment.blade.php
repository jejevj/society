@include('layouts.header-v2')

<style>
.reg-fullscreen{position:relative;min-height:100vh}
.reg-fullscreen::before{content:'';position:fixed;inset:0;z-index:-2;background-image:url('{{ asset("storage/" . ($set->gambar_login ?? "")) }}');background-size:cover;background-position:center}
.reg-fullscreen::after{content:'';position:fixed;inset:0;z-index:-1;background:rgba(10,10,30,.82)}
.reg-content{position:relative;z-index:1;display:flex;align-items:flex-start;padding-top:120px;padding-bottom:60px;min-height:100vh}

.step-bar{display:flex;align-items:center;gap:0;margin-bottom:28px}
.step-item{display:flex;flex-direction:column;align-items:center;flex:1;position:relative}
.step-circle{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.88rem;border:2.5px solid;transition:all .3s;position:relative;z-index:1}
.step-circle.done{background:#22c55e;border-color:#22c55e;color:#fff}
.step-circle.active{background:#E62020;border-color:#E62020;color:#fff}
.step-circle.idle{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.25);color:rgba(255,255,255,.5)}
.step-label{font-size:.68rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-top:6px;text-align:center}
.step-label.done{color:#22c55e}.step-label.active{color:#fff}.step-label.idle{color:rgba(255,255,255,.35)}
.step-connector{flex:1;height:2px;background:rgba(255,255,255,.12);margin:0 -1px;margin-top:-22px;position:relative;z-index:0}
.step-connector.done{background:#22c55e}

.reg-card{background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.35);padding:36px 32px}
.reg-card h2{font-size:1.2rem;font-weight:800;color:#1a1a1a;margin-bottom:4px}
.reg-card .subtitle{color:#999;font-size:.82rem;margin-bottom:20px}

.struk-box{background:#fafafa;border:1.5px solid #e8e8e8;border-radius:14px;padding:18px 16px;margin-bottom:16px}
.struk-title{font-size:.7rem;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#aaa;margin-bottom:12px;display:flex;align-items:center;gap:6px}
.struk-row{display:flex;justify-content:space-between;align-items:flex-start;padding:7px 0;border-bottom:1px dashed #eee;font-size:.87rem;gap:8px}
.struk-row:last-child{border-bottom:none}
.struk-row .sk{color:#666;flex:1;min-width:0}
.struk-row .sv{font-weight:700;color:#1a1a1a;text-align:right;white-space:nowrap}
.struk-row .sv.red{color:#E62020}
.struk-row .sv.green{color:#16a34a}

.struk-divider{border:none;border-top:2px dashed #ddd;margin:10px 0}

.order-total{background:#1a1a2e;border-radius:12px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;margin:16px 0}
.order-total .tl{color:rgba(255,255,255,.7);font-size:.9rem;font-weight:600}
.order-total .tr{color:#f8ee93;font-size:1.25rem;font-weight:800}

.btn-pay{background:#E62020;color:#fff;border:none;border-radius:10px;padding:14px;font-weight:700;font-size:1rem;width:100%;cursor:pointer;transition:background .2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-pay:hover{background:#c41a1a}
.btn-pay:disabled{background:#9ca3af;cursor:not-allowed}

.pay-status-box{margin-top:12px;padding:12px 14px;border-radius:10px;font-size:.87rem;display:none}
.pay-status-box.pending{background:#fefce8;border:1.5px solid #fde047;color:#854d0e;display:flex;align-items:center;gap:8px}
.pay-status-box.error{background:#fef2f2;border:1.5px solid #fca5a5;color:#dc2626;display:flex;align-items:center;gap:8px}
.pay-status-box.success{background:#f0fdf4;border:1.5px solid #bbf7d0;color:#166534;display:flex;align-items:center;gap:8px}

.spinner{width:16px;height:16px;border:2.5px solid currentColor;border-top-color:transparent;border-radius:50%;animation:spin .7s linear infinite;flex-shrink:0}
@keyframes spin{to{transform:rotate(360deg)}}

@media(max-width:767.98px){.reg-content{padding-top:80px}.reg-card{padding:22px 14px}.left-col{display:none}}
</style>

<div class="reg-fullscreen">
<div class="reg-content">
<div class="container">
<div class="row justify-content-center align-items-start g-5">

    {{-- Left col: step indicator --}}
    <div class="col-md-6 col-lg-5 left-col">
        @if(isset($event) && $event)
            <span style="display:inline-block;background:rgba(255,255,255,.12);color:#f8ee93;font-size:.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 14px;border-radius:20px;border:1px solid rgba(255,255,255,.25);margin-bottom:18px">Mendaftar untuk</span>
            <h1 style="font-size:2rem;font-weight:800;color:#fff;line-height:1.25;margin-bottom:6px">{{ $event->judul_event }}</h1>
            <div style="color:rgba(255,255,255,.6);font-size:.95rem;margin-bottom:24px">{{ $event->sub_judul_event }}</div>
        @endif
        <div style="border-top:1px solid rgba(255,255,255,.12);padding-top:24px">
        @php $steps=[['done'=>true,'label'=>'Data Diri'],['done'=>true,'label'=>'Verifikasi OTP'],['done'=>true,'label'=>'Add-on'],['active'=>true,'label'=>'Pembayaran']]; @endphp
        <div class="step-bar">
            @foreach($steps as $i=>$s)
                @if($i>0)<div class="step-connector done"></div>@endif
                <div class="step-item">
                    <div class="step-circle {{ ($s['done']??false)?'done':'active' }}">
                        @if($s['done']??false)<i class="fa-solid fa-check" style="font-size:.9rem"></i>@else{{ $i+1 }}@endif
                    </div>
                    <span class="step-label {{ ($s['done']??false)?'done':'active' }}">{{ $s['label'] }}</span>
                </div>
            @endforeach
        </div>
        </div>
    </div>

    {{-- Right col: struk + tombol bayar --}}
    <div class="col-md-6 col-lg-5">
        <div class="reg-card">
            @if($set && $set->logo_app)
                <img src="{{ asset('storage/'.$set->logo_app) }}" alt="Logo" style="width:44px;height:44px;object-fit:contain;margin-bottom:12px">
            @endif
            <h2>Ringkasan Pembayaran</h2>
            <p class="subtitle">Tinjau pesanan Anda sebelum melanjutkan ke pembayaran</p>

            {{-- STRUK --}}
            <div class="struk-box">
                <div class="struk-title"><i class="fa-solid fa-receipt"></i> Detail Transaksi</div>

                {{-- Info peserta --}}
                <div class="struk-row"><span class="sk">Nama Peserta</span><span class="sv">{{ $data['nama'] }}</span></div>
                <div class="struk-row"><span class="sk">Email</span><span class="sv">{{ $data['email'] }}</span></div>
                @if(isset($event))
                <div class="struk-row"><span class="sk">Event</span><span class="sv">{{ $event->judul_event }}</span></div>
                @endif

                <hr class="struk-divider">

                {{-- Baris harga event --}}
                @php $hargaBase=(float)($data['harga_event']??($event->harga_event??0)); @endphp
                @if($hargaBase > 0)
                <div class="struk-row">
                    <span class="sk"><i class="fa-solid fa-ticket me-1" style="color:#E62020"></i>Biaya Pendaftaran Event</span>
                    <span class="sv red">Rp {{ number_format($hargaBase,0,',','.') }}</span>
                </div>
                @else
                <div class="struk-row">
                    <span class="sk"><i class="fa-solid fa-ticket me-1" style="color:#16a34a"></i>Biaya Pendaftaran Event</span>
                    <span class="sv green">Gratis</span>
                </div>
                @endif

                {{-- Baris paket add-on yang dipilih --}}
                @if($selectedPaket && $selectedPaket->count())
                    @foreach($selectedPaket as $p)
                    <div class="struk-row">
                        <span class="sk"><i class="fa-solid fa-box-open me-1" style="color:#6366f1"></i>{{ $p->judul_paket }}</span>
                        @if(($p->harga_paket??0)>0)
                            <span class="sv red">Rp {{ number_format($p->harga_paket,0,',','.') }}</span>
                        @else
                            <span class="sv green">Gratis</span>
                        @endif
                    </div>
                    @endforeach
                @endif

                <hr class="struk-divider">

                {{-- Order ID --}}
                @if(isset($orderId) && $orderId)
                <div class="struk-row">
                    <span class="sk">Order ID</span>
                    <span class="sv" style="font-size:.78rem;color:#888">{{ $orderId }}</span>
                </div>
                @endif
            </div>

            {{-- Total --}}
            <div class="order-total">
                <span class="tl"><i class="fa-solid fa-money-bill-wave me-1"></i>Total Pembayaran</span>
                <span class="tr">Rp {{ number_format($data['total_harga']??0,0,',','.') }}</span>
            </div>

            @if($midtransConfig)
                <button id="btnPay" class="btn-pay">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                </button>

                <div id="payStatusBox" class="pay-status-box"></div>
            @else
                <div style="background:#fefce8;border:1.5px solid #fde047;border-radius:10px;padding:12px 14px;color:#854d0e;font-size:.85rem;margin-bottom:12px">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    Gateway pembayaran belum dikonfigurasi. Hubungi administrator.
                </div>
            @endif
        </div>
    </div>

</div>
</div>
</div>
</div>

@if($midtransConfig)
<script>
(function(){
    var SNAP_TOKEN   = '{{ $snapToken ?? '' }}';
    var CLIENT_KEY   = '{{ $midtransConfig->client_key ?? '' }}';
    var IS_PROD      = {{ ($midtransConfig->is_production ?? false) ? 'true' : 'false' }};
    var SNAP_URL     = IS_PROD
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
    var ORDER_ID     = '{{ $orderId ?? '' }}';
    var CSRF         = '{{ csrf_token() }}';
    var CHECK_URL    = '{{ route("register-event.check-payment") }}';
    var SUCCESS_URL  = '{{ route("register-event.success") }}';
    var CALLBACK_URL = '{{ route("register-event.midtrans-callback") }}';

    var pollTimer    = null;
    var pollCount    = 0;
    var MAX_POLL     = 120; // max 10 menit (5 detik x 120)

    var btn     = document.getElementById('btnPay');
    var statusBox = document.getElementById('payStatusBox');

    function setStatus(type, html){
        statusBox.className = 'pay-status-box ' + type;
        statusBox.innerHTML = html;
        statusBox.style.display = 'flex';
    }

    function stopPoll(){
        if(pollTimer){ clearInterval(pollTimer); pollTimer=null; }
    }

    function startPolling(){
        pollCount = 0;
        pollTimer = setInterval(function(){
            pollCount++;
            if(pollCount > MAX_POLL){
                stopPoll();
                setStatus('error','<i class="fa-solid fa-clock"></i><span>Sesi pembayaran habis. Muat ulang halaman untuk mencoba lagi.</span>');
                return;
            }

            fetch(CHECK_URL, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json'},
                body: JSON.stringify({order_id: ORDER_ID})
            })
            .then(function(r){ return r.json(); })
            .then(function(res){
                if(res.status === 'paid'){
                    stopPoll();
                    setStatus('success','<i class="fa-solid fa-circle-check"></i><span>Pembayaran berhasil! Mengalihkan...</span>');
                    setTimeout(function(){ window.location.href = SUCCESS_URL; }, 1500);
                } else if(res.status === 'failed'){
                    stopPoll();
                    setStatus('error','<i class="fa-solid fa-circle-xmark"></i><span>Pembayaran gagal atau dibatalkan. Klik Bayar Sekarang untuk mencoba lagi.</span>');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                }
                // status pending: lanjut polling
            })
            .catch(function(){ /* abaikan error jaringan saat polling */ });
        }, 5000);
    }

    function loadSnapScript(callback){
        if(window.snap && typeof window.snap.pay==='function'){ callback(); return; }
        var s = document.createElement('script');
        s.src = SNAP_URL;
        s.setAttribute('data-client-key', CLIENT_KEY);
        s.onload = function(){ setTimeout(callback, 150); };
        s.onerror = function(){
            setStatus('error','<i class="fa-solid fa-triangle-exclamation"></i><span>Gagal memuat Midtrans Snap. Periksa koneksi internet Anda.</span>');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
        };
        document.head.appendChild(s);
    }

    function openSnap(token){
        if(!window.snap || typeof window.snap.pay!=='function'){
            setStatus('error','<i class="fa-solid fa-triangle-exclamation"></i><span>Midtrans Snap tidak tersedia. Pastikan Client Key sudah diisi di konfigurasi.</span>');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
            return;
        }

        window.snap.pay(token, {
            onSuccess: function(result){
                stopPoll();
                setStatus('success','<i class="fa-solid fa-circle-check"></i><span>Pembayaran berhasil! Mengalihkan...</span>');
                // Kirim konfirmasi ke callback
                fetch(CALLBACK_URL, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify(result)
                })
                .then(function(r){ return r.json(); })
                .then(function(res){
                    setTimeout(function(){ window.location.href = res.redirect || SUCCESS_URL; }, 800);
                })
                .catch(function(){ setTimeout(function(){ window.location.href = SUCCESS_URL; }, 800); });
            },
            onPending: function(result){
                setStatus('pending','<div class="spinner"></div><span>Menunggu pembayaran... Kami akan otomatis mendeteksi ketika Anda selesai membayar.</span>');
                startPolling();
            },
            onError: function(result){
                stopPoll();
                setStatus('error','<i class="fa-solid fa-circle-xmark"></i><span>Pembayaran gagal: '+(result.status_message||'Error tidak diketahui.')+'</span>');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
            },
            onClose: function(){
                // Popup ditutup — mulai polling kalau mungkin sudah bayar di tab lain
                setStatus('pending','<div class="spinner"></div><span>Memeriksa status pembayaran...</span>');
                startPolling();
            }
        });
    }

    btn.addEventListener('click', function(){
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner" style="border-color:#fff;border-top-color:transparent"></div> Memuat pembayaran...';
        setStatus('pending','<div class="spinner"></div><span>Menghubungi Midtrans...</span>');
        stopPoll();

        loadSnapScript(function(){
            openSnap(SNAP_TOKEN);
        });
    });
})();
</script>
@endif

@include('layouts.footer-v2')
