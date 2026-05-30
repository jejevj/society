@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Event', 'url' => null],
										['label' => 'Paper', 'url' => route('event-paper')],
										['label' => 'Edit Paper', 'url' => null],
									]])
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
									<div class="card card-flush">
										<div class="card-header align-items-center py-5">
											<div class="card-title"><span class="fs-5 fw-bold">Edit Paper</span></div>
											<div class="card-toolbar">
												<a href="{{ route('event-paper') }}" class="btn btn-sm btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
											</div>
										</div>
										<div class="card-body">

											{{-- Info Peserta --}}
											<div class="alert alert-info d-flex align-items-center mb-5">
												<i class="fa fa-user me-3"></i>
												<div>
													<strong>{{$detail->nama_peserta ?? '-'}}</strong> &nbsp;|&nbsp;
													{{$detail->email_peserta ?? '-'}} &nbsp;|&nbsp;
													{{$detail->instansi_peserta ?? '-'}} &nbsp;|&nbsp;
													Event: <strong>{{$detail->judul_event ?? '-'}}</strong>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-8">
													<div class="mb-5">
														<label class="form-label required">Judul Paper</label>
														<input type="text" id="input-judul" class="form-control" value="{{$detail->judul_paper ?? ''}}" placeholder="Judul paper">
													</div>
													<div class="mb-5">
														<label class="form-label required">Deskripsi / Abstrak</label>
														<textarea id="input-deskripsi" class="form-control" rows="5" placeholder="Deskripsi atau abstrak paper...">{{$detail->deskripsi_paper ?? ''}}</textarea>
													</div>
													<div class="mb-5">
														<label class="form-label">Ganti File (PDF/PPT/PPTX) — <span class="text-muted">kosongkan jika tidak ingin ganti</span></label>
														<input type="file" id="input-file" class="form-control" accept=".pdf,.ppt,.pptx">
													</div>
												</div>
												<div class="col-md-4">
													<div class="mb-5">
														<label class="form-label">File Saat Ini</label>
														@if($detail->file_paper)
															<a href="{{ asset('storage/' . $detail->file_paper) }}" target="_blank" class="btn btn-light-primary btn-sm d-block mb-2">
																<i class="fa fa-file"></i> Lihat File ({{ strtoupper($detail->tipe_file_paper) }})
															</a>
														@else
															<span class="text-muted">Tidak ada file</span>
														@endif
													</div>
													<div class="mb-5">
														<label class="form-label">Status Paper</label>
														<div>
															@if($detail->status_paper == 'A')
																<span class="badge bg-success fs-6">Approved</span>
															@elseif($detail->status_paper == 'R')
																<span class="badge bg-danger fs-6">Rejected</span>
															@else
																<span class="badge bg-warning text-dark fs-6">Pending</span>
															@endif
														</div>
													</div>
													@if($detail->catatan_paper)
													<div class="mb-5">
														<label class="form-label">Catatan Admin</label>
														<div class="alert alert-warning">{{$detail->catatan_paper}}</div>
													</div>
													@endif
												</div>
											</div>

											<div class="d-flex justify-content-end mt-4">
												<button type="button" id="btnSimpan" class="btn btn-marron-submit">
													<i class="fa fa-save"></i> Simpan Perubahan
												</button>
											</div>

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
				$('#btnSimpan').click(function () {
					var formData = new FormData();
					formData.append('_token', '{{ csrf_token() }}');
					formData.append('key', '{{ $id_paper }}');
					formData.append('judul', $('#input-judul').val());
					formData.append('deskripsi', $('#input-deskripsi').val());
					if ($('#input-file')[0].files.length > 0) {
						formData.append('file', $('#input-file')[0].files[0]);
					}
					$.ajax({
						url: "{{ route('updatePaperAction') }}",
						type: 'POST',
						data: formData,
						contentType: false,
						processData: false,
						success: function (res) {
							Swal.fire('Success', res.message, 'success').then(function () {
								window.location.href = "{{ route('event-paper') }}";
							});
						},
						error: function (xhr) {
							var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
							Swal.fire('Error', msg, 'error');
						}
					});
				});
			});
		</script>

@include('admin-panel.layouts.footer')
