@include('admin-panel.layouts.header-login')
				<div class="app-wrapper flex-column flex-row-fluid" style="margin-top:200px;" id="kt_app_wrapper">
					<div class="app-container container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid align-items-center">
								<div id="kt_app_content" class="app-content mw-480">
									<div class="card card-flush mb-10">
                                        <div class="text-center mb-6">
											<img src="{{ asset('logo/logo-kemhan.png') }}"
												alt="Logo"
												style="height:60px;" class="mb-3">

											<h2 class="fw-bold text-dark mb-1">
												Verifikasi OTP Login
											</h2>

											<div class="text-muted fs-6">
												Portal Admin Kementerian Pertahanan
											</div>

											<!-- accent line -->
											<div class="mx-auto mt-3"
												style="width:60px;height:3px;background:linear-gradient(90deg,#198754,#d4af37);border-radius:10px;">
											</div>
										</div>
                                        <div class="row m-4">
                                            
											<div class="col-md-12">
                                                <form  method="POST" id="actForm" enctype="multipart/form-data"> 
                                                    @csrf 
                                                    <input type="hidden" name="key" value="{{ $key }}">
                                                    <div class="row">                
                                                        <div class="col-md-12 my-4">
                                                            <input name="otp" type="text" class="form-control form-control-lg border-start-1 border-end-1 ps-2" style="height:60px;" placeholder="Masukkan kode otp anda" />
                                                        </div>
                                                        <div class="col-md-12 my-4">
                                                            <button type="submit" class="btn btn-marron-monitoring w-100 h-50px mt-1 text-white">Verifikasi OTP</button>
                                                        </div>                    
                                                    </div>
                                                </form>
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
                                        url: "{{ route('verifyOtpAdminPanelAction') }}",
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function (response) {
                                            if (response.status) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    text: response.message
                                                }).then(() => {
                                                    window.location.href = "{{ url(env('APP_ROUTE') . '/dashboard') }}";
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
							

@include('admin-panel.layouts.footer')
