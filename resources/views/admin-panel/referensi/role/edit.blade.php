
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
										<h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">Edit Role
										<span class="page-desc text-white opacity-50 fs-6 fw-bold pt-4"></span>
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
									<div class="mb-4">
										<div class="d-flex justify-content-end">
											<a class="btn btn-warning btn-sm" href="{{ route('ref-role') }}">
												<i class="fa fa-backward"></i> Kembali
											</a>
										</div>
									</div>
									<div class="card card-flush">
										<div class="card-body">
											<form id="actForm" class="mb-4">
												@csrf
												<input type="hidden" name="key" value="{{$id_role}}">
												<div class="row">
													<label class="fs-4 opacity-75 mb-4 col-md-2">Nama Role:</label>
													<div class="col-md-4 mb-4">
														<input type="text" name="nama" class="form-control py-4" placeholder="Masukkan Role" value="{{ $detail->nama_role}}">
													</div>
													<label class="fs-4 opacity-75 mb-4 col-md-2">Kode Role:</label>
													<div class="col-md-4 mb-4">
														<input type="text" name="kode" class="form-control py-4" placeholder="Kode Singkat Role" value="{{ $detail->kode_role}}">
													</div>
													<div class="col-md-2 mb-4">
														<label class="fs-4 opacity-75 mb-4">Akses Lihat Semua Data:</label>
													</div>
													<div class="col-md-4 mb-4">
														<select name="all_data" class="form-control py-4">
															<option <?php if($detail->all_data_role == 'N'){ echo 'selected';}?> value="N">Tidak</option>
															<option <?php if($detail->all_data_role == 'Y'){ echo 'selected';}?> value="Y">Ya</option>
														</select>
													</div>
													<label class="fs-4 opacity-75 mb-4 col-md-2">Nama Singkat Role:</label>
													<div class="col-md-4 mb-4">
														<textarea name="deskripsi" class="form-control py-4">{{ $detail->deskripsi_role}}</textarea>
													</div>
																								
												</div>
												<div class="row">
													
													<div class="col-md-12 mb-4">
														<button class="btn btn-marron-submit w-100" id="btn-save"><i class="fa fa-save text-white"></i>Submit</button>
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
					url: "{{ route('updateRoleAction') }}",
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
							window.location.href = "{{ route('ref-role') }}";
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
		

@include('admin-panel.layouts.footer')