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
        background: rgba(230, 32, 32, 0.72);
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

    .register-info-col {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-right: 48px;
    }

    .register-info-col .badge-event {
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

    .register-info-col h1 {
        font-size: 2.4rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 18px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.2);
    }

    .register-info-col p {
        font-size: 1rem;
        color: rgba(255,255,255,0.88);
        line-height: 1.8;
        margin-bottom: 28px;
    }

    .register-info-steps {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .register-info-steps li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: rgba(255,255,255,0.92);
        font-size: 0.94rem;
        margin-bottom: 16px;
    }

    .register-info-steps .step-num {
        background: rgba(255,255,255,0.2);
        color: #f8ee93;
        font-weight: 800;
        font-size: 0.85rem;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1.5px solid rgba(255,255,255,0.3);
    }

    .register-info-steps .step-text strong {
        display: block;
        color: #fff;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .register-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        padding: 36px 32px;
    }

    .register-card .card-logo {
        width: 48px;
        height: 48px;
        object-fit: contain;
        margin-bottom: 12px;
    }

    .register-card h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .register-card .subtitle {
        color: #999;
        font-size: 0.85rem;
        margin-bottom: 22px;
    }

    .register-card .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #333;
        margin-bottom: 5px;
    }

    .register-card .form-control {
        border-radius: 10px;
        border: 1.5px solid #e0e0e0;
        padding: 10px 13px;
        font-size: 0.92rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .register-card .form-control:focus {
        border-color: #E62020;
        box-shadow: 0 0 0 3px rgba(230,32,32,0.1);
        outline: none;
    }

    .btn-register-primary {
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

    .btn-register-primary:hover { background: #c41a1a; color: #fff; }

    .register-footer-text {
        text-align: center;
        color: #bbb;
        font-size: 0.78rem;
        margin-top: 18px;
    }

    .register-switch-link {
        text-align: center;
        margin-top: 14px;
        font-size: 0.87rem;
        color: #888;
    }

    .register-switch-link a {
        color: #E62020;
        font-weight: 600;
        text-decoration: none;
    }

    .register-switch-link a:hover { text-decoration: underline; }

    .password-hint {
        font-size: 0.78rem;
        color: #aaa;
        margin-top: 4px;
    }

    @media (max-width: 767.98px) {
        .register-info-col { display: none; }
        .register-content { padding-top: 100px; }
        .register-card { padding: 24px 16px; }
    }
</style>

<div class="register-fullscreen">
    <div class="register-content">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4">

                {{-- Col kiri: informasi & langkah registrasi --}}
                <div class="col-md-7 col-lg-7 register-info-col">
                    <span class="badge-event">{{ $set->nama_app ?? env('APP_NAME', 'Society Event') }}</span>
                    <h1>Daftar Akun<br>Sekarang</h1>
                    <p>Buat akun untuk mengakses seluruh fitur layanan. Proses registrasi mudah dan verifikasi dilakukan melalui email.</p>
                    <ul class="register-info-steps">
                        <li>
                            <span class="step-num">1</span>
                            <span class="step-text"><strong>Isi Data Diri</strong>Lengkapi formulir dengan data yang valid dan aktif</span>
                        </li>
                        <li>
                            <span class="step-num">2</span>
                            <span class="step-text"><strong>Verifikasi Email</strong>Cek inbox dan klik tautan verifikasi yang dikirim ke email</span>
                        </li>
                        <li>
                            <span class="step-num">3</span>
                            <span class="step-text"><strong>Akun Diaktifkan Admin</strong>Tim kami akan memvalidasi data identitas Anda</span>
                        </li>
                        <li>
                            <span class="step-num">4</span>
                            <span class="step-text"><strong>Masuk & Gunakan Layanan</strong>Login menggunakan email dan password yang telah didaftarkan</span>
                        </li>
                    </ul>
                </div>

                {{-- Col kanan: form registrasi --}}
                <div class="col-md-5 col-lg-4 col-11">
                    <div class="register-card">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="card-logo">
                        <h2>Buat Akun Baru</h2>
                        <div class="subtitle">Lengkapi semua data di bawah ini</div>

                        <form method="POST" id="actRegisterForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama" placeholder="Nama lengkap sesuai identitas">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="email@domain.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Min. 8 karakter">
                                    <div class="password-hint">Harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*#?&._-)</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">No Identitas (KTP/NIM/NIP)</label>
                                    <input type="text" class="form-control" name="identitas" placeholder="Masukkan nomor identitas" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">File No Identitas <small class="text-muted">(JPG/PNG, maks 2MB)</small></label>
                                    <input type="file" class="form-control" name="file">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">No Telepon</label>
                                    <input type="text" class="form-control" name="telepon" placeholder="08xxxxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Pekerjaan</label>
                                    <input type="text" class="form-control" name="pekerjaan" placeholder="Jabatan / profesi Anda">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap"></textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn-register-primary" id="btnDaftar">
                                        <i class="fa-solid fa-user-plus"></i> Daftar Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="register-switch-link">
                            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
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
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}' });
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
                    Swal.fire({ title: "Sedang diproses...", text: "Mohon tunggu sebentar", allowOutsideClick: false, allowEscapeKey: false, didOpen: () => { Swal.showLoading(); } });
                },
                success: function (response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({ icon: "success", title: "Registrasi Berhasil", text: response.message, timer: 3000, showConfirmButton: false }).then(() => {
                            window.location.href = "{{ route('login') }}";
                        });
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
</script>

@include('layouts.footer-v2')
