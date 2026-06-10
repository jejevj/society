@include('layouts.header-v2')

<style>
.register-fullscreen {
    position: relative;
    min-height: 100vh;
}
.register-fullscreen::before {
    content: '';
    position: fixed;
    inset: 0;
    z-index: -2;
    background-image: url('{{ asset('storage/'.$set->gambar_login) }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.register-fullscreen::after {
    content: '';
    position: fixed;
    inset: 0;
    z-index: -1;
    background: rgba(10, 10, 30, 0.85);
}
.register-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: flex-start;
    padding-top: 100px;
    padding-bottom: 60px;
    min-height: 100vh;
}
.event-info-col {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding-right: 40px;
    padding-top: 10px;
}
.event-badge {
    display: inline-block;
    background: rgba(255,255,255,0.12);
    color: #f8ee93;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    margin-bottom: 18px;
    width: fit-content;
}
.event-info-col h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.25;
    margin-bottom: 6px;
}
.event-info-col .event-subtitle {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.65);
    margin-bottom: 18px;
}
.event-meta {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 22px;
}
.event-meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.88);
    font-size: 0.92rem;
}
.event-meta-item i {
    color: #f8ee93;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}
.reg-steps-sidebar {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 1px solid rgba(255,255,255,0.12);
    padding-top: 22px;
}
.reg-steps-sidebar li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 18px;
    color: rgba(255,255,255,0.55);
    font-size: 0.87rem;
}
.reg-steps-sidebar li.active { color: rgba(255,255,255,0.95); }
.reg-steps-sidebar li.done   { color: #6ee7b7; }
.step-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.78rem;
    font-weight: 800;
    background: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.4);
    border: 1.5px solid rgba(255,255,255,0.18);
}
.reg-steps-sidebar li.active .step-circle {
    background: #E62020;
    color: #fff;
    border-color: #E62020;
    box-shadow: 0 0 0 3px rgba(230,32,32,0.25);
}
.reg-steps-sidebar li.done .step-circle {
    background: #10b981;
    color: #fff;
    border-color: #10b981;
}
.step-label strong { display: block; font-weight: 700; color: inherit; margin-bottom: 1px; }

/* Card */
.register-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.35);
    padding: 36px 32px;
}
.register-card .card-logo {
    width: 44px;
    height: 44px;
    object-fit: contain;
    margin-bottom: 10px;
}
.step-header h2 { font-size: 1.25rem; font-weight: 800; color: #1a1a1a; margin-bottom: 2px; }
.step-header .step-sub { color: #999; font-size: 0.82rem; margin-bottom: 20px; }

.event-assign-banner {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 20px;
    border-left: 4px solid #f8ee93;
}
.event-assign-banner .eab-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #f8ee93; margin-bottom: 3px; }
.event-assign-banner .eab-name  { font-size: 0.88rem; font-weight: 700; color: #fff; }

.register-card .form-label { font-weight: 600; font-size: 0.82rem; color: #333; margin-bottom: 4px; }
.register-card .form-control,
.register-card .form-select {
    border-radius: 9px;
    border: 1.5px solid #e0e0e0;
    padding: 9px 12px;
    font-size: 0.88rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.register-card .form-control:focus,
.register-card .form-select:focus {
    border-color: #E62020;
    box-shadow: 0 0 0 3px rgba(230,32,32,0.1);
    outline: none;
}
.register-card .form-control:disabled,
.register-card .form-control[readonly] {
    background: #f5f5f5;
    color: #888;
    cursor: not-allowed;
}
.section-divider {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #aaa;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 6px;
    margin-bottom: 12px;
    margin-top: 16px;
}
.password-hint { font-size: 0.73rem; color: #bbb; margin-top: 4px; }
.btn-reg-primary {
    background: #E62020;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 11px;
    font-weight: 700;
    font-size: 0.92rem;
    width: 100%;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-reg-primary:hover { background: #c41a1a; }
.register-footer-text { text-align: center; color: #999; font-size: 0.74rem; margin-top: 16px; }

@media (max-width: 767.98px) {
    .event-info-col { display: none; }
    .register-content { padding-top: 80px; }
    .register-card { padding: 20px 14px; }
}
</style>

<div class="register-fullscreen">
    <div class="register-content">
        <div class="container">
            <div class="row align-items-start justify-content-center g-5">

                {{-- LEFT: Event info panel --}}
                <div class="col-md-7 col-lg-6 event-info-col">
                    <span class="event-badge">You're registered for</span>
                    <h1>{{ $namaEvent }}</h1>
                    <div class="event-subtitle">Complete your account to access the participant portal</div>

                    <ul class="reg-steps-sidebar">
                        <li class="done">
                            <span class="step-circle"><i class="fa-solid fa-check" style="font-size:0.7rem;"></i></span>
                            <span class="step-label"><strong>Payment Confirmed</strong>Your seat is secured</span>
                        </li>
                        <li class="active">
                            <span class="step-circle">2</span>
                            <span class="step-label"><strong>Complete Your Account</strong>Fill in your details &amp; set a password</span>
                        </li>
                        <li>
                            <span class="step-circle">3</span>
                            <span class="step-label"><strong>Access Portal</strong>Login and manage your event attendance</span>
                        </li>
                    </ul>
                </div>

                {{-- RIGHT: Form card --}}
                <div class="col-md-5 col-lg-5 col-11">
                    <div class="register-card">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="card-logo">

                        <div class="step-header">
                            <h2>Complete Your Account</h2>
                            <div class="step-sub">Step 2 of 3 &mdash; Review &amp; complete your information</div>
                        </div>

                        {{-- Event banner --}}
                        <div class="event-assign-banner">
                            <div class="eab-label">&#127937; Registered Event</div>
                            <div class="eab-name">{{ $namaEvent }}</div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4 p-3" style="border-radius:10px;font-size:0.85rem;">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('peserta.register.submit', $token) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- ── Account Information ── --}}
                            <div class="section-divider">Account Information</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control"
                                           value="{{ old('nama', $namaPeserta) }}"
                                           placeholder="Full name as on ID" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email_display" class="form-control"
                                           value="{{ $emailPeserta }}" readonly disabled>
                                    {{-- hidden untuk kirim ke server --}}
                                    <input type="hidden" name="email" value="{{ $emailPeserta }}">
                                    <div class="password-hint"><i class="fa fa-lock me-1"></i>Email tidak dapat diubah &mdash; digunakan sebagai username login</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="Min. 8 characters" required>
                                    <div class="password-hint">Disarankan: kombinasi huruf besar, huruf kecil, angka, dan karakter khusus</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           placeholder="Repeat your password" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="telepon" class="form-control"
                                           value="{{ old('telepon', $nohp) }}"
                                           placeholder="e.g. 08xxxxxxxxxx"
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                </div>
                            </div>

                            {{-- ── Identity Verification ── --}}
                            <div class="section-divider">Identity Verification</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">ID Number <span class="text-danger">*</span></label>
                                    <input type="text" name="identitas" class="form-control"
                                           value="{{ old('identitas') }}"
                                           placeholder="Enter your ID number"
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ID Document Scan <small class="text-muted">(JPG/PNG, max 2MB)</small> <span class="text-danger">*</span></label>
                                    <input type="file" name="file" class="form-control" accept="image/jpg,image/jpeg,image/png" required>
                                </div>
                            </div>

                            {{-- ── Organization ── --}}
                            <div class="section-divider">Organization</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                                    <input type="text" name="organisasi" class="form-control"
                                           value="{{ old('organisasi', $instansi) }}"
                                           placeholder="University, company, institution, etc." required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Organization Type <span class="text-danger">*</span></label>
                                    <select name="tipe_organisasi" class="form-select" required>
                                        <option value="" disabled {{ old('tipe_organisasi') ? '' : 'selected' }}>-- Select type --</option>
                                        <option value="university"         {{ old('tipe_organisasi') == 'university'         ? 'selected' : '' }}>University / Academic Institution</option>
                                        <option value="research_institute" {{ old('tipe_organisasi') == 'research_institute' ? 'selected' : '' }}>Research Institute</option>
                                        <option value="company"            {{ old('tipe_organisasi') == 'company'            ? 'selected' : '' }}>Private Company</option>
                                        <option value="government"         {{ old('tipe_organisasi') == 'government'         ? 'selected' : '' }}>Government Agency</option>
                                        <option value="hospital"           {{ old('tipe_organisasi') == 'hospital'           ? 'selected' : '' }}>Hospital / Medical Institution</option>
                                        <option value="ngo"                {{ old('tipe_organisasi') == 'ngo'                ? 'selected' : '' }}>NGO / Non-Profit</option>
                                        <option value="other"              {{ old('tipe_organisasi') == 'other'              ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Job Title / Profession <span class="text-danger">*</span></label>
                                    <input type="text" name="pekerjaan" class="form-control"
                                           value="{{ old('pekerjaan') }}"
                                           placeholder="e.g. Researcher, Lecturer, Director" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="alamat" class="form-control" rows="2"
                                              placeholder="Full address" required>{{ old('alamat') }}</textarea>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-12">
                                    <button type="submit" class="btn-reg-primary">
                                        <i class="fa-solid fa-check-circle"></i> Create Account &amp; Login
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="register-footer-text">&copy; {{ date('Y') }} {{ $set->nama_app ?? env('APP_NAME') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('layouts.footer-v2')
