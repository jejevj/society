
@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									{!!$breadcrumb!!}
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
											<a class="btn btn-warning btn-sm" href="{{ route('ref-menu') }}">
												<i class="fa fa-backward"></i> Kembali
											</a>
										</div>
									</div>
									<div class="card card-flush">
										
										<div class="card-body">
											<form id="actForm" class="mb-4" enctype="multipart/form-data">
												@csrf
												<div class="row">
													<div class="col-md-2 mt-4">
														<label class="fs-4 opacity-75 mb-4">Nama Menu:</label>
													</div>
													<div class="col-md-4 mt-4">
														<input type="text" name="nama" class="form-control py-4" placeholder="Masukkan Menu">
													</div>
													<div class="col-md-2 mt-4">
														<label class="fs-4 opacity-75 mb-4">Kode Menu:</label>
													</div>
													<div class="col-md-4 mt-4">
														<input type="text" name="kode" class="form-control py-4" placeholder="Masukkan Kode Menu">
													</div>
													<div class="col-md-2 mt-4">
														<label class="fs-4 opacity-75 mb-4">Icon Menu:</label>
													</div>
													<div class="col-md-4 mt-4">
														<input type="text" name="icon" class="form-control py-4" placeholder="Masukkan Icon Menu">
													</div>
													<div class="col-md-2 mt-4">
														<label class="fs-4 opacity-75 mb-4">Urutan Menu:</label>
													</div>
													<div class="col-md-4 mt-4">
														<input type="text" name="urutan" class="form-control py-4 only-number" placeholder="Masukkan Urutan Menu">
													</div>
													<div class="col-md-2 mt-4">
														<label class="fs-4 opacity-75 mb-4">Jenis Menu:</label>
													</div>
													<div class="col-md-4 mt-4">
														<select name="jenis" id="jenis_menu" class="form-control py-4">
															<option value="">-Pilih Jenis-</option>
															@foreach($status_menu as $s)
																<option value="{{ $s->kode_status }}">{{ $s->keterangan_status }}</option>
															@endforeach
														</select>
													</div>

													<div class="col-md-2 mt-4 master-menu" style="display:none;">
														<label class="fs-4 opacity-75 mb-4">Master Menu:</label>
													</div>
													<div class="col-md-4 mt-4 master-menu" style="display:none;">
														<select name="parent" class="form-control py-4">
															<option value="">-Pilih Master Menu-</option>
															@foreach($master_menu as $s)
																<option value="{{ $s->id_menu }}">{{ $s->nama_menu }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-md-2 mt-4">
														<label class="fs-4 opacity-75 mb-4">Deskripsi Menu:</label>
													</div>
													<div class="col-md-4 mt-4">
														<textarea name="deskripsi" class="form-control py-4"></textarea>
													</div>
																							
												</div>
												<hr>
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
		<script>
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			document.addEventListener("DOMContentLoaded", function () {
				const jenisMenu = document.getElementById("jenis_menu");
				const masterMenuElements = document.querySelectorAll(".master-menu");

				function toggleMasterMenu() {
					if (jenisMenu.value === "D") {
						masterMenuElements.forEach(el => el.style.display = "block");
					} else {
						masterMenuElements.forEach(el => el.style.display = "none");
					}
				}
				toggleMasterMenu();
				jenisMenu.addEventListener("change", toggleMasterMenu);
			});

			$('#btn-save').on('click', function (e) {
				e.preventDefault();

				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('addMenuAction') }}",
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
							window.location.href = "{{ route('ref-menu') }}";
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