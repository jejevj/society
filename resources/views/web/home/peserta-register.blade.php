@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-detail border-0 pt-6 pb-4">
                        <h2 class="text-white fw-bold mb-1">
                            <i class="fa fa-user-plus me-2"></i>Lengkapi Data Akun
                        </h2>
                        <p class="text-white-50 mb-0 small">Buat akun untuk mengakses portal peserta event</p>
                    </div>
                    <div class="card-body p-8">

                        {{-- Event info --}}
                        <div class="alert alert-light-success d-flex align-items-center mb-6 p-4">
                            <i class="fa fa-check-circle text-success fs-3 me-3"></i>
                            <div>
                                <div class="fw-bold">Anda terdaftar di:</div>
                                <div class="text-muted">{{ $namaEvent }}</div>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('peserta.register.submit', $token) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label required fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control form-control-solid"
                                       value="{{ old('nama', $namaPeserta) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-solid bg-secondary"
                                       value="{{ $emailPeserta }}" readonly disabled>
                                <small class="text-muted">Email tidak dapat diubah</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required fw-semibold">No. HP / WhatsApp</label>
                                <input type="text" name="telepon" class="form-control form-control-solid"
                                       value="{{ old('telepon', $nohp) }}" placeholder="08xxxxxxxxxx" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Instansi / Organisasi</label>
                                <input type="text" name="instansi" class="form-control form-control-solid"
                                       value="{{ old('instansi', $instansi) }}" placeholder="Nama perusahaan / universitas (opsional)">
                            </div>

                            <div class="mb-4">
                                <label class="form-label required fw-semibold">Password</label>
                                <input type="password" name="password" id="passwordInput"
                                       class="form-control form-control-solid" placeholder="Minimal 8 karakter" required>
                            </div>

                            <div class="mb-6">
                                <label class="form-label required fw-semibold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                       class="form-control form-control-solid" placeholder="Ulangi password" required>
                            </div>

                            <button type="submit" class="btn bg-detail text-white w-100 py-3 fw-bold">
                                <i class="fa fa-check me-2"></i>Buat Akun &amp; Masuk
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('layouts.footer-v2')
