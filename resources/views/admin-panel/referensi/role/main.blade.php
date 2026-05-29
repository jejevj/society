
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
										<h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">Role
										<span class="page-desc text-white opacity-50 fs-6 fw-bold pt-4"></span>
									</div>
									<div class="d-flex gap-4 gap-lg-13">
										<div class="d-flex flex-column">
											<span class="text-white fw-bold fs-3 mb-1 text-center">{{ $role_count; }}</span>
											<div class="text-white opacity-50 fw-bold">Role</div>
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
													<label class="col-md-2 fs-4 opacity-75 mt-2">role:</label>
													<input type="text" id="search-nama-role" class="form-control ps-12" placeholder="Masukkan Role">
												</div>
												<div class="col-md-6 mt-4">
													<button id="searchRole" class="btn btn-marron-submit w-100"><i class="fa fa-search text-white"></i>Cari</button>
													
												</div>
												<div class="col-md-6 mt-4">
													<button id="resetSearchRole" class="btn btn-warning  w-100"><i class="fa fa-rotate"></i>Reset</button>
												</div>
											</div>
										</div>
									</div>
									<div class="card card-flush mt-4">
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											<div class="card-title">
											</div>
											<?php if($cek_permit['c']){?>
											<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
												<a href="{{ route('tambah-ref-role') }}" class="btn btn-marron-submit"> <i class="fa fa-plus text-white"></i> Tambah role</a>
											</div>
											<?php }?>
										</div>
										<div class="card-body pt-0">
											<table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="">No</th>
														<th class="text-center min-w-100px">Nama Role</th>
                                                        <th class="text-center min-w-100px">Kode Role</th>
														<th class="text-center min-w-100px">Deskripsi Role</th>
                                                        <td class="text-center min-w-100px">Created at</td>
														<th class="text-center min-w-70px">Actions</th>
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
                        url: "{{ route('getTableRole') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-role').val();
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'nama_role', name: 'nama_role'},
                        {data: 'kode_role', name: 'kode_role'},
						{data: 'deskripsi_role', name: 'deskripsi_role'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                     ]
                });
                $('#searchRole').click(function () {
                     $('#mainTable').DataTable().ajax.reload();
                });
                $('#resetSearchRole').click(function () {
                    $('#search-nama-role').val('');
                    $('#mainTable').DataTable().ajax.reload();
                });
                    
            });
			
			 $(document).on('click', '.btn-delete-role', function() {
                var keypost = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi', text: 'Apakah Anda yakin menghapus data role ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        	url: "{{ route('deleteRoleAction') }}", 
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