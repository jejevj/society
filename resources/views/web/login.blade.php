@include('layouts.header-front')

<style>
    .login-fullscreen {
        position: relative;
        min-height: 100vh;
    }

    .login-fullscreen::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -2;
        background-image: url('{{ asset('storage/'.$set->gambar_login) }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .login-fullscreen::after {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        background: rgba(230, 32, 32, 0.72);
    }

    .login-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        padding-top: 120px;
        padding-bottom: 60px;
        min-height: 100vh;
    }

    .login-info-col {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-right: 48px;
    }

    .login-info-col .badge-event {
        display: inline-block;
        background: rgba(255,255,255,0.18);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.35);
        margin-bottom: 20px;
        width: fit-content;
    }

    .login-info-col h1 {
        font-size: 2.6rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 18px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.2);
    }

    .login-info-col p {
        font-size: 1rem;
        color: rgba(255,255,255,0.88);
        line-height: 1.8;
        margin-bottom: 28px;
    }

    .login-info-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .login-info-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255,255,255,0.92);
        font-size: 0.94rem;
        margin-bottom: 13px;
    }

    .login-info-features li .feat-icon {
        color: #f8ee93;
        font-size: 1rem;
        flex-shrink: 0;
        width: 18px;
        text-align: center;
    }

    .login-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        padding: 40px 36px;
    }

    .login-card .card-logo {
        width: 52px;
        height: 52px;
        object-fit: contain;
        margin-bottom: 14px;
    }

    .login-card h2 {
        font-size: 1.45rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .login-card .subtitle {
        color: #999;
        font-size: 0.87rem;
        margin-bottom: 26px;
    }

    .login-card .form-label {
        font-weight: 600;
        font-size: 0.87rem;
        color: #333;
        margin-bottom: 6px;
    }

    .login-card .form-control {
        border-radius: 10px;
        border: 1.5px solid #e0e0e0;
        padding: 11px 14px;
        font-size: 0.94rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .login-card .form-control:focus {
        border-color: #E62020;
        box-shadow: 0 0 0 3px rgba(230,32,32,0.1);
        outline: none;
    }

    .btn-login-primary {
        background: #E62020;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.96rem;
        width: 100%;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-login-primary:hover { background: #c41a1a; color: #fff; }

    .btn-login-secondary {
        background: #f5c842;
        color: #5a4200;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.96rem;
        width: 100%;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-login-secondary:hover { background: #e0b030; color: #3a2a00; }

    .btn-login-outline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-align: center;
        background: transparent;
        color: #555;
        border: 1.5px solid #ddd;
        border-radius: 10px;
        padding: 11px;
        font-weight: 600;
        font-size: 0.93rem;
        width: 100%;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-login-outline:hover {
        border-color: #E62020;
        color: #E62020;
        background: rgba(230,32,32,0.04);
    }

    .login-footer-text {
        text-align: center;
        color: #bbb;
        font-size: 0.78rem;
        margin-top: 22px;
    }

    @media (max-width: 767.98px) {
        .login-info-col { display: none; }
        .login-content { padding-top: 100px; }
        .login-card { padding: 28px 20px; }
    }
</style>

<div class="login-fullscreen">
    <div class="login-content">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4">

                {{-- Col kiri: informasi event --}}
                <div class="col-md-8 col-lg-7 login-info-col">
                    <span class="badge-event">{{ $set->nama_app ?? env('APP_NAME', 'Society Event') }}</span>
                    <h1>{!! nl2br(e($set->judul_login ?? 'Portal Layanan')) !!}</h1>
                    <p>{{ $set->deskripsi_login ?? $set->deskripsi_app ?? 'Akses informasi, data, dan layanan secara mudah, cepat, dan aman melalui satu platform terintegrasi.' }}</p>
                    <ul class="login-info-features">
                        <li><span class="feat-icon"><i class="fa-solid fa-circle-check"></i></span> {{ $set->fitur1_login ?? 'Data dan informasi resmi terverifikasi' }}</li>
                        <li><span class="feat-icon"><i class="fa-solid fa-circle-check"></i></span> {{ $set->fitur2_login ?? 'Akses berbagai layanan event dan kegiatan' }}</li>
                        <li><span class="feat-icon"><i class="fa-solid fa-shield-halved"></i></span> {{ $set->fitur3_login ?? 'Sistem keamanan berlapis dengan verifikasi OTP' }}</li>
                        <li><span class="feat-icon"><i class="fa-solid fa-user-check"></i></span> {{ $set->fitur4_login ?? 'Registrasi mudah dan proses validasi transparan' }}</li>
                    </ul>
                </div>

                {{-- Col kanan: card login --}}
                <div class="col-md-4 col-lg-4 col-11">
                    <div class="login-card">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="card-logo">
                        <h2>Masuk Akun</h2>
                        <div class="subtitle">Silakan masuk untuk melanjutkan</div>

                        <form method="POST" id="actLoginForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email anda">
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password anda">
                            </div>
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn-login-primary">
                                    <i class="fa-solid fa-right-to-bracket"></i> Login
                                </button>
                                <a href="{{ route('register') }}" class="btn-login-secondary">
                                    <i class="fa-solid fa-user-plus"></i> Registrasi
                                </a>
                                <a href="{{ route('lupa-password') }}" class="btn-login-outline">
                                    <i class="fa-solid fa-key"></i> Lupa Password
                                </a>
                            </div>
                        </form>

                        <div class="login-footer-text">&copy; {{ date('Y') }} {{ $set->nama_app ?? env('APP_NAME') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}' });
    @endif

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $(document).ready(function () {
        $("#actLoginForm").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('loginAction') }}",
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function () {
                    Swal.fire({ title: "Sedang diproses...", text: "Mohon tunggu sebentar", allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                },
                success: function (res) {
                    Swal.close();
                    if (res.status) {
                        Swal.fire({ icon: "success", title: "Berhasil", text: res.message, timer: 1500, showConfirmButton: false }).then(() => {
                            window.location.href = "{{ url(env('APP_ROUTE') . '/otpLogin') }}/" + res.key;
                        });
                    } else {
                        Swal.fire({ icon: "error", title: "Login Gagal", text: res.message });
                    }
                },
                error: function () {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Terjadi kesalahan, coba lagi." });
                }
            });
        });
    });
</script>

@include('layouts.footer-front')
