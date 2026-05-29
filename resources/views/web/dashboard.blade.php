@include('layouts.header-v2')

				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div class="card border-0 banner-hero">
						<div class="card-body h-100">
							<div class="row align-items-center">
								<div class="col-md-6 banner-hero-slider">
									<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
										<div class="carousel-inner" id="slider_container">
											<div class="text-center">
												<span class="text-muted">Sedang memuat... ⏳</span>
											</div>
										</div>
										<button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev" aria-label="Slide Sebelumnya">
											<span class="carousel-control-prev-icon"></span>
										</button>
										<button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next" aria-label="Slide Berikutnya">
											<span class="carousel-control-next-icon"></span>
										</button>
									</div>
								</div>
								<div class="col-md-6 ">
									<div class="row">
										<div class="col-12 banner-hero-text">
											<div class="typing-wrapper">
												<h2 class="banner-hero-title" id="typingTitle"></h2>
												<p class="banner-hero-desc" id="typingDesc"></p>
											</div>
										</div>
										<div class="col-md-10">
											<div class="px-4">
												<div class="stat-wrapper flex-wrap justify-content-between align-items-center py-3">
													<div class="row stat-besar">
														<div class="col-6 col-md-3">
															<div class="stat-item d-flex align-items-center">
																<div class="icon me-3">
																	<i class="bi bi-database"></i>
																</div>
																<div>
																	<h2 class="mb-0 fw-bold" id="dataset_count">⏳</h2>
																	<span class="stat-label">Dataset</span>
																</div>
															</div>
														</div>
														<div class="col-6 col-md-3">
															<div class="stat-item d-flex align-items-center">
																<div class="icon me-3">
																	<i class="bi bi-bar-chart"></i>
																</div>
																<div>
																	<h2 class="mb-0 fw-bold" id="infografis_count">⏳</h2>
																	<span class="stat-label">Infografis</span>
																</div>
															</div>
														</div>
														<div class="col-6 col-md-3">
															<div class="stat-item d-flex align-items-center">
																<div class="icon me-3">
																	<i class="bi bi-box-seam"></i>
																</div>
																<div>
																	<h2 class="mb-0 fw-bold" id="total_data">⏳</h2>
																	<span class="stat-label">Total Data</span>
																</div>
															</div>
														</div>
														<div class="col-6 col-md-3">
															<div class="stat-item d-flex align-items-center">
																<div class="icon me-3">
																	<i class="bi bi-clipboard-data"></i>
																</div>
																<div>
																	<h2 class="mb-0 fw-bold" id="organisasi_count">⏳</h2>
																	<span class="stat-label">Satker</span>
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
							<div class="row align-items-center">
								<div class="col-md-12 mt-2">
									<div class="justify-content-center">
										<div class="position-relative">
											<form action="{{ url(env('APP_ROUTE').'/list') }}" method="GET">
												<div class="search-box d-flex flex-column flex-md-row align-items-stretch ml10p">
													<label for="topik" class="visually-hidden">Pilih Topik</label>
													<select name="topik" id="topik" class="form-select search-select">
														<option value="">📂 Semua Topik</option>
														@foreach($topik as $t)
															<option value="{{ $t->id_topik }}">
																📂 {{ $t->nama_topik }}
															</option>
														@endforeach
													</select>
													 <label for="search-input" class="visually-hidden">Cari dataset atau infografis</label>
													<input id="search-input" type="text" name="search" id="search-input" class="form-control search-input" placeholder="Cari dataset, infografis..." autocomplete="off" >
													<button type="submit" class="btn search-btn fw-semibold">
														<i class="fa fa-search me-1 text-maroon-btn"></i>
														Cari
													</button>
												</div>
											</form>
											<div
												id="autocomplete-results"
												class="list-group position-absolute w-100 shadow mt-2 d-none autocomplete-box">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="container-xxl mt-dashboard">
						<div class="app-main flex-column flex-row-fluid">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="mb-2">
										<div class="h-xl-100 mt-2 mb-1">
											<div class="mb-4">
												<div class="row">
													<div class="col-md-12">
														<strong class="fs-1 mt-3">Dataset Terbaru</strong>
														<div id="dataset_container"></div>																	
														<div class="row">
															<div class="col-md-12 my-4 text-end">
																<a href="{{ route('list', ['tipe' => 'DT']) }}" class="text-dark">Lihat Selengkapnya <i class="fa-solid fa-right-long text-dark"></i></a>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<strong class="fs-1 mt-3">Infografis Terbaru</strong>
														<div id="infografis_container"></div>
														<div class="row">
															<div class="col-md-12 my-4 text-end">
																<a href="{{ route('list', ['tipe' => 'IG']) }}" class="text-dark">Lihat Selengkapnya <i class="fa-solid fa-right-long text-dark"></i></a>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<strong class="fs-1 m-3">Tautan</strong>
														<div class="card card-body">
															<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
																<div class="carousel-inner" id="tautan_container">
																	<div class="text-center py-5 w-100">
																		<span class="text-muted">Loading... ⏳</span>
																	</div>
																</div>
																<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
																	<span class="carousel-control-prev-icon"></span>
																</button>
																<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
																	<span class="carousel-control-next-icon"></span>
																</button>
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
										<div class="card card-flush h-xl-100 mb-4">
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

<script>
    

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.input-group')) {
            resultBox.classList.add('d-none');
        }
    });
	window.APP_URLS = {
        count: "{{ route('countDataDashboard') }}",
        list: "{{ route('listDataDashboard') }}",
        tautan: "{{ route('tautanDashboard') }}",
        slider: "{{ route('sliderDashboard') }}"
    };
	
</script>
<script src="{{ env('ASSET_URL') }}assets/js/min/dashboard.min.js"></script>

@include('layouts.footer-v2')
