
@include('layouts.header-v2')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar"></div>
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush">
										<div class="card-body">
											<form id="actForm" class="mb-4" enctype="multipart/form-data">
												@csrf
												<div class="row">
													<div class="col-md-12 mb-4">
														<h3 class="fs-1q my-2 text-marron">Ganti Password</h3>
													</div>
													<div class="col-md-12 mb-4">
														<label class="fs-4 opacity-75 mb-4">Password Lama:</label><br>
														<input name="password_lama" type="password" class="form-control py-4" placeholder="Masukkan password lama">
													</div>
													<div class="col-md-12 mb-4">
														<label class="fs-4 opacity-75 mb-4">Password Baru:</label><br>
														<input name="password_baru" type="password" class="form-control py-4" placeholder="Masukkan password baru">
													</div>
													<div class="col-md-12 mb-4">
														<label class="fs-4 opacity-75 mb-4">Konfirmasi Password Baru:</label><br>
														<input name="konfirmasi_password_baru" type="password" class="form-control py-4" placeholder="Masukkan konfirmasi password baru">
													</div>
												</div>
												<div class="row">													
													<div class="col-md-12 mt-4">
														<button type="submit" id="btn-save" class="btn btn-marron-monitoring text-white w-100"><i class="fa fa-save text-white"></i>Submit</button>
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
		<script>
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			$('#btn-save').on('click', function (e) {
				e.preventDefault();

				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('updatePasswordUserAction') }}",
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					success: function (response) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message
						}).then(() => {
							location.reload();
						});
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
