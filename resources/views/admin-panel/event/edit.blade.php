@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Events', 'url' => route('event')],
										['label' => 'Edit Event', 'url' => null],
									]])
								</div>
								<div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
									<div class="page-title d-flex align-items-center me-3">
										<h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{$menu}}</h1>
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
											<form id="formEditEvent" enctype="multipart/form-data">
												@csrf
												<input type="hidden" name="key" value="{{ $kode_event }}">
												<div class="row">
													<div class="col-md-6 mb-3">
														<label class="form-label required">Title</label>
														<input type="text" name="judul" class="form-control" value="{{ $detail->judul_event ?? '' }}" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">Sub Title</label>
														<input type="text" name="sub_judul" class="form-control" value="{{ $detail->sub_judul_event ?? '' }}" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">Start Date</label>
														<input type="date" name="awal" class="form-control" value="{{ $detail->tanggal_awal_event ?? '' }}" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">End Date</label>
														<input type="date" name="akhir" class="form-control" value="{{ $detail->tanggal_akhir_event ?? '' }}" required>
													</div>
													<div class="col-md-12 mb-3">
														<label class="form-label required">Location</label>
														<input type="text" name="lokasi" class="form-control" value="{{ $detail->lokasi_event ?? '' }}" required>
													</div>
													<div class="col-md-12 mb-3">
														<label class="form-label required">Description</label>
														<textarea name="keterangan" class="form-control" rows="4" required>{{ $detail->keterangan_event ?? '' }}</textarea>
													</div>
													<div class="col-md-12 mb-3">
														<label class="form-label">Background Image <small class="text-muted">(leave empty to keep current image)</small></label>
														@if(!empty($detail->background_event))
															<div class="mb-2">
																<img src="{{ asset('storage/'.$detail->background_event) }}" width="120" class="img-thumbnail">
															</div>
														@endif
														<input type="file" name="gambar" class="form-control" accept="image/jpg,image/jpeg,image/png">
														<small class="text-muted">Max 5MB. Format: jpg, jpeg, png</small>
													</div>
													<div class="col-md-12 mt-4">
														<button type="submit" class="btn btn-marron-submit"><i class="fa fa-save text-white"></i> Update</button>
														<a href="{{ route('event') }}" class="btn btn-secondary ms-2">Cancel</a>
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
		<!--end::App-->
		<script>
			$(document).ready(function () {
				$('#formEditEvent').on('submit', function(e) {
					e.preventDefault();
					var formData = new FormData(this);
					$.ajax({
						url: "{{ route('updateEventAction') }}",
						type: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						success: function(response) {
							if (response.success) {
								Swal.fire('Success', response.message, 'success').then(function() {
									window.location.href = "{{ route('event') }}";
								});
							} else {
								Swal.fire('Error', response.message, 'error');
							}
						},
						error: function(xhr) {
							var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update data';
							Swal.fire('Error', msg, 'error');
						}
					});
				});
			});
		</script>
@include('admin-panel.layouts.footer')
