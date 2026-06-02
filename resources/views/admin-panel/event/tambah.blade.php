@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Events', 'url' => route('event')],
										['label' => 'Add Event', 'url' => null],
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
											<form id="formTambahEvent" enctype="multipart/form-data">
												@csrf
												<div class="row">
													<div class="col-md-6 mb-3">
														<label class="form-label required">Title</label>
														<input type="text" name="judul" class="form-control" placeholder="Event title" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">Sub Title</label>
														<input type="text" name="sub_judul" class="form-control" placeholder="Sub title" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">Start Date</label>
														<input type="date" name="awal" class="form-control" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">End Date</label>
														<input type="date" name="akhir" class="form-control" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">Location</label>
														<input type="text" name="lokasi" class="form-control" placeholder="Location" required>
													</div>
													<div class="col-md-6 mb-3">
														<label class="form-label required">Registration Fee (Rp)</label>
														<div class="input-group">
															<span class="input-group-text">Rp</span>
															<input type="number" name="harga" class="form-control" placeholder="0" min="0" value="0" required>
														</div>
														<small class="text-muted">Set to 0 if the event is free</small>
													</div>
													<div class="col-md-12 mb-3">
														<label class="form-label required">Description</label>
														<textarea name="keterangan" class="form-control" rows="4" placeholder="Description" required></textarea>
													</div>
													<div class="col-md-12 mb-3">
														<label class="form-label required">Background Image</label>
														<input type="file" name="gambar" class="form-control" accept="image/jpg,image/jpeg,image/png" required>
														<small class="text-muted">Max 5MB. Format: jpg, jpeg, png</small>
													</div>
													<div class="col-md-12 mt-4">
														<button type="submit" class="btn btn-marron-submit"><i class="fa fa-save text-white"></i> Save</button>
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
				$('#formTambahEvent').on('submit', function(e) {
					e.preventDefault();
					var formData = new FormData(this);
					$.ajax({
						url: "{{ route('addEventAction') }}",
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
							var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to save data';
							Swal.fire('Error', msg, 'error');
						}
					});
				});
			});
		</script>
@include('admin-panel.layouts.footer')
