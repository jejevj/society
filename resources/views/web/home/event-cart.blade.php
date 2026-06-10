@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">

        {{-- Progress Steps --}}
        <div class="d-flex align-items-center justify-content-center mb-8 gap-3">
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-detail px-3 py-2">1</span>
                <span class="ms-2 fw-bold text-detail">Add-On</span>
            </div>
            <div class="border-top border-2 flex-grow-1 mx-2" style="max-width:60px"></div>
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-secondary px-3 py-2">2</span>
                <span class="ms-2 text-muted">Participant Data</span>
            </div>
            <div class="border-top border-2 flex-grow-1 mx-2" style="max-width:60px"></div>
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-secondary px-3 py-2">3</span>
                <span class="ms-2 text-muted">Checkout</span>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">
                            <i class="fa-solid fa-box-open text-detail me-2"></i>
                            Select Add-On Package
                        </h2>
                        <a href="{{ route('my-cart') }}" class="btn btn-light-warning">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back to Cart
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="formPaket">
                            @csrf
                            <input type="hidden" name="kode_cart" value="{{ $cart->kode_cart }}">
                            <div class="row">
                                @forelse($paket as $item)
                                    <div class="col-md-6 mb-4">
                                        <label class="paket-card w-100 h-100 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                class="paket-checkbox"
                                                name="paket[]"
                                                value="{{ $item->kode_paket }}"
                                                data-harga="{{ $item->harga_paket }}"
                                                {{ in_array($item->kode_paket, $selectedPaket ?? []) ? 'checked' : '' }}>
                                            <div class="paket-content">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="fw-bold mb-2">{{ $item->judul_paket }}</h5>
                                                        <div class="text-muted-detail small paket-desc">
                                                            {{ $item->keterangan_paket }}
                                                        </div>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" disabled>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="fw-bold fs-4 text-detail">
                                                    Rp {{ number_format($item->harga_paket, 0, ',', '.') }}
                                                </div>
                                                <small class="text-muted">
                                                    × {{ $cart->qty }} participant(s)
                                                </small>
                                                <div class="fw-bold text-success mt-1">
                                                    Total: Rp {{ number_format($item->harga_paket * $cart->qty, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-light-warning">
                                            No add-on package available.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top:100px">
                    <div class="card-header bg-detail">
                        <h3 class="text-white pt-5 mb-0">Booking Summary</h3>
                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold">{{ $cart->judul_event }}</h4>
                        <div class="text-muted-detail mb-4">{{ $cart->lokasi_event }}</div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Participants</span>
                            <strong>{{ $cart->qty }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Event Price</span>
                            <strong>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Add-On</span>
                            <strong id="addon-total">Rp 0</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-5">Total Payment</span>
                            <span class="fw-bold fs-2 text-detail" id="grand-total">
                                Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <button type="button" id="btnContinue" class="btn bg-detail text-white w-100 mt-5 py-3">
                            <i class="fa fa-arrow-right me-2"></i>Save & Fill Participant Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#btnContinue').on('click', function() {
    let btn = $(this);
    $.ajax({
        url: "{{ route('savePackageCart') }}",
        type: "POST",
        data: $('#formPaket').serialize(),
        beforeSend: function() {
            btn.prop('disabled', true);
            btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
        },
        success: function(res) {
            if (res.status) {
                // Redirect ke participant form, bukan langsung checkout
                window.location.href = "{{ url(env('APP_ROUTE') . '/participant-form') }}/" + res.kode_cart;
            } else {
                Swal.fire({ icon: 'warning', title: 'Warning', text: res.message, confirmButtonColor: '#ff7a00' });
            }
        },
        error: function(xhr) {
            let message = xhr.responseJSON?.message ?? 'Failed to process request';
            Swal.fire({ icon: 'error', title: 'Error', text: message, confirmButtonColor: '#ff7a00' });
        },
        complete: function() {
            btn.prop('disabled', false);
            btn.html('<i class="fa fa-arrow-right me-2"></i>Save & Fill Participant Data');
        }
    });
});

let eventTotal = {{ $cart->subtotal }};
let qty = {{ $cart->qty }};

function hitungAddon() {
    let addon = 0;
    $('.paket-checkbox:checked').each(function() {
        let hargaPaket = parseInt($(this).data('harga')) || 0;
        addon += (hargaPaket * qty);
    });
    $('#addon-total').html('Rp ' + addon.toLocaleString('id-ID'));
    $('#grand-total').html('Rp ' + (eventTotal + addon).toLocaleString('id-ID'));
}

$(document).on('change', '.paket-checkbox', function() { hitungAddon(); });
hitungAddon();
</script>
@include('layouts.footer-v2')
