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
															Topik
														</h2>
                                                        <p class="list-desc mb-4 mw-600">
															Temukan data yang anda inginkan dibawah ini sesuai topik yang ada
														</p>
														
													</div>
												</div>
											</div>
										</div>
											
										<div class="h-xl-100 mt-10 mb-4">
											<div class="mb-4 mx-4">
												<div class="row">
                                                @foreach($topik as $o)
                                                    <div class="col-md-2 my-3">
                                                        <a href="{{ route('list', ['topik' => $o->id_topik]) }}" 
                                                        class="text-decoration-none">

                                                            <div class="card border-0 shadow-sm org-card h-100">

                                                                <div class="card-body p-3 d-flex flex-column text-center">

                                                                    <div class="mb-3">
                                                                        <img src="{{ url('storage/'.$o->gambar_topik) }}" 
                                                                            class="rounded img-fluid w-220"
                                                                            loading="lazy">
                                                                    </div>

                                                                    <div class="fw-semibold text-dark fs-5 mb-3">
                                                                        {{ $o->nama_topik }}
                                                                    </div>

                                                                    <div class="mt-auto d-flex justify-content-center gap-3 small text-marron flex-wrap">

                                                                        <span>
                                                                            <i class="fa fa-database text-marron"></i>
                                                                            Dataset: {{ $o->total_dataset }}
                                                                        </span>

                                                                        <span>
                                                                            <i class="fa fa-chart-bar text-marron"></i>
                                                                            Infografis: {{ $o->total_infografis }}
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
                                                    {{ $topik->links('pagination::bootstrap-5') }}
                                                </div>
                                            </div>
										</div>
									</div>
								</div>
							</div>
						</div>
							

@include('layouts.footer-v2')
