@include('layouts.header-v2')

				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid mb-140">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush mb-10">
										<div class="card border-0 list-header-monitoring" style="background-image: url('{{ asset('storage/'.$set->gambar_login) }}'); ">
											<div class="card-body d-block text-start">
												<div class="row">
													<div class="col-12">
														<h2 class="list-title mb-2">
															Lupa Password
														</h2>
													</div>
												</div>
											</div>
										</div>
										<div class="h-xl-100 mt-10 mb-4">
											<div class="mx-4 mb-4">
												<div class="row">
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
                                                        <form  method="POST" id="actForm" enctype="multipart/form-data"> 
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-12 my-4">
                                                                    <label for="" class="fs-2 mb-0 text-dark opacity-75">Email:</label>
                                                                    <input name="email" type="text" class="form-control form-control-lg border-start-1 border-end-1 ps-2" style="height:60px;" placeholder="Masukkan email anda" />
                                                                </div>
                                                                <div class="col-md-12 my-4">
                                                                    <button type="submit" class="btn btn-marron-monitoring w-100 h-50px mt-1 text-white">Selanjutnya</button>
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

                                    let formData = new FormData(this);

                                    $.ajax({
                                        url: "{{ route('lupaPasswordAction') }}",
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
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
                            </script>

@include('layouts.footer-v2')
