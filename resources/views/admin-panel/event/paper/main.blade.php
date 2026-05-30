@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Event', 'url' => null],
										['label' => 'Paper', 'url' => null],
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
													<label class="fs-6 fw-semibold mb-2">Paper Title</label>
													<input type="text" id="filter-nama" class="form-control" placeholder="Paper title...">
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
												<span class="fs-5 fw-bold">Submitted Papers List</span>
											</div>
										</div>
										<div class="card-body pt-0">
											<table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th>No</th>
														<th class="min-w-150px">Paper Title</th>
														<th class="min-w-150px">Participant</th>
														<th class="min-w-100px">Event</th>
														<th class="min-w-80px">File</th>
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

		{{-- Modal Paper Status --}}
		<div class="modal fade" id="modalStatusPaper" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalStatusPaperLabel">Update Paper Status</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="modal-paper-key">
						<input type="hidden" id="modal-paper-status">
						<div class="mb-3">
							<label class="form-label">Notes (optional)</label>
							<textarea id="modal-paper-catatan" class="form-control" rows="3" placeholder="Write notes for participant..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-marron-submit" id="btnSubmitPaperStatus">Save</button>
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
						url: "{{ route('getTablePaper') }}",
						data: function (d) {
							d.kode_event = $('#filter-event').val();
							d.nama = $('#filter-nama').val();
							d.status = $('#filter-status').val();
						}
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'judul_paper', name: 'judul_paper'},
						{data: 'peserta_info', name: 'peserta_info', orderable: false, searchable: false},
						{data: 'judul_event', name: 'judul_event'},
						{data: 'file_info', name: 'file_info', orderable: false, searchable: false},
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
								table.ajax.reload();
							});
						},
						error: function (xhr) {
							var msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
							Swal.fire('Error', msg, 'error');
						}
					});
				});

				$(document).on('click', '.btn-delete-paper', function () {
					var keypost = $(this).data('id');
					Swal.fire({
						title: 'Confirm', text: 'Delete this paper? Related files will also be deleted.', icon: 'warning',
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
										table.ajax.reload();
									});
								},
								error: function () {
									Swal.fire('Error', 'Failed to delete paper', 'error');
								}
							});
						}
					});
				});
			});
		</script>

@include('admin-panel.layouts.footer')
