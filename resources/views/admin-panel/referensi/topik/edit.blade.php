
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
									<div class="mb-4">
										<div class="d-flex justify-content-end">
											<a class="btn btn-warning btn-sm" href="{{ route('ref-topik') }}">
												<i class="fa fa-backward"></i> Kembali
											</a>
										</div>
									</div>
									<div class="card card-flush">
										<div class="card-body">
											<form id="actForm" class="mb-4">
												@csrf
												<input type="hidden" name="key" value="{{$id_topik}}">
												<div class="row">
													<label class="fs-4 opacity-75 mb-4 col-md-2">Code:</label>
													<div class="col-md-10 mb-4">
														<input type="text" name="kode" class="form-control py-4" placeholder="Code" value="{{ $detail->kode_topik}}" readonly>
													</div>
													<label class="fs-4 opacity-75 mb-4 col-md-2">Tag Name:</label>
													<div class="col-md-4 mb-4">
														<input type="text" name="nama" class="form-control py-4" placeholder="Tag Name" value="{{ $detail->nama_topik}}">
													</div>
													
													<label class="fs-4 opacity-75 mb-4 col-md-2">Orders:</label>
													<div class="col-md-4 mb-4">
														<input type="number" name="urutan" class="form-control py-4" placeholder="Orders" value="{{ $detail->urutan_topik}}">
													</div>
																									
												</div>
												<div class="row">
													
													<div class="col-md-12 mb-4">
														<button class="btn btn-marron-submit w-100" id="btn-save"><i class="fa fa-save text-white"></i>Save</button>
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
					url: "{{ route('updateTopikAction') }}",
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
						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: response.message
						}).then(() => {
							window.location.href = "{{ route('ref-topik') }}";
						});
					},
					error: function (xhr) {
						let message = 'There is an error.';
						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						Swal.fire({
							icon: 'error',
							title: 'Failed',
							text: message
						});
					}
				});
			});


			
		</script>
		

@include('admin-panel.layouts.footer')