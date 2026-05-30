<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login &mdash; {{ env('APP_NAME', 'Society Event') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/min/style.bundle.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/min/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/min/swal.min.js') }}"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: #f4f6fb;
        }

        .login-wrap {
            display: flex;
            min-height: 100vh;
        }

        /* Col kiri: gambar */
        .login-img {
            flex: 0 0 66.6667%;
            max-width: 66.6667%;
            position: relative;
            overflow: hidden;
        }

        .login-img img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .login-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(180,20,20,0.55) 0%, rgba(20,20,60,0.45) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 48px;
        }

        .login-img-overlay .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .login-img-overlay .brand img {
            height: 56px;
            width: auto;
            object-fit: contain;
        }

        .login-img-overlay p {
            color: rgba(255,255,255,0.82);
            font-size: 0.95rem;
            line-height: 1.75;
            max-width: 480px;
        }

        /* Col kanan: form */
        .login-form-col {
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            padding: 48px 36px;
            box-shadow: -4px 0 24px rgba(0,0,0,0.07);
        }

        .login-form-inner {
            width: 100%;
            max-width: 360px;
        }

        .login-form-inner .logo-sm {
            height: 48px;
            margin-bottom: 24px;
        }

        .login-form-inner h2 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .login-form-inner .subtitle {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 32px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.84rem;
            color: #333;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-login {
            width: 100%;
            border-radius: 10px;
            border: 1.5px solid #e0e0e0;
            padding: 11px 14px;
            font-size: 0.93rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            background: #fafafa;
        }

        .form-control-login:focus {
            border-color: #E62020;
            box-shadow: 0 0 0 3px rgba(230,32,32,0.1);
            background: #fff;
        }

        .btn-submit {
            width: 100%;
            background: #E62020;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 700;
            font-size: 0.96rem;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-submit:hover { background: #c41a1a; }

        .login-footer-text {
            text-align: center;
            color: #bbb;
            font-size: 0.76rem;
            margin-top: 28px;
        }

        @media (max-width: 768px) {
            .login-wrap { flex-direction: column; }
            .login-img { display: none; }
            .login-form-col { flex: 1; max-width: 100%; padding: 40px 24px; box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="login-wrap">

    {{-- Col kiri: background image --}}
    <div class="login-img">
        <img src="/ldt-asset/images/1780075229_bg-scbank.jpeg" alt="Background Society Event">
        <div class="login-img-overlay">
            <div class="brand">
                <img src="/ldt-asset/images/logo-name.png" alt="Logo Society Event">
            </div>
            <p>{{ $set->deskripsi_app ?? 'Kelola data, event, dan layanan secara mudah melalui panel administrasi terpusat.' }}</p>
        </div>
    </div>

    {{-- Col kanan: form login --}}
    <div class="login-form-col">
        <div class="login-form-inner">
            <img src="/ldt-asset/images/logo_.png" alt="Logo" class="logo-sm">
            <h2>Masuk Admin</h2>
            <div class="subtitle">Silakan masuk untuk mengelola sistem</div>

            <form id="actForm">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Username / Email</label>
                    <input type="text" name="username" class="form-control-login" placeholder="Masukkan username atau email">
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control-login" placeholder="Masukkan password">
                </div>
                <button type="submit" class="btn-submit" id="btn-save">
                    <i class="fa fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="login-footer-text">&copy; {{ date('Y') }} {{ $set->nama_app ?? env('APP_NAME', 'Society Event') }}</div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $("#actForm").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('login-backend-action') }}",
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function () {
                    Swal.fire({
                        title: "Memproses...",
                        text: "Mohon tunggu sebentar",
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                },
                success: function (res) {
                    Swal.close();
                    if (res.status) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            if (res.redirect) window.location.href = res.redirect;
                        });
                    } else {
                        Swal.fire({ icon: "error", title: "Login Gagal", text: res.message });
                    }
                },
                error: function () {
                    Swal.close();
                    Swal.fire({ icon: "error", title: "Oops...", text: "Terjadi kesalahan, coba lagi." });
                }
            });
        });
    });
</script>

</body>
</html>
