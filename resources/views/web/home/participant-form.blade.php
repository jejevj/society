@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">

        {{-- Progress Steps --}}
        <div class="d-flex align-items-center justify-content-center mb-8 gap-3">
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-success px-3 py-2">1</span>
                <span class="ms-2 fw-semibold text-success">Add-On</span>
            </div>
            <div class="border-top border-2 flex-grow-1 mx-2" style="max-width:60px"></div>
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-detail px-3 py-2">2</span>
                <span class="ms-2 fw-bold text-detail">Participant Data</span>
            </div>
            <div class="border-top border-2 flex-grow-1 mx-2" style="max-width:60px"></div>
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-secondary px-3 py-2">3</span>
                <span class="ms-2 text-muted">Checkout</span>
            </div>
        </div>

        <form id="formParticipants" method="POST" action="{{ route('save-participants') }}">
            @csrf
            <input type="hidden" name="kode_cart" value="{{ $cart->kode_cart }}">

            <div class="row">
                <div class="col-lg-8">

                    @for($i = 1; $i <= $qty; $i++)
                    @php
                        $existing = $participants[$i-1] ?? null;
                    @endphp
                    <div class="card shadow-sm border-0 mb-5">
                        <div class="card-header bg-detail d-flex align-items-center justify-content-between">
                            <h4 class="text-white mb-0 pt-4 pb-2">
                                <i class="fa-solid fa-user me-2"></i>
                                Participant {{ $i }}
                                @if($i === 1)<span class="badge bg-warning text-dark ms-2 fs-8">Primary</span>@endif
                            </h4>
                            @if($i === 1)
                            <div class="form-check form-check-custom form-check-solid me-2 pt-4 pb-2">
                                <input class="form-check-input" type="checkbox" id="sameAsUser" value="1">
                                <label class="form-check-label text-white fw-semibold" for="sameAsUser">
                                    Same as my account data
                                </label>
                            </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">Full Name</label>
                                    <input type="text"
                                           name="participants[{{ $i }}][nama]"
                                           id="p{{ $i }}_nama"
                                           class="form-control form-control-solid"
                                           placeholder="Full name"
                                           value="{{ old('participants.'.$i.'.nama', $existing->nama_peserta ?? '') }}"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">Email</label>
                                    <input type="email"
                                           name="participants[{{ $i }}][email]"
                                           id="p{{ $i }}_email"
                                           class="form-control form-control-solid"
                                           placeholder="email@example.com"
                                           value="{{ old('participants.'.$i.'.email', $existing->email_peserta ?? '') }}"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">Phone Number</label>
                                    <input type="text"
                                           name="participants[{{ $i }}][no_hp]"
                                           id="p{{ $i }}_no_hp"
                                           class="form-control form-control-solid"
                                           placeholder="e.g. 08123456789"
                                           value="{{ old('participants.'.$i.'.no_hp', $existing->no_hp_peserta ?? '') }}"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Institution / Organization</label>
                                    <input type="text"
                                           name="participants[{{ $i }}][instansi]"
                                           id="p{{ $i }}_instansi"
                                           class="form-control form-control-solid"
                                           placeholder="Company / University / etc."
                                           value="{{ old('participants.'.$i.'.instansi', $existing->instansi_peserta ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('event-cart', $cart->kode_cart) }}" class="btn btn-light-secondary px-6">
                            <i class="fa fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn bg-detail text-white px-8 py-3" id="btnSubmitParticipants">
                            <i class="fa fa-arrow-right me-2"></i>Proceed to Checkout
                        </button>
                    </div>

                </div>

                {{-- Summary Sidebar --}}
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
                                <strong>{{ $qty }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Event Price</span>
                                <strong>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</strong>
                            </div>
                            @if(count($addon) > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Add-On</span>
                                <strong>Rp {{ number_format($addon->sum('harga_paket') * $qty, 0, ',', '.') }}</strong>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold fs-2 text-detail">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
// Checkbox "Same as my account data" — only for Participant 1
document.getElementById('sameAsUser').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('p1_nama').value    = '{{ addslashes($user->nama_user ?? '') }}';
        document.getElementById('p1_email').value   = '{{ addslashes($user->email_user ?? '') }}';
        document.getElementById('p1_no_hp').value   = '{{ addslashes($user->no_hp_user ?? '') }}';
        document.getElementById('p1_instansi').value = '{{ addslashes($user->organisasi_user ?? '') }}';
    } else {
        document.getElementById('p1_nama').value    = '';
        document.getElementById('p1_email').value   = '';
        document.getElementById('p1_no_hp').value   = '';
        document.getElementById('p1_instansi').value = '';
    }
});

// Prevent double submit
document.getElementById('formParticipants').addEventListener('submit', function() {
    let btn = document.getElementById('btnSubmitParticipants');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
});
</script>

@include('layouts.footer-v2')
