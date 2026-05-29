@include('layouts.header-v2')

<style>
    .login-page-wrapper {
        min-height: 100vh;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .login-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        background-image: url('{{ asset('storage/'.$set->gambar_login) }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .login-overlay {
        position: fixed;
        inset: 0;
        z-index: 1;
        background: rgba(230, 32, 32, 0.72);
    }

    .login-content {
        position: relative;
        z-index: 2;
        flex: 1;
        display: flex;
        align-items: center;
        padding-top: 100px;
        padding-bottom: 40px;
        min-height: 100vh;
    }

    .login-info-col {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-right: 40px;
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
    }

    .login-info-col h1 {
        font-size: 2.6rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 18px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.18);
    }

    .login-info-col p {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.88);
        line-height: 1.75;
        margin-bottom: 28px;
    }

    .login-info-features {
        list-style: none;
        padding: 0;
        margin: 0 0 32px 0;
    }

    .login-info-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255,255,255,0.92);
        font-size: 0.95rem;
        margin-bottom: 12px;
    }

    .login-info-features li i {
        color: #f8ee93;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .login-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        padding: 40px 36px;
        border: none;
    }

    .login-card .card-logo {
        width: 56px;
        height: 56px;
        object-fit: contain;
        margin-bottom: 12px;
    }

    .login-card h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .login-card .subtitle {
        color: #888;
        font-size: 0.88rem;
        margin-bottom: 28px;
    }

    .login-card .form-label {
        font-weight: 600;
        font-size: 0.88rem;
        color: #333;
        margin-bottom: 6px;
    }

    .login-card .form-control {
        border-radius: 10px;
        border: 1.5px solid #e0e0e0;
        padding: 11px 14px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }

    .login-card .form-control:focus {
        border-color: #E62020;
        box-shadow: 0 0 0 3px rgba(230,32,32,0.1);
    }

    .btn-login-primary {
        background: #E62020;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.97rem;
        width: 100%;
        transition: background 0.2s;
    }

    .btn-login-primary:hover {
        background: #c41a1a;
        color: #fff;
    }

    .btn-login-secondary {
        background: #f8ee93;
        color: #7a6200;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.97rem;
        width: 100%;
        transition: background 0.2s;
    }

    .btn-login-secondary:hover {
        background: #f0e050;
        color: #5a4800;
    }

    .btn-login-outline {
        background: transparent;
        color: #666;
        border: 1.5px solid #ddd;
        border-radius: 10px;
        padding: 11px;
        font-weight: 600;
        font-size: 0.93rem;
        width: 100%;
        transition: all 0.2s;
    }

    .btn-login-outline:hover {
        border-color: #E62020;
        color: #E62020;
        background: rgba(230,32,32,0.04);
    }

    .login-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 18px 0;
        color: #bbb;
        font-size: 0.82rem;
    }

    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e8e8e8;
    }

    .login-footer-text {
        text-align: center;
        color: #bbb;
        font-size: 0.78rem;
        margin-top: 20px;
    }

    @media (max-width: 767.98px) {
        .login-info-col {
            display: none;
        }
        .login-content {
            align-items: flex-start;
            padding-top: 90px;
        }
        .login-card {
            padding: 28px 20px;
        }
    }
</style>

<div class="login-page-wrapper">
    <div class="login-bg"></div>
    <div class="login-overlay"></div>

    <div class="login-content">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4">

                {{-- Col kiri: informasi event --}}
                <div class="col-md-8 col-lg-7 login-info-col">
                    <span class="badge-event">Society Event - Science Bank</span>
                    <h1>Portal Layanan<br>Data Terbuka</h1>
                    <p>Akses informasi, data, dan layanan Kementerian Pertahanan RI secara mudah, cepat, dan aman melalui satu platform terintegrasi.</p>
                    <ul class="login-info-features">
                        <li><i class="fa-solid fa-circle-check"></i> Data dan informasi resmi Kementerian Pertahanan RI</li>
                        <li><i class="fa-solid fa-circle-check"></i> Akses berbagai layanan event dan kegiatan</li>
                        <li><i class="fa-solid fa-circle-check"></i> Sistem keamanan berlapis dengan verifikasi OTP</li>
                        <li><i class="fa-solid fa-circle-check"></i> Registrasi mudah dan proses validasi transparan</li>
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
                                <input type="email" name="email"
                                    class="form-control"
                                    placeholder="Masukkan email anda">
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control"
                                    placeholder="Masukkan password anda">
                            </div>
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn-login-primary">
                                    <i class="fa-solid fa-right-to-bracket me-2"></i> Login
                                </button>
                                <button type="button"
                                    class="btn-login-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalUnduh">
                                    <i class="fa-solid fa-user-plus me-2"></i> Registrasi
                                </button>
                                <a href="{{ route('lupa-password') }}" class="btn-login-outline text-decoration-none text-center d-block">
                                    <i class="fa-solid fa-key me-2"></i> Lupa Password
                                </a>
                            </div>
                        </form>

                        <div class="login-footer-text">© 2026 Kementerian Pertahanan RI</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal Registrasi --}}
<div class="modal fade" id="modalUnduh" tabindex="-1" aria-labelledby="modalUnduhLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="actForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-marron" id="modalUnduhLabel">Registrasi Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Identitas</label>
                                <input type="text" class="form-control" name="identitas" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">File No Identitas</label>
                                <input type="file" class="form-control" name="file">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control" name="telepon" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" class="form-control" name="pekerjaan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-marron" id="btnKirim">Registrasi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
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
        $("#actForm").on("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('registrasiAction') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({ title: "Sedang diproses...", text: "Mohon tunggu sebentar", allowOutsideClick: false, allowEscapeKey: false, didOpen: () => { Swal.showLoading(); } });
                },
                success: function (response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({ icon: "success", title: "Berhasil", text: response.message, timer: 2000, showConfirmButton: false }).then(() => { location.reload(); });
                    } else {
                        Swal.fire({ icon: "error", title: "Gagal", text: response.message });
                    }
                },
                error: function (xhr) {
                    Swal.close();
                    let message = 'Terjadi kesalahan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) { message = xhr.responseJSON.message; }
                    Swal.fire({ icon: "error", title: "Registrasi Gagal", text: message });
                }
            });
        });
    });

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

@include('layouts.footer-v2')
