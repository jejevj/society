@extends('web.layouts.app')

@section('title', 'Participant Data')

@section('content')
<div class="container py-5">

    {{-- Progress Steps --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-center gap-0">
                {{-- Step 1 --}}
                <div class="d-flex flex-column align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white fw-bold"
                         style="width:36px;height:36px;font-size:14px;">&#10003;</div>
                    <small class="text-success fw-semibold mt-1" style="font-size:11px;">Cart &amp; Package</small>
                </div>
                <div class="flex-grow-1 border-top border-2 border-success mx-2" style="max-width:80px;"></div>
                {{-- Step 2 (active) --}}
                <div class="d-flex flex-column align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:36px;height:36px;font-size:14px;background:#0d6efd;">2</div>
                    <small class="text-primary fw-semibold mt-1" style="font-size:11px;">Participants</small>
                </div>
                <div class="flex-grow-1 border-top border-2 border-secondary mx-2" style="max-width:80px;"></div>
                {{-- Step 3 --}}
                <div class="d-flex flex-column align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold border border-secondary text-secondary"
                         style="width:36px;height:36px;font-size:14px;">3</div>
                    <small class="text-secondary mt-1" style="font-size:11px;">Checkout</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
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
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="fw-semibold">
                            <i class="fas fa-user me-2"></i>
                            Participant {{ $i + 1 }}
                            @if ($qty > 1) <span class="badge bg-white text-primary ms-1">{{ $i + 1 }}/{{ $qty }}</span> @endif
                        </span>
                        @if ($i === 0)
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="checkbox" id="sameAsUser" onclick="fillFromUser(this)">
                            <label class="form-check-label text-white small" for="sameAsUser">
                                Same as my account data
                            </label>
                        </div>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="participants[{{ $i }}][nama]"
                                       id="p_nama_{{ $i }}"
                                       class="form-control @error('participants.'.$i.'.nama') is-invalid @enderror"
                                       value="{{ old('participants.'.$i.'.nama', $participants[$i]->nama_peserta ?? '') }}"
                                       placeholder="Full name"
                                       required>
                                @error('participants.'.$i.'.nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       name="participants[{{ $i }}][email]"
                                       id="p_email_{{ $i }}"
                                       class="form-control @error('participants.'.$i.'.email') is-invalid @enderror"
                                       value="{{ old('participants.'.$i.'.email', $participants[$i]->email_peserta ?? '') }}"
                                       placeholder="email@example.com"
                                       required>
                                @error('participants.'.$i.'.email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="participants[{{ $i }}][no_hp]"
                                       id="p_no_hp_{{ $i }}"
                                       class="form-control @error('participants.'.$i.'.no_hp') is-invalid @enderror"
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
                                       class="form-control"
                                       value="{{ old('participants.'.$i.'.instansi', $participants[$i]->instansi_peserta ?? '') }}"
                                       placeholder="Company / university (optional)">
                            </div>
                        </div>
                    </div>
                </div>
                @endfor

                <div class="d-flex justify-content-between mt-2">
                    <a href="{{ route('event-cart', $cart->kode_cart) }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary px-5">
                        Continue to Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- SIDEBAR SUMMARY --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top:80px;">
                <div class="card-header bg-light py-3 px-4">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2 text-primary"></i>Order Summary</h6>
                </div>
                <div class="card-body px-4 py-3">
                    <p class="fw-semibold mb-1">{{ $cart->judul_event }}</p>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $cart->lokasi_event }}<br>
                        <i class="fas fa-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($cart->tanggal_awal_event)->format('d M Y') }}
                        @if($cart->tanggal_akhir_event && $cart->tanggal_akhir_event != $cart->tanggal_awal_event)
                         – {{ \Carbon\Carbon::parse($cart->tanggal_akhir_event)->format('d M Y') }}
                        @endif
                    </p>

                    <hr>
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Event Fee × {{ $qty }}</span>
                        <span>Rp {{ number_format($cart->harga_event * $qty, 0, ',', '.') }}</span>
                    </div>

                    @if ($addon->count())
                        @foreach ($addon as $ad)
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ $ad->judul_paket }} × {{ $qty }}</span>
                            <span>Rp {{ number_format($ad->harga_paket * $qty, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    @endif

                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-3 p-2 bg-light rounded small text-muted">
                        <i class="fas fa-users me-1"></i>{{ $qty }} participant(s)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data user dari server (aman, tidak expose password)
const userData = {
    nama     : @json($user->nama_user     ?? ''),
    email    : @json($user->email_user    ?? ''),
    no_hp    : @json($user->no_hp_user    ?? ''),
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
@endpush
