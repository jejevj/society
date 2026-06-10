@include('layouts.header-v2')

<style>
.riwayat-page { padding-top: 100px; padding-bottom: 60px; min-height: 100vh; background: #f5f7fa; }
.rw-section-title { font-size: 1rem; font-weight: 800; color: #1a1a1a; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }

/* Event card */
.ev-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    overflow: hidden;
    margin-bottom: 16px;
    border: 1.5px solid #f0f0f0;
    transition: box-shadow 0.2s;
}
.ev-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.11); }
.ev-card-img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    background: #e9ecef;
}
.ev-card-body { padding: 14px 16px; }
.ev-card-title { font-weight: 700; font-size: 0.92rem; color: #1a1a1a; margin-bottom: 4px; line-height: 1.3; }
.ev-card-sub { font-size: 0.78rem; color: #888; margin-bottom: 8px; }
.ev-card-meta { font-size: 0.78rem; color: #666; display: flex; flex-direction: column; gap: 3px; margin-bottom: 10px; }
.ev-card-meta span i { width: 14px; color: #E62020; }

/* Status badges */
.ev-badge { display: inline-block; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.8px; }
.ev-badge.confirmed   { background: #d1fae5; color: #065f46; }
.ev-badge.pending_pay { background: #fef3c7; color: #92400e; }
.ev-badge.failed      { background: #fee2e2; color: #991b1b; }
.ev-badge.pending_otp { background: #e0e7ff; color: #3730a3; }

/* Retry banner */
.retry-banner {
    background: linear-gradient(135deg, #fff7ed, #fff);
    border: 1.5px solid #fed7aa;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
.retry-banner .rb-text { font-size: 0.82rem; color: #92400e; }
.retry-banner .rb-text strong { display: block; font-size: 0.88rem; color: #78350f; margin-bottom: 2px; }
.btn-retry {
    background: #E62020;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: background 0.2s;
}
.btn-retry:hover { background: #c41a1a; }
.btn-retry:disabled { background: #9ca3af; cursor: not-allowed; }

/* Addon pills */
.addon-pill { display: inline-block; background: #f0f4ff; color: #3730a3; font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 20px; margin: 2px 2px 0 0; }

/* Pay status inline */
.pay-inline { font-size: 0.8rem; margin-top: 6px; display: none; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 8px; }
.pay-inline.pending { background: #fefce8; border: 1px solid #fde047; color: #854d0e; display: flex; }
.pay-inline.error   { background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626; display: flex; }
.pay-inline.ok      { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; display: flex; }
.spinner-sm { width: 13px; height: 13px; border: 2px solid currentColor; border-top-color: transparent; border-radius: 50%; animation: spin .7s linear infinite; flex-shrink: 0; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="riwayat-page">
<div class="container">

{{-- ─── BANNER: pending payment ─── --}}
@php
    $pendingEvents = $eventRegistrasi->whereIn('payment_status', ['PENDING','UNPAID'])->where('status_registrasi', '!=', 'A');
@endphp
@if($pendingEvents->count() > 0)
<div class="alert mb-4" style="background:#fff7ed;border:1.5px solid #fed7aa;border-radius:14px;padding:16px 20px;">
    <div style="font-weight:800;color:#92400e;font-size:0.95rem;margin-bottom:6px;">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>Pembayaran Belum Selesai
    </div>
    <div style="font-size:0.85rem;color:#78350f;">
        Kamu memiliki {{ $pendingEvents->count() }} event yang belum selesai dibayar.
        Selesaikan pembayaran agar pendaftaran kamu dikonfirmasi.
    </div>
</div>
@endif

{{-- ─── EVENT REGISTRATION ─── --}}
<div class="rw-section-title">
    <i class="fa-solid fa-calendar-check" style="color:#E62020"></i> Riwayat Pendaftaran Event
</div>

@if($eventRegistrasi->isEmpty())
<div class="text-center text-muted py-4" style="background:#fff;border-radius:14px;border:1.5px solid #f0f0f0;margin-bottom:28px;">
    <i class="fa-solid fa-calendar-xmark fa-2x mb-2" style="color:#ddd"></i>
    <div style="font-size:0.88rem;">Belum ada pendaftaran event.</div>
</div>
@else
<div class="row g-3 mb-4">
@foreach($eventRegistrasi as $reg)
<div class="col-md-4 col-sm-6">
    <div class="ev-card">
        @if(!empty($reg->background_event))
            <img src="{{ asset('storage/'.$reg->background_event) }}" class="ev-card-img" alt="{{ $reg->judul_event }}">
        @else
            <div class="ev-card-img d-flex align-items-center justify-content-center" style="background:#f1f5f9;">
                <i class="fa-solid fa-calendar-days fa-2x" style="color:#cbd5e1"></i>
            </div>
        @endif

        <div class="ev-card-body">
            <div class="ev-card-title">{{ $reg->judul_event }}</div>
            <div class="ev-card-sub">{{ $reg->sub_judul_event }}</div>
            <div class="ev-card-meta">
                <span><i class="fa-solid fa-location-dot"></i> {{ $reg->lokasi_event }}</span>
                <span><i class="fa-solid fa-calendar-days"></i>
                    {{ date('d M Y', strtotime($reg->tanggal_awal_event)) }}
                    &ndash;
                    {{ date('d M Y', strtotime($reg->tanggal_akhir_event)) }}
                </span>
                <span><i class="fa-solid fa-id-badge"></i> {{ ucfirst($reg->role_peserta ?? 'Participant') }}</span>
                @if($reg->total_bayar > 0)
                <span><i class="fa-solid fa-money-bill"></i>
                    Rp {{ number_format($reg->total_bayar, 0, ',', '.') }}
                </span>
                @endif
            </div>

            @php
                if ($reg->status_registrasi === 'A') {
                    $badgeClass = 'confirmed';
                    $badgeLabel = 'Terdaftar';
                } elseif (in_array($reg->payment_status, ['FAILED','EXPIRE','CANCEL'])) {
                    $badgeClass = 'failed';
                    $badgeLabel = 'Pembayaran Gagal';
                } elseif (in_array($reg->payment_status, ['PENDING','UNPAID'])) {
                    $badgeClass = 'pending_pay';
                    $badgeLabel = 'Menunggu Pembayaran';
                } else {
                    $badgeClass = 'pending_otp';
                    $badgeLabel = $reg->status_registrasi;
                }
            @endphp
            <span class="ev-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>

            @if($reg->addons->count() > 0)
            <div class="mt-2">
                @foreach($reg->addons as $addon)
                    <span class="addon-pill">{{ $addon->judul_paket }}</span>
                @endforeach
            </div>
            @endif

            @if($reg->status_registrasi !== 'A' && in_array($reg->payment_status, ['PENDING','UNPAID']))
            <div class="retry-banner mt-2">
                <div class="rb-text">
                    <strong>Pembayaran belum selesai</strong>
                    Selesaikan untuk konfirmasi pendaftaran.
                </div>
                <button class="btn-retry"
                    data-order-id="{{ $reg->midtrans_order_id }}"
                    data-kode-event="{{ $reg->kode_event }}"
                    onclick="doRetryPayment(this)">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                </button>
            </div>
            <div class="pay-inline" id="payStatus_{{ $reg->kode_event }}"></div>
            @endif

            @if($reg->status_registrasi === 'A' && $reg->confirmed_at)
            <div style="font-size:0.73rem;color:#10b981;margin-top:6px;">
                <i class="fa-solid fa-circle-check me-1"></i>
                Terdaftar pada {{ date('d M Y H:i', strtotime($reg->confirmed_at)) }}
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
</div>
@endif

</div>
</div>

<script>
var _csrf         = '{{ csrf_token() }}';
var _clientKey    = '{{ $midtransConfig->client_key ?? '' }}';
var _isProduction = {{ isset($midtransConfig) && $midtransConfig && ($midtransConfig->is_production ?? false) ? 'true' : 'false' }};
var _snapUrl      = _isProduction
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js';

$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': _csrf } });

function setPayInline(kodeEvent, type, html) {
    var box = document.getElementById('payStatus_' + kodeEvent);
    if (!box) return;
    box.className = 'pay-inline ' + type;
    box.innerHTML = html;
}

function loadSnapScript(callback) {
    if (window.snap && typeof window.snap.pay === 'function') { callback(); return; }
    var s = document.createElement('script');
    s.src = _snapUrl;
    s.setAttribute('data-client-key', _clientKey);
    s.onload  = function () { setTimeout(callback, 150); };
    s.onerror = function () { alert('Gagal memuat gateway pembayaran. Periksa koneksi internet Anda.'); };
    document.head.appendChild(s);
}

function doRetryPayment(btn) {
    var kodeEvent = btn.dataset.kodeEvent;
    var orderId   = btn.dataset.orderId;

    btn.disabled = true;
    btn.innerHTML = '<div class="spinner-sm"></div> Loading...';
    setPayInline(kodeEvent, 'pending', '<div class="spinner-sm"></div><span>Menyiapkan pembayaran...</span>');

    $.ajax({
        url: '{{ route("cart.check-payment") }}',
        type: 'POST',
        data: { _token: _csrf, order_id: orderId },
        success: function (r) {
            if (r.status === 'paid') {
                setPayInline(kodeEvent, 'ok', '<i class="fa-solid fa-circle-check"></i><span>Pembayaran sudah diterima. Muat ulang halaman.</span>');
                setTimeout(function () { location.reload(); }, 1500);
                return;
            }
            if (r.status === 'failed') {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>Pembayaran gagal/expired. Hubungi admin.</span>');
                return;
            }

            if (!_clientKey) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-triangle-exclamation"></i><span>Konfigurasi pembayaran belum diatur.</span>');
                return;
            }

            loadSnapScript(function () {
                if (!window.snap || typeof window.snap.pay !== 'function') {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                    setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-triangle-exclamation"></i><span>Midtrans tidak tersedia.</span>');
                    return;
                }

                $.ajax({
                    url: '{{ route("cart.retry-snap-token") }}',
                    type: 'POST',
                    data: { _token: _csrf, order_id: orderId },
                    success: function (res) {
                        if (!res.snap_token) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                            setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>' + (res.message || 'Gagal mendapatkan token.') + '</span>');
                            return;
                        }
                        window.snap.pay(res.snap_token, {
                            onSuccess: function () {
                                setPayInline(kodeEvent, 'ok', '<i class="fa-solid fa-circle-check"></i><span>Pembayaran berhasil! Muat ulang halaman.</span>');
                                setTimeout(function () { location.reload(); }, 1800);
                            },
                            onPending: function () {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                                setPayInline(kodeEvent, 'pending', '<div class="spinner-sm"></div><span>Pembayaran pending. Kembali ke halaman ini setelah selesai.</span>');
                            },
                            onError: function (result) {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                                setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>' + (result.status_message || 'Pembayaran gagal.') + '</span>');
                            },
                            onClose: function () {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                                setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>Jendela pembayaran ditutup. Klik Bayar Sekarang untuk mencoba lagi.</span>');
                            }
                        });
                    },
                    error: function (xhr) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal mendapatkan token pembayaran.';
                        setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>' + msg + '</span>');
                    }
                });
            });
        },
        error: function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
            setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>Gagal memeriksa status pembayaran.</span>');
        }
    });
}
</script>

@include('layouts.footer-v2')
