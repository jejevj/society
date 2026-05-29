@include('layouts.header-v2')

				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush" >
										<div class="card border-0 list-header-monitoring" style="background-image: url('{{ asset('storage/'.$set->gambar_organisasi) }}');">
											<div class="card-body d-block text-start">
												<div class="row">
													<div class="col-12">
														<h2 class="list-title mb-2">
															Organisasi/Satker
														</h2>
														<p class="list-desc mb-4 mw-600">
															{{ $set->deskripsi_organisasi}}
														</p>
													</div>
												</div>
											</div>
										</div>
											
										<div class="h-xl-100 mt-10 mb-4">
											<div class="mb-4 mx-4">
												<div class="row">
                                                @foreach($organisasi as $o)
                                                    <div class="col-md-3 my-3">
														<a href="{{ route('list', ['org' => $o->id_organisasi]) }}" class="text-decoration-none">
															<div class="card border-0 shadow-sm org-card">
																<div class="card-body p-3 d-flex flex-column">
																	<div class="d-flex align-items-center mb-2">
																		<img src="{{ url('storage/'.$o->tmp_foto_organisasi) }}" 
																			width="100"
																			class="me-2 rounded"
																			loading="lazy">

																		<div class="fw-semibold text-dark fs-5">
																			{{ $o->nama_organisasi }}
																		</div>
																	</div>
																	<div class="text-muted small mb-2 text-truncate-2 px-4">
																		{{ $o->deskripsi_organisasi }}
																	</div>
																	<div class="mt-auto d-flex justify-content-between small text-marron">
																		<span>
																			<i class="fa fa-database text-marron"></i> Dataset: {{ $o->total_dataset }}
																		</span>
																		<span>
																			<i class="fa fa-chart-bar text-marron"></i> Infografis: {{ $o->total_infografis }}
																		</span>
																	</div>
																</div>
															</div>
														</a>
													</div>
                                                @endforeach
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    {{ $organisasi->links('pagination::bootstrap-5') }}
                                                </div>
                                            </div>
										</div>
									</div>
								</div>
							</div>
						</div>
							

@include('layouts.footer-v2')
