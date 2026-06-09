@include('layouts.header-v2')
<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">
        <div class="d-flex justify-content-between align-items-center mb-8">
            <div>
                <h1 class="fw-bold mb-1">
                    <i class="fa-solid fa-cart-shopping text-detail me-2"></i>
                    My Cart
                </h1>

                <div class="text-muted">
                    Review your selected events before checkout
                </div>
            </div>
        </div>

        @if($cart->count())
            <div class="row">
                @foreach($cart as $item)
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-5">
                                <div class="row align-items-center">
                                    <div class="col-lg-7">
                                        <h3 class="fw-bold mb-2">
                                            {{ $item->judul_event }}
                                        </h3>
                                        <div class="mb-2 text-muted-detail">
                                            <i class="fa-solid fa-location-dot me-2"></i>
                                            {{ $item->lokasi_event }}
                                        </div>
                                        <div class="mb-2 text-muted-detail">
                                            <i class="fa-regular fa-calendar-days me-2"></i>
                                            {{ \Carbon\Carbon::parse($item->tanggal_awal_event)->format('d M Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item->tanggal_akhir_event)->format('d M Y') }}
                                        </div>
                                        <div class="border rounded p-3 mt-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                <div>
                                                    <small class="text-muted d-block">
                                                        Participants
                                                    </small>
                                                    <div class="d-flex align-items-center gap-2 mt-2">
                                                        <button type="button"
                                                                class="btn btn-sm btn-light btn-minus"
                                                                data-cart="{{ $item->kode_cart }}">
                                                            -
                                                        </button>
                                                        <input
                                                            type="number"
                                                            class="form-control text-center qty-input"
                                                            value="{{ $item->qty }}"
                                                            min="1"
                                                            data-cart="{{ $item->kode_cart }}"
                                                            data-harga="{{ $item->harga_event }}"
                                                            style="width:80px">

                                                        <button type="button"
                                                                class="btn btn-sm btn-light btn-plus"
                                                                data-cart="{{ $item->kode_cart }}">
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button
                                                        type="button"
                                                        class="btn bg-detail btn-sm btnUpdateCart"
                                                        data-cart="{{ $item->kode_cart }}">
                                                        <i class="fa fa-save me-1 text-white"></i>
                                                        Update
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-dark btn-sm btnDeleteCart"
                                                        data-cart="{{ $item->kode_cart }}">
                                                        <i class="fa fa-trash me-1"></i>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5 text-lg-end">
                                        <div class="mb-2">
                                            <small class="text-muted-detail">
                                                Event
                                            </small>
                                            <div>
                                                Rp {{ number_format($item->subtotal,0,',','.') }}
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <small class="text-muted-detail">
                                                Add-On Package
                                            </small>
                                            <div>
                                                Rp {{ number_format($item->total_paket * $item->qty,0,',','.') }}
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="text-muted-detail mb-1 fs-4">
                                            Total Price
                                        </div>
                                        <div
                                            class="fw-bold fs-4 text-detail total-cart"
                                            id="total-{{ $item->kode_cart }}"
                                            data-addon="{{ $item->total_paket }}">
                                            Rp {{ number_format($item->subtotal + ($item->total_paket * $item->qty),0,',','.') }}
                                        </div>
                                        <a href="{{ url(env('APP_ROUTE').'/event-cart/'.$item->kode_cart) }}"
                                           class="btn bg-detail text-white mt-4">
                                            <i class="fa-solid fa-arrow-right me-2 text-white"></i>
                                            Continue Checkout
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-15">
                    <i class="fa-solid fa-cart-shopping text-muted fs-5x mb-5"></i>
                    <h3 class="fw-bold">
                        Your cart is empty
                    </h3>
                    <p class="text-muted">
                        You haven't added any event yet.
                    </p>
                    <a href="{{ url(env('APP_ROUTE').'/home') }}"
                       class="btn bg-detail text-white">
                        Browse Events
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
<script>
$(document).on('click','.btn-plus',function(){

    let input = $(this).siblings('.qty-input');

    input.val(
        parseInt(input.val()) + 1
    ).trigger('keyup');

});

$(document).on('click','.btn-minus',function(){

    let input = $(this).siblings('.qty-input');

    let qty = parseInt(input.val());

    if(qty > 1){
        input.val(qty - 1).trigger('keyup');
    }

});

$(document).on('keyup change','.qty-input',function(){

    let qty = parseInt($(this).val()) || 1;

    let harga = parseInt($(this).data('harga'));

    let cart = $(this).data('cart');

    let addon = parseInt(
        $('#total-'+cart).data('addon')
    ) || 0;

    let total =
        (harga * qty)
        +
        (addon * qty);

    $('#total-'+cart).html(
        'Rp ' + total.toLocaleString('id-ID')
    );

});

$('.btnUpdateCart').click(function(){

    let cart = $(this).data('cart');

    let qty = $('.qty-input[data-cart="'+cart+'"]').val();

    $.post(
        "{{ route('updateCartEvent') }}",
        {
            _token:'{{ csrf_token() }}',
            kode_cart:cart,
            qty:qty
        },
        function(res){

            if(res.status){

                Swal.fire({
                    icon:'success',
                    title:'Success',
                    text:res.message
                }).then(()=>{
                    location.reload();
                });

            }

        }
    );

});

$('.btnDeleteCart').click(function(){

    let cart = $(this).data('cart');

    Swal.fire({
        title:'Delete Cart?',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Delete'
    }).then((result)=>{

        if(result.isConfirmed){

            $.post(
                "{{ route('deleteCartEvent') }}",
                {
                    _token:'{{ csrf_token() }}',
                    kode_cart:cart
                },
                function(res){

                    if(res.status){

                        Swal.fire({
                            icon:'success',
                            title:'Success',
                            text:res.message
                        }).then(()=>{
                            location.reload();
                        });

                    }

                }
            );

        }

    });

});
</script>
@include('layouts.footer-v2')