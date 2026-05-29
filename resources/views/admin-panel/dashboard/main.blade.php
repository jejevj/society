
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
										<h1 class="page-heading d-flex  fw-bolder fs-2 flex-column justify-content-center my-0">{{$menu}} 
										<span class="page-desc  opacity-50 fs-6 fw-bold pt-4"></span>
										</h1>
									</div>
									<div class="d-flex gap-4 gap-lg-13">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="app-container container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="">
										<div class="card-body">
											<div class="row">
												
												<div class="col-md-4 mb-4">
													<div class="card h-100 border-0 shadow-sm statistik-card statistik-success">
														<div class="card-body p-4">

															<div class="d-flex align-items-center justify-content-between mb-4">
																<div>
																	<div class="fs-4 fw-semibold mb-1">
																		Event
																	</div>

																	<div class="fs-1 fw-bold text-dark">
																		0
																	</div>
																</div>

																<div class="statistik-icon">
																	<i class="fa fa-database text-maroon"></i>
																</div>
															</div>

															<div class="border-top pt-3">
																<div class="d-flex justify-content-between mb-2">
																	<span class="text-dark">
																		Terupload
																	</span>
																	<span class="fw-bold text-dark">
																		0
																	</span>
																</div>
																<div class="d-flex justify-content-between">
																	<span class="text-dark">
																		Dilihat
																	</span>

																	<span class="fw-bold text-dark">
																		0
																	</span>
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
					</div>
				</div>
			</div>
		</div>
		

@include('admin-panel.layouts.footer')