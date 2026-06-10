@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">

        {{-- Progress Steps --}}
        <div class="d-flex align-items-center justify-content-center mb-8 gap-3">
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-success px-3 py-2">&#10003;</span>
                <span class="ms-2 fw-bold text-success">Add-On</span>
            </div>
            <div class="border-top border-2 border-success flex-grow-1 mx-2" style="max-width:60px"></div>
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

        <div class="row">
            {{-- FORM COLUMN --}}
            <div class="col-lg-8">

                <form action="{{ route('save-participants') }}" method="POST" id="formParticipants">
                    @csrf
                    <input type="hidden" name="kode_cart" value="{{ $cart->kode_cart }}">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @for ($i = 0; $i < $qty; $i++)
                    <div class="card shadow-sm border-0 mb-5">
                        <div class="card-header border-0 pt-5 pb-3 d-flex justify-content-between align-items-center">
                            <h4 class="fw-bold mb-0">
                                <i class="fa-solid fa-user text-detail me-2"></i>
                                Participant {{ $i + 1 }}
                                @if ($qty > 1)
                                    <span class="badge bg-light text-detail ms-2 fs-7">{{ $i + 1 }} / {{ $qty }}</span>
                                @endif
                            </h4>
                            @if ($i === 0)
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="checkbox" id="sameAsUser" onchange="fillFromUser(this)">
                                <label class="form-check-label fw-semibold text-muted" for="sameAsUser">
                                    Same as my account data
                                </label>
                            </div>
                            @endif
                        </div>
                        <div class="card-body pt-2 pb-6 px-6">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label required fw-semibold">Full Name</label>
                                    <input type="text"
                                           name="participants[{{ $i }}][nama]"
                                           id="p_nama_{{ $i }}"
                                           class="form-control form-control-solid @error('participants.'.$i.'.nama') is-invalid @enderror"
                                           value="{{ old('participants.'.$i.'.nama', $participants[$i]->nama_peserta ?? '') }}"
                                           placeholder="Enter full name"
                                           required>
                                    @error('participants.'.$i.'.nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required fw-semibold">Email Address</label>
                                    <input type="email"
                                           name="participants[{{ $i }}][email]"
                                           id="p_email_{{ $i }}"
                                           class="form-control form-control-solid @error('participants.'.$i.'.email') is-invalid @enderror"
                                           value="{{ old('participants.'.$i.'.email', $participants[$i]->email_peserta ?? '') }}"
                                           placeholder="email@example.com"
                                           required>
                                    @error('participants.'.$i.'.email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required fw-semibold">Phone Number</label>
                                    <input type="text"
                                           name="participants[{{ $i }}][no_hp]"
                                           id="p_no_hp_{{ $i }}"
                                           class="form-control form-control-solid @error('participants.'.$i.'.no_hp') is-invalid @enderror"
                                           value="{{ old('participants.'.$i.'.no_hp', $participants[$i]->no_hp_peserta ?? '') }}"
                                           placeholder="08xxxxxxxxxx"
                                           required>
                                    @error('participants.'.$i.'.no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Institution / Organization</label>
                                    <input type="text"
                                           name="participants[{{ $i }}][instansi]"
                                           id="p_instansi_{{ $i }}"
                                           class="form-control form-control-solid"
                                           value="{{ old('participants.'.$i.'.instansi', $participants[$i]->instansi_peserta ?? '') }}"
                                           placeholder="Company / university (optional)">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('event-cart', $cart->kode_cart) }}" class="btn btn-light-warning px-6">
                            <i class="fa fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn bg-detail text-white px-8">
                            Continue to Checkout <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>

            </div>

            {{-- SIDEBAR SUMMARY --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top:100px">
                    <div class="card-header bg-detail">
                        <h3 class="text-white pt-5 mb-0">
                            <i class="fa fa-receipt me-2"></i>Booking Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold">{{ $cart->judul_event }}</h4>
                        <div class="text-muted mb-1">
                            <i class="fa fa-map-marker-alt me-1"></i>{{ $cart->lokasi_event }}
                        </div>
                        <div class="text-muted mb-4 small">
                            <i class="fa fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($cart->tanggal_awal_event)->format('d M Y') }}
                            @if($cart->tanggal_akhir_event && $cart->tanggal_akhir_event != $cart->tanggal_awal_event)
                             &ndash; {{ \Carbon\Carbon::parse($cart->tanggal_akhir_event)->format('d M Y') }}
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Participants</span>
                            <strong>{{ $qty }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Event Fee</span>
                            <strong>Rp {{ number_format($cart->harga_event * $qty, 0, ',', '.') }}</strong>
                        </div>

                        @if ($addon->count())
                            @foreach ($addon as $ad)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">+ {{ $ad->judul_paket }}</span>
                                <strong class="small">Rp {{ number_format($ad->harga_paket * $qty, 0, ',', '.') }}</strong>
                            </div>
                            @endforeach
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-5">Total Payment</span>
                            <span class="fw-bold fs-2 text-detail">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <i class="fa fa-info-circle me-1 text-detail"></i>
                                Please fill in data for all <strong>{{ $qty }} participant(s)</strong> before proceeding.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- userData inject dari kolom yang benar di app_user --}}
<script>
const userData = {
    nama     : @json($user->nama_user      ?? ''),
    email    : @json($user->username_user  ?? ''),
    no_hp    : @json($user->telepon_user   ?? ''),
    instansi : @json($user->organisasi_user ?? ''),
};

function fillFromUser(checkbox) {
    if (checkbox.checked) {
        document.getElementById('p_nama_0').value     = userData.nama;
        document.getElementById('p_email_0').value    = userData.email;
        document.getElementById('p_no_hp_0').value    = userData.no_hp;
        document.getElementById('p_instansi_0').value = userData.instansi;
    } else {
        document.getElementById('p_nama_0').value     = '';
        document.getElementById('p_email_0').value    = '';
        document.getElementById('p_no_hp_0').value    = '';
        document.getElementById('p_instansi_0').value = '';
    }
}
</script>

@include('layouts.footer-v2')
