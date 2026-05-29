@include('layouts.header-v2')

				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush">
                                        <div class="card border-0 list-header-monitoring" style="background-image: url('{{ asset('storage/'.$set->gambar_permohonan) }}'); ">
											<div class="card-body d-block text-start">
												<div class="row">
													<div class="col-12">
														<h2 class="list-title mb-2">
															Monitoring Permohonan
														</h2>
														<p class="list-desc mb-4 mw-600">
															{{ $set->deskripsi_permohonan}}
														</p>
													</div>
												</div>
											</div>
										</div>
										<div class="card border-0 shadow-sm rounded-3">
                                            <div class="card-body p-4">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4 d-none d-md-block">
                                                        <div class="h-100 d-flex align-items-center justify-content-center">
                                                            <img src="{{ url('storage/'.$set->gambar2_permohonan) }}" 
                                                                class="img-fluid rounded opacity-75 mh-220"
                                                                loading="lazy">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <h5 class="fw-bold mb-1">Cek Status Permohonan</h5>
                                                            <div class="text-muted small">
                                                                Masukkan identitas dan kode permohonan untuk melihat status informasi.
                                                            </div>
                                                        </div>
                                                        <form id="actForm" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted mb-1">
                                                                    NIK / No. Identitas
                                                                </label>
                                                                <input type="text" name="identitas" class="form-control form-control-lg" placeholder="Contoh: 3201xxxxxxxxxxxx">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted mb-1">
                                                                    Kode Permohonan
                                                                </label>
                                                                <input type="text" name="kode" class="form-control form-control-lg" placeholder="Masukkan kode permohonan">
                                                            </div>
                                                            <div class="d-grid">
                                                                <button class="btn btn-gold fw-semibold py-2 h-btn-50 text-white" type="submit">
                                                                    <i class="fa fa-search me-1 text-white"></i> Cek Permohonan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
                            <div class="modal fade" id="resultModal" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div id="resultHeader" class="p-4 text-center text-white">
                                            <h2 id="resultTitle" class="fw-bold mb-0 text-white"></h2>
                                        </div>
                                        <div class="modal-body text-center p-5">
                                            <div id="resultIcon" class="mb-4"></div>
                                            <p id="resultMessage" class="fs-4 text-dark"></p>
                                        </div>
                                        <div class="text-center pb-4">
                                            <button type="button" class="btn btn-secondary px-5 " data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $.ajaxSetup({
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                                });

                                $('#actForm').on('submit', function (e) {
                                    e.preventDefault();

                                    let formData = new FormData(this);

                                    $.ajax({
                                        url: "{{ route('monitoringAction') }}",
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function (response) {

                                        if (response.success) {
                                            $('#resultHeader').css('background', 'linear-gradient(135deg, #d4af37, #b6a449)');
                                            $('#resultTitle').text(response.hdr);

                                            $('#resultIcon').html(response.icon);

                                        } else {
                                            $('#resultHeader').css('background', 'linear-gradient(135deg, #6b1f1f, #a83232)');
                                            $('#resultTitle').text('Gagal');

                                            $('#resultIcon').html(`
                                                <i class="fa fa-times-circle text-danger" style="font-size:70px;"></i>
                                            `);
                                        }

                                        $('#resultMessage').html(response.message);
                                        $('#resultModal').modal('show');
                                        
                                        },error: function (xhr) {
                                            let message = 'Terjadi kesalahan.';

                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                message = xhr.responseJSON.message;
                                            }

                                            $('#resultHeader').css('background', 'linear-gradient(135deg, #dc3545, #ff6b6b)');
                                            $('#resultTitle').text('Error');

                                            $('#resultIcon').html(`
                                                <i class="fa fa-exclamation-triangle text-warning" style="font-size:70px;"></i>
                                            `);

                                            $('#resultMessage').text(message);
                                            $('#resultModal').modal('show');
                                        }
                                    });
                                });
                            </script>
							

@include('layouts.footer-v2')
