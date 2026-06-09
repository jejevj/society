
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
										<h1 class="page-heading d-flex  fw-bolder fs-2 flex-column justify-content-center my-0 text-white">General Setting 
										    <span class="page-desc  opacity-50 fs-6 fw-bold pt-4"></span>
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
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">General setting</a>
                                                </li>
                                               
                                            </ul>
                                            <div class="tab-content mt-4" id="myTabContent">
                                                @include('admin-panel.konten-web.general-setting.konten')
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
		<script>
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			$('#btn-save').on('click', function (e) {
				e.preventDefault();

				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('updateSettingAction') }}",
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

            $('#btn-save-new').on('click', function (e) {
				e.preventDefault();
				let formData = new FormData($('#actForm2')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('addSliderAction') }}",
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
                    beforeSend: function () {
                		Swal.fire({
                            title: "Sedang diproses...",
                            text: "Mohon tunggu",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                    	});
                    },
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

            $('#btn-save-new2').on('click', function (e) {
				e.preventDefault();
				let formData = new FormData($('#actForm3')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('addSliderTextAction') }}",
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
                    beforeSend: function () {
                		Swal.fire({
                            title: "Sedang diproses...",
                            text: "Mohon tunggu",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                    	});
                    },
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
                        url: "{{ route('getTableSlider') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-slider').val();
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'judul_slider', name: 'judul_slider'},
                        {data: 'foto', name: 'foto'},
                        {data: 'urutan_slider', name: 'urutan_tautan'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                     ]
                });
                $('#searchSlider').click(function () {
                     $('#mainTable').DataTable().ajax.reload();
                });
                $('#resetSearchSlider').click(function () {
                    $('#search-nama-slider').val('');
                    $('#mainTable').DataTable().ajax.reload();
                });
                    
            });

            $(document).ready(function () {
                $('#mainTable2').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('getTableSliderText') }}",
                        data: function (d) {
                            d.nama = $('#search-nama-slider').val();
                        }
                    },
                                        
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'judul_slider', name: 'judul_slider'},
                        {data: 'deskripsi_slider', name: 'deskripsi_slider'},
                        {data: 'urutan_slider', name: 'urutan_tautan'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                     ]
                });
                $('#searchSlider').click(function () {
                     $('#mainTable2').DataTable().ajax.reload();
                });
                $('#resetSearchSlider').click(function () {
                    $('#search-nama-slider').val('');
                    $('#mainTable2').DataTable().ajax.reload();
                });
                    
            });
			
			$(document).on('click', '.btn-delete-slider', function() {
                var keypost = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi', text: 'Apakah Anda yakin menghapus data slider ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        	url: "{{ route('deleteSliderAction') }}", 
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