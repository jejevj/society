@include('layouts.header-v2')

<style>
.reg-fullscreen { position: relative; min-height: 100vh; }
.reg-fullscreen::before {
    content: ''; position: fixed; inset: 0; z-index: -2;
    background-image: url('{{ asset("storage/" . ($set->gambar_login ?? "")) }}');
    background-size: cover; background-position: center;
}
.reg-fullscreen::after {
    content: ''; position: fixed; inset: 0; z-index: -1;
    background: rgba(10,10,30,0.82);
}
.reg-content { position:relative;z-index:1;display:flex;align-items:center;padding-top:120px;padding-bottom:60px;min-height:100vh; }

/* Step indicator */
.step-bar { display:flex; align-items:center; gap:0; margin-bottom:28px; }
.step-item { display:flex; flex-direction:column; align-items:center; flex:1; position:relative; }
.step-circle {
    width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-weight:800; font-size:0.88rem; border:2.5px solid; transition:all 0.3s;
    position:relative; z-index:1;
}
.step-circle.done   { background:#22c55e; border-color:#22c55e; color:#fff; }
.step-circle.active { background:#E62020; border-color:#E62020; color:#fff; }
.step-circle.idle   { background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.25); color:rgba(255,255,255,0.5); }
.step-label { font-size:0.68rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; margin-top:6px; text-align:center; }
.step-label.done   { color:#22c55e; }
.step-label.active { color:#fff; }
.step-label.idle   { color:rgba(255,255,255,0.35); }
.step-connector { flex:1; height:2px; background:rgba(255,255,255,0.12); margin:0 -1px; margin-top:-22px; position:relative; z-index:0; }
.step-connector.done { background:#22c55e; }

/* Card */
.reg-card { background:#fff; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,0.35); padding:36px 32px; }
.reg-card h2 { font-size:1.25rem; font-weight:800; color:#1a1a1a; margin-bottom:4px; }
.reg-card .subtitle { color:#999; font-size:0.82rem; margin-bottom:24px; }

/* OTP input boxes */
.otp-inputs { display:flex; gap:10px; justify-content:center; margin:20px 0; }
.otp-inputs input {
    width:50px; height:58px; text-align:center; font-size:1.6rem; font-weight:800;
    border:2px solid #e0e0e0; border-radius:10px; outline:none; transition:border-color 0.2s, box-shadow 0.2s;
    color:#1a1a1a;
}
.otp-inputs input:focus { border-color:#E62020; box-shadow:0 0 0 3px rgba(230,32,32,0.12); }
.otp-inputs input.filled { border-color:#E62020; background:#fff5f5; }

.btn-primary-red {
    background:#E62020; color:#fff; border:none; border-radius:10px; padding:12px;
    font-weight:700; font-size:0.94rem; width:100%; cursor:pointer; transition:background 0.2s;
    display:flex; align-items:center; justify-content:center; gap:8px;
}
.btn-primary-red:hover { background:#c41a1a; color:#fff; }

.resend-area { text-align:center; margin-top:14px; font-size:0.84rem; color:#888; }
.resend-area a { color:#E62020; font-weight:600; cursor:pointer; text-decoration:none; }
.resend-area a:hover { text-decoration:underline; }

.email-hint { background:#f8f8fb; border-radius:10px; padding:10px 14px; font-size:0.83rem; color:#555; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.email-hint i { color:#E62020; }

@media (max-width:767.98px) {
    .reg-content { padding-top:80px; }
    .reg-card { padding:22px 14px; }
    .otp-inputs input { width:42px; height:50px; font-size:1.3rem; }
    .left-col { display:none; }
}
</style>

<div class="reg-fullscreen">
<div class="reg-content">
<div class="container">
<div class="row justify-content-center align-items-start g-5">

    {{-- Left info --}}
    <div class="col-md-6 col-lg-5 left-col">
        @if(isset($event) && $event)
            <span style="display:inline-block;background:rgba(255,255,255,0.12);color:#f8ee93;font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 14px;border-radius:20px;border:1px solid rgba(255,255,255,0.25);margin-bottom:18px;">Mendaftar untuk</span>
            <h1 style="font-size:2rem;font-weight:800;color:#fff;line-height:1.25;margin-bottom:6px;">{{ $event->judul_event }}</h1>
            <div style="color:rgba(255,255,255,0.6);font-size:0.95rem;margin-bottom:24px;">{{ $event->sub_judul_event }}</div>
        @endif

        {{-- Step indicator kiri --}}
        <div style="border-top:1px solid rgba(255,255,255,0.12);padding-top:24px;">
        @php $steps = [['label'=>'Data Diri','done'=>true],['label'=>'Verifikasi OTP','active'=>true],['label'=>'Add-on','idle'=>true],['label'=>'Pembayaran','idle'=>true]]; @endphp
        <div class="step-bar">
            @foreach($steps as $i => $s)
                @if($i > 0)<div class="step-connector {{ $s['done'] ?? false ? 'done' : '' }}"></div>@endif
                <div class="step-item">
                    <div class="step-circle {{ isset($s['done']) ? 'done' : (isset($s['active']) ? 'active' : 'idle') }}">
                        @if(isset($s['done']))<i class="fa-solid fa-check" style="font-size:0.9rem;"></i>@else{{ $i+1 }}@endif
                    </div>
                    <span class="step-label {{ isset($s['done']) ? 'done' : (isset($s['active']) ? 'active' : 'idle') }}">{{ $s['label'] }}</span>
                </div>
            @endforeach
        </div>
        </div>
    </div>

    {{-- Right: OTP card --}}
    <div class="col-md-6 col-lg-5">
        <div class="reg-card">
            @if($set && $set->logo_app)
                <img src="{{ asset('storage/' . $set->logo_app) }}" alt="Logo" style="width:44px;height:44px;object-fit:contain;margin-bottom:12px;">
            @endif
            <h2>Verifikasi Email</h2>
            <p class="subtitle">Masukkan kode 6 digit yang telah dikirim ke email Anda</p>

            @if(session('error'))
                <div class="alert alert-danger alert-sm py-2" style="font-size:0.85rem;">{{ session('error') }}</div>
            @endif

            <div class="email-hint">
                <i class="fa-solid fa-envelope"></i>
                <span>Kode dikirim ke <strong>{{ $data['email'] ?? '' }}</strong></span>
            </div>

            <form action="{{ route('register-event.verify-otp') }}" method="POST" id="formOtp">
                @csrf
                <div class="otp-inputs">
                    @for($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" autocomplete="off">
                    @endfor
                </div>
                <input type="hidden" name="otp" id="otpHidden">

                <button type="submit" class="btn-primary-red mt-2" id="btnVerify">
                    <i class="fa-solid fa-shield-check"></i> Verifikasi OTP
                </button>
            </form>

            <div class="resend-area">
                Tidak terima kode? <a id="btnResend">Kirim Ulang OTP</a>
                <span id="resendMsg" style="display:none;color:#22c55e;font-weight:600;"></span>
            </div>

            {{-- Mobile step bar --}}
            <div class="d-md-none" style="margin-top:24px;">
                <div class="step-bar" style="filter:invert(0);">
                    @php $msteps = [['done'=>true,'label'=>'Data'],['active'=>true,'label'=>'OTP'],['idle'=>true,'label'=>'Add-on'],['idle'=>true,'label'=>'Bayar']]; @endphp
                    @foreach($msteps as $mi => $ms)
                        @if($mi > 0)<div class="step-connector {{ $ms['done'] ?? false ? 'done' : '' }}" style="background:{{ (isset($ms['done'])) ? '#22c55e' : '#e0e0e0' }};"></div>@endif
                        <div class="step-item">
                            <div class="step-circle {{ isset($ms['done']) ? 'done' : (isset($ms['active']) ? 'active' : 'idle') }}" style="{{ isset($ms['idle']) ? 'background:#f0f0f0;border-color:#ddd;color:#aaa;' : '' }}">
                                @if(isset($ms['done']))<i class="fa-solid fa-check" style="font-size:0.8rem;"></i>@else{{ $mi+1 }}@endif
                            </div>
                            <span style="font-size:0.62rem;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;margin-top:4px;color:{{ isset($ms['done']) ? '#22c55e' : (isset($ms['active']) ? '#E62020' : '#aaa') }};">{{ $ms['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
</div>

<script>
// OTP digit navigation
const digits  = document.querySelectorAll('.otp-digit');
const hidden  = document.getElementById('otpHidden');
const form    = document.getElementById('formOtp');

digits.forEach((input, idx) => {
    input.addEventListener('input', e => {
        input.value = input.value.replace(/[^0-9]/g, '').slice(-1);
        input.classList.toggle('filled', input.value !== '');
        if (input.value && idx < digits.length - 1) digits[idx + 1].focus();
        syncHidden();
    });
    input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !input.value && idx > 0) digits[idx - 1].focus();
    });
    input.addEventListener('paste', e => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        [...text].slice(0, 6).forEach((ch, i) => { if (digits[i]) { digits[i].value = ch; digits[i].classList.add('filled'); } });
        syncHidden();
        const next = [...digits].findIndex(d => !d.value);
        if (next !== -1) digits[next].focus(); else digits[5].focus();
    });
});

function syncHidden() {
    hidden.value = [...digits].map(d => d.value).join('');
}

form.addEventListener('submit', e => {
    syncHidden();
    if (hidden.value.length < 6) {
        e.preventDefault();
        alert('Masukkan 6 digit kode OTP.');
    }
});

// Resend OTP
document.getElementById('btnResend').addEventListener('click', function () {
    const btn = this;
    btn.style.pointerEvents = 'none';
    btn.textContent = 'Mengirim...';
    fetch('{{ route("register-event.resend-otp") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(res => {
        const msg = document.getElementById('resendMsg');
        msg.textContent = res.message;
        msg.style.display = 'inline';
        msg.style.color = res.success ? '#22c55e' : '#E62020';
        btn.textContent = 'Kirim Ulang OTP';
        btn.style.pointerEvents = 'auto';
        setTimeout(() => { msg.style.display = 'none'; }, 5000);
    });
});
</script>

@include('layouts.footer-v2')
