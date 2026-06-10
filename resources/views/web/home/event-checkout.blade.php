@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">
                            <i class="fa-solid fa-cart-shopping text-detail me-2"></i>
                            Checkout Review
                        </h2>
                        <a href="{{ route('event-cart', $cart->kode_cart) }}" class="btn btn-light-warning">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="border rounded p-5 mb-5">
                            <h3 class="fw-bold mb-3">{{ $cart->judul_event }}</h3>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <small class="text-muted-detail d-block">Event Date</small>
                                    <strong>
                                        {{ \Carbon\Carbon::parse($cart->tanggal_awal_event)->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($cart->tanggal_akhir_event)->format('d M Y') }}
                                    </strong>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small class="text-muted-detail d-block">Location</small>
                                    <strong>{{ $cart->lokasi_event }}</strong>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small class="text-muted-detail d-block">Participant</small>
                                    <strong>
                                        <i class="fa-solid fa-user-check text-detail me-1"></i>
                                        1 Person (You)
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="fw-bold mb-4">Selected Add-On Packages</h4>
                        @forelse($addon as $item)
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-bold">{{ $item->judul_paket }}</div>
                                            <small class="text-muted-detail">
                                                Rp {{ number_format($item->harga_paket, 0, ',', '.') }}
                                            </small>
                                        </div>
                                        <div class="fw-bold text-detail">
                                            Rp {{ number_format($item->harga_paket, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light-warning">No add-on package selected.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top:100px">
                    <div class="card-header bg-detail">
                        <h3 class="text-white mb-0 pt-5">Payment Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Event Registration</span>
                            <strong>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Add-On Package</span>
                            <strong>Rp {{ number_format($subtotalAddon, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span class="fw-bold fs-4">Total</span>
                            <span class="fw-bold fs-1 text-detail">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>

                        @if($snapToken)
                        <button id="btnPayNow" class="btn bg-detail text-white w-100 py-3">
                            <i class="fa-solid fa-credit-card me-2 text-white"></i>
                            Proceed To Payment
                        </button>
                        <div id="paymentStatus" class="mt-3 d-none"></div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            Gagal memuat payment gateway. Silakan refresh halaman.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($snapToken)
@php
    $isSandbox  = !($midtransConfig->is_production ?? false);
    $clientKey  = $midtransConfig->client_key ?? '';
    $snapDomain = $isSandbox ? 'app.sandbox.midtrans.com' : 'app.midtrans.com';
@endphp
<script src="https://{{ $snapDomain }}/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script>
const SNAP_TOKEN = '{{ $snapToken }}';
const ORDER_ID   = '{{ $orderId }}';
const CHECK_URL  = '{{ route('cart.check-payment') }}';
const SUCCESS_URL = '{{ route('cart-payment.success') }}';
const CSRF_TOKEN = '{{ csrf_token() }}';

let pollingInterval = null;

function startPolling() {
    $('#paymentStatus').removeClass('d-none').html(
        '<div class="d-flex align-items-center gap-2 text-muted"><span class="spinner-border spinner-border-sm"></span><span>Menunggu konfirmasi pembayaran...</span></div>'
    );
    pollingInterval = setInterval(function () {
        $.ajax({
            url: CHECK_URL,
            type: 'POST',
            data: { _token: CSRF_TOKEN, order_id: ORDER_ID },
            success: function (res) {
                if (res.status === 'paid') {
                    clearInterval(pollingInterval);
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Anda telah terdaftar untuk event ini.',
                        confirmButtonColor: '#E62020',
                        allowOutsideClick: false
                    }).then(() => { window.location.href = SUCCESS_URL; });
                } else if (res.status === 'failed') {
                    clearInterval(pollingInterval);
                    $('#paymentStatus').html(
                        '<div class="alert alert-danger mt-2"><i class="fa-solid fa-circle-xmark me-2"></i>Pembayaran gagal atau dibatalkan. Silakan coba lagi.</div>'
                    );
                    $('#btnPayNow').prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i>Coba Bayar Lagi');
                }
            }
        });
    }, 3000);
}

$('#btnPayNow').on('click', function () {
    $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading payment...');
    snap.pay(SNAP_TOKEN, {
        onSuccess: function (result) { startPolling(); },
        onPending: function (result) { startPolling(); },
        onError: function (result) {
            $('#btnPayNow').prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i>Proceed To Payment');
            Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: 'Terjadi kesalahan saat proses pembayaran.', confirmButtonColor: '#E62020' });
        },
        onClose: function () {
            $('#btnPayNow').prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i>Proceed To Payment');
        }
    });
});
</script>
@endif

@include('layouts.footer-v2')
