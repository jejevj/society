
@include('admin-panel.layouts.header')
<style>
.json-log {
    max-width: 300px;
    max-height: 200px;
    overflow: auto;
    background: #f8f9fa;
    padding: 10px;
    font-size: 12px;
    border-radius: 6px;
}
</style>
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									{!! $breadcrumb !!}
								</div>
								<div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
									<div class="page-title d-flex align-items-center me-3">
										<h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">Log Aktivitas
										<span class="page-desc text-white opacity-50 fs-6 fw-bold pt-4"></span>
									</div>
									<div class="d-flex gap-4 gap-lg-13">
										<div class="d-flex flex-column">
											<span class="text-white fw-bold fs-3 mb-1 text-center">1512</span>
											<div class="text-white opacity-50 fw-bold">Log Aktivitas</div>
										</div>										
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
											<div class="row">
												<div class="col-md-12">
													<label class="fs-4 opacity-75 mt-2">Aktivitas:</label>
													<input type="text" id="search-nama-log" class="form-control ps-12" placeholder="Masukkan Aktivitas">
												</div>
												<div class="col-md-6 mt-4">
													<button id="searchLog" class="btn btn-marron-submit btn-sm w-100"><i class="fa fa-search text-white"></i>Cari</button>
													
												</div>
												<div class="col-md-6 mt-4">
													<button id="resetSearchLog" class="btn btn-warning btn-sm w-100"><i class="fa fa-rotate"></i>Reset</button>
												</div>
											</div>
										</div>
									</div>
									<div class="card card-flush mt-4">
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											<div class="card-title">
											</div>
											<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
											</div>
										</div>
										<div class="card-body pt-0">
											<table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="">No</th>
														<th class=" min-w-100px">Pengguna</th>
                                                        <td class=" min-w-100px">Info</td>
														<th class=" min-w-50px">Waktu</th>
														<th class="">Data Lama</th>
														<th class="">Data Baru</th>
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
		<script>
			function toggleJson(btn){
				let pre = btn.nextElementSibling;

				if(pre.classList.contains('d-none')){
					pre.classList.remove('d-none');
					btn.innerText = 'Tutup';
				}else{
					pre.classList.add('d-none');
					btn.innerText = 'Lihat';
				}
			}
			$(document).ready(function () {
                $('#mainTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('getTableLog') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-log').val();
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'user_log', name: 'user_log'},
						{data: 'deskripsi_log', name: 'deskripsi_log'},
                        {data: 'created_at', name: 'created_at'},
						{data: 'data_lama_log', name: 'data_lama_log'},
						{data: 'data_baru_log', name: 'data_baru_log'},
                     ]
                });
                $('#searchLog').click(function () {
                     $('#mainTable').DataTable().ajax.reload();
                });
                $('#resetSearchLog').click(function () {
                    $('#search-nama-log').val('');
                    $('#mainTable').DataTable().ajax.reload();
                });
                    
            });
</script>

@include('admin-panel.layouts.footer')