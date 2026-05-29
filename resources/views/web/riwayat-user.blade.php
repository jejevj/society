@include('layouts.header-v2')
				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush mb-10">
										<div class="h-xl-100 mt-10 mb-10">
											<div class="mb-4">
												<div class="row">
													<div class="col-md-12">
														<h3 class="fs-1q m-4 text-marron">Riwayat Permohonan</h3>
													</div>
													<div class="col-md-12">
														<div class="p-4">
															@if($riwayat->count() > 0)
																<div class="row g-5">
																	@foreach($riwayat as $dt)
																		@php
																			if ($dt->status_permohonan == 'Y') {
																				$badge = 'bg-success';
																				$icon  = 'fa-check-circle text-success';
																			} elseif ($dt->status_permohonan == 'P') {
																				$badge = 'bg-warning';
																				$icon  = 'fa-clock text-warning';
																			} else {
																				$badge = 'bg-danger';
																				$icon  = 'fa-times-circle text-danger';
																			}
																		@endphp

																		<div class="col-md-4">
																			<div class="card shadow-sm border-0 h-100 hover-shadow" style="transition:0.3s;">
																				<div class="card-body">
																					<div class="d-flex justify-content-between align-items-start mb-3">
																						<h5 class="fw-bold mb-0">
																							{{ $dt->judul_data }}
																						</h5>
																						<span class="badge {{ $badge }} px-3 py-2 text-white">
																							{{ $dt->keterangan_status }}
																						</span>
																					</div>
																					<div class="mb-2">
																						<span class="text-muted">Kode:</span><br>
																						<span class="fw-semibold">
																							{{ $dt->kode_permohonan }}
																						</span>
																					</div>

																					<div class="mb-2">
																						<span class="text-muted">Tujuan:</span><br>
																						<span>
																							{{ ucwords($dt->tujuan_permohonan) ?? '-' }}
																						</span>
																					</div>

																					<div class="mb-3">
																						<span class="text-muted">Waktu:</span><br>
																						<span>
																							{{ date('d M Y H:i', strtotime($dt->created_at)) }}
																						</span>
																					</div>

																					<div class="text-end">
																						<i class="fa {{ $icon }}"
																						style="font-size:24px;"></i>
																					</div>

																					<div class="mb-2">
																						<span class="text-muted">Catatan:</span><br>
																						<span>
																							{{ ucwords($dt->catatan_validasi_permohonan) ?? '-' }}
																						</span>
																					</div>
																				</div>
																			</div>
																		</div>

																	@endforeach

																</div>

																<div class="mt-5 d-flex justify-content-end">
																	{{ $riwayat->links('pagination::bootstrap-5') }}
																</div>

															@else

																<div class="card border-0 shadow-sm">
																	<div class="card-body text-center py-10">

																		<div class="mb-4">
																			<i class="fa fa-folder-open text-muted"
																			style="font-size:60px;"></i>
																		</div>

																		<h4 class="fw-bold text-dark mb-2">
																			Data Riwayat Belum Tersedia
																		</h4>

																		<p class="text-muted mb-0">
																			Belum ada riwayat permohonan yang dapat ditampilkan.
																		</p>

																	</div>
																</div>

															@endif

														</div>
													</div>
												</div>
											</div>
										</div>	
									</div>
								</div>
							</div>
							

@include('layouts.footer-v2')
