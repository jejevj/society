
@include('layouts.header-v2')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar"></div>
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush">
										<div class="card-body">
											<form id="actForm" enctype="multipart/form-data">
												@csrf
												<div class="row">
													<div class="col-md-12">
														<h3 class="fs-1q mt-4 text-marron">Data Profil</h3>
													</div>
													<div class="col-md-4 mb-4">
														<label class="fs-4 opacity-75 mb-4">Nama:</label><br>
														<input type="text" name="nama" class="form-control py-4" value="{{ $detail->nama_user }}">
													</div>
													<div class="col-md-4 mb-4">
														<label class="fs-4 opacity-75 mb-4">Email:</label><br>
														<input type="text" name="username" class="form-control py-4" value="{{ $detail->username_user }}">
													</div>
                                                    <div class="col-md-4 mb-4">
														<label class="fs-4 opacity-75 mb-4">No Identitas:</label><br>
														<input type="text" name="identitas" class="form-control py-4" value="{{ $detail->identitas_user }}">
													</div>
                                                    <div class="col-md-4 mb-4">
														<label class="fs-4 opacity-75 mb-4">No Telepon:</label><br>
														<input type="text" name="telepon" class="form-control py-4" value="{{ $detail->telepon_user }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
													</div>
                                                    <div class="col-md-4 mb-4">
														<label class="fs-4 opacity-75 mb-4">Pekerjaan:</label><br>
														<input type="text" name="pekerjaan" class="form-control py-4" value="{{ $detail->pekerjaan_user }}">
													</div>
                                                    <div class="col-md-4 mb-4">
														<label class="fs-4 opacity-75 mb-4">Alamat:</label><br>
														<input type="text" name="alamat" class="form-control py-4" value="{{ $detail->alamat_user }}">
													</div>
													<div class="col-md-12 mb-4">
														<label class="fs-4 opacity-75 mb-4">Foto Identitas:</label><br>
														<?php if(!empty($detail->file_identitas_user)){?>
															<img src="{{ url('storage/'.$detail->file_identitas_user) }}" class="" alt="Preview" style="max-height:150px;" loading="lazy"><br><br>
														<?php } else {?>
                                                            <label >File identitas tidak ada</label>
														<?php }?>
															<input type="file" name="foto" accept="image/*" class="form-control py-4" placeholder="Masukkan Foto">
															<span class="fs-8 text-muted">Ekstensi: jpg | jpeg | png</span>
													</div>
										
												</div>
												<div class="row">													
													<div class="col-md-12 mt-4">
														<button type="submit" id="btn-save" class="btn btn-marron-monitoring w-100 text-white"><i class="fa fa-save text-white"></i>Submit</button>
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

			$('#btn-save').on('click', function (e) {
				e.preventDefault();

				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('updateProfilUserAction') }}",
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
