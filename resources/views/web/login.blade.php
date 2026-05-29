@include('layouts.header-v2')
				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush mb-10">
                                        <div class="card border-0 list-header-monitoring" style="background-image: url('{{ asset('storage/'.$set->gambar_login) }}'); ">
											<div class="card-body d-block text-start">
												<div class="row">
													<div class="col-12">
														<h2 class="list-title mb-2">
															Login
														</h2>
													</div>
												</div>
											</div>
										</div>
                                        <div class="overflow-hidden">
                                            <div class="row g-0">
                                                <div class="col-md-4 d-none d-md-block">
                                                    <div class="h-100 w-100 position-relative">
                                                        <div class="h-100 w-100"
                                                            style="background-image:url('{{ asset('storage/'.$set->gambar2_login) }}');
                                                            background-size: cover;
                                                            background-position: center;">
                                                        </div>
                                                        <div class="position-absolute top-0 start-0 w-100 h-100"
                                                            style="background: linear-gradient(135deg, rgba(0,0,0,0.2), rgba(212,175,55,0.15));">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="p-6 p-lg-10">
                                                        <div class="mb-8">
                                                            <h2 class="fw-bold text-dark mb-2 fs-2">
                                                                Login Sistem
                                                            </h2>
                                                            <div class="text-muted fs-6">
                                                                Portal Layanan Data Terbuka Kementerian Pertahanan RI
                                                            </div>
                                                        </div>
                                                        <form method="POST" id="actLoginForm" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="mb-5">
                                                                <label class="form-label fw-semibold text-dark">Email</label>
                                                                <input type="email" name="email"
                                                                    class="form-control form-control-lg form-control-solid"
                                                                    placeholder="Masukkan email anda">
                                                            </div>
                                                            <div class="mb-6">
                                                                <label class="form-label fw-semibold text-dark">Password</label>
                                                                <input type="password" name="password"
                                                                    class="form-control form-control-lg form-control-solid"
                                                                    placeholder="Masukkan password anda">
                                                            </div>
                                                            <div class="d-grid gap-3 mb-6">
                                                                <button type="submit"
                                                                    class="btn btn-marron-monitoring rounded-3 text-white">
                                                                    <i class="fa-solid fa-right-to-bracket me-2 text-white"></i>
                                                                    Login
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-gold-monitoring btn-lg rounded-3 text-white"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalUnduh">
                                                                    <i class="fa-solid fa-user-plus me-2 text-white"></i>
                                                                    Registrasi
                                                                </button>
                                                                <a href="{{ route('lupa-password') }}"
                                                                    class="btn btn-outline-secondary btn-lg rounded-3">
                                                                    <i class="fa-solid fa-key me-2"></i>
                                                                    Lupa Password
                                                                </a>
                                                            </div>
                                                            <div class="text-center text-muted fs-7">
                                                                © 2026 Kementerian Pertahanan RI
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
                            <div class="modal fade" id="modalUnduh" tabindex="-1" aria-labelledby="modalUnduhLabel" aria-hidden="true">
							    <div class="modal-dialog modal-lg">
									<div class="modal-content">
										<form  method="POST" id="actForm" enctype="multipart/form-data"> 
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
																<input type="text" class="form-control" id="nama" name="nama" >
														    </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
																<label for="email" class="form-label">Email </label>
																<input type="email" class="form-control" id="email" name="email" >
															</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
															    <label for="password" class="form-label">Password </label>
																<input type="password" class="form-control" id="password" name="password" >
															</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
															    <label for="" class="form-label">No Identitas </label>
																<input type="text" class="form-control" id="" name="identitas" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
															</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
														        <label for="" class="form-label">File No Identitas </label>
																<input type="file" class="form-control" id="" name="file" >
															</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
																<label for="" class="form-label">No Telepon </label>
																<input type="text" class="form-control" id="" name="telepon" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
															</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
																<label for="" class="form-label">Pekerjaan </label>
																<input type="text" class="form-control" id="" name="pekerjaan" >
															</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
														        <label for="" class="form-label">Alamat </label>
																<textarea name="alamat" class="form-control" id="" rows="5"></textarea>
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
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: '{{ session('success') }}'
                                    });
                                @endif

                                @if(session('error'))
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: '{{ session('error') }}'
                                    });
                                @endif

                                $.ajaxSetup({
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                                });

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
                                                Swal.fire({
                                                    title: "Sedang diproses...",
                                                    text: "Mohon tunggu sebentar",
                                                    allowOutsideClick: false,
                                                    allowEscapeKey: false,
                                                    didOpen: () => {
                                                        Swal.showLoading();
                                                    }
                                                });
                                            },
                                            success: function (response) {
                                                Swal.close();
                                                if (response.success) {
                                                    Swal.fire({
                                                        icon: "success",
                                                        title: "Berhasil",
                                                        text: response.message,
                                                        timer: 2000,
                                                        showConfirmButton: false
                                                    }).then(() => {
                                                        location.reload();
                                                    });

                                                } else {
                                                    Swal.fire({
                                                        icon: "error",
                                                        title: "Gagal",
                                                        text: response.message
                                                    });
                                                }
                                            },
                                            error: function (xhr) {
                                                Swal.close();
                                                let message = 'Terjadi kesalahan.';
                                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                                    message = xhr.responseJSON.message;
                                                }
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Registrasi Gagal",
                                                    text: message
                                                });
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
                                                Swal.fire({
                                                    title: "Sedang diproses...",
                                                    text: "Mohon tunggu sebentar",
                                                    allowOutsideClick: false,
                                                    didOpen: () => {
                                                        Swal.showLoading();
                                                    }
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
                                                        window.location.href = "{{ url(env('APP_ROUTE') . '/otpLogin') }}/" + res.key;
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        icon: "error",
                                                        title: "Login Gagal",
                                                        text: res.message
                                                    });
                                                }
                                            },
                                            error: function (xhr) {
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Oops...",
                                                    text: "Terjadi kesalahan, coba lagi."
                                                });
                                            }
                                        });
                                    });
                                });
                            </script>
							

@include('layouts.footer-v2')
