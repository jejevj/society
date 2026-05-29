
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
										<h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">{{$menu}}
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
											<a class="btn btn-warning btn-sm" href="{{ route('setting') }}">
												<i class="fa fa-backward"></i> Kembali
											</a>
										</div>
									</div>
									<div class="card card-flush">
										<div class="card-body">
											<form id="actForm" class="mb-4">
												@csrf
												<input type="hidden" name="key" value="{{$id_slider}}">
												<input type="hidden" name="jenis" value="{{$detail->jenis_slider}}">
												<div class="row">
													
													<div class="col-md-6 mb-2">
														<label class="fs-4 opacity-75 mb-4">Judul:</label><br>
														<input type="text" name="judul" value="{{$detail->judul_slider}}" class="form-control py-4">
													</div>
													
													<div class="col-md-6">
														<label class="fs-4 opacity-75 mb-4">Urutan:</label><br>
														<input type="number" class="form-control py-4" name="urutan" value="{{$detail->urutan_slider}}">
													</div>
													<?php if($detail->jenis_slider == 'gambar'){?>
													<div class="col-md-12 mt-4 mb-4">
														<label class="fs-4 opacity-75 mb-4">Image:</label><br>
														<img src="{{ url('storage/'.$detail->gambar_slider) }}" class="img-fluid rounded" alt="Preview" style="max-height:150px;">
														<input type="file" name="gambar" class="form-control py-4" placeholder="Masukkan gambar">
													</div>
													<?php }else{ ?>
													<div class="col-md-12">
														<label class="fs-4 opacity-75 mb-4">Deskripsi:</label><br>
                                                        <textarea name="deskripsi" class="form-control py-4">{{$detail->deskripsi_slider}}</textarea>
													</div>
													<?php }?>
                                                    
													
												</div>
												
												<div class="row mt-2">
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
					url: "{{ route('updateSliderAction') }}",
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

@include('admin-panel.layouts.footer')