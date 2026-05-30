
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
										<h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{ $menu }}
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
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-end">
                                                    <a class="btn btn-warning btn-sm" href="{{ route('programEvent', $detail->kode_event) }}">
                                                        <i class="fa fa-backward"></i> back
                                                    </a>
                                                </div>
                                            </div>
											<form id="actForm" class="mb-4" enctype="multipart/form-data">
												@csrf
                                                <input type="hidden" name="key" value="{{$detail->kode_event}}">
												<div class="row">
													<div class="col-md-6">
														<label class="fs-4 opacity-75 mb-4">Day:</label>
														<input type="text" class="form-control py-4" name="hari" placeholder="Day" >
													</div>
													<div class="col-md-6">
														<label class="fs-4 opacity-75 mb-4">Date:</label>
														<input type="date" class="form-control py-4" name="tanggal" placeholder="Date" >
													</div>
                                                    
													<div class="mt-8">
                                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                                            <h4>Program Details</h4>
                                                            <button type="button" class="btn btn-success btn-sm" id="btn-add-detail">
                                                                <i class="fa fa-plus"></i> Add Detail
                                                            </button>
                                                        </div>

                                                        <div id="detail-container">

                                                            <div class="detail-item border rounded p-4 mb-4">
                                                                <div class="row">

                                                                    <div class="col-md-3">
                                                                        <label class="fs-6 mb-2">Session</label>
                                                                        <input type="text" class="form-control"
                                                                            name="sesi[]" placeholder="Session">
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label class="fs-6 mb-2">Description</label>
                                                                        <input type="text" class="form-control"
                                                                            name="keterangan[]" placeholder="Description">
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <label class="fs-6 mb-2">Start Time</label>
                                                                        <input type="time" class="form-control"
                                                                            name="jam_awal[]">
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <label class="fs-6 mb-2">End Time</label>
                                                                        <input type="time" class="form-control"
                                                                            name="jam_akhir[]">
                                                                    </div>

                                                                    <div class="col-md-2 d-flex align-items-end">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-remove-detail w-100">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>											
												</div>
												<div class="row">													
													<div class="col-md-12 mt-4">
														<button type="submit" id="btn-save" class="btn btn-marron-submit w-100"><i class="fa fa-save text-white"></i>Save</button>
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

            let detailIndex = 1;

            $('#btn-add-detail').on('click', function () {

                let html = `
                    <div class="detail-item border rounded p-4 mb-4">
                        <div class="row">

                            <div class="col-md-3">
                                <label class="fs-6 mb-2">Session</label>
                                <input type="text"
                                    class="form-control"
                                    name="sesi[]"
                                    placeholder="Session">
                            </div>

                            <div class="col-md-3">
                                <label class="fs-6 mb-2">Description</label>
                                <input type="text"
                                    class="form-control"
                                    name="keterangan[]"
                                    placeholder="Description">
                            </div>

                            <div class="col-md-2">
                                <label class="fs-6 mb-2">Start Time</label>
                                <input type="time"
                                    class="form-control"
                                    name="jam_awal[]">
                            </div>

                            <div class="col-md-2">
                                <label class="fs-6 mb-2">End Time</label>
                                <input type="time"
                                    class="form-control"
                                    name="jam_akhir[]">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button"
                                    class="btn btn-danger btn-remove-detail w-100">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                `;

                $('#detail-container').append(html);
            });

            $(document).on('click', '.btn-remove-detail', function () {
                $(this).closest('.detail-item').remove();
            });

			$('#btn-save').on('click', function (e) {
				e.preventDefault();

				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('addProgramEventAction') }}",
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
							window.location.href = "{{ route('programEvent', $detail->kode_event) }}";
						});
					},
					error: function (xhr) {
						let message = 'Terjadi kesalahan.';
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