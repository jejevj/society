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
            width: 100%;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        .login-wrap {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* ── Col kiri: gambar full ── */
        .login-img {
            flex: 0 0 66.6667%;
            width: 66.6667%;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .login-img img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .login-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(180,20,20,0.58) 0%, rgba(20,20,60,0.48) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 52px;
            z-index: 1;
        }

        .login-img-overlay .brand {
            margin-bottom: 18px;
        }

        .login-img-overlay .brand img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .login-img-overlay p {
            color: rgba(255,255,255,0.85);
            font-size: 1rem;
            line-height: 1.75;
            max-width: 500px;
        }

        /* ── Col kanan: form ── */
        .login-form-col {
            flex: 0 0 33.3333%;
            width: 33.3333%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            padding: 48px 36px;
            box-shadow: -4px 0 24px rgba(0,0,0,0.08);
            overflow-y: auto;
        }

        .login-form-inner {
            width: 100%;
            max-width: 340px;
        }

        .login-form-inner .logo-sm {
            height: 48px;
            margin-bottom: 24px;
            display: block;
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

        .form-label-custom {
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
            margin-bottom: 16px;
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
            margin-top: 4px;
        }

        .btn-submit:hover { background: #c41a1a; }

        .login-footer-text {
            text-align: center;
            color: #bbb;
            font-size: 0.76rem;
            margin-top: 28px;
        }

        @media (max-width: 768px) {
            body { overflow: auto; }
            .login-wrap { flex-direction: column; height: auto; }
            .login-img { display: none; }
            .login-form-col { flex: 1; width: 100%; height: auto; min-height: 100vh; box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="login-wrap">

    {{-- Col kiri: background image fullscreen --}}
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
                <div class="mb-1">
                    <label class="form-label-custom">Username / Email</label>
                    <input type="text" name="username" class="form-control-login" placeholder="Masukkan username atau email">
                </div>
                <div class="mb-1">
                    <label class="form-label-custom">Password</label>
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
