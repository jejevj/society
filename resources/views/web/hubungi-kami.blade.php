@include('layouts.header-v2')
				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush mb-10">
                                        <div class="card border-0 list-header-monitoring" style="background-image: url('{{ asset('storage/'.$set->gambar_hubungi) }}'); ">
											<div class="card-body d-block text-start">
												<div class="row">
													<div class="col-12">
														<h2 class="list-title mb-2">
															Hubungi Kami
														</h2>
														<p class="list-desc mb-4 mw-600">
															{{ $set->deskripsi_hubungi}}
														</p>
													</div>
												</div>
											</div>
										</div>
										<div class="h-xl-100 mt-10 mb-4">
											<div class="mb-4 mx-4">
												<div class="row">
                                                    <div class="col-md-4">
                                                        <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end h-100" style="background-image:url('{{ asset('storage/'.$set->gambar2_hubungi) }}"></div>
                                                    </div>
												<div class="col-md-8">
                                                    <form id="actForm" class="mb-4" enctype="multipart/form-data">
												        @csrf
                                                        <input type="hidden" name="angka1" value="{{ $angka1 }}">
                                                        <input type="hidden" name="angka2" value="{{ $angka2 }}">
                                                        <input type="hidden" name="operator" value="{{ $operator }}">
                                                        <div class="row">
                                                            <div class="col-md-6 my-2">
                                                                <label class="fs-4 mb-0 text-dark opacity-75 mb-3">Nama Lengkap:</label>
                                                                <input type="text" name="nama" class="form-control form-control-lg border-start-1 border-end-1 ps-2" style="height:60px;" placeholder="Masukkan nama lengkap" />
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label class="fs-4 mb-0 text-dark opacity-75 mb-3">Email Aktif:</label>
                                                                <input type="text" name="email" class="form-control form-control-lg border-start-1 border-end-1 ps-2" style="height:60px;" placeholder="Masukkan email aktif" />
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label class="fs-4 mb-0 text-dark opacity-75 mb-3">No KTP:</label>
                                                                <input type="text" name="ktp" class="form-control form-control-lg border-start-1 border-end-1 ps-2 h-60" placeholder="Masukkan no ktp" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"/>
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label class="fs-4 mb-0 text-dark opacity-75 mb-3">File Pelaporan:</label>
                                                                <input type="file" name="file" class="form-control form-control-lg border-start-1 border-end-1 ps-2 h-60"/>
                                                            </div>
                                                            <div class="col-md-12 my-2">
                                                                <label class="fs-4 opacity-75 mb-3">Pesan:</label>
                                                                <textarea name="pesan" id="deskripsi" class="form-control form-control-lg border-start-1 border-end-1 ps-2 tiny" rows="3"></textarea>
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label class="fs-4 mb-0 text-dark opacity-75">Captcha:</label>
                                                                <div class="d-flex align-items-center">
                                                                    <span id="captcha-img">
                                                                        {!! captcha_img() !!}
                                                                    </span>
                                                                    <button type="button" class="btn btn-light-primary btn-sm ms-2" id="refresh-captcha"><i class="fa fa-sync"></i></button>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <input type="text" name="captcha" class="form-control form-control-lg h-60" placeholder="Masukkan kode captcha" />
                                                            </div>
                                                            <div class="col-md-12 mt-2">
                                                                <button class="btn btn-gold w-100 h-50px text-white" type="submit" id="btn-save"><i class="fa fa-upload text-white"></i> Submit </button>
                                                            </div>
                                                        </div>
                                                    </form>									
												</div>		
											</div>
										</div>
                                	</div>
								</div>
							</div>
						</div>							
                            <script>
                                $.ajaxSetup({
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                                });

                                $('#actForm').on('submit', function (e) {
                                    e.preventDefault();
                                    if (typeof tinymce !== "undefined") {
                                        tinymce.triggerSave();
                                    }
                                    let formData = new FormData(this);

                                    $.ajax({
                                        url: "{{ route('hubungiKamiAction') }}",
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        beforeSend: function () {
                                            Swal.fire({
                                                title: "Sedang diproses...",
                                                text: "Mohon tunggu",
                                                allowOutsideClick: false,
                                                allowEscapeKey: false,
                                                didOpen: () => {
                                                    Swal.showLoading();
                                                }
                                            });
                                        },
                                        success: function (response) {
                                            if (response.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    text: response.message
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal',
                                                    text: response.message
                                                });
                                            }
                                        },
                                        error: function (xhr) {
                                            let message = 'Terjadi kesalahan.';
                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                message = xhr.responseJSON.message;
                                            }

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal',
                                                text: message
                                            });
                                        }
                                    });
                                });

                                $('#refresh-captcha').click(function () {
                                    $.ajax({
                                        type: 'GET',
                                        url: "{{ route('refresh.captcha') }}",
                                        success: function (data) {
                                            $("#captcha-img").html(data.captcha);
                                        }
                                    });
                                });
                            </script>

@include('layouts.footer-v2')
