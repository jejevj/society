@include('layouts.header-v2')

<style>
/* ── Base ── */
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

/* ── LEFT: Event Info ── */
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

/* ── Step Indicator (left panel) ── */
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
    transition: color 0.3s;
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
    transition: all 0.3s;
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
.step-label strong {
    display: block;
    font-weight: 700;
    color: inherit;
    margin-bottom: 1px;
}

/* ── RIGHT: Card ── */
.register-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.35);
    padding: 36px 32px;
    min-height: 500px;
}
.register-card .card-logo {
    width: 44px;
    height: 44px;
    object-fit: contain;
    margin-bottom: 10px;
}
.step-header h2 {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 2px;
}
.step-header .step-sub {
    color: #999;
    font-size: 0.82rem;
    margin-bottom: 20px;
}
.event-assign-banner {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 20px;
    border-left: 4px solid #f8ee93;
}
.event-assign-banner .eab-label {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #f8ee93;
    margin-bottom: 3px;
}
.event-assign-banner .eab-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: #fff;
}
.event-assign-banner .eab-meta {
    font-size: 0.76rem;
    color: rgba(255,255,255,0.6);
    margin-top: 2px;
}
.register-card .form-label {
    font-weight: 600;
    font-size: 0.82rem;
    color: #333;
    margin-bottom: 4px;
}
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
.password-hint {
    font-size: 0.73rem;
    color: #bbb;
    margin-top: 4px;
}
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
.btn-reg-primary:disabled { background: #9ca3af; cursor: not-allowed; }
.btn-reg-secondary {
    background: #f5f5f5;
    color: #555;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    padding: 10px;
    font-weight: 600;
    font-size: 0.88rem;
    width: 100%;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-reg-secondary:hover { background: #ebebeb; }

/* ── OTP ── */
.otp-boxes {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin: 18px 0;
}
.otp-boxes input {
    width: 52px;
    height: 58px;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    outline: none;
    transition: border-color 0.2s;
}
.otp-boxes input:focus {
    border-color: #E62020;
    box-shadow: 0 0 0 3px rgba(230,32,32,0.12);
}
.otp-email-hint {
    text-align: center;
    color: #888;
    font-size: 0.82rem;
    margin-bottom: 6px;
}
.otp-resend {
    text-align: center;
    font-size: 0.82rem;
    color: #aaa;
    margin-top: 10px;
}
.otp-resend a {
    color: #E62020;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

/* ── Package Cards ── */
.package-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 12px;
}
.package-card {
    display: flex;
    align-items: center;
    gap: 14px;
    border: 2px solid #e8e8e8;
    border-radius: 12px;
    padding: 12px 14px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    position: relative;
}
.package-card:hover:not(.locked) {
    border-color: #E62020;
    background: #fff5f5;
}
.package-card.selected {
    border-color: #E62020;
    background: #fff5f5;
}
.package-card.locked {
    cursor: default;
    opacity: 0.75;
}
.package-card input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.package-check {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    border: 2px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s;
}
.package-card.selected .package-check {
    background: #E62020;
    border-color: #E62020;
    color: #fff;
}
.package-card.locked.selected .package-check {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
}
.package-img {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}
.package-info {
    flex: 1;
}
.package-info .pkg-name {
    font-weight: 700;
    color: #1a1a1a;
    font-size: 0.9rem;
}
.package-info .pkg-sub {
    color: #888;
    font-size: 0.78rem;
    margin-top: 1px;
}
.pkg-badge-free {
    font-size: 0.72rem;
    font-weight: 700;
    background: #d1fae5;
    color: #065f46;
    padding: 2px 8px;
    border-radius: 20px;
    white-space: nowrap;
    flex-shrink: 0;
}
.pkg-badge-paid {
    font-size: 0.72rem;
    font-weight: 700;
    background: #fee2e2;
    color: #991b1b;
    padding: 2px 8px;
    border-radius: 20px;
    white-space: nowrap;
    flex-shrink: 0;
}
.event-fee-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 10px;
    padding: 10px 12px;
    margin-bottom: 10px;
    font-size: 0.85rem;
}
.event-fee-row.paid { background: #fff5f5; border-color: #fca5a5; }
.event-fee-label { color: #555; font-weight: 600; }
.event-fee-value.free { color: #16a34a; font-weight: 700; }
.event-fee-value.paid-price { color: #E62020; font-weight: 700; }

/* ── Struk Payment ── */
.struk-box {
    background: #fafafa;
    border: 1.5px solid #e8e8e8;
    border-radius: 14px;
    padding: 16px 14px;
    margin-bottom: 14px;
}
.struk-title {
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #aaa;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.struk-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 6px 0;
    border-bottom: 1px dashed #eee;
    font-size: 0.85rem;
    gap: 8px;
}
.struk-row:last-child { border-bottom: none; }
.struk-row .sk { color: #666; flex: 1; min-width: 0; }
.struk-row .sv { font-weight: 700; color: #1a1a1a; text-align: right; white-space: nowrap; }
.struk-row .sv.red { color: #E62020; }
.struk-row .sv.green { color: #16a34a; }
.struk-divider { border: none; border-top: 2px dashed #ddd; margin: 8px 0; }
.total-bar {
    background: #1a1a2e;
    border-radius: 12px;
    padding: 13px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 14px 0;
}
.total-bar .tl { color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600; }
.total-bar .tr { color: #f8ee93; font-size: 1.2rem; font-weight: 800; }

/* ── Pay status ── */
.pay-status-box { margin-top: 10px; padding: 11px 14px; border-radius: 10px; font-size: 0.85rem; display: none; align-items: center; gap: 8px; }
.pay-status-box.pending { background: #fefce8; border: 1.5px solid #fde047; color: #854d0e; display: flex; }
.pay-status-box.error   { background: #fef2f2; border: 1.5px solid #fca5a5; color: #dc2626; display: flex; }
.pay-status-box.ok      { background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #166534; display: flex; }
.spinner { width: 15px; height: 15px; border: 2.5px solid currentColor; border-top-color: transparent; border-radius: 50%; animation: spin .7s linear infinite; flex-shrink: 0; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Success ── */
.success-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px 0 10px;
}
.success-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #d1fae5;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    font-size: 2rem;
    color: #10b981;
}
.success-container h3 {
    font-size: 1.2rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 6px;
}
.success-container p { color: #888; font-size: 0.85rem; max-width: 280px; }

.register-switch-link {
    text-align: center;
    margin-top: 14px;
    font-size: 0.84rem;
    color: #888;
}
.register-switch-link a { color: #E62020; font-weight: 600; text-decoration: none; }
.register-footer-text { text-align: center; color: #ccc; font-size: 0.74rem; margin-top: 12px; }

@media (max-width: 767.98px) {
    .event-info-col { display: none; }
    .register-content { padding-top: 80px; }
    .register-card { padding: 20px 14px; }
    .otp-boxes input { width: 44px; height: 50px; font-size: 1.3rem; }
}
</style>

<div class="register-fullscreen">
    <div class="register-content">
        <div class="container">
            <div class="row align-items-start justify-content-center g-5">

                {{-- LEFT --}}
                <div class="col-md-7 col-lg-6 event-info-col">
                    @if(isset($event) && $event)
                        <span class="event-badge">You are registering for</span>
                        <h1>{{ $event->judul_event }}</h1>
                        <div class="event-subtitle">{{ $event->sub_judul_event }}</div>
                        <div class="event-meta">
                            <div class="event-meta-item">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ $event->lokasi_event }}</span>
                            </div>
                            <div class="event-meta-item">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>
                                    {{ date('d M Y', strtotime($event->tanggal_awal_event)) }}
                                    &ndash;
                                    {{ date('d M Y', strtotime($event->tanggal_akhir_event)) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <span class="event-badge">{{ $set->nama_app ?? env('APP_NAME', 'Society Event') }}</span>
                        <h1>Create Your<br>Account</h1>
                    @endif

                    <ul class="reg-steps-sidebar" id="sidebarSteps">
                        <li class="active" data-step="1">
                            <span class="step-circle">1</span>
                            <span class="step-label"><strong>Fill in Your Details</strong>Personal, identity &amp; organization data</span>
                        </li>
                        <li data-step="2">
                            <span class="step-circle">2</span>
                            <span class="step-label"><strong>Verify Email (OTP)</strong>Enter the code sent to your email</span>
                        </li>
                        @if(isset($event) && $event)
                        <li data-step="3">
                            <span class="step-circle">3</span>
                            <span class="step-label"><strong>Select Packages</strong>Choose event packages</span>
                        </li>
                        <li data-step="4">
                            <span class="step-circle">4</span>
                            <span class="step-label"><strong>Payment</strong>Complete payment to confirm your seat</span>
                        </li>
                        @else
                        <li data-step="3">
                            <span class="step-circle">3</span>
                            <span class="step-label"><strong>Account Active</strong>You're all set!</span>
                        </li>
                        @endif
                    </ul>
                </div>

                {{-- RIGHT --}}
                <div class="col-md-5 col-lg-5 col-11">
                    <div class="register-card">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="card-logo">

                        {{-- STEP 1 --}}
                        <div id="step1">
                            <div class="step-header">
                                <h2>Create New Account</h2>
                                <div class="step-sub">Step 1 of {{ isset($event) && $event ? '4' : '3' }} &mdash; Fill in your information</div>
                            </div>

                            @if(isset($event) && $event)
                            <div class="event-assign-banner">
                                <div class="eab-label">Registering for Event</div>
                                <div class="eab-name">{{ $event->judul_event }}</div>
                                <div class="eab-meta">
                                    {{ $event->lokasi_event }} &nbsp;|&nbsp;
                                    {{ date('d M Y', strtotime($event->tanggal_awal_event)) }}
                                    &ndash;
                                    {{ date('d M Y', strtotime($event->tanggal_akhir_event)) }}
                                </div>
                            </div>
                            @endif

                            <form id="formStep1" enctype="multipart/form-data">
                                @csrf
                                @if(isset($event) && $event)
                                    <input type="hidden" name="kode_event" value="{{ $event->kode_event }}">
                                @endif

                                <div class="section-divider">Account Information</div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" placeholder="Full name as on ID">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" placeholder="email@domain.com">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="Min. 8 characters">
                                        <div class="password-hint">Must contain uppercase, lowercase, number, and special character (@$!%*#?&amp;._-)</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telepon" placeholder="e.g. 08xxxxxxxxxx" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    </div>
                                </div>

                                <div class="section-divider">Identity Verification</div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">ID Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="identitas" placeholder="Enter your ID number" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">ID Document Scan <small class="text-muted">(JPG/PNG, max 2MB)</small> <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="file" accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                </div>

                                <div class="section-divider">Organization</div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="organisasi" placeholder="University, company, institution, etc.">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Organization Type <span class="text-danger">*</span></label>
                                        <select name="tipe_organisasi" class="form-select">
                                            <option value="" disabled selected>-- Select type --</option>
                                            <option value="university">University / Academic Institution</option>
                                            <option value="research_institute">Research Institute</option>
                                            <option value="company">Private Company</option>
                                            <option value="government">Government Agency</option>
                                            <option value="hospital">Hospital / Medical Institution</option>
                                            <option value="ngo">NGO / Non-Profit</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Job Title / Profession <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pekerjaan" placeholder="e.g. Researcher, Lecturer, Director">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea name="alamat" class="form-control" rows="2" placeholder="Full address"></textarea>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <button type="submit" class="btn-reg-primary" id="btnStep1">
                                            <i class="fa-solid fa-arrow-right"></i> Continue to Verification
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="register-switch-link mt-3">
                                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
                            </div>
                        </div>

                        {{-- STEP 2: OTP --}}
                        <div id="step2" style="display:none;">
                            <div class="step-header">
                                <h2>Email Verification</h2>
                                <div class="step-sub">Step 2 of {{ isset($event) && $event ? '4' : '3' }} &mdash; Check your inbox</div>
                            </div>
                            <div class="otp-email-hint">
                                We sent a 6-digit OTP to<br>
                                <strong id="otpEmailDisplay" style="color:#1a1a1a;"></strong>
                            </div>
                            <div class="otp-boxes">
                                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                            </div>
                            <input type="hidden" id="otpValue">
                            <button class="btn-reg-primary" id="btnVerifyOtp">
                                <i class="fa-solid fa-check-circle"></i> Verify OTP
                            </button>
                            <div class="otp-resend mt-2">
                                Didn't receive the code? <a id="resendOtp">Resend</a>
                                <span id="resendTimer" style="display:none;"> (<span id="timerCount">60</span>s)</span>
                            </div>
                            <button class="btn-reg-secondary mt-3" id="btnBackStep1">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </button>
                        </div>

                        {{-- STEP 3: Package Selection --}}
                        <div id="step3" style="display:none;">
                            <div class="step-header">
                                <h2>Select Packages</h2>
                                <div class="step-sub">Step 3 of 4 &mdash; Choose the packages for this event</div>
                            </div>

                            {{-- Baris harga dasar event --}}
                            @php $hargaEventBase = (float) ($event->harga_event ?? 0); @endphp
                            <div class="event-fee-row {{ $hargaEventBase > 0 ? 'paid' : '' }}">
                                <span class="event-fee-label"><i class="fa-solid fa-ticket me-1"></i>Event Registration Fee</span>
                                @if($hargaEventBase > 0)
                                    <span class="event-fee-value paid-price">Rp {{ number_format($hargaEventBase, 0, ',', '.') }}</span>
                                @else
                                    <span class="event-fee-value free"><i class="fa-solid fa-circle-check me-1"></i>Free</span>
                                @endif
                            </div>

                            <div class="package-grid" id="packageGrid">
                                <div class="text-center text-muted py-3" id="packageLoading">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Loading packages...
                                </div>
                            </div>

                            <div class="row g-2 mt-2">
                                <div class="col-12">
                                    <button class="btn-reg-primary" id="btnStep3Next">
                                        <i class="fa-solid fa-arrow-right"></i> Continue to Payment
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 4: Payment (struk + snap) --}}
                        <div id="step4" style="display:none;">
                            <div class="step-header">
                                <h2>Payment Summary</h2>
                                <div class="step-sub">Step 4 of 4 &mdash; Review your order then pay</div>
                            </div>

                            {{-- Struk --}}
                            <div class="struk-box" id="strukBox">
                                <div class="struk-title"><i class="fa-solid fa-receipt"></i> Transaction Detail</div>
                                <div class="struk-row">
                                    <span class="sk">Name</span>
                                    <span class="sv" id="strukNama"></span>
                                </div>
                                <div class="struk-row">
                                    <span class="sk">Email</span>
                                    <span class="sv" id="strukEmail"></span>
                                </div>
                                <hr class="struk-divider">
                                <div id="strukItems"></div>
                                <hr class="struk-divider">
                                <div class="struk-row">
                                    <span class="sk">Order ID</span>
                                    <span class="sv" style="font-size:.75rem;color:#999" id="strukOrderId">-</span>
                                </div>
                            </div>

                            <div class="total-bar">
                                <span class="tl"><i class="fa-solid fa-money-bill-wave me-1"></i>Total Payment</span>
                                <span class="tr" id="strukTotal">Rp 0</span>
                            </div>

                            <button class="btn-reg-primary" id="btnPayNow">
                                <i class="fa-solid fa-credit-card"></i> Pay Now
                            </button>

                            <div id="payStatusBox" class="pay-status-box"></div>

                            <button class="btn-reg-secondary mt-3" id="btnBackStep3">
                                <i class="fa-solid fa-arrow-left"></i> Back to Packages
                            </button>
                        </div>

<<<<<<< Updated upstream
=======
                        {{-- SUCCESS --}}
>>>>>>> Stashed changes
                        <div id="stepSuccess" style="display:none;">
                            <div class="success-container">
                                <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                                <h3>Registration Complete!</h3>
                                <p id="successMsg">You are now enrolled in the event. A confirmation email has been sent.</p>
                                <a href="{{ route('login') }}" class="btn-reg-primary mt-4" style="text-decoration:none;max-width:220px;">
                                    <i class="fa-solid fa-right-to-bracket"></i> Login to Your Account
                                </a>
                            </div>
                        </div>

                        <div class="register-footer-text">&copy; {{ date('Y') }} {{ $set->nama_app ?? env('APP_NAME') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
var _csrf      = '{{ csrf_token() }}';
var _kodeEvent = '{{ isset($event) && $event ? $event->kode_event : '' }}';
var _hasEvent  = {{ isset($event) && $event ? 'true' : 'false' }};

// Midtrans config dari DB (diisi setelah getRegistrationSnapToken berhasil)
var _snapToken    = null;
var _clientKey    = '{{ $midtransConfig->client_key ?? '' }}';
var _isProduction = {{ isset($midtransConfig) && $midtransConfig && $midtransConfig->environment === 'production' ? 'true' : 'false' }};
var _snapUrl      = _isProduction
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js';

var _userId        = null;
var _userName      = '';
var _userEmail     = '';
var _totalAmount   = 0;
var _isFree        = false;
var _orderId       = '';
var _selectedPkgs  = [];

$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': _csrf } });

function formatRp(n) {
    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
}

function setPayStatus(type, html) {
    var box = document.getElementById('payStatusBox');
    box.className = 'pay-status-box ' + type;
    box.innerHTML = html;
}

function goToStep(n) {
    ['step1','step2','step3','step4','stepSuccess'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    var target = document.getElementById('step' + n) || document.getElementById('stepSuccess');
    if (target) target.style.display = 'block';

    document.querySelectorAll('#sidebarSteps li').forEach(function(li) {
        var s = parseInt(li.dataset.step);
        li.classList.remove('active','done');
        if (s < n)      li.classList.add('done');
        else if (s===n) li.classList.add('active');
        var circle = li.querySelector('.step-circle');
        circle.innerHTML = s < n ? '<i class="fa-solid fa-check" style="font-size:0.7rem;"></i>' : s;
    });
}

// ── STEP 1 ──
$('#formStep1').on('submit', function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    Swal.fire({ title: 'Processing...', text: 'Please wait', allowOutsideClick: false, allowEscapeKey: false, didOpen: function() { Swal.showLoading(); } });
    $.ajax({
        url: '{{ route("registrasiAction") }}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(r) {
            Swal.close();
            if (r.success) {
                _userId     = r.user_id;
                _userEmail  = $('#formStep1 [name=email]').val();
                _userName   = $('#formStep1 [name=nama]').val();
                $('#otpEmailDisplay').text(_userEmail);
                goToStep(2);
                startResendTimer();
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: r.message });
            }
        },
        error: function(xhr) {
            Swal.close();
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred.';
            Swal.fire({ icon: 'error', title: 'Failed', text: msg });
        }
    });
});

// ── OTP ──
$(document).on('input', '.otp-digit', function() {
    this.value = this.value.replace(/[^0-9]/g,'');
    if (this.value.length===1) $(this).nextAll('.otp-digit').first().focus();
    syncOtp();
});
$(document).on('keydown', '.otp-digit', function(e) {
    if (e.key==='Backspace' && !this.value) $(this).prevAll('.otp-digit').first().focus();
});
function syncOtp() {
    var val='';
    $('.otp-digit').each(function(){ val+=this.value; });
    $('#otpValue').val(val);
}

// ── STEP 2: Verify OTP ──
$('#btnVerifyOtp').on('click', function() {
    syncOtp();
    var otp = $('#otpValue').val();
    if (otp.length!==6) { Swal.fire({ icon:'warning', title:'Incomplete', text:'Please enter all 6 digits.' }); return; }
    Swal.fire({ title: 'Verifying...', allowOutsideClick: false, didOpen: function(){ Swal.showLoading(); } });
    $.ajax({
        url: '{{ route("verifyOtpRegistrasi") }}',
        type: 'POST',
        data: { _token: _csrf, user_id: _userId, otp: otp, has_event: _hasEvent ? 1 : 0 },
        success: function(r) {
            Swal.close();
            if (r.success) {
                if (_hasEvent) { loadPackages(); goToStep(3); }
                else { goToStep('Success'); $('#successMsg').text('Your account has been verified. Please wait for admin activation before logging in.'); }
            } else { Swal.fire({ icon:'error', title:'Invalid OTP', text: r.message }); }
        },
        error: function(xhr) {
            Swal.close();
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Verification failed.';
            Swal.fire({ icon:'error', title:'Error', text: msg });
        }
    });
});

$('#btnBackStep1').on('click', function(){ goToStep(1); });

// Resend OTP
var resendInterval;
function startResendTimer() {
    var count=60;
    $('#resendOtp').css('pointer-events','none').css('opacity','0.4');
    $('#resendTimer').show();
    $('#timerCount').text(count);
    clearInterval(resendInterval);
    resendInterval = setInterval(function() {
        count--;
        $('#timerCount').text(count);
        if (count<=0) { clearInterval(resendInterval); $('#resendOtp').css('pointer-events','').css('opacity','1'); $('#resendTimer').hide(); }
    }, 1000);
}
$('#resendOtp').on('click', function() {
    $.ajax({
        url: '{{ route("resendOtpRegistrasi") }}',
        type: 'POST',
        data: { _token: _csrf, user_id: _userId },
        success: function(r) {
            if (r.success) {
                $('.otp-digit').val(''); $('#otpValue').val(''); $('.otp-digit').first().focus();
                startResendTimer();
                Swal.fire({ icon:'success', title:'OTP Sent', text:'A new OTP has been sent.', timer:2000, showConfirmButton:false });
            }
        }
    });
});

// ── STEP 3: Load packages ──
function loadPackages() {
    if (!_hasEvent) return;
    $('#packageLoading').show();
    $.ajax({
        url: '{{ route("getEventPackages") }}',
        type: 'POST',
        data: { _token: _csrf, kode_event: _kodeEvent },
        success: function(r) {
            $('#packageLoading').hide();
            var html = '';
            if (r.packages && r.packages.length > 0) {
                r.packages.forEach(function(pkg) {
                    var isFree = !pkg.harga_paket || parseInt(pkg.harga_paket) === 0;
                    var iconUrl = pkg.icon_paket ? '/storage/' + pkg.icon_paket : '';
                    var imgTag  = iconUrl
                        ? '<img src="' + iconUrl + '" class="package-img" alt="' + pkg.judul_paket + '">'
                        : '<div class="package-img d-flex align-items-center justify-content-center bg-light text-muted" style="font-size:1.3rem;"><i class="fa-solid fa-cube"></i></div>';
                    var cardClass = 'package-card' + (isFree ? ' selected locked' : '');
                    var checkAttr = isFree ? 'checked disabled' : '';
                    var badge     = isFree
                        ? '<span class="pkg-badge-free">Free</span>'
                        : '<span class="pkg-badge-paid">Rp ' + parseInt(pkg.harga_paket).toLocaleString('id-ID') + '</span>';

                    html += '<div class="' + cardClass + '" data-id="' + pkg.kode_paket + '" data-name="' + pkg.judul_paket + '" data-price="' + (pkg.harga_paket || 0) + '" data-free="' + (isFree ? '1' : '0') + '">';
                    html += '<input type="checkbox" class="pkg-checkbox" value="' + pkg.kode_paket + '" ' + checkAttr + '>';
                    html += '<div class="package-check"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i></div>';
                    html += imgTag;
                    html += '<div class="package-info"><div class="pkg-name">' + pkg.judul_paket + '</div>';
                    if (pkg.lokasi_paket) html += '<div class="pkg-sub">' + pkg.lokasi_paket + '</div>';
                    html += '</div>' + badge + '</div>';
                });
            } else {
                html = '<div class="text-center text-muted py-3">No packages available for this event.</div>';
            }
            $('#packageGrid').html(html);
            updateSelected();
        },
        error: function() { $('#packageGrid').html('<div class="text-center text-muted py-3">Failed to load packages.</div>'); }
    });
}

$(document).on('click', '.package-card', function() {
    if ($(this).hasClass('locked')) return;
    $(this).toggleClass('selected');
    $(this).find('.pkg-checkbox').prop('checked', $(this).hasClass('selected'));
    updateSelected();
});

function updateSelected() {
    _selectedPkgs = [];
    $('.package-card.selected').each(function() {
        _selectedPkgs.push({ id: $(this).data('id'), name: $(this).data('name'), price: parseInt($(this).data('price')) || 0 });
    });
}

// ── STEP 3 -> STEP 4 ──
$('#btnStep3Next').on('click', function() {
    var btn = $(this);
    btn.prop('disabled', true).html('<div class="spinner" style="border-color:#fff;border-top-color:transparent"></div> Loading...');

    var pkgIds = _selectedPkgs.map(function(p){ return p.id; });

    $.ajax({
        url: '{{ route("getRegistrationSnapToken") }}',
        type: 'POST',
        data: { _token: _csrf, user_id: _userId, kode_event: _kodeEvent, packages: pkgIds },
        success: function(r) {
            btn.prop('disabled', false).html('<i class="fa-solid fa-arrow-right"></i> Continue to Payment');
            if (!r.success) {
                Swal.fire({ icon: 'error', title: 'Error', text: r.message });
                return;
            }

            _isFree      = r.free === true;
            _snapToken   = r.snap_token || null;
            _totalAmount = r.total_amount || 0;
            _orderId     = r.order_id    || '';

            if (r.client_key) {
                _clientKey    = r.client_key;
                _isProduction = r.is_production === true;
                _snapUrl      = _isProduction
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js';
            }

            buildStruk(r);
            goToStep(4);

            if (_isFree) {
                document.getElementById('btnPayNow').style.display = 'none';
                enrollFree();
            } else {
                document.getElementById('btnPayNow').style.display = '';
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fa-solid fa-arrow-right"></i> Continue to Payment');
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to prepare payment.';
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
        }
    });
});

function buildStruk(r) {
    document.getElementById('strukNama').textContent  = _userName;
    document.getElementById('strukEmail').textContent = _userEmail;
    document.getElementById('strukOrderId').textContent = _orderId || '-';

    var items = '';
    var hargaEv = parseFloat(r.harga_event || 0);
    if (hargaEv > 0) {
        items += '<div class="struk-row"><span class="sk"><i class="fa-solid fa-ticket me-1" style="color:#E62020"></i>Event Registration</span><span class="sv red">' + formatRp(hargaEv) + '</span></div>';
    } else {
        items += '<div class="struk-row"><span class="sk"><i class="fa-solid fa-ticket me-1" style="color:#16a34a"></i>Event Registration</span><span class="sv green">Free</span></div>';
    }
    (r.selected_paket || []).forEach(function(p) {
        var harga = parseFloat(p.harga_paket || 0);
        var hargaHtml = harga > 0 ? '<span class="sv red">' + formatRp(harga) + '</span>' : '<span class="sv green">Free</span>';
        items += '<div class="struk-row"><span class="sk"><i class="fa-solid fa-box-open me-1" style="color:#6366f1"></i>' + p.judul_paket + '</span>' + hargaHtml + '</div>';
    });
    document.getElementById('strukItems').innerHTML = items;
    document.getElementById('strukTotal').textContent = formatRp(_totalAmount);
}

$('#btnBackStep3').on('click', function(){ goToStep(3); });

function enrollFree() {
    setPayStatus('pending', '<div class="spinner"></div><span>Enrolling you in the event...</span>');
    $.ajax({
        url: '{{ route("enrollEventFree") }}',
        type: 'POST',
        data: { _token: _csrf, user_id: _userId, kode_event: _kodeEvent, packages: _selectedPkgs.map(function(p){ return p.id; }) },
        success: function(r) {
            if (r.success) {
                setPayStatus('ok', '<i class="fa-solid fa-circle-check"></i><span>Enrolled successfully! Redirecting...</span>');
                setTimeout(function(){ goToStep('Success'); $('#successMsg').text('Registration complete. You are now enrolled in the event.'); }, 1200);
            } else {
                setPayStatus('error', '<i class="fa-solid fa-circle-xmark"></i><span>' + r.message + '</span>');
            }
        },
        error: function() { setPayStatus('error', '<i class="fa-solid fa-circle-xmark"></i><span>Failed to enroll. Please try again.</span>'); }
    });
}

// ── STEP 4: Pay Now ──
function loadSnapScript(callback) {
    if (window.snap && typeof window.snap.pay === 'function') { callback(); return; }
    var s = document.createElement('script');
    s.src = _snapUrl;
    s.setAttribute('data-client-key', _clientKey);
    s.onload = function(){ setTimeout(callback, 150); };
    s.onerror = function(){
        setPayStatus('error', '<i class="fa-solid fa-triangle-exclamation"></i><span>Failed to load payment gateway. Check your connection.</span>');
        document.getElementById('btnPayNow').disabled = false;
        document.getElementById('btnPayNow').innerHTML = '<i class="fa-solid fa-credit-card"></i> Pay Now';
    };
    document.head.appendChild(s);
}

$('#btnPayNow').on('click', function() {
    var btn = document.getElementById('btnPayNow');

    if (!_snapToken) {
        Swal.fire({ icon: 'warning', title: 'Please wait', text: 'Payment gateway is loading. Try again in a moment.' });
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<div class="spinner" style="border-color:#fff;border-top-color:transparent"></div> Loading payment...';
    setPayStatus('pending', '<div class="spinner"></div><span>Connecting to Midtrans...</span>');

    loadSnapScript(function() {
        if (!window.snap || typeof window.snap.pay !== 'function') {
            setPayStatus('error', '<i class="fa-solid fa-triangle-exclamation"></i><span>Midtrans Snap unavailable. Ensure Client Key is set in configuration.</span>');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Pay Now';
            return;
        }

        window.snap.pay(_snapToken, {
            onSuccess: function(result) {
                setPayStatus('ok', '<i class="fa-solid fa-circle-check"></i><span>Payment successful! Enrolling...</span>');
                $.ajax({
                    url: '{{ route("paymentRegistrationCallback") }}',
                    type: 'POST',
                    data: {
                        _token: _csrf,
                        user_id: _userId,
                        kode_event: _kodeEvent,
                        packages: _selectedPkgs.map(function(p){ return p.id; }),
                        midtrans_result: JSON.stringify(result)
                    },
                    success: function(r) {
                        goToStep('Success');
                        $('#successMsg').text('Payment successful! You are now enrolled in the event.');
                    },
                    error: function(){
                        goToStep('Success');
                        $('#successMsg').text('Payment received. Our team will confirm your enrollment shortly.');
                    }
                });
            },
            onPending: function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Pay Now';
                setPayStatus('pending', '<div class="spinner"></div><span>Payment pending. Complete the payment and come back here.</span>');
            },
            onError: function(result) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Pay Now';
                setPayStatus('error', '<i class="fa-solid fa-circle-xmark"></i><span>Payment failed: ' + (result.status_message || 'Unknown error.') + '</span>');
            },
            onClose: function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> Pay Now';
                setPayStatus('error', '<i class="fa-solid fa-circle-xmark"></i><span>You closed the payment window. Click Pay Now to try again.</span>');
            }
        });
    });
});
</script>

@include('layouts.footer-v2')
