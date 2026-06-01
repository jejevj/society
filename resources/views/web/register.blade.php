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
        background: rgba(10, 10, 30, 0.82);
    }

    .register-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        padding-top: 120px;
        padding-bottom: 60px;
        min-height: 100vh;
    }

    /* ── LEFT: Event Info ── */
    .event-info-col {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-right: 48px;
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
        font-size: 2.2rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 6px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }

    .event-info-col .event-subtitle {
        font-size: 1rem;
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

    .event-packages {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 22px;
    }

    .event-package-badge {
        display: flex;
        align-items: center;
        gap: 7px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 30px;
        padding: 5px 14px;
        color: #fff;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .event-package-badge img {
        width: 22px;
        height: 22px;
        object-fit: contain;
    }

    .event-collab-list {
        list-style: none;
        padding: 0;
        margin: 0 0 24px;
    }

    .event-collab-list li {
        color: rgba(255,255,255,0.8);
        font-size: 0.87rem;
        margin-bottom: 5px;
        padding-left: 14px;
        position: relative;
    }

    .event-collab-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #f8ee93;
    }

    .event-steps {
        list-style: none;
        padding: 0;
        margin: 0;
        border-top: 1px solid rgba(255,255,255,0.12);
        padding-top: 20px;
    }

    .event-steps li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: rgba(255,255,255,0.85);
        font-size: 0.88rem;
        margin-bottom: 14px;
    }

    .event-steps .step-num {
        background: rgba(255,255,255,0.15);
        color: #f8ee93;
        font-weight: 800;
        font-size: 0.8rem;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1.5px solid rgba(255,255,255,0.25);
    }

    .event-steps .step-text strong {
        display: block;
        color: #fff;
        font-weight: 700;
        margin-bottom: 1px;
    }

    .no-event-info {
        color: rgba(255,255,255,0.6);
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    /* ── RIGHT: Register Card ── */
    .register-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.35);
        padding: 36px 32px;
    }

    .register-card .card-logo {
        width: 48px;
        height: 48px;
        object-fit: contain;
        margin-bottom: 12px;
    }

    .register-card h2 {
        font-size: 1.3rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .register-card .subtitle {
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
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #f8ee93;
        margin-bottom: 3px;
    }

    .event-assign-banner .eab-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
    }

    .event-assign-banner .eab-meta {
        font-size: 0.78rem;
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
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .register-card .form-control:focus,
    .register-card .form-select:focus {
        border-color: #E62020;
        box-shadow: 0 0 0 3px rgba(230,32,32,0.1);
        outline: none;
    }

    .section-divider {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #aaa;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 6px;
        margin-bottom: 12px;
        margin-top: 16px;
    }

    .btn-register-primary {
        background: #E62020;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.94rem;
        width: 100%;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-register-primary:hover { background: #c41a1a; color: #fff; }

    .password-hint {
        font-size: 0.75rem;
        color: #bbb;
        margin-top: 4px;
    }

    .register-switch-link {
        text-align: center;
        margin-top: 14px;
        font-size: 0.86rem;
        color: #888;
    }

    .register-switch-link a {
        color: #E62020;
        font-weight: 600;
        text-decoration: none;
    }

    .register-switch-link a:hover { text-decoration: underline; }

    .register-footer-text {
        text-align: center;
        color: #ccc;
        font-size: 0.75rem;
        margin-top: 14px;
    }

    @media (max-width: 767.98px) {
        .event-info-col { display: none; }
        .register-content { padding-top: 90px; }
        .register-card { padding: 22px 14px; }
    }
</style>

<div class="register-fullscreen">
    <div class="register-content">
        <div class="container">
            <div class="row align-items-start justify-content-center g-5">

                {{-- ═══════════════════════════════════════ --}}
                {{-- LEFT: Event Information                 --}}
                {{-- ═══════════════════════════════════════ --}}
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

                        @if(isset($event->kolaborasi) && $event->kolaborasi->count())
                            <div style="margin-bottom:8px;">
                                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:8px;">In Collaboration With</div>
                                <ul class="event-collab-list">
                                    @foreach($event->kolaborasi as $kolaborasi)
                                        <li>{{ $kolaborasi->nama_kolaborasi }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(isset($event->paket) && $event->paket->count())
                            <div style="margin-bottom:24px;">
                                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:10px;">Activity Packages</div>
                                <div class="event-packages">
                                    @foreach($event->paket as $paket)
                                        <div class="event-package-badge">
                                            <img src="{{ asset('storage/' . $paket->icon_paket) }}" alt="{{ $paket->judul_paket }}">
                                            {{ $paket->judul_paket }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    @else
                        <span class="event-badge">{{ $set->nama_app ?? env('APP_NAME', 'Society Event') }}</span>
                        <h1>Create Your<br>Account</h1>
                        <p class="no-event-info">Register to access all event features and services.</p>
                    @endif

                    {{-- Registration steps always shown --}}
                    <ul class="event-steps">
                        <li>
                            <span class="step-num">1</span>
                            <span class="step-text">
                                <strong>Fill in Your Details</strong>
                                Complete the form with valid personal and organization data
                            </span>
                        </li>
                        <li>
                            <span class="step-num">2</span>
                            <span class="step-text">
                                <strong>Verify Your Email</strong>
                                Check your inbox and click the verification link
                            </span>
                        </li>
                        <li>
                            <span class="step-num">3</span>
                            <span class="step-text">
                                <strong>Admin Activation</strong>
                                Our team will validate your identity data
                            </span>
                        </li>
                        <li>
                            <span class="step-num">4</span>
                            <span class="step-text">
                                <strong>Login &amp; Join the Event</strong>
                                Use your email and password to sign in
                            </span>
                        </li>
                    </ul>

                </div>

                {{-- ═══════════════════════════════════════ --}}
                {{-- RIGHT: Registration Card               --}}
                {{-- ═══════════════════════════════════════ --}}
                <div class="col-md-5 col-lg-5 col-11">
                    <div class="register-card">

                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="card-logo">
                        <h2>Create New Account</h2>
                        <div class="subtitle">Fill in all fields below to register</div>

                        {{-- Event assignment banner --}}
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

                        <form method="POST" id="actRegisterForm" enctype="multipart/form-data">
                            @csrf

                            {{-- Pass event kode if available --}}
                            @if(isset($event) && $event)
                                <input type="hidden" name="kode_event" value="{{ $event->kode_event }}">
                            @endif

                            {{-- ── Account Information ── --}}
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
                                    <input type="text" class="form-control" name="telepon" placeholder="e.g. 08xxxxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>

                            {{-- ── Identity ── --}}
                            <div class="section-divider">Identity Verification</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">ID Number (National ID / Student ID / Employee ID) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="identitas" placeholder="Enter your ID number" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ID Document Scan <small class="text-muted">(JPG/PNG, max 2MB)</small> <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" accept="image/jpg,image/jpeg,image/png">
                                </div>
                            </div>

                            {{-- ── Organization ── --}}
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

                            {{-- ── Role in Event (only shown when event context exists) ── --}}
                            @if(isset($event) && $event)
                            <div class="section-divider">Role in Event</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Participation Role <span class="text-danger">*</span></label>
                                    <select name="role_event" class="form-select">
                                        <option value="" disabled selected>-- Select your role --</option>
                                        <option value="participant">Participant</option>
                                        <option value="speaker">Speaker / Presenter</option>
                                        <option value="sponsor">Sponsor Representative</option>
                                        <option value="committee">Organizing Committee</option>
                                        <option value="media">Media / Press</option>
                                        <option value="observer">Observer</option>
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="row g-3 mt-1">
                                <div class="col-12">
                                    <button type="submit" class="btn-register-primary" id="btnDaftar">
                                        <i class="fa-solid fa-user-plus"></i>
                                        Register Now
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="register-switch-link">
                            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
                        </div>

                        <div class="register-footer-text">&copy; {{ date('Y') }} {{ $set->nama_app ?? env('APP_NAME') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Success', text: '{{ session('success') }}' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Failed', text: '{{ session('error') }}' });
    @endif

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $(document).ready(function () {
        $("#actRegisterForm").on("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('registrasiAction') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: "Processing...",
                        text: "Please wait a moment",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                },
                success: function (response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Registration Successful",
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('login') }}";
                        });
                    } else {
                        Swal.fire({ icon: "error", title: "Failed", text: response.message });
                    }
                },
                error: function (xhr) {
                    Swal.close();
                    let message = 'An error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) { message = xhr.responseJSON.message; }
                    Swal.fire({ icon: "error", title: "Registration Failed", text: message });
                }
            });
        });
    });
</script>

@include('layouts.footer-v2')
