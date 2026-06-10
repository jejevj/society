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
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-4">Total</span>
                            <span class="fw-bold fs-1 text-detail">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <button class="btn bg-detail text-white w-100 py-3 mt-5">
                            <i class="fa-solid fa-credit-card me-2 text-white"></i>
                            Proceed To Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer-v2')
