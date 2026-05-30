@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Event', 'url' => null],
										['label' => 'Participant Registration', 'url' => null],
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

									{{-- Filter Card --}}
									<div class="card card-flush">
										<div class="card-body">
											<div class="row">
												<div class="col-md-4">
													<label class="fs-6 fw-semibold mb-2">Event</label>
													<select id="filter-event" class="form-select">
														<option value="">-- All Events --</option>
														@foreach($events as $ev)
														<option value="{{$ev->kode_event}}">{{$ev->judul_event}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-4">
													<label class="fs-6 fw-semibold mb-2">Participant Name</label>
													<input type="text" id="filter-nama" class="form-control" placeholder="Participant name...">
												</div>
												<div class="col-md-4">
													<label class="fs-6 fw-semibold mb-2">Status</label>
													<select id="filter-status" class="form-select">
														<option value="">-- All Status --</option>
														<option value="P">Pending</option>
														<option value="A">Approved</option>
														<option value="R">Rejected</option>
													</select>
												</div>
												<div class="col-md-6 mt-4">
													<button id="btnSearch" class="btn btn-marron-submit w-100"><i class="fa fa-search text-white"></i> Search</button>
												</div>
												<div class="col-md-6 mt-4">
													<button id="btnReset" class="btn btn-warning w-100"><i class="fa fa-rotate"></i> Reset</button>
												</div>
											</div>
										</div>
									</div>

									{{-- Table Card --}}
									<div class="card card-flush mt-4">
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											<div class="card-title">
												<span class="fs-5 fw-bold">Participant Registration List</span>
											</div>
										</div>
										<div class="card-body pt-0">
											<table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th>No</th>
														<th class="min-w-120px">Participant Name</th>
														<th class="min-w-100px">Email</th>
														<th class="min-w-100px">Institution</th>
														<th class="min-w-100px">Event</th>
														<th class="min-w-80px">Paper</th>
														<th class="min-w-80px">Status</th>
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

		{{-- Notes Modal --}}
		<div class="modal fade" id="modalCatatan" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalCatatanLabel">Notes</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="modal-key">
						<input type="hidden" id="modal-status">
						<div class="mb-3">
							<label class="form-label">Notes (optional)</label>
							<textarea id="modal-catatan" class="form-control" rows="3" placeholder="Write notes..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-marron-submit" id="btnSubmitStatus">Save</button>
					</div>
				</div>
			</div>
		</div>

		<script>
			$(document).ready(function () {
				var table = $('#mainTable').DataTable({
					processing: true,
					serverSide: true,
					ajax: {
						url: "{{ route('getTableRegistrasi') }}",
						data: function (d) {
							d.kode_event = $('#filter-event').val();
							d.nama = $('#filter-nama').val();
							d.status = $('#filter-status').val();
						}
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'nama_peserta', name: 'nama_peserta'},
						{data: 'email_peserta', name: 'email_peserta'},
						{data: 'instansi_peserta', name: 'instansi_peserta'},
						{data: 'judul_event', name: 'judul_event'},
						{data: 'paper_info', name: 'paper_info', orderable: false, searchable: false},
						{data: 'status_badge', name: 'status_badge', orderable: false, searchable: false},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					]
				});

				$('#btnSearch').click(function () {
					table.ajax.reload();
				});
				$('#btnReset').click(function () {
					$('#filter-event').val('');
					$('#filter-nama').val('');
					$('#filter-status').val('');
					table.ajax.reload();
				});

				// Approve
				$(document).on('click', '.btn-approve-registrasi', function () {
					$('#modal-key').val($(this).data('id'));
					$('#modal-status').val('A');
					$('#modalCatatanLabel').text('Approve Registration');
					$('#modal-catatan').val('');
					$('#modalCatatan').modal('show');
				});

				// Reject
				$(document).on('click', '.btn-reject-registrasi', function () {
					$('#modal-key').val($(this).data('id'));
					$('#modal-status').val('R');
					$('#modalCatatanLabel').text('Reject Registration');
					$('#modal-catatan').val('');
					$('#modalCatatan').modal('show');
				});

				// Submit status
				$('#btnSubmitStatus').click(function () {
					$.ajax({
						url: "{{ route('updateStatusRegistrasiAction') }}",
						type: 'POST',
						data: {
							_token: "{{ csrf_token() }}",
							key: $('#modal-key').val(),
							status: $('#modal-status').val(),
							catatan: $('#modal-catatan').val(),
						},
						success: function (res) {
							$('#modalCatatan').modal('hide');
							Swal.fire('Success', res.message, 'success').then(function () {
								table.ajax.reload();
							});
						},
						error: function (xhr) {
							var msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
							Swal.fire('Error', msg, 'error');
						}
					});
				});

				// Delete registration
				$(document).on('click', '.btn-delete-registrasi', function () {
					var keypost = $(this).data('id');
					Swal.fire({
						title: 'Confirm', text: 'Delete this registration and all related papers?', icon: 'warning',
						showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
						confirmButtonText: 'Yes', cancelButtonText: 'Cancel'
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								url: "{{ route('deleteRegistrasiAction') }}",
								type: 'POST',
								data: { _token: "{{ csrf_token() }}", key: keypost },
								success: function (res) {
									Swal.fire('Success', res.message, 'success').then(function () {
										table.ajax.reload();
									});
								},
								error: function () {
									Swal.fire('Error', 'Failed to delete data', 'error');
								}
							});
						}
					});
				});
			});
		</script>

@include('admin-panel.layouts.footer')
