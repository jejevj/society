
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
											<a class="btn btn-warning btn-sm" href="{{ route('ref-role') }}">
												<i class="fa fa-backward"></i> Kembali
											</a>
										</div>
									</div>
									<?php if($cek_permit['c']){?>
									<div class="card card-flush">
										<div class="card-body">
											<form id="actForm" class="mb-4">
												@csrf
												<input type="hidden" name="key" value="{{$id_role}}">
												<div class="row">
													<label class="fs-4 opacity-75 mb-4 col-md-3">Nama Role:</label>
													<div class="col-md-9 mb-4">
														<input type="text" name="nama" class="form-control py-4" placeholder="Masukkan Role" value="{{ $detail->nama_role}}" disabled>
													</div>
													<label class="fs-4 opacity-75 mb-4 col-md-3">Menu:</label>
													<div class="col-md-9 mb-4">
                                                        <select name="menu_id" data-control="select2" data-placeholder="-Pilih-" class="form-select form-select-solid">
                                                            <option></option> <!-- biar placeholder jalan -->
                                                            <?php foreach($list_menu as $ls){ ?>
                                                                <option value="<?= $ls->id_menu ?>"><?= $ls->nama_menu ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
																					
												</div>
												<div class="row">
													<label class="fs-4 opacity-75 mb-4 col-md-3">Permission:</label>
													<div class="col-md-9 mb-4">
														<div class="d-flex gap-5">

															<div class="form-check">
																<input class="form-check-input"
																	type="checkbox"
																	name="permit_r"
																	value="1"
																	id="permit_r">
																<label class="form-check-label" for="permit_r">
																	Read
																</label>
															</div>
															<div class="form-check">
																<input class="form-check-input"
																	type="checkbox"
																	name="permit_c"
																	value="1"
																	id="permit_c">
																<label class="form-check-label" for="permit_c">
																	Create
																</label>
															</div>

															<div class="form-check">
																<input class="form-check-input"
																	type="checkbox"
																	name="permit_u"
																	value="1"
																	id="permit_u">
																<label class="form-check-label" for="permit_u">
																	Update
																</label>
															</div>

															<div class="form-check">
																<input class="form-check-input"
																	type="checkbox"
																	name="permit_d"
																	value="1"
																	id="permit_d">
																<label class="form-check-label" for="permit_d">
																	Delete
																</label>
															</div>

														</div>
													</div>
												</div>
                                                <br>
												<div class="row">
													
													<div class="col-md-12 mb-4">
														<button class="btn btn-marron-submit w-100" id="btn-save"><i class="fa fa-plus text-white"></i>Tambah Akses Menu</button>
													</div>
												</div>
											</form>
										</div>
									</div>
                                    <hr>
									<?php }?>
                                    <div class="card card-flush mt-4">
										<div class="card-body">
											<div class="row">
												
												<div class="col-md-12">
													<label class="fs-4 opacity-75 mt-2">Menu:</label>
													<input type="text" id="search-nama-menu" class="form-control ps-12" placeholder="Masukkan Menu">
												</div>
												<div class="col-md-6 mt-4">
													<button id="searchMenu" class="btn btn-marron-submit w-100"><i class="fa fa-search text-white"></i>Cari</button>
													
												</div>
												<div class="col-md-6 mt-4">
													<button id="resetSearchMenu" class="btn btn-warning  w-100"><i class="fa fa-rotate"></i>Reset</button>
												</div>
											</div>
										</div>
									</div>
                                    <div class="card card-flush ">
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											
										</div>
										<div class="card-body pt-0">
											<table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="">No</th>
														<th class="text-center min-w-100px">Menu</th>
														<td class="text-center min-w-100px">Permit</td>
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
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			$('#btn-save').on('click', function (e) {
				e.preventDefault();

				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('addAksesMenuAction') }}",
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
							location.reload();
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

            $(document).ready(function () {
                $('#mainTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('getTableMenuRole') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-menu').val();
                            d.key = '<?= $id_role?>'
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'nama_menu', name: 'nama_menu', className: 'text-center'},
						{data: 'permit', name: 'permit', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'created_at', name: 'created_at', className: 'text-center'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                     ]
                });
                $('#searchMenu').click(function () {
                     $('#mainTable').DataTable().ajax.reload();
                });
                $('#resetSearchMenu').click(function () {
                    $('#search-nama-menu').val('');
                    $('#mainTable').DataTable().ajax.reload();
                });
                    
            });
			
			 $(document).on('click', '.btn-delete-menu-role', function() {
                var keypost = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi', text: 'Apakah Anda yakin menghapus data akses menu ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        	url: "{{ route('deleteMenuRoleAction') }}", 
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