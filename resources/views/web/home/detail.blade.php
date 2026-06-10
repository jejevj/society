@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    
    <div class="mx-4">
        <div class="py-8 px-4">
            <div class="row align-items-start">
                <div class="col-md-9 card">
                    <div class="card-body">
                        <h1 class="fw-bold mb-3">
                            {{ $detail->judul_event ?? 'Detail Event' }}
                        </h1>

                        <p class="text-muted-detail fs-6" style="text-align: justify;">
                            {{ $detail->keterangan_event ?? '-' }}
                        </p>

                        @if(!empty($detail->tanggal_awal_event))
                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <div class="px-4 py-3 rounded-3 bg-detail w-price">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fs-5"><i class="fa-regular fa-calendar-days me-2 fs-3 text-white"></i> Event Date</div>
                                            <div class="fw-bold">
                                                {{ \Carbon\Carbon::parse($detail->tanggal_awal_event)->format('d M Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($detail->tanggal_akhir_event)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-4 py-3 rounded-3  bg-detail w-price">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fs-5"><i class="fa-solid fa-money-bill-wave me-2 fs-3 text-white"></i> Price</div>
                                            <div class="fw-bold">
                                                 {{ number_format($detail->harga_event ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-4 py-3 rounded-3  bg-detail w-price">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fs-5"><i class="fa-solid fa-location-dot me-2 fs-3 text-white"></i> Location</div>
                                            <div class="fw-bold">
                                                 {{ $detail->lokasi_event ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif
                    </div>
                    <div class="row card-body">
                        <div class="col-md-12">
                            <div class="mb-8">
                                <h3 class="fw-bold mb-4">Programs Event</h3>
                                <div class="accordion" id="accordionProgram">
                                    @forelse($program as $key => $pr)

                                        <div class="accordion-item mb-3 border rounded">
                                            <h2 class="accordion-header" id="heading{{ $key }}">
                                                <button class="accordion-button collapsed fw-bold"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $key }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse{{ $key }}">
                                                    Day {{ $pr->hari_program ?? '-' }}
                                                </button>
                                            </h2>

                                            <div id="collapse{{ $key }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $key }}"
                                                data-bs-parent="#accordionProgram">

                                                <div class="accordion-body p-0">

                                                    @if(!empty($pr->program) && count($pr->program))
                                                        <ul class="list-group list-group-flush">
                                                            @foreach($pr->program as $d)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <span>
                                                                        {{ $d->sesi_program_detail ?? '-' }} <br>
                                                                        <span class="small text-muted-detail">{{ $d->keterangan_program_detail ?? '-' }}</span>
                                                                    </span>

                                                                    <span class="badge badge-light-primary">
                                                                        {{ $d->awal_program_detail ?? '' }}
                                                                        -
                                                                        {{ $d->akhir_program_detail ?? '' }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="p-4 text-muted">
                                                            -
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <p class="text-muted">-</p>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-8">
                                <h3 class="fw-bold mb-4">Collaborators</h3>
                                <div class="row">
                                    @forelse($kolaborasi as $k)
                                        <div class="col-md-4 mb-4">
                                            <div class="card border-0 shadow-sm text-center p-3 h-100">

                                                <h6 class="fw-bold">
                                                    {{ $k->nama_kolaborasi ?? '-' }}
                                                </h6>

                                                <small class="text-muted-detail" style="text-align:justify;">
                                                    {{ $k->keterangan_kolaborasi ?? '-' }}
                                                </small>

                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">-</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="container mb-10">
                                <div class="mb-8">
                                    <h3 class="fw-bold mb-4">Add On Package</h3>
                                    <div class="row">
                                        @forelse($paket as $p)
                                            <div class="col-md-3 mb-4">
                                                <div class="card shadow-sm h-100 border-0">
                                                    <div class="card-body d-flex flex-column">
                                                        <h5 class="fw-bold">{{ $p->judul_paket ?? '-' }}</h5>

                                                        <div class="text-muted-detail mb-3 flex-grow-1"
                                                            style="max-height: 120px; overflow-y: auto; text-align: justify;">
                                                            {{ $p->keterangan_paket ?? '-' }}
                                                        </div>

                                                        <div class="fw-bold text-detail mt-auto">
                                                            <i class="fa-regular fa-money-bill-1 text-detail"></i>
                                                            {{ number_format($p->harga_paket ?? 0, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted"></p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                        <div class="card-header bg-detail">
                            <h3 class="fw-bold mb-0 pt-6 text-white">Registration</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Event Price
                                </label>

                                <div class="fs-2 fw-bold text-detail">
                                    <span >
                                        {{ number_format($detail->harga_event ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            @if(!$is_registered)
                                <div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold mb-3">
                                            Number of Participants
                                        </label>

                                        <div class="qty-wrapper">
                                            <button type="button" class="qty-btn" id="btn-minus">
                                                <i class="fa fa-minus"></i>
                                            </button>

                                            <input type="number"
                                                id="qty"
                                                class="qty-input"
                                                value="1"
                                                min="1"
                                                step="1">

                                            <button type="button" class="qty-btn" id="btn-plus">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <small class="text-muted mt-2 d-block">
                                            Select the number of participants.
                                        </small>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fw-semibold">Total</span>
                                    <span class="fw-bold fs-3 text-detail" id="total-harga">
                                        Rp {{ number_format($detail->harga_event ?? 0,0,',','.') }}
                                    </span>
                                </div>

                                <form id="formCart">
                                    @csrf

                                    <input type="hidden"
                                        name="kode_event"
                                        value="{{ $detail->kode_event }}">

                                    <input type="hidden"
                                        name="quantity"
                                        id="quantity_submit"
                                        value="1">

                                    <button type="submit"
                                            class="btn bg-detail w-100 py-3">
                                        <i class="fa fa-cart-shopping me-2 text-white"></i>
                                        Buy Now
                                    </button>
                                </form>
                            @else
                                <hr>

                                <button type="button"
                                        class="btn btn-secondary w-100 py-3"
                                        disabled>
                                    <i class="fa fa-circle-check me-2"></i>
                                    Terdaftar
                                </button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
@if(!$is_registered)
$('#formCart').on('submit', function(e){

    e.preventDefault();

    $.ajax({
        url: "{{ route('event.add.cart') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res){

            if(res.status){

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message,
                    confirmButtonColor: '#ff7a00'
                }).then((result) => {
                    if(result.isConfirmed){
                        window.location.href = "{{ url(env('APP_ROUTE') . '/event-cart') }}/" + res.kode_cart;
                    }
                });
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: res.message,
                    confirmButtonColor: '#ff7a00'
                });

            }
        },
        error: function(){

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to process request.'
            });

        }
    });

});

document.addEventListener('DOMContentLoaded', function() {

    const harga = {{ (int)($detail->harga_event ?? 0) }};
    const qty = document.getElementById('qty');
    const total = document.getElementById('total-harga');
    const qtySubmit = document.getElementById('quantity_submit');

    function hitungTotal() {
        let jumlah = parseInt(qty.value) || 1;

        if (jumlah < 1) {
            jumlah = 1;
            qty.value = 1;
        }

        qtySubmit.value = jumlah;

        total.innerHTML = 'Rp ' + (harga * jumlah).toLocaleString('id-ID');
    }

    document.getElementById('btn-plus').addEventListener('click', function() {
        qty.value = parseInt(qty.value) + 1;
        hitungTotal();
    });

    document.getElementById('btn-minus').addEventListener('click', function() {
        if (parseInt(qty.value) > 1) {
            qty.value = parseInt(qty.value) - 1;
            hitungTotal();
        }
    });

    qty.addEventListener('input', hitungTotal);

    hitungTotal();
});
@endif
</script>
@include('layouts.footer-v2')
