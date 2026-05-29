
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
										<h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">Data 
										<span class="page-desc text-white opacity-50 fs-6 fw-bold pt-4"></span>
									</div>
									<div class="d-flex gap-4 gap-lg-13">
										<div class="d-flex flex-column">
											<span class="text-white fw-bold fs-3 mb-1 text-center">{{$total_dataset + $total_infografis}}</span>
											<div class="text-white opacity-50 fw-bold">Total</div>
										</div>
										<div class="d-flex flex-column">
											<span class="text-white fw-bold fs-3 mb-1 text-center">{{$total_dataset}}</span>
											<div class="text-white opacity-50 fw-bold">Dataset</div>
										</div>
										<div class="d-flex flex-column">
											<span class="text-white fw-bold fs-3 mb-1 text-center">{{$total_infografis}}</span>
											<div class="text-white opacity-50 fw-bold">Infografis</div>
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
									@include('admin-panel.data.chart')
									<div class="card card-flush mt-4">
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											<div class="card-title">
											</div>
											<?php if($cek_permit['c']){?>
											<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
												<a href="{{ route('tambah-data') }}" class="btn btn-marron-monitoring text-white btn-sm"><i class="fa fa-plus"></i> Tambah Dataset</a>
												<a href="{{ route('tambah-data-infografis') }}" class="btn btn-marron-monitoring text-white btn-sm"><i class="fa fa-plus"></i> Tambah Infografis</a>
											</div>
											<?php }?>
										</div>
										<div class="card-body pt-0">
											<table id="mainTable" class="table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="">No</th>
														<th class="min-w-200px"></th>
														<th class="w-200px">Judul</th>
														<th class="min-w-10px">Info</th>
														<th class="min-w-10px">Info</th>
														<th class="min-w-100px">Waktu</th>
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
                        url: "{{ route('getTableData') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-data').val();
							d.tipe = $('#search-tipe-data').val();
							d.organisasi = $('#search-organisasi-data').val();
							d.sifat = $('#search-sifat-data').val();
							d.awal = $('#search-awal-data').val();
							d.akhir = $('#search-akhir-data').val();
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'foto', name: 'foto', orderable: false, searchable: false},
						{data: 'judul_master', name: 'judul_master'},
						
						{data: 'info', name: 'info', orderable: false, searchable: false},
						{data: 'info2', name: 'info2', orderable: false, searchable: false},
						{data: 'waktu', name: 'waktu', orderable: false, searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
                $('#searchData').click(function () {
                     $('#mainTable').DataTable().ajax.reload();
                });
                $('#resetSearchData').click(function () {
                    $('#search-nama-data').val('');
					$('#search-tipe-data').val('');
					$('#search-organisasi-data').val('');
					$('#search-sifat-data').val('');
					$('#search-awal-data').val('');
					$('#search-akhir-data').val('');
                    $('#mainTable').DataTable().ajax.reload();
                });
                    
            });
			
			$(document).on('click', '.btn-delete-data', function() {
                var keypost = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi', text: 'Apakah Anda yakin menghapus data ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        	url: "{{ route('deleteDataAction') }}", 
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