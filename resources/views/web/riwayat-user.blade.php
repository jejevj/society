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
.ev-badge.confirmed    { background: #d1fae5; color: #065f46; }
.ev-badge.pending_pay  { background: #fef3c7; color: #92400e; }
.ev-badge.expired      { background: #fee2e2; color: #991b1b; }
.ev-badge.pending_otp  { background: #e0e7ff; color: #3730a3; }

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
.retry-banner .rb-text.expired { color: #991b1b; }
.retry-banner .rb-text.expired strong { color: #7f1d1d; }
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

/* Permohonan table */
.table-riwayat { font-size: 0.85rem; }
.table-riwayat thead th { background: #f8f9fa; font-weight: 700; color: #555; border-bottom: 2px solid #e9ecef; }
.badge-status { font-size: 0.72rem; padding: 3px 8px; border-radius: 20px; font-weight: 700; }

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

{{-- ─── BANNER RETRY: hanya tampil jika ada PENDING_PAYMENT atau PAYMENT_EXPIRED ─── --}}
@php
    $pendingEvents = $eventRegistrasi->whereIn('status_registrasi', ['PENDING_PAYMENT','PAYMENT_EXPIRED']);
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
        @if($reg->gambar_event)
            <img src="{{ asset('storage/'.$reg->gambar_event) }}" class="ev-card-img" alt="{{ $reg->judul_event }}">
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
                <span><i class="fa-solid fa-id-badge"></i> {{ ucfirst($reg->role_peserta ?? 'participant') }}</span>
            </div>

            {{-- Status badge --}}
            @php
                $badgeClass = match($reg->status_registrasi) {
                    'CONFIRMED'       => 'confirmed',
                    'PENDING_PAYMENT' => 'pending_pay',
                    'PAYMENT_EXPIRED' => 'expired',
                    default           => 'pending_otp',
                };
                $badgeLabel = match($reg->status_registrasi) {
                    'CONFIRMED'       => 'Terdaftar',
                    'PENDING_PAYMENT' => 'Menunggu Pembayaran',
                    'PAYMENT_EXPIRED' => 'Pembayaran Expired',
                    'PENDING_OTP'     => 'Menunggu Verifikasi',
                    default           => $reg->status_registrasi,
                };
            @endphp
            <span class="ev-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>

            {{-- Addons --}}
            @if($reg->addons->count() > 0)
            <div class="mt-2">
                @foreach($reg->addons as $addon)
                    <span class="addon-pill">{{ $addon->judul_paket }}</span>
                @endforeach
            </div>
            @endif

            {{-- RETRY BANNER --}}
            @if(in_array($reg->status_registrasi, ['PENDING_PAYMENT','PAYMENT_EXPIRED']))
            <div class="retry-banner mt-2">
                <div class="rb-text {{ $reg->status_registrasi === 'PAYMENT_EXPIRED' ? 'expired' : '' }}">
                    <strong>
                        @if($reg->status_registrasi === 'PAYMENT_EXPIRED')
                            Pembayaran telah expired
                        @else
                            Pembayaran belum selesai
                        @endif
                    </strong>
                    Selesaikan untuk konfirmasi pendaftaran.
                </div>
                <button class="btn-retry"
                    data-kode-event="{{ $reg->kode_event }}"
                    data-event-nama="{{ $reg->judul_event }}"
                    onclick="doRetryPayment(this)">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                </button>
            </div>
            <div class="pay-inline" id="payStatus_{{ $reg->kode_event }}"></div>
            @endif

            {{-- Confirmed at --}}
            @if($reg->status_registrasi === 'CONFIRMED' && $reg->confirmed_at)
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

{{-- ─── RIWAYAT PERMOHONAN DATA ─── --}}
<div class="rw-section-title">
    <i class="fa-solid fa-file-lines" style="color:#6366f1"></i> Riwayat Permohonan Data
</div>

@if($riwayat->isEmpty())
<div class="text-center text-muted py-4" style="background:#fff;border-radius:14px;border:1.5px solid #f0f0f0;">
    <i class="fa-solid fa-inbox fa-2x mb-2" style="color:#ddd"></i>
    <div style="font-size:0.88rem;">Belum ada riwayat permohonan.</div>
</div>
@else
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-riwayat mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Data</th>
                        <th>Tujuan</th>
                        <th>Tgl Permohonan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($riwayat as $i => $item)
                <tr>
                    <td class="ps-4">{{ $riwayat->firstItem() + $i }}</td>
                    <td>{{ $item->judul_data ?? '-' }}</td>
                    <td>{{ Str::limit($item->tujuan_permohonan ?? '-', 50) }}</td>
                    <td>{{ $item->created_at ? date('d M Y', strtotime($item->created_at)) : '-' }}</td>
                    <td>
                        <span class="badge-status"
                            style="background:{{ match($item->kode_status ?? '') {
                                'APPROVED' => '#d1fae5', 'PENDING' => '#fef3c7',
                                'REJECTED' => '#fee2e2', default   => '#f3f4f6'
                            } }};
                            color:{{ match($item->kode_status ?? '') {
                                'APPROVED' => '#065f46', 'PENDING' => '#92400e',
                                'REJECTED' => '#991b1b', default   => '#374151'
                            } }};">
                            {{ $item->keterangan_status ?? $item->kode_status ?? '-' }}
                        </span>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{ $riwayat->links() }}
@endif

</div>
</div>

{{-- ─── SCRIPT RETRY PAYMENT ─── --}}
<script>
var _csrf        = '{{ csrf_token() }}';
var _clientKey   = '{{ $midtransConfig->client_key ?? '' }}';
var _isProduction = {{ isset($midtransConfig) && $midtransConfig && $midtransConfig->environment === 'production' ? 'true' : 'false' }};
var _snapUrl     = _isProduction
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
    s.onload = function () { setTimeout(callback, 150); };
    s.onerror = function () {
        alert('Gagal memuat gateway pembayaran. Periksa koneksi internet Anda.');
    };
    document.head.appendChild(s);
}

function doRetryPayment(btn) {
    var kodeEvent = btn.dataset.kodeEvent;
    var eventNama = btn.dataset.eventNama;

    btn.disabled = true;
    btn.innerHTML = '<div class="spinner-sm"></div> Loading...';
    setPayInline(kodeEvent, 'pending', '<div class="spinner-sm"></div><span>Menyiapkan pembayaran...</span>');

    $.ajax({
        url: '{{ route("retryPayment") }}',
        type: 'POST',
        data: { _token: _csrf, kode_event: kodeEvent },
        success: function (r) {
            if (!r.success) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>' + (r.message || 'Gagal memuat pembayaran.') + '</span>');
                return;
            }

            if (r.free) {
                // Gratis, langsung enroll
                $.ajax({
                    url: '{{ route("retryPaymentCallback") }}',
                    type: 'POST',
                    data: { _token: _csrf, kode_event: kodeEvent },
                    success: function () {
                        setPayInline(kodeEvent, 'ok', '<i class="fa-solid fa-circle-check"></i><span>Berhasil terdaftar! Muat ulang halaman.</span>');
                        setTimeout(function () { location.reload(); }, 1800);
                    }
                });
                return;
            }

            // Update client key jika dikembalikan server
            if (r.client_key) {
                _clientKey    = r.client_key;
                _isProduction = r.is_production === true;
                _snapUrl      = _isProduction
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js';
            }

            var snapToken = r.snap_token;

            loadSnapScript(function () {
                if (!window.snap || typeof window.snap.pay !== 'function') {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                    setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-triangle-exclamation"></i><span>Midtrans tidak tersedia.</span>');
                    return;
                }

                window.snap.pay(snapToken, {
                    onSuccess: function (result) {
                        setPayInline(kodeEvent, 'ok', '<i class="fa-solid fa-circle-check"></i><span>Pembayaran berhasil! Mendaftarkan...</span>');
                        $.ajax({
                            url: '{{ route("retryPaymentCallback") }}',
                            type: 'POST',
                            data: {
                                _token: _csrf,
                                kode_event: kodeEvent,
                                midtrans_result: JSON.stringify(result)
                            },
                            success: function () {
                                setPayInline(kodeEvent, 'ok', '<i class="fa-solid fa-circle-check"></i><span>Terdaftar! Muat ulang halaman.</span>');
                                setTimeout(function () { location.reload(); }, 1800);
                            },
                            error: function () {
                                setPayInline(kodeEvent, 'ok', '<i class="fa-solid fa-circle-check"></i><span>Pembayaran diterima. Halaman akan dimuat ulang.</span>');
                                setTimeout(function () { location.reload(); }, 2000);
                            }
                        });
                    },
                    onPending: function () {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        setPayInline(kodeEvent, 'pending', '<div class="spinner-sm"></div><span>Pembayaran pending. Selesaikan dan kembali ke halaman ini.</span>');
                    },
                    onError: function (result) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>Pembayaran gagal: ' + (result.status_message || 'Coba lagi.') + '</span>');
                    },
                    onClose: function () {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>Kamu menutup jendela pembayaran. Klik Bayar Sekarang untuk mencoba lagi.</span>');
                    }
                });
            });
        },
        error: function (xhr) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal memuat pembayaran.';
            setPayInline(kodeEvent, 'error', '<i class="fa-solid fa-circle-xmark"></i><span>' + msg + '</span>');
        }
    });
}
</script>

@include('layouts.footer-v2')
