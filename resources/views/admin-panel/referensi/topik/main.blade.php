
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
										<h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{$menu}} 
										<span class="page-desc opacity-50 fs-6 fw-bold pt-4"></span>
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
													<label class="fs-4 opacity-75 mt-2">Tag:</label>
													<input type="text" id="search-nama-topik" class="form-control ps-12" placeholder="Tag Name">
												</div>
												<div class="col-md-6 mt-4">
													<button id="searchTopik" class="btn btn-marron-submit btn-sm w-100"><i class="fa fa-search text-white"></i>Search</button>
												</div>
												<div class="col-md-6 mt-4">
													<button id="resetSearchTopik" class="btn btn-warning btn-sm w-100"><i class="fa fa-rotate"></i>Reset</button>
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
												<a href="{{ route('tambah-ref-topik') }}" class="btn btn-marron-submit btn-sm"><i class="fa fa-plus text-white"></i> Add Tag</a>
											</div>
											<?php }?>
										</div>
										<div class="card-body pt-0">
											<!--begin::Table-->
											<table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="">No</th>
														<th class="min-w-70px">Code</th>
														<th class="min-w-100px">Name</th>
														<th class="min-w-100px">Orders</th>
                                                        <th class="min-w-100px">Created at</th>
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
                        url: "{{ route('getTableTopik') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-topik').val();
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'kode_topik', name: 'kode_topik'},
						{data: 'nama_topik', name: 'nama_topik'},
						{data: 'urutan_topik', name: 'urutan_topik'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                     ]
                });
                $('#searchTopik').click(function () {
                     $('#mainTable').DataTable().ajax.reload();
                });
                $('#resetSearchTopik').click(function () {
                    $('#search-nama-role').val('');
                    $('#mainTable').DataTable().ajax.reload();
                });
                    
            });
			
			 $(document).on('click', '.btn-delete-topik', function() {
                var keypost = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi', text: 'Are you sure you want to delete this tag data?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        	url: "{{ route('deleteTopikAction') }}", 
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",  
                                key: keypost
                            },
                            success: function(response) {
                                Swal.fire({
        	                        title: 'Success',
                                    text: 'Data Successfully Deleted',
                                    icon: 'success'
                                }).then(function() {
        			                location.reload();  
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'Data Deletion Failed', 'error');
                            }
                        });
                    }
                });

            });
		</script>

@include('admin-panel.layouts.footer')