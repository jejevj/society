
@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									{!! $breadcrumb !!}
								</div>
								<div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
									<div class="page-title d-flex align-items-center me-3">
										<h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{$menu}}
										<span class="page-desc opacity-50 fs-6 fw-bold pt-4"></span>
										</h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="app-container container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush">
										<div class="card-body">
											<div class="row">
												
                                                <div class="col-md-12 mt-4 mb-4">
                                                    <div class="">
                                                        <div >
															<form id="actForm" class="mb-4" enctype="multipart/form-data">
															@csrf
																<div class="row">
																	<h3 class="fs-1 mb-4">Profil Pengguna</h3>
																	<div class="col-md-12 mb-4">
																		<?php if(!empty($detail->foto_user)){?>
																			<img src="{{ url('storage/'.$detail->foto_user) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
																		<?php } else {?>
																			<img src="{{ asset('assets/logo-v2/user.png') }}" class="" alt="Preview" style="max-height:150px;"><br><br>
																		<?php }?>
																		
																		<input type="file" name="foto" class="form-control py-4" placeholder="Photo">
																	</div>
																	<div class="col-md-6 mb-4">
																		<label class="fs-4 opacity-75 mb-4">Name:</label><br>
																		<input type="text" name="nama" class="form-control py-4" value="{{ $detail->nama_user }}">
																	</div>
																	<div class="col-md-6 mb-4">
																		<label class="fs-4 opacity-75 mb-4">Email:</label><br>
																		<input type="text" name="username" class="form-control py-4" value="{{ $detail->username_user }}">
																	</div>
																	<div class="col-md-6 mb-4">
																		<label class="fs-4 opacity-75 mb-4">Role:</label><br>
																		<input type="text" readonly class="form-control py-4" value="{{ $detail->nama_role }}">
																	</div>
																	
																</div>
																<div class="row">													
																	<div class="col-md-12 mt-4">
																		<button type="submit" id="btn-save" class="btn btn-marron-submit w-100"><i class="fa fa-save text-white"></i>Submit</button>
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

			$('#btn-save').on('click', function (e) {
				e.preventDefault();
				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
				$.ajax({
					url: "{{ route('updateProfilAction') }}",
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					beforeSend: function () {
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Is saving data',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
					success: function (response) {
						if(response.success == true){
							Swal.fire({
								icon: 'success',
								title: 'Success',
								text: response.message
							}).then(() => {
								location.reload();
							});
						}else{
							Swal.fire({
								icon: 'error',
								title: 'Failed',
								text: response.message
							});
						}
					},
					error: function (xhr) {
						let message = 'There is an error.';
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