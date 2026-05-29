<div class="card card-flush">
										<div class="card-body">
											<ul class="nav nav-tabs mb-4" id="chartTab" role="tablist">
												<li class="nav-item" role="presentation">
													<button class="nav-link active" id="topik-tab" data-bs-toggle="tab" data-bs-target="#topik" type="button">
														Chart Topik
													</button>
												</li>
												<li class="nav-item" role="presentation">
													<button class="nav-link" id="satker-tab" data-bs-toggle="tab" data-bs-target="#satker" type="button">
														Chart Satker
													</button>
												</li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane fade show active" id="topik">
													<div class="card card-flush">
														<div class="card-body">
															<canvas id="trafficChart" height="50"></canvas>
														</div>
													</div>
												</div>

												<div class="tab-pane fade" id="satker">
													<div class="card card-flush">
														<div class="card-body">
															<canvas id="trafficChart2" height="50"></canvas>
														</div>
													</div>
												</div>

											</div>
											<script>
											let chart1, chart2;
											document.addEventListener("DOMContentLoaded", function () {
												const ctx = document.getElementById('trafficChart').getContext('2d');
												chart1 = new Chart(ctx, {
													type: 'bar',
													data: {
														labels: {!! json_encode($chart_labels) !!},
														datasets: [
															{
																label: 'Total',
																data: {!! json_encode($chart_counts) !!},
																backgroundColor: 'rgba(15, 11, 5, 0.7)'
															},
															{
																label: 'Jumlah Dataset',
																data: {!! json_encode($chart_counts_dt) !!},
																backgroundColor: 'rgba(101, 22, 6, 0.7)'
															},
															{
																label: 'Jumlah Infografis',
																data: {!! json_encode($chart_counts_ig) !!},
																backgroundColor: 'rgba(201, 183, 17, 0.7)'
															}
														]
													},

													options: {
														responsive: true
													}
												});
											});

											document.getElementById('satker-tab').addEventListener('shown.bs.tab', function () {
												if (!chart2) {
													const ctx2 = document.getElementById('trafficChart2').getContext('2d');
													chart2 = new Chart(ctx2, {
														type: 'bar',
														data: {
															labels: {!! json_encode($chart_labels2) !!},
															datasets: [
																{
																	label: 'Total',
																	data: {!! json_encode($chart_counts2) !!},
																	backgroundColor: 'rgba(15, 11, 5, 0.7)'
																},
																{
																	label: 'Jumlah Dataset',
																	data: {!! json_encode($chart_counts2_dt) !!},
																	backgroundColor: 'rgba(101, 22, 6, 0.7)'
																},
																{
																	label: 'Jumlah Infografis',
																	data: {!! json_encode($chart_counts2_ig) !!},
																	backgroundColor: 'rgba(201, 183, 17, 0.7)'
																}
															]
														},

														options: {
															responsive: true
														}
													});
												}
											});
										</script>

											<div class="row mt-6">
												<div class="col-md-2">
													<label class="fs-4 opacity-75">Judul:</label>
													<input type="text" id="search-nama-data" class="form-control ps-12  mt-2" placeholder="Masukkan judul">
												</div>
												<div class="col-md-2">
													<label class="fs-4 opacity-75">Tipe:</label>
													<select name="" id="search-tipe-data" class="form-control ps-12 mt-2" data-control="select2">
														<option value="">- Pilih Tipe -</option>
														<?php foreach($tipe as $t){?>
														<option value="<?= $t->kode_status;?>"><?= $t->keterangan_status;?></option>
														<?php }?>
													</select>
												</div>
												<div class="col-md-2">
													<label class="fs-4 opacity-75">Organisasi/Satker:</label>
													<select name="" id="search-organisasi-data" class="form-control ps-12 mt-2" data-control="select2">
														<option value="">- Pilih Organisasi/Satker -</option>
														<?php foreach($organisasi as $o){?>
														<option value="<?= $o->id_organisasi;?>"><?= $o->nama_organisasi;?></option>
														<?php }?>
													</select>
												</div>
                                                <div class="col-md-2">
													<label class="fs-4 opacity-75">Sifat Data:</label>
													<select name="" id="search-sifat-data" class="form-control ps-12 mt-2" data-control="select2">
														<option value="">- Pilih Sifat Data -</option>
														<option value="TERBUKA">TERBUKA</option>
                                                        <option value="TERBATAS">TERBATAS</option>
                                                        <option value="TERTUTUP">TERTUTUP</option>
                                                        <option value="RAHASIA">RAHASIA</option>
													</select>
												</div>
												<div class="col-md-2">
													<label class="fs-4 opacity-75">Tanggal Awal:</label>
													<input type="date" id="search-awal-data" class="form-control ps-12  mt-2" placeholder="Masukkan Tanggal Awal">
												</div>
												<div class="col-md-2">
													<label class="fs-4 opacity-75">Tanggal Akhir:</label>
													<input type="date" id="search-akhir-data" class="form-control ps-12  mt-2" placeholder="Masukkan Tanggal Akhir">
												</div>
												<div class="col-md-6 mt-4">
													<button id="searchData" class="btn btn-marron-monitoring text-white btn-sm w-100"><i class="fa fa-search"></i>Cari</button>
													
												</div>
												<div class="col-md-6 mt-4">
													<button id="resetSearchData" class="btn btn-warning btn-sm w-100"><i class="fa fa-rotate"></i>Reset</button>
												</div>
											</div>
										</div>
									</div>