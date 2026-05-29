
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
										<h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">{{ $menu }}
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
									<div class="card card-flush">
										<div class="card-body">
											<div class="row">
												<div class="col-md-12">
													<label class="fs-4 opacity-75">Tautan:</label>
													<input type="text" id="search-nama-tautan" class="form-control ps-12" placeholder="Masukkan tautan">
												</div>
												
												<div class="col-md-6 mt-4">
													<button id="searchTautan" class="btn btn-marron-submit btn0sm w-100"><i class="fa fa-search text-white"></i>Cari</button>
													
												</div>
												<div class="col-md-6 mt-4">
													<button id="resetSearchTautan" class="btn btn-warning btn-sm w-100"><i class="fa fa-rotate"></i>Reset</button>
												</div>
											</div>
										</div>
									</div>
									<div class="card card-flush mt-4">
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											<div class="card-title">
											</div>
											<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                                <a href="{{route('tambah-tautan')}}" class="btn btn-marron-submit"><i class="fa fa-plus text-white"></i>Tambah Tautan</a>
											</div>
										</div>
										<div class="card-body pt-0">
											<table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="">No</th>
                                                        <th class="min-w-100px">Tautan</th>
														<th class="min-w-100px">Image</th>
														<th class="min-w-100px">URL</th>
														<th class="min-w-10px">Urutan</th>
														<th class="min-w-70px">Actions</th>
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
			$(document).ready(function () {
                $('#mainTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('getTableTautan') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-tautan').val();
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'nama_tautan', name: 'nama_tautan'},
                        {data: 'foto', name: 'foto'},
						{data: 'link_tautan', name: 'link_tautan'},
                        {data: 'urutan_tautan', name: 'urutan_tautan'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                     ]
                });
                $('#searchTautan').click(function () {
                     $('#mainTable').DataTable().ajax.reload();
                });
                $('#resetSearchTautan').click(function () {
                    $('#search-nama-tautan').val('');
                    $('#mainTable').DataTable().ajax.reload();
                });
                    
            });
			
			$(document).on('click', '.btn-delete-tautan', function() {
                var keypost = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi', text: 'Apakah Anda yakin menghapus data tautan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        	url: "{{ route('deleteTautanAction') }}", 
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",  
                                key: keypost
                            },
                            success: function(response) {
                                Swal.fire({
        	                        title: 'Success',
                                    text: 'Data Berhasil Dihapus',
                                    icon: 'success'
                                }).then(function() {
        			                location.reload();  
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'Data Gagal Dihapus', 'error');
                            }
                        });
                    }
                });

            });
		</script>

@include('admin-panel.layouts.footer')