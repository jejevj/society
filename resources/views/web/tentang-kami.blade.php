@include('layouts.header-v2')

				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush mb-10">
										<div class="card border-0 list-header-monitoring" style="background-image: url('{{ asset('storage/'.$set->gambar_tentang) }}'); ">
											<div class="card-body d-block text-start">
												<div class="row">
													<div class="col-12">
														<h2 class="list-title mb-2">
															Tentang Kami
														</h2>
													</div>
												</div>
											</div>
										</div>
										<div class="h-xl-100 mt-10 mb-4">
											<div class="mx-4 mb-4">
												<div class="row">
                                                    <div class="col-md-4">
                                                        <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end h-100" style="background-image:url('{{ asset('storage/'.$set->gambar2_tentang) }}"></div>
                                                    </div>
													<div class="col-md-8">
														<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
															<div class="card-header bg-white border-0 py-5 px-6">
																<div class="d-flex align-items-center gap-3">
																	<div>
																		<h2 class="fw-bold text-dark mb-1 fs-3">
																			Tentang Layanan Data Terbuka
																		</h2>
																		<span class="text-muted fs-6">
																			Kementerian Pertahanan Republik Indonesia
																		</span>
																	</div>
																</div>
															</div>
															<div class="card-body bg-white px-6 py-8">
																<div class="mb-6">
																	<div class="fs-5 text-gray-700 lh-lg text-justify tentang-content">
																		{!! $set->deskripsi_tentang !!}
																	</div>
																</div>
																<div class="separator my-8 opacity-25"></div>
																<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4">
																	<div class="d-flex align-items-center gap-3">
																		<img src="{{ asset('logo/logo-kemhan.png') }}" alt="Logo Kemhan"
																			style="height: 48px;" loading="lazy">
																		<div class="text-muted fs-7">
																			Sistem Data Terintegrasi dan Terbuka
																		</div>
																	</div>

																	<div class="text-muted fs-7 text-md-end">
																		© 2026 Kementerian Pertahanan Republik Indonesia.<br>
																		All rights reserved.
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
							</div>
							

@include('layouts.footer-v2')
