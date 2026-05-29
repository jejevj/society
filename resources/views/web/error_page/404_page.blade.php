@include('layouts.header-v2')

				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6" style="background: url('{{ asset('assets/logo-v2/nanotechnology.png') }}') top center no-repeat,linear-gradient(10deg, #3779c5ff 40%, #8af3adff 110%); background-size: contain, cover;">  
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
							</div>
						</div>
					</div>
					<div class="app-container container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush mb-10">
											<div class="card border-0 h-md-100" data-bs-theme="light" style="background: linear-gradient(112.14deg,rgba(56, 126, 207, 1) 40%,rgba(154, 224, 241, 1) 100%)">
												<div class="card-body">
													<div class="row align-items-center h-100">
														<div class="col-md-7 ps-xl-13">
															<div class="row">
																<div class="text-white mb-6 pt-6">
																	<span class="fs-3qx fw-bold">Satu Data Pertahanan</span>
																	<span class="fs-5 fw-semibold me-2 d-block lh-1 pb-2 opacity-75">
																		Pembaruan terakhir: {{ \Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('j F Y') }}
																	</span>
																</div>
															</div>
															
														</div>
														<div class="col-md-5 pt-10">
															<div class="row">
																<div class="col-3">
																	<div class="d-flex align-items-center me-5 me-xl-13">
																		<div class="text-white">
																			<span class="fs-2qx d-block fw-bold text-center">{{ $organisasi_count }}</span>
																			<span class="fw-bold fs-3">Satker</span>
																		</div>
																	</div>
																</div>
																<div class="col-3">
																	<div class="d-flex align-items-center me-5 me-xl-13">
																		<div class="text-white">
																			<span class="fs-2qx d-block fw-bold text-center">{{ $dataset_count }}</span>
																			<span class="fw-bold fs-3">Dataset</span>
																		</div>
																	</div>
																</div>
																<div class="col-3">
																	<div class="d-flex align-items-center me-5 me-xl-13">
																		<div class="text-white">
																			<span class="fs-2qx d-block fw-bold text-center">{{ $infografis_count }}</span>
																			<span class="fw-bold fs-3">Infografis</span>
																		</div>
																	</div>
																</div>
																<div class="col-3">
																	<div class="d-flex align-items-center me-5 me-xl-13">
																		<div class="text-white">
																			<span class="fs-2qx d-block fw-bold text-center">{{ $dataset_count + $infografis_count }}</span>
																			<span class="fw-bold fs-3">Data</span>
																		</div>
																	</div>
																</div>
															</div>


															
														</div>
														<?php if(!empty($slider)){?>
														<div class="col-12">
															<div class="row">
																<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
																	<div class="carousel-inner">
																		<?php $sl=0; foreach($slider as $d){ $sl++?>
																		<div class="carousel-item <?php if($sl==1){echo 'active';}?>">
																			<div class="d-flex flex-column flex-lg-row align-items-center justify-content-between p-5" style="height: 300px; background-color: transparent">																			
																				<div class="col-lg-12 text-center">
																					<img src="{{ asset('storage/'.$d->gambar_slider) }}" class="img-fluid" alt="Logo">
																				</div>
																			</div>
																		</div>
																		<?php }?>
																	</div>

																	<button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
																		<span class="carousel-control-prev-icon"></span>
																	</button>
																	<button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
																		<span class="carousel-control-next-icon"></span>
																	</button>
																</div>
																
															</div>
														</div>
														<?php }?>
														<div class="col-md-12 pt-3">
															<div class="position-relative mt-10">
																<div>
																	<form action="{{ url('/list') }}" method="GET" class="input-group w-100">
																		<div class="row w-100">
																			<div class="col-md-2 mb-3 mb-md-0">
																				<select name="topik" class="form-select form-select-lg border-primary rounded-start text-dark fw-semibold" style="height:70px; background-color: #f9f9f9;">
																					<option value="" class="text-muted">Pilih Topik</option>
																					<option value="">📂 Semua</option>
																					<?php foreach($topik as $t){?>
																					<option value="{{ $t->id_topik }}">📂 {{ $t->nama_topik }}</option>
																					<?php }?>
																				</select>
																			</div>
																			<div class="col-md-8 mb-3 mb-md-0">
																				<input type="text" name="search" id="search-input" class="form-control form-control-lg border-start border-end-0 border-primary w-100" style="height:70px;" placeholder="Masukkan kata kunci..." autocomplete="off">
																			</div>
																			<div class="col-md-2">
																				<button class="btn btn-primary btn-lg rounded-end fw-bold w-100" style="height:70px; padding: 0 40px; font-size: 1.25rem;">
																					<i class="fa fa-search me-2"></i> Cari
																				</button>
																			</div>
																		</div>
																	</form>
																</div>
																<div id="autocomplete-results" class="list-group position-absolute w-100 shadow-sm mt-1 d-none" style="z-index: 1050;"></div>
															</div>
														</div>
														<!--end::Col-->
													</div>
													<!--end::Row-->
												</div>
												<!--end::Body-->
											</div>
											<!--end::Engage widget 8-->
											
											
											
											<div class="card card-flush h-xl-100 mt-10 mb-">
												<div class="card-header py-4 text-center">
													<h1 class="fw-bold fs-2qx mb-1 text-center">Data Terbaru</h1>
												</div>
												<div class="card-body mb-4">
													<div class="row">
														<div class="col-md-6">
															<div class="card">
																<div class="card-header text-center w-100" style="background: linear-gradient(90deg,rgba(51, 185, 84, 1) 40%,rgba(55, 214, 81, 1) 100%)">
																	<h3 class="fs-1 pt-6 mb-0 text-white">DATASET</h3>
																</div>
																<div class="card-body">
                                                                    <?php foreach($dataset as $d){?>
																	<a href="{{ route('detail-data', ['url_data' => $d->kode_data]) }}">
																		<div class="row">
																			<div class="col-2 my-2">
																				<img src="{{ asset($d->path_image_file ?? 'assets/logo-v2/csv.png') }}" alt="Dataset Icon" class="img-fluid">
																			</div>
																			<div class="col-10 my-2">
																				<div class="row">
																					<h5 class="opacity-75 fw-normal">{{ $d->judul_master }}</h5>
																				</div>
																				<div class="row">
																					<div class="col-md-3 my-1">
																						<span class="opacity-75 fw-normal text-white badge badge-dark">
																							<i class="fa fa-calendar text-white px-2"></i>
																							{{ \Carbon\Carbon::parse($d->created_at)->format('j M Y') }}
																						</span>
																					</div>
																					
																					<div class="col-md-2 my-1">
																						<span class="opacity-75 fw-normal text-white badge badge-dark"></i> {{ $d->keterangan_status }}</span>
																					</div>
																					<div class="col-md-3 my-1">
																						<?php if($d->sifat_data == 'TERBATAS'){?>
																						<span class="opacity-75 fw-normal badge badge-danger text-white"> {{ $d->sifat_data }}</span>
																						<?php }else{?>
																						<span class="opacity-75 fw-normal badge badge-primary text-white"> {{ $d->sifat_data }}</span>
																						<?php }?>
																					</div>
																					<div class="col-md-4 my-1">
																						<span class="opacity-75 fw-normal text-dark badge badge-secondary"> <i class="fa fa-download text-dark px-1"></i> {{ $d->jumlah_download }}</span>
																						<span class="opacity-75 fw-normal text-dark badge badge-secondary"> <i class="fa fa-eye text-dark px-1"></i> {{ $d->jumlah_lihat }}</span>
																					</div>
																					
																				</div>
																			</div>
																		</div><hr>
																	</a>
                                                                    <?php }?>
																	
																	<div class="row">
																		<div class="col-md-7 my-4">
																		</div>
																		<div class="col-md-5 my-4">
																			<a href="{{ route('list', ['tipe' => 'DT']) }}" class="text-dark">Lihat Selengkapnya <i class="fa-solid fa-right-long text-dark"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="card">
																<div class="card-header text-center w-100" style="background: linear-gradient(90deg,rgba(58, 133, 204, 1) 40%,rgba(79, 158, 204, 1) 100%)">
																	<h3 class="fs-1 pt-6 mb-0 text-white">INFOGRAFIS</h3>
																</div>
																<div class="card-body">
                                                                    <?php foreach($infografis as $d){?>
																	<a href="{{ route('detail-data', ['url_data' => $d->kode_data]) }}">
																		<div class="row">
																			<div class="col-2 my-2">
																				<img src="{{ asset($d->path_image_file ?? 'assets/logo-v2/csv.png') }}" alt="Kekuatan Icon"  class="img-fluid">
																			</div>
																			<div class="col-10 my-2">
																				<div class="row">
																					<h5 class="opacity-75 fw-normal">{{ $d->judul_master }}</h5>
																				</div>
																				<div class="row">
																					<div class="col-md-3 my-1">
																						<span class="opacity-75 fw-normal text-white badge badge-dark">
																							<i class="fa fa-calendar text-white px-2"></i>
																							{{ \Carbon\Carbon::parse($d->created_at)->format('j M Y') }}
																						</span>
																					</div>
																					
																					<div class="col-md-2 my-1">
																						<span class="opacity-75 fw-normal text-white badge badge-dark"></i> {{ $d->keterangan_status }}</span>
																					</div>
																					<div class="col-md-3 my-1">
																						<?php if($d->sifat_data == 'TERBATAS'){?>
																						<span class="opacity-75 fw-normal badge badge-danger text-white"> {{ $d->sifat_data }}</span>
																						<?php }else{?>
																						<span class="opacity-75 fw-normal badge badge-primary text-white"> {{ $d->sifat_data }}</span>
																						<?php }?>
																					</div>
																					<div class="col-md-4 my-1">
																						<span class="opacity-75 fw-normal text-dark badge badge-secondary"> <i class="fa fa-download text-dark px-1"></i> {{ $d->jumlah_download }}</span>
																						<span class="opacity-75 fw-normal text-dark badge badge-secondary"> <i class="fa fa-eye text-dark px-1"></i> {{ $d->jumlah_lihat }}</span>
																					</div>
																					
																				</div>
																			</div>
																		</div><hr>
																	</a>
                                                                    <?php }?>
																	
																	<div class="row">
																		<div class="col-md-7 my-4">
																		</div>
																		<div class="col-md-5 my-4">
																			<a href="{{ route('list', ['tipe' => 'IG']) }}" class="text-dark">Lihat Selengkapnya <i class="fa-solid fa-right-long text-dark"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="card card-flush h-xl-100 mt-4 mb-4">
												<div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px" style="background-image:url('assets/media/svg/shapes/top-green.png" data-bs-theme="light">
													<h3 class="card-title align-items-start flex-column text-white pt-15">
														<span class="fw-bold fs-2x mb-3">Topik Dataset</span>
														<div class="fs-4 text-white">
															<span class="opacity-75">Topik Dataset pada website Satu Data Kementerian Pertahanan</span>
														</div>
													</h3>													
												</div>
												<div class="card-body mt-n20">
													<div class="mt-n20 position-relative">
														<div class="row g-3 g-lg-6">
                                                            <?php foreach($topik as $t){?>
															<div class="col-4">
																<a href="{{ route('list', ['topik' => $t->id_topik]) }}">
																	<div class="rounded-2 px-6 py-5" style="background: linear-gradient(180deg, rgb(240, 240, 240), rgb(255, 255, 255));">
																		<div class="d-flex flex-column align-items-center justify-content-center text-center">
																			<div class="symbol mb-4">
																				<span>
																					<img src="{{ asset('storage/'.$t->gambar_topik) }}" alt="Perencana Icon" width="70">
																				</span>
																			</div>
																			<div class="m-0">
																				<span class="text-gray-700 fw-bolder d-block fs-2 lh-1 ls-n1 mb-1">{{ $t->nama_topik }}</span>
																			</div>
																		</div>
																	</div>
																</a>
															</div>
                                                            <?php }?>
														</div>
													</div>
												</div>
											</div>
											<br><br>
											<h1 class="fs-2qx fw-bold mb-10 mt-4 opacity-75 text-center position-relative d-inline-block mx-auto pb-2" style="border-bottom: 3px solid rgba(48, 105, 226, 1);">LINK TAUTAN</h1>
											<div class="card card-flush h-xl-100 mb-4" style="background: linear-gradient(180deg, rgba(230, 242, 255, 1), rgba(233, 240, 248, 1));">
											<div class="card-body">
												<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
													<div class="carousel-inner">
														@foreach($tautan->chunk(3) as $key => $chunk)
															<div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
																<div class="row text-center">
																	@foreach($chunk as $item)
																		<div class="col-md-4 my-1">
																			<a target="_blank" href="{{ $item->link_tautan }}">
																				<img src="{{ asset('storage/'.$item->gambar_tautan) }}"
																					class="d-block mx-auto img-fluid"
																					style="height: 100px;"
																					alt="{{ $item->nama_tautan ?? 'Tautan' }}">
																			</a>
																		</div>
																	@endforeach
																</div>
															</div>
														@endforeach
													</div>
 													<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
														<span class="carousel-control-prev-icon" aria-hidden="true"></span>
														<span class="visually-hidden">Sebelumnya</span>
													</button>
													<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
														<span class="carousel-control-next-icon" aria-hidden="true"></span>
														<span class="visually-hidden">Selanjutnya</span>
													</button>
												</div>
											</div>
										</div>
										</div>
									</div>
								</div>
							</div>


@include('layouts.footer-v2')
