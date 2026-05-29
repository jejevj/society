
@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<li class="breadcrumb-item text-white fw-bold lh-1">
											<a href="index.html" class="text-white text-hover-primary">
												<i class="ki-outline ki-home text-white fs-3"></i>
											</a>
										</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Konten Web</li>
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Link Tautan</li>
                                        <li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
										</li>
										<li class="breadcrumb-item text-white fw-bold lh-1">Tambah Link Tautan</li>
                                        
									</ul>
								</div>
								<div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
									<div class="page-title d-flex align-items-center me-3">
										<h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">Tambah Link Tautan
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
											<a class="btn btn-warning btn-sm" href="{{ route('tautan') }}">
												<i class="fa fa-backward"></i> Kembali
											</a>
										</div>
									</div>
									<div class="card card-flush">
										<div class="card-body">
											<form id="actForm" class="mb-4" enctype="multipart/form-data">
												@csrf
												<div class="row">
													<div class="col-md-6 mb-4">
														<label class="fs-4 opacity-75 mb-4">Tautan:</label><br>
														<input name="nama" type="text" class="form-control py-4" value="">
													</div>
													<div class="col-md-6 mb-4">
														<label class="fs-4 opacity-75 mb-4">Image:</label><br>
														<input type="file" name="gambar" class="form-control py-4" placeholder="Masukkan gambar">
													</div>
													<div class="col-md-6 mb-4">
														<label class="fs-4 opacity-75 mb-4">Link:</label><br>
														<input type="text" name="link" class="form-control py-4" value="">
													</div>
													<div class="col-md-6 mb-4">
														<label class="fs-4 opacity-75 mb-4">Urutan:</label><br>
														<input type="number" name="urutan" class="form-control py-4" value="">
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
		<script>
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			$('#btn-save').on('click', function (e) {
				e.preventDefault();

				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('addTautanAction') }}",
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
							window.location.href = "{{ route('tautan') }}";
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
 <!-- <script>
document.getElementById('fileUpload').addEventListener('change', function(event) {
    const fileName = event.target.files.length ? event.target.files[0].name : 'Tidak ada file dipilih';
    document.getElementById('file-name').textContent = "File dipilih: " + fileName;
});
</script> -->

@include('admin-panel.layouts.footer')