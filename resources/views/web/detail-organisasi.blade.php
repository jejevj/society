@include('layouts.header-v2')

				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" style="margin-top:200px;" id="kt_app_wrapper">
					
					<!--begin::Wrapper container-->
					<div class="container-xxl">
						<!--begin::Main-->
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<!--begin::Content wrapper-->
							<div class="d-flex flex-column flex-column-fluid">
								<!--begin::Content-->
								<div id="kt_app_content" class="app-content">
									<!--begin::Products-->
									<div class="card card-flush mb-10">
										<!--begin::Card header-->
										<!--begin::Engage widget 8-->
											<div class="card border-0 h-md-100" data-bs-theme="light" style="background: linear-gradient(10deg, rgba(53, 127, 212, 1) 50%, rgba(30, 129, 96, 1) 100%);">
												<!--begin::Body-->
												<div class="card-body">
													<!--begin::Row-->
													<div class="row align-items-center h-100">
														<!--begin::Col-->
														<div class="col-7 ps-xl-13">
															<!--begin::Title-->
															<div class="text-white mb-6 pt-6">
																<span class="fs-2qx fw-bold">ITJEN</span>
																<span class="fs-3 fw-semibold me-2 d-block lh-1 pb-2 opacity-75">Inspektorat Jendral Kemhan RI</span>
																
															</div>
															<!--end::Title-->
															
														</div>
														<!--end::Col-->
														<!--begin::Col-->
														<div class="col-5 pt-10">
															<!--begin::Illustration-->
															<div class="bgi-no-repeat bgi-size-contain bgi-position-x-end h-150px" style="background-image:url('{{ asset('assets/logo-v2/certificate.png') }}"></div>
															<!--end::Illustration-->
														</div>
														<!--end::Col-->
													</div>
													<!--end::Row-->
												</div>
												<!--end::Body-->
											</div>
											<!--end::Engage widget 8-->
											<div class="card card-flush h-xl-100 mt-10 mb-4">
												
												<div class="card-body mb-4">
													<div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-8 mt-2">
                                                                    <input type="text" class="form-control form-control-lg border-start-1 border-end-1 ps-2" style="height:60px;" placeholder="Masukkan kata kunci organisasi/satker..." />
                                                                </div>
                                                                <div class="col-md-4 mt-2">
                                                                     <button class="btn btn-primary w-100 h-100">
                                                                        <i class="fa fa-search"></i> Cari
                                                                    </button>
                                                                </div>
                                                               
                                                            </div>                                                            
														</div><br>
                                                        <div class="col-md-12 mt-10 mb-10">
                                                            <div class="card">
                                                                <div class="card-body text-center w-100" style="background: linear-gradient(90deg,rgb(87, 235, 124) 40%,rgb(80, 212, 162) 100%)">
                                                                    <h3 class="fs-1 mb-0 text-white">DATA SATKER ITJEN</h3>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-3">
                                                            <div class="row">
                                                                <div class="col-md-12 my-3">
                                                                    <div class="card border border-primary">
                                                                        <div class="card-body">
                                                                            <h2 class="fs-2 mb-0 text-dark opacity-75">Program Keja Pengawasan Tahunan</h2>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 my-3">
                                                                    <div class="card border border-primary">
                                                                        <div class="card-body">
                                                                            <h2 class="fs-2 mb-0 text-dark opacity-75">Keuangan Itjen</h2>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-md-12">
                                                                </div>
                                                            </div>
                                                                
                                                        </div> -->
                                                        <div class="col-md-3 mt-10">
                                                            <div class="card shadow-sm">
                                                                <div class="card-header" style="background: linear-gradient(90deg,rgb(100, 104, 98) 40%,rgb(71, 75, 75) 100%); min-height:0;; min-height:0;">
                                                                    <h5 class="mt-3 text-white"><i class="fa-solid fa-filter text-white"></i> Kategori</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" checked value="semua" id="filterSemua">
                                                                        <label class="form-check-label" for="filterSemua">Semua</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="dataset" id="filterDataset">
                                                                        <label class="form-check-label" for="filterDataset">Dataset</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="infoGrafis" id="filterInfoGrafis">
                                                                        <label class="form-check-label" for="filterInfoGrafis">Info Grafis</label>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="card shadow-sm mt-2">
                                                                <div class="card-header" style="background: linear-gradient(90deg,rgb(100, 104, 98) 40%,rgb(71, 75, 75) 100%); min-height:0;">
                                                                    <h5 class="mt-3 text-white"><i class="fa-solid fa-filter text-white"></i> Organisasi/Satker</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox"  value="semua" id="filterSemua">
                                                                        <label class="form-check-label" for="filterSemua">Semua</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" checked value="dataset" id="filterDataset">
                                                                        <label class="form-check-label" for="filterDataset">Itjen</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="infoGrafis" id="filterInfoGrafis">
                                                                        <label class="form-check-label" for="filterInfoGrafis">Strahan</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="infoGrafis" id="filterInfoGrafis">
                                                                        <label class="form-check-label" for="filterInfoGrafis">Renhan</label>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="card shadow-sm mt-2">
                                                                <div class="card-header" style="background: linear-gradient(90deg,rgb(100, 104, 98) 40%,rgb(71, 75, 75) 100%); min-height:0;">
                                                                    <h5 class="mt-3 text-white"><i class="fa-solid fa-filter text-white"></i> Topik</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" checked value="semua" id="filterSemua">
                                                                        <label class="form-check-label" for="filterSemua">Semua</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="dataset" id="filterDataset">
                                                                        <label class="form-check-label" for="filterDataset">Pekerjaan Umum</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="infoGrafis" id="filterInfoGrafis">
                                                                        <label class="form-check-label" for="filterInfoGrafis">Surat</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="infoGrafis" id="filterInfoGrafis">
                                                                        <label class="form-check-label" for="filterInfoGrafis">Keuangan</label>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="card shadow-sm mt-2">
                                                                <div class="card-header" style="background: linear-gradient(90deg,rgb(100, 104, 98) 40%,rgb(71, 75, 75) 100%); min-height:0;">
                                                                    <h5 class="mt-3 text-white fs-3"><i class="fa-solid fa-filter text-white fs-3"></i> Status</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="semua" checked id="filterSemua">
                                                                        <label class="form-check-label" for="filterSemua">Semua</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="terbuka" id="filterTerbuka">
                                                                        <label class="form-check-label" for="filterDataset">Data Terbuka</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" value="terbatas" id="filterTerbatas">
                                                                        <label class="form-check-label" for="filterTerbatas">Data Terbatas</label>
                                                                    </div>
                                                                    
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="row">
                                                                <div class="col-md-12 my-3">
                                                                    <a href="{{ route('detail') }}" class="text-decoration-none">
                                                                        <div class="row align-items-center py-3">
                                                                            <div class="col-md-1 d-flex justify-content-center">
                                                                                <img src="{{ asset('assets/logo-v2/csv.png') }}" alt="CSV Icon" width="70" loading="lazy">
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <h3 class="opacity-75 fw-normal mb-2">
                                                                                    PROGRAM KERJA PENGAWASAN TAHUNAN (PKPT)
                                                                                </h3>
                                                                                <div class="row mb-1">
                                                                                    <div class="col-md-2">
                                                                                        <span class="opacity-75 fw-normal fs-5 text-dark text-dark">Topik: </span>
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <span class="badge badge-success">DIKLAT</span>
                                                                                        <span class="badge badge-success">DATASET</span>
                                                                                        <span class="badge badge-danger">DATA TERBATAS</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-2">
                                                                                        <span class="opacity-75 fw-normal fs-5 text-dark text-dark">Kategori: </span>
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <span class="opacity-75 fw-normal fs-5 text-dark text-dark">PROGRAM KERJA PENGAWASAN TAHUNAN - ITJEN</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3 text-md-end">
                                                                                <h5 class="text-muted mb-0">Ukuran: 701.21 kb | Type: csv</h5>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                    </a>
                                                                </div>
                                                                <div class="col-md-12 my-3">
                                                                    <a href="{{ route('detail') }}" class="text-decoration-none">
                                                                        <div class="row align-items-center py-3">
                                                                            <div class="col-md-1 d-flex justify-content-center">
                                                                                <img src="{{ asset('assets/logo-v2/csv.png') }}" alt="CSV Icon" width="70" loading="lazy">
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <h3 class="opacity-75 fw-normal mb-2">
                                                                                    DATA APLIKASI
                                                                                </h3>
                                                                                <div class="row mb-1">
                                                                                    <div class="col-md-2">
                                                                                        <span class="opacity-75 fw-normal fs-5 text-dark text-dark">Topik: </span>
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <span class="badge badge-success">DIKLAT</span>
                                                                                        <span class="badge badge-success">DATASET</span>
                                                                                        <span class="badge badge-warning">DATA TERBUKA</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-2">
                                                                                        <span class="opacity-75 fw-normal fs-5 text-dark text-dark">Kategori: </span>
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <span class="opacity-75 fw-normal fs-5 text-dark text-dark">-</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3 text-md-end">
                                                                                <h5 class="text-muted mb-0">Ukuran: 701.21 kb | Type: csv</h5>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                    </a>
                                                                    <div class="row">
                                                                        <div class="col-md-7 my-4">
                                                                            <!-- Kosong atau konten lain -->
                                                                        </div>
                                                                        <div class="col-md-5 my-4">
                                                                            <!-- Pagination Bootstrap -->
                                                                            <nav aria-label="Page navigation example">
                                                                                <ul class="pagination justify-content-end mb-0">
                                                                                    <li class="page-item disabled">
                                                                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Sebelumnya</a>
                                                                                    </li>
                                                                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                                                    <li class="page-item " aria-current="page">
                                                                                        <a class="page-link" href="#">2</a>
                                                                                    </li>
                                                                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                                                    <li class="page-item">
                                                                                        <a class="page-link" href="#">Berikutnya</a>
                                                                                    </li>
                                                                                </ul>
                                                                            </nav>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                        

														
													</div>
												</div>


											</div>
											
									</div>
									<!--end::Products-->
								</div>
								<!--end::Content-->
							</div>
							<!--end::Content wrapper-->
							

@include('layouts.footer-v2')
