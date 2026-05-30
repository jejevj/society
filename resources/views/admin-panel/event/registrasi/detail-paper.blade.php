@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Event', 'url' => null],
										['label' => 'Registrasi Peserta', 'url' => route('event-registrasi')],
										['label' => 'Detail Paper', 'url' => null],
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

									{{-- Info Peserta --}}
									<div class="card card-flush mb-4">
										<div class="card-header align-items-center py-5">
											<div class="card-title"><span class="fs-5 fw-bold">Informasi Peserta</span></div>
											<div class="card-toolbar">
												<a href="{{ route('event-registrasi') }}" class="btn btn-sm btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
											</div>
										</div>
										<div class="card-body pt-0">
											<div class="row">
												<div class="col-md-6">
													<table class="table table-borderless">
														<tr><td class="fw-bold w-150px">Kode Registrasi</td><td>: {{$registrasi->kode_registrasi ?? '-'}}</td></tr>
														<tr><td class="fw-bold">Nama</td><td>: {{$registrasi->nama_peserta ?? '-'}}</td></tr>
														<tr><td class="fw-bold">Email</td><td>: {{$registrasi->email_peserta ?? '-'}}</td></tr>
														<tr><td class="fw-bold">No HP</td><td>: {{$registrasi->no_hp_peserta ?? '-'}}</td></tr>
													</table>
												</div>
												<div class="col-md-6">
													<table class="table table-borderless">
														<tr><td class="fw-bold w-150px">Instansi</td><td>: {{$registrasi->instansi_peserta ?? '-'}}</td></tr>
														<tr><td class="fw-bold">Event</td><td>: {{$registrasi->judul_event ?? '-'}}</td></tr>
														<tr>
															<td class="fw-bold">Status</td>
															<td>:
																@if($registrasi->status_registrasi == 'A')
																	<span class="badge bg-success">Approved</span>
																@elseif($registrasi->status_registrasi == 'R')
																	<span class="badge bg-danger">Rejected</span>
																@else
																	<span class="badge bg-warning text-dark">Pending</span>
																@endif
															</td>
														</tr>
														<tr><td class="fw-bold">Terdaftar</td><td>: {{$registrasi->created_at ?? '-'}}</td></tr>
													</table>
												</div>
											</div>
										</div>
									</div>

									{{-- Paper Table --}}
									<div class="card card-flush">
										<div class="card-header align-items-center py-5">
											<div class="card-title"><span class="fs-5 fw-bold">Paper yang Disubmit</span></div>
										</div>
										<div class="card-body pt-0">
											<table id="paperTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th>No</th>
														<th class="min-w-150px">Judul Paper</th>
														<th class="min-w-150px">Deskripsi</th>
														<th class="min-w-80px">File</th>
														<th class="min-w-80px">Status</th>
														<th class="min-w-80px">Catatan</th>
														<th class="text-center min-w-100px">Actions</th>
													</tr>
												</thead>
											</table>
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

		{{-- Modal Status Paper --}}
		<div class="modal fade" id="modalStatusPaper" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalStatusPaperLabel">Update Status Paper</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="modal-paper-key">
						<input type="hidden" id="modal-paper-status">
						<div class="mb-3">
							<label class="form-label">Catatan (opsional)</label>
							<textarea id="modal-paper-catatan" class="form-control" rows="3" placeholder="Tulis catatan untuk peserta..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
						<button type="button" class="btn btn-marron-submit" id="btnSubmitPaperStatus">Simpan</button>
					</div>
				</div>
			</div>
		</div>

		<script>
			$(document).ready(function () {
				var kodeRegistrasi = '{{ $registrasi->kode_registrasi ?? '' }}';

				var paperTable = $('#paperTable').DataTable({
					processing: true,
					serverSide: true,
					ajax: {
						url: "{{ route('getTablePaperByRegistrasi') }}",
						data: function (d) {
							d.kode_registrasi = kodeRegistrasi;
						}
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'judul_paper', name: 'judul_paper'},
						{data: 'deskripsi_paper', name: 'deskripsi_paper'},
						{data: 'file_info', name: 'file_info', orderable: false, searchable: false},
						{data: 'status_badge', name: 'status_badge', orderable: false, searchable: false},
						{data: 'catatan_paper', name: 'catatan_paper'},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					]
				});

				$(document).on('click', '.btn-approve-paper', function () {
					$('#modal-paper-key').val($(this).data('id'));
					$('#modal-paper-status').val('A');
					$('#modalStatusPaperLabel').text('Approve Paper');
					$('#modal-paper-catatan').val('');
					$('#modalStatusPaper').modal('show');
				});

				$(document).on('click', '.btn-reject-paper', function () {
					$('#modal-paper-key').val($(this).data('id'));
					$('#modal-paper-status').val('R');
					$('#modalStatusPaperLabel').text('Reject Paper');
					$('#modal-paper-catatan').val('');
					$('#modalStatusPaper').modal('show');
				});

				$('#btnSubmitPaperStatus').click(function () {
					$.ajax({
						url: "{{ route('updateStatusPaperAction') }}",
						type: 'POST',
						data: {
							_token: "{{ csrf_token() }}",
							key: $('#modal-paper-key').val(),
							status: $('#modal-paper-status').val(),
							catatan: $('#modal-paper-catatan').val(),
						},
						success: function (res) {
							$('#modalStatusPaper').modal('hide');
							Swal.fire('Success', res.message, 'success').then(function () {
								paperTable.ajax.reload();
							});
						},
						error: function (xhr) {
							var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
							Swal.fire('Error', msg, 'error');
						}
					});
				});

				$(document).on('click', '.btn-delete-paper', function () {
					var keypost = $(this).data('id');
					Swal.fire({
						title: 'Confirm', text: 'Hapus paper ini?', icon: 'warning',
						showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
						confirmButtonText: 'Yes', cancelButtonText: 'Cancel'
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								url: "{{ route('deletePaperAction') }}",
								type: 'POST',
								data: { _token: "{{ csrf_token() }}", key: keypost },
								success: function (res) {
									Swal.fire('Success', res.message, 'success').then(function () {
										paperTable.ajax.reload();
									});
								},
								error: function () {
									Swal.fire('Error', 'Gagal menghapus paper', 'error');
								}
							});
						}
					});
				});
			});
		</script>

@include('admin-panel.layouts.footer')
