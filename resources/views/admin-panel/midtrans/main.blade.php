@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Settings', 'url' => null],
										['label' => 'Midtrans Configurations', 'url' => null],
									]])
								</div>
								<div class="d-flex flex-stack pt-4 pb-4">
									<div class="page-title d-flex align-items-center me-3">
										<h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{ $menu }}
											<span class="page-desc opacity-50 fs-6 fw-bold pt-2"></span>
										</h1>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="app-container container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content pt-4 pb-6">

									<div class="card card-flush shadow-sm">

										<div class="card-header card-header-stretch border-bottom border-gray-200 d-flex flex-column align-items-stretch pt-5 pb-0 gap-3">

											<div class="d-flex align-items-center justify-content-between">
												<h3 class="fw-bold text-gray-800 mb-0">
													<i class="fa fa-credit-card text-primary me-2"></i> Midtrans Gateway
												</h3>
												@if($config && $config->is_active == 'Y')
													<span class="badge badge-light-success fs-7">
														<i class="fa fa-circle text-success me-1 fs-9"></i>
														{{ strtoupper($config->environment) }} Active
													</span>
												@else
													<span class="badge badge-light-warning fs-7">
														<i class="fa fa-circle text-warning me-1 fs-9"></i>
														Not Active
													</span>
												@endif
											</div>

											<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold mb-0" id="midtransMainTab" role="tablist">
												<li class="nav-item" role="presentation">
													<a class="nav-link active text-active-primary pb-4"
													   id="tab-konfigurasi-link"
													   data-bs-toggle="tab"
													   href="#tab_konfigurasi"
													   role="tab"
													   aria-controls="tab_konfigurasi"
													   aria-selected="true">
														<i class="fa fa-cog me-2"></i> Konfigurasi
													</a>
												</li>
												<li class="nav-item" role="presentation">
													<a class="nav-link text-active-primary pb-4"
													   id="tab-transaksi-link"
													   data-bs-toggle="tab"
													   href="#tab_transaksi"
													   role="tab"
													   aria-controls="tab_transaksi"
													   aria-selected="false">
														<i class="fa fa-list me-2"></i> Transaksi
														<span class="badge badge-light ms-1">{{ $tabCounts['all'] }}</span>
													</a>
												</li>
												<li class="nav-item" role="presentation">
													<a class="nav-link text-active-primary pb-4"
													   id="tab-create-order-link"
													   data-bs-toggle="tab"
													   href="#tab_create_order"
													   role="tab"
													   aria-controls="tab_create_order"
													   aria-selected="false">
														<i class="fa fa-shopping-cart me-2"></i> Create Order
													</a>
												</li>
											</ul>

										</div>{{-- end single card-header --}}

										<div class="card-body pt-6">

											<div class="tab-content" id="midtransMainTabContent">

												{{-- ======================================================== --}}
												{{-- TAB 1: KONFIGURASI --}}
												{{-- ======================================================== --}}
												<div class="tab-pane fade show active" id="tab_konfigurasi" role="tabpanel">
													<div class="row g-6">

														<div class="col-xl-7">
															<div class="card card-flush h-100">
																<div class="card-header">
																	<h3 class="card-title"><i class="fa fa-cog text-primary me-2"></i> API Configuration</h3>
																</div>
																<div class="card-body">
																	<form id="formMidtransConfig">
																		@csrf
																		<div class="row">
																			<div class="col-md-12 mb-5">
																				<label class="form-label required fw-bold">Environment</label>
																				<div class="d-flex gap-4">
																					<div class="form-check form-check-custom form-check-solid">
																						<input class="form-check-input" type="radio" name="environment" id="env_sandbox" value="sandbox"
																							{{ (!$config || $config->environment == 'sandbox') ? 'checked' : '' }}>
																						<label class="form-check-label" for="env_sandbox">
																							<span class="badge badge-warning fs-7 px-3 py-2"><i class="fa fa-flask me-1"></i> Sandbox (Testing)</span>
																						</label>
																					</div>
																					<div class="form-check form-check-custom form-check-solid">
																						<input class="form-check-input" type="radio" name="environment" id="env_production" value="production"
																							{{ ($config && $config->environment == 'production') ? 'checked' : '' }}>
																						<label class="form-check-label" for="env_production">
																							<span class="badge badge-danger fs-7 px-3 py-2"><i class="fa fa-rocket me-1"></i> Production (Live)</span>
																						</label>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-6 mb-5">
																				<label class="form-label required fw-bold">Server Key</label>
																				<div class="input-group">
																					<input type="password" class="form-control" id="server_key" name="server_key"
																						placeholder="SB-Mid-server-xxxxx" value="{{ $config->server_key ?? '' }}">
																					<button class="btn btn-light-secondary" type="button" onclick="toggleVisibility('server_key', this)"><i class="fa fa-eye"></i></button>
																				</div>
																			</div>
																			<div class="col-md-6 mb-5">
																				<label class="form-label required fw-bold">Client Key</label>
																				<div class="input-group">
																					<input type="password" class="form-control" id="client_key" name="client_key"
																						placeholder="SB-Mid-client-xxxxx" value="{{ $config->client_key ?? '' }}">
																					<button class="btn btn-light-secondary" type="button" onclick="toggleVisibility('client_key', this)"><i class="fa fa-eye"></i></button>
																				</div>
																			</div>
																			<div class="col-md-12 mb-5">
																				<label class="form-label fw-bold">Merchant ID <span class="text-muted">(optional)</span></label>
																				<input type="text" class="form-control" name="merchant_id"
																					placeholder="e.g. G123456789" value="{{ $config->merchant_id ?? '' }}">
																			</div>
																			<div class="col-md-12 mb-5">
																				<label class="form-label fw-bold">Webhook / Notification URL <span class="text-muted">(optional)</span></label>
																				<input type="url" class="form-control" name="webhook_url"
																					placeholder="https://yourdomain.com/midtrans/webhook" value="{{ $config->webhook_url ?? '' }}">
																			</div>
																			<div class="col-md-4 mb-5">
																				<label class="form-label fw-bold">Finish Redirect URL</label>
																				<input type="url" class="form-control" name="finish_redirect_url" placeholder="https://..." value="{{ $config->finish_redirect_url ?? '' }}">
																			</div>
																			<div class="col-md-4 mb-5">
																				<label class="form-label fw-bold">Unfinish Redirect URL</label>
																				<input type="url" class="form-control" name="unfinish_redirect_url" placeholder="https://..." value="{{ $config->unfinish_redirect_url ?? '' }}">
																			</div>
																			<div class="col-md-4 mb-5">
																				<label class="form-label fw-bold">Error Redirect URL</label>
																				<input type="url" class="form-control" name="error_redirect_url" placeholder="https://..." value="{{ $config->error_redirect_url ?? '' }}">
																			</div>
																			<div class="col-md-12 mb-5">
																				<label class="form-label required fw-bold">Status</label>
																				<div class="d-flex gap-4">
																					<div class="form-check form-check-custom form-check-solid">
																						<input class="form-check-input" type="radio" name="is_active" id="status_active" value="Y"
																							{{ ($config && $config->is_active == 'Y') ? 'checked' : '' }}>
																						<label class="form-check-label" for="status_active">Active</label>
																					</div>
																					<div class="form-check form-check-custom form-check-solid">
																						<input class="form-check-input" type="radio" name="is_active" id="status_inactive" value="N"
																							{{ (!$config || $config->is_active == 'N') ? 'checked' : '' }}>
																						<label class="form-check-label" for="status_inactive">Inactive</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<?php if($cek_permit['u']){ ?>
																		<div class="d-flex gap-3">
																			<button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i> Save Configuration</button>
																			<button type="button" class="btn btn-light-info" id="btnTestConnection"><i class="fa fa-plug me-2"></i> Test Connection</button>
																		</div>
																		<?php } ?>
																	</form>
																</div>
															</div>
														</div>

														<div class="col-xl-5">
															<div class="card card-flush h-100">
																<div class="card-header">
																	<h3 class="card-title"><i class="fa fa-credit-card text-success me-2"></i> Enabled Payment Types</h3>
																	<div class="card-toolbar">
																		<button class="btn btn-sm btn-light-secondary" type="button" id="btnSelectAll">Select All</button>
																		<button class="btn btn-sm btn-light-secondary ms-2" type="button" id="btnDeselectAll">Deselect All</button>
																	</div>
																</div>
																<div class="card-body pt-0">
																	<div class="row g-3" id="paymentTypesContainer">
																		@foreach($allPaymentTypes as $typeKey => $typeLabel)
																		<div class="col-12">
																			<label class="d-flex align-items-center cursor-pointer border rounded px-4 py-3 payment-type-item {{ in_array($typeKey, $selectedTypes) ? 'border-primary bg-light-primary' : 'border-gray-200' }}">
																				<input type="checkbox" class="form-check-input me-3 payment-type-checkbox"
																					value="{{ $typeKey }}" {{ in_array($typeKey, $selectedTypes) ? 'checked' : '' }}>
																				<span class="fs-6">{{ $typeLabel }}</span>
																			</label>
																		</div>
																		@endforeach
																	</div>
																</div>
															</div>
														</div>

														<div class="col-xl-12 mt-4">
															<div class="row g-6">
																<div class="col-xl-6">
																	<div class="card card-flush">
																		<div class="card-header"><h3 class="card-title"><i class="fa fa-search text-info me-2"></i> Get Transaction Status</h3></div>
																		<div class="card-body">
																			<div class="input-group">
																				<input type="text" class="form-control" id="status_order_id" placeholder="Enter Order ID">
																				<button class="btn btn-info" id="btnGetStatus"><i class="fa fa-search me-1"></i> Check</button>
																			</div>
																			<div id="statusResult" class="mt-3"></div>
																		</div>
																	</div>
																</div>
																<div class="col-xl-6">
																	<div class="card card-flush">
																		<div class="card-header"><h3 class="card-title"><i class="fa fa-tasks text-warning me-2"></i> Transaction Actions</h3></div>
																		<div class="card-body">
																			<label class="form-label fw-bold">Order ID</label>
																			<input type="text" class="form-control mb-3" id="action_order_id" placeholder="Enter Order ID">
																			<div class="d-flex flex-wrap gap-2">
																				<button class="btn btn-light-success btn-sm" id="btnApprove"><i class="fa fa-check me-1"></i> Approve</button>
																				<button class="btn btn-light-danger btn-sm" id="btnCancel"><i class="fa fa-times me-1"></i> Cancel</button>
																				<button class="btn btn-light-secondary btn-sm" id="btnExpire"><i class="fa fa-clock me-1"></i> Expire</button>
																			</div>
																			<div id="actionResult" class="mt-3"></div>
																		</div>
																	</div>
																</div>
																<div class="col-xl-6">
																	<div class="card card-flush">
																		<div class="card-header"><h3 class="card-title"><i class="fa fa-undo text-danger me-2"></i> Refund Transaction</h3></div>
																		<div class="card-body">
																			<div class="mb-3"><label class="form-label fw-bold">Order ID</label><input type="text" class="form-control" id="refund_order_id" placeholder="Enter Order ID"></div>
																			<div class="mb-3"><label class="form-label fw-bold">Refund Amount (IDR)</label><input type="number" class="form-control" id="refund_amount" placeholder="e.g. 50000" min="1"></div>
																			<div class="mb-3"><label class="form-label fw-bold">Reason</label><input type="text" class="form-control" id="refund_reason" placeholder="Customer request refund"></div>
																			<button class="btn btn-danger" id="btnRefund"><i class="fa fa-undo me-1"></i> Process Refund</button>
																			<div id="refundResult" class="mt-3"></div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>{{-- end row g-6 --}}
												</div>{{-- end tab_konfigurasi --}}

												{{-- ======================================================== --}}
												{{-- TAB 2: TRANSAKSI --}}
												{{-- ======================================================== --}}
												<div class="tab-pane fade" id="tab_transaksi" role="tabpanel">
													<div class="card card-flush">
														<div class="card-header align-items-center py-5 gap-2">
															<h3 class="card-title"><i class="fa fa-list text-primary me-2"></i> Riwayat Transaksi Midtrans</h3>
															<div class="card-toolbar gap-2">
																<button class="btn btn-sm btn-light-primary" id="btnFetchFromMidtrans"><i class="fa fa-cloud-download-alt me-1"></i> Fetch from Midtrans</button>
																<button class="btn btn-sm btn-light-success" id="btnSyncAll"><i class="fa fa-sync-alt me-1"></i> Sync All Status</button>
															</div>
														</div>
														<div class="card-body pt-0">
															<div class="d-flex flex-wrap gap-2 mb-5 mt-4">
																<button class="btn btn-sm btn-primary filter-status active" data-status="all">
																	All <span class="badge badge-light ms-1">{{ $tabCounts['all'] }}</span>
																</button>
																<button class="btn btn-sm btn-light-warning filter-status" data-status="pending">
																	Pending <span class="badge badge-warning ms-1">{{ $tabCounts['pending'] }}</span>
																</button>
																<button class="btn btn-sm btn-light-success filter-status" data-status="settlement">
																	Settlement <span class="badge badge-success ms-1">{{ $tabCounts['settlement'] }}</span>
																</button>
																<button class="btn btn-sm btn-light-danger filter-status" data-status="cancel">
																	Cancel <span class="badge badge-danger ms-1">{{ $tabCounts['cancel'] }}</span>
																</button>
																<button class="btn btn-sm btn-light-secondary filter-status" data-status="expire">
																	Expire <span class="badge badge-secondary ms-1">{{ $tabCounts['expire'] }}</span>
																</button>
																<button class="btn btn-sm btn-light-danger filter-status" data-status="deny">
																	Deny <span class="badge badge-danger ms-1">{{ $tabCounts['deny'] }}</span>
																</button>
																<button class="btn btn-sm btn-light-info filter-status" data-status="refund">
																	Refund <span class="badge badge-info ms-1">{{ $tabCounts['refund'] }}</span>
																</button>
															</div>

															{{-- Fetch from Midtrans panel (hidden by default) --}}
															<div id="fetchMidtransPanel" class="alert alert-primary d-none mb-4">
																<div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
																	<div>
																		<p class="mb-1 fw-bold"><i class="fa fa-info-circle me-1"></i> Fetch Status per Order ID dari Midtrans</p>
																		<p class="mb-0 text-muted fs-7">Midtrans tidak menyediakan endpoint list semua transaksi. Masukkan Order ID spesifik untuk cek & simpan statusnya.</p>
																	</div>
																	<div class="d-flex gap-2">
																		<input type="text" class="form-control form-control-sm" id="fetchOrderId" placeholder="Masukkan Order ID" style="min-width:200px">
																		<button class="btn btn-sm btn-primary" id="btnDoFetch"><i class="fa fa-search me-1"></i> Fetch & Simpan</button>
																		<button class="btn btn-sm btn-light-secondary" id="btnCloseFetchPanel"><i class="fa fa-times"></i></button>
																	</div>
																</div>
																<div id="fetchResult" class="mt-2"></div>
															</div>

															<table id="dtTransaksi" class="table table-striped table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 w-100">
																<thead>
																	<tr class="fw-bold text-muted bg-light">
																		<th class="ps-4 min-w-50px">#</th>
																		<th class="min-w-150px">Order ID</th>
																		<th class="min-w-150px">Transaction ID</th>
																		<th class="min-w-100px">Status</th>
																		<th class="min-w-120px">Payment Type</th>
																		<th class="min-w-120px">Amount</th>
																		<th class="min-w-130px">Tgl Transaksi</th>
																		<th class="min-w-80px text-center">Aksi</th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table>
														</div>
													</div>
												</div>{{-- end tab_transaksi --}}

												{{-- ======================================================== --}}
												{{-- TAB 3: CREATE ORDER --}}
												{{-- ======================================================== --}}
												<div class="tab-pane fade" id="tab_create_order" role="tabpanel">
													<div class="row g-6">

														<div class="col-xl-6">
															<div class="card card-flush h-100">
																<div class="card-header">
																	<h3 class="card-title"><i class="fa fa-key text-primary me-2"></i> SNAP Token &amp; Payment Popup</h3>
																	<div class="card-toolbar">
																		<span class="badge badge-light-primary">via /snap/v1/transactions</span>
																	</div>
																</div>
																<div class="card-body">
																	<p class="text-muted fs-7 mb-5">Generate SNAP token lalu tampilkan payment popup Midtrans langsung di halaman ini.</p>
																	<div class="row g-3">
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Order ID <span class="text-danger">*</span></label>
																			<input type="text" class="form-control" id="snap_order_id" placeholder="order-{{ date('YmdHis') }}">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Amount (IDR) <span class="text-danger">*</span></label>
																			<input type="number" class="form-control" id="snap_amount" placeholder="100000" min="1">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
																			<input type="text" class="form-control" id="snap_first_name" placeholder="John">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Last Name</label>
																			<input type="text" class="form-control" id="snap_last_name" placeholder="Doe">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
																			<input type="email" class="form-control" id="snap_email" placeholder="john@example.com">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Phone <span class="text-danger">*</span></label>
																			<input type="text" class="form-control" id="snap_phone" placeholder="08xxxxxxxxxx">
																		</div>
																	</div>
																	<div class="d-flex gap-2 mt-4">
																		<button class="btn btn-primary" id="btnCreateSnap">
																			<i class="fa fa-key me-1"></i> Generate Token &amp; Bayar
																		</button>
																		<button class="btn btn-light-secondary" id="btnGenerateOrderId" type="button">
																			<i class="fa fa-random me-1"></i> Auto Order ID
																		</button>
																	</div>
																	<div id="snapResult" class="mt-3"></div>
																</div>
															</div>
														</div>

														<div class="col-xl-6">
															<div class="card card-flush h-100">
																<div class="card-header">
																	<h3 class="card-title"><i class="fa fa-bolt text-warning me-2"></i> Direct Charge API</h3>
																	<div class="card-toolbar">
																		<span class="badge badge-light-warning">via /v2/charge</span>
																	</div>
																</div>
																<div class="card-body">
																	<p class="text-muted fs-7 mb-5">Buat charge langsung tanpa popup. Cocok untuk VA, QRIS, dan e-wallet via API.</p>
																	<div class="row g-3">
																		<div class="col-md-12">
																			<label class="form-label fw-bold">Payment Type <span class="text-danger">*</span></label>
																			<select class="form-select" id="charge_payment_type">
																				<option value="">-- Pilih Metode Pembayaran --</option>
																				<optgroup label="Bank Transfer">
																					<option value="bca_va">BCA Virtual Account</option>
																					<option value="bni_va">BNI Virtual Account</option>
																					<option value="bri_va">BRI Virtual Account</option>
																					<option value="permata_va">Permata Virtual Account</option>
																					<option value="other_va">Other Virtual Account</option>
																				</optgroup>
																				<optgroup label="E-Wallet">
																					<option value="gopay">GoPay</option>
																					<option value="shopeepay">ShopeePay</option>
																					<option value="qris">QRIS</option>
																				</optgroup>
																				<optgroup label="Convenience Store">
																					<option value="indomaret">Indomaret</option>
																					<option value="alfamart">Alfamart</option>
																				</optgroup>
																			</select>
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Order ID <span class="text-danger">*</span></label>
																			<input type="text" class="form-control" id="charge_order_id" placeholder="order-{{ date('YmdHis') }}">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Amount (IDR) <span class="text-danger">*</span></label>
																			<input type="number" class="form-control" id="charge_amount" placeholder="100000" min="1">
																		</div>
																		<div class="col-md-12">
																			<label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
																			<input type="text" class="form-control" id="charge_first_name" placeholder="John">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
																			<input type="email" class="form-control" id="charge_email" placeholder="john@example.com">
																		</div>
																		<div class="col-md-6">
																			<label class="form-label fw-bold">Phone <span class="text-danger">*</span></label>
																			<input type="text" class="form-control" id="charge_phone" placeholder="08xxxxxxxxxx">
																		</div>
																	</div>
																	<button class="btn btn-warning mt-4" id="btnCreateCharge">
																		<i class="fa fa-bolt me-1"></i> Create Charge
																	</button>
																	<div id="chargeResult" class="mt-3"></div>
																</div>
															</div>
														</div>

													</div>{{-- end row g-6 --}}
												</div>{{-- end tab_create_order --}}

											</div>{{-- end tab-content --}}

										</div>{{-- end card-body --}}
									</div>{{-- end card utama --}}

								</div>{{-- end kt_app_content --}}
							</div>
						</div>
					</div>
				</div>
@include('admin-panel.layouts.footer')

{{-- ================================================================ --}}
{{-- SCRIPTS --}}
{{-- ================================================================ --}}
<script>
(function () {

    // ================================================================
    // CSRF SETUP GLOBAL — semua AJAX otomatis kirim X-CSRF-TOKEN
    // ================================================================
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    window.addEventListener('load', function () {
        if (typeof KTMenu !== 'undefined') {
            KTMenu.init();
        }

        var tabLinks = document.querySelectorAll('#midtransMainTab [data-bs-toggle="tab"]');
        tabLinks.forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                tabLinks.forEach(function (t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                    var target = document.querySelector(t.getAttribute('href'));
                    if (target) { target.classList.remove('show', 'active'); }
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                var pane = document.querySelector(this.getAttribute('href'));
                if (pane) {
                    pane.classList.add('show', 'active');
                    if (this.getAttribute('href') === '#tab_transaksi' && !window._dtTransaksiLoaded) {
                        initDtTransaksi('all');
                    }
                }
            });
        });
    });

    var dtTransaksi            = null;
    var currentStatus          = 'all';
    window._dtTransaksiLoaded  = false;

    var routeTable  = '{{ route("getTableMidtransTransaksi") }}';
    var routeSync   = '{{ route("syncMidtransTransaksiAction") }}';
    var csrfToken   = '{{ csrf_token() }}';

    // Snap environment for client-side popup
    var snapClientKey = '{{ $config->client_key ?? "" }}';
    var snapEnv       = '{{ $config->environment ?? "sandbox" }}';
    var snapScriptUrl = snapEnv === 'production'
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';

    // Lazy-load snap.js hanya saat dibutuhkan
    function loadSnapScript(callback) {
        if (window.snap) { callback(); return; }
        var s = document.createElement('script');
        s.src = snapScriptUrl;
        s.setAttribute('data-client-key', snapClientKey);
        s.onload  = callback;
        s.onerror = function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat Midtrans Snap.js. Periksa client key & koneksi.' });
        };
        document.head.appendChild(s);
    }

    function initDtTransaksi(status) {
        currentStatus = status || 'all';
        if (dtTransaksi) { dtTransaksi.destroy(); }
        dtTransaksi = $('#dtTransaksi').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: routeTable,
                type: 'GET',
                data: function (d) { d.status = currentStatus; }
            },
            columns: [
                { data: 'DT_RowIndex',       orderable: false, searchable: false, className: 'ps-4' },
                { data: 'order_id' },
                { data: 'transaction_id' },
                { data: 'transaction_status' },
                { data: 'payment_type' },
                { data: 'gross_amount' },
                { data: 'transaction_time' },
                { data: 'aksi',              orderable: false, searchable: false, className: 'text-center' },
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            language: {
                processing:  '<span class="spinner-border spinner-border-sm me-2"></span> Loading...',
                emptyTable:  'Belum ada data transaksi',
                zeroRecords: 'Tidak ada transaksi ditemukan',
            }
        });
        window._dtTransaksiLoaded = true;
    }

    $(document).on('click', '.filter-status', function () {
        $('.filter-status').removeClass('active btn-primary').addClass('btn-light-secondary');
        $(this).removeClass('btn-light-secondary').addClass('active btn-primary');
        var status = $(this).data('status');
        if (window._dtTransaksiLoaded) {
            currentStatus = status;
            dtTransaksi.ajax.reload();
        } else {
            initDtTransaksi(status);
        }
    });

    // ================================================================
    // FETCH FROM MIDTRANS — cek & simpan status per order_id
    // ================================================================
    $('#btnFetchFromMidtrans').on('click', function () {
        $('#fetchMidtransPanel').removeClass('d-none');
        $('#fetchOrderId').focus();
    });
    $('#btnCloseFetchPanel').on('click', function () {
        $('#fetchMidtransPanel').addClass('d-none');
        $('#fetchResult').html('');
    });
    $('#btnDoFetch').on('click', function () {
        var orderId = $('#fetchOrderId').val().trim();
        if (!orderId) return Swal.fire({ icon: 'warning', title: 'Input', text: 'Masukkan Order ID.' });
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Fetching...');
        $.ajax({
            url: '{{ route("getMidtransStatusAction") }}',
            type: 'POST',
            data: { order_id: orderId },
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-search me-1"></i> Fetch & Simpan');
                if (res.success) {
                    $('#fetchResult').html('<div class="alert alert-success mt-2"><i class="fa fa-check me-2"></i>Status berhasil diambil & disimpan. Order ID: <strong>' + orderId + '</strong> — Status: <strong>' + (res.data.transaction_status || '-') + '</strong></div>');
                    if (window._dtTransaksiLoaded) dtTransaksi.ajax.reload(null, false);
                } else {
                    $('#fetchResult').html('<div class="alert alert-danger mt-2">' + res.message + '</div>');
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-search me-1"></i> Fetch & Simpan');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan jaringan.';
                $('#fetchResult').html('<div class="alert alert-danger mt-2">' + msg + '</div>');
            }
        });
    });

    $(document).on('click', '.btn-sync-row', function () {
        var orderId = $(this).data('order');
        var btn     = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: routeSync, type: 'POST',
            data: { order_id: orderId },
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-sync-alt"></i>');
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Synced!', text: res.message, timer: 1500, showConfirmButton: false });
                    dtTransaksi.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                }
            },
            error: function () {
                btn.prop('disabled', false).html('<i class="fa fa-sync-alt"></i>');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.' });
            }
        });
    });

    $(document).on('click', '.btn-detail-row', function () {
        var orderId = $(this).data('order');
        $.ajax({
            url: '{{ route("getMidtransStatusAction") }}',
            type: 'POST', data: { order_id: orderId },
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        title: 'Detail Transaksi: ' + orderId,
                        html: '<pre style="text-align:left;font-size:12px;overflow:auto;max-height:400px">' + JSON.stringify(res.data, null, 2) + '</pre>',
                        width: 700,
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                }
            }
        });
    });

    $('#formMidtransConfig').on('submit', function (e) {
        e.preventDefault();
        var paymentTypes = [];
        $('.payment-type-checkbox:checked').each(function () { paymentTypes.push($(this).val()); });
        var formData = $(this).serializeArray();
        paymentTypes.forEach(function (pt) { formData.push({ name: 'payment_types[]', value: pt }); });
        $.ajax({
            url: '{{ route("updateMidtransConfigAction") }}',
            type: 'POST', data: formData,
            success: function (res) {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, timer: 1800, showConfirmButton: false })
                        .then(function () { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Validation error';
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            }
        });
    });

    $(document).on('change', '.payment-type-checkbox', function () {
        var label = $(this).closest('label');
        if ($(this).is(':checked')) {
            label.addClass('border-primary bg-light-primary').removeClass('border-gray-200');
        } else {
            label.removeClass('border-primary bg-light-primary').addClass('border-gray-200');
        }
    });
    $('#btnSelectAll').on('click',   function () { $('.payment-type-checkbox').prop('checked', true).trigger('change'); });
    $('#btnDeselectAll').on('click', function () { $('.payment-type-checkbox').prop('checked', false).trigger('change'); });

    $('#btnTestConnection').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Testing...');
        $.ajax({
            url: '{{ route("testMidtransConnectionAction") }}',
            type: 'POST',
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-plug me-2"></i> Test Connection');
                Swal.fire({ icon: res.success ? 'success' : 'error', title: res.success ? 'Connected!' : 'Failed', text: res.message });
            },
            error: function () {
                btn.prop('disabled', false).html('<i class="fa fa-plug me-2"></i> Test Connection');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Network error.' });
            }
        });
    });

    $('#btnGetStatus').on('click', function () {
        var orderId = $('#status_order_id').val().trim();
        if (!orderId) return Swal.fire({ icon: 'warning', title: 'Input', text: 'Masukkan Order ID.' });
        $.ajax({
            url: '{{ route("getMidtransStatusAction") }}',
            type: 'POST', data: { order_id: orderId },
            success: function (res) {
                if (res.success) {
                    $('#statusResult').html('<pre class="bg-light rounded p-3 mt-2" style="font-size:12px;overflow:auto;max-height:300px">' + JSON.stringify(res.data, null, 2) + '</pre>');
                } else {
                    $('#statusResult').html('<div class="alert alert-danger mt-2">' + res.message + '</div>');
                }
            },
            error: function () {
                $('#statusResult').html('<div class="alert alert-danger mt-2">Terjadi kesalahan jaringan.</div>');
            }
        });
    });

    function doTransactionAction(url, data, resultDiv) {
        $.ajax({
            url: url, type: 'POST', data: data,
            success: function (res) {
                if (res.success) {
                    $(resultDiv).html('<pre class="bg-light rounded p-3 mt-2" style="font-size:12px;overflow:auto;max-height:200px">' + JSON.stringify(res.data, null, 2) + '</pre>');
                } else {
                    $(resultDiv).html('<div class="alert alert-danger mt-2">' + res.message + '</div>');
                }
            },
            error: function () {
                $(resultDiv).html('<div class="alert alert-danger mt-2">Terjadi kesalahan jaringan.</div>');
            }
        });
    }

    $('#btnApprove').on('click', function () {
        var o = $('#action_order_id').val().trim(); if (!o) return;
        doTransactionAction('{{ route("approveMidtransAction") }}', { order_id: o }, '#actionResult');
    });
    $('#btnCancel').on('click', function () {
        var o = $('#action_order_id').val().trim(); if (!o) return;
        doTransactionAction('{{ route("cancelMidtransAction") }}', { order_id: o }, '#actionResult');
    });
    $('#btnExpire').on('click', function () {
        var o = $('#action_order_id').val().trim(); if (!o) return;
        doTransactionAction('{{ route("expireMidtransAction") }}', { order_id: o }, '#actionResult');
    });
    $('#btnRefund').on('click', function () {
        var o   = $('#refund_order_id').val().trim();
        var amt = $('#refund_amount').val().trim();
        var rsn = $('#refund_reason').val().trim();
        if (!o || !amt || !rsn) return Swal.fire({ icon: 'warning', title: 'Input', text: 'Lengkapi semua field refund.' });
        doTransactionAction('{{ route("refundMidtransAction") }}', { order_id: o, amount: amt, reason: rsn }, '#refundResult');
    });

    $('#btnSyncAll').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Syncing...');
        $.ajax({
            url: routeSync, type: 'POST',
            data: { sync_all: true },
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-sync-alt me-1"></i> Sync All Status');
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Synced!', text: res.message, timer: 1800, showConfirmButton: false });
                    if (window._dtTransaksiLoaded) dtTransaksi.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                }
            },
            error: function () {
                btn.prop('disabled', false).html('<i class="fa fa-sync-alt me-1"></i> Sync All Status');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.' });
            }
        });
    });

    function generateOrderId() {
        var now = new Date();
        var pad = function(n){ return String(n).padStart(2,'0'); };
        return 'ORD-' + now.getFullYear() + pad(now.getMonth()+1) + pad(now.getDate()) + pad(now.getHours()) + pad(now.getMinutes()) + pad(now.getSeconds());
    }
    $('#btnGenerateOrderId').on('click', function () {
        $('#snap_order_id').val(generateOrderId());
    });

    // ================================================================
    // CREATE SNAP TOKEN & OPEN PAYMENT POPUP
    // window.snap.pay() — Midtrans SNAP JS API
    // ================================================================
    $('#btnCreateSnap').on('click', function () {
        var orderId   = $('#snap_order_id').val().trim();
        var amount    = $('#snap_amount').val().trim();
        var firstName = $('#snap_first_name').val().trim();
        var lastName  = $('#snap_last_name').val().trim();
        var email     = $('#snap_email').val().trim();
        var phone     = $('#snap_phone').val().trim();

        if (!orderId || !amount || !firstName || !email || !phone) {
            return Swal.fire({ icon: 'warning', title: 'Input', text: 'Lengkapi semua field wajib.' });
        }

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Generating...');
        $('#snapResult').html('');

        $.ajax({
            url: '{{ route("createMidtransSnapTokenAction") }}',
            type: 'POST',
            data: {
                order_id:   orderId,
                amount:     amount,
                first_name: firstName,
                last_name:  lastName,
                email:      email,
                phone:      phone,
            },
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-key me-1"></i> Generate Token &amp; Bayar');

                if (res.success && res.data && res.data.token) {
                    $('#snapResult').html('<div class="alert alert-success mt-2"><i class="fa fa-check me-2"></i>Token berhasil. Membuka payment popup...</div>');

                    loadSnapScript(function () {
                        if (typeof window.snap === 'undefined' || typeof window.snap.pay !== 'function') {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'window.snap tidak tersedia. Pastikan client key sudah diisi di konfigurasi.' });
                            return;
                        }
                        window.snap.pay(res.data.token, {
                            onSuccess: function (result) {
                                Swal.fire({ icon: 'success', title: 'Pembayaran Berhasil!', text: 'Order ID: ' + result.order_id });
                                $('#snapResult').html('<div class="alert alert-success mt-2"><i class="fa fa-check-circle me-2"></i>Pembayaran berhasil! Order ID: <strong>' + result.order_id + '</strong></div>');
                                if (window._dtTransaksiLoaded) dtTransaksi.ajax.reload(null, false);
                            },
                            onPending: function (result) {
                                Swal.fire({ icon: 'info', title: 'Menunggu Pembayaran', text: 'Order ID: ' + result.order_id });
                                $('#snapResult').html('<div class="alert alert-warning mt-2"><i class="fa fa-clock me-2"></i>Pending. Order ID: <strong>' + result.order_id + '</strong></div>');
                            },
                            onError: function (result) {
                                Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: result.status_message || 'Error tidak diketahui.' });
                                $('#snapResult').html('<div class="alert alert-danger mt-2"><i class="fa fa-times-circle me-2"></i>Error: ' + (result.status_message || '-') + '</div>');
                            },
                            onClose: function () {
                                $('#snapResult').html('<div class="alert alert-secondary mt-2"><i class="fa fa-times me-2"></i>Popup ditutup oleh user.</div>');
                            }
                        });
                    });

                } else {
                    var errMsg = (res.data && res.data.error_messages)
                        ? (Array.isArray(res.data.error_messages) ? res.data.error_messages.join(', ') : res.data.error_messages)
                        : (res.message || 'Gagal mendapatkan token.');
                    $('#snapResult').html('<div class="alert alert-danger mt-2"><i class="fa fa-times me-2"></i>' + errMsg + '</div>');
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-key me-1"></i> Generate Token &amp; Bayar');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan jaringan.';
                if (xhr.status === 419) msg = 'CSRF token mismatch. Coba refresh halaman.';
                $('#snapResult').html('<div class="alert alert-danger mt-2"><i class="fa fa-times me-2"></i>' + msg + '</div>');
            }
        });
    });

    // ================================================================
    // CREATE DIRECT CHARGE
    // ================================================================
    $('#btnCreateCharge').on('click', function () {
        var paymentType = $('#charge_payment_type').val();
        var orderId     = $('#charge_order_id').val().trim();
        var amount      = $('#charge_amount').val().trim();
        var firstName   = $('#charge_first_name').val().trim();
        var email       = $('#charge_email').val().trim();
        var phone       = $('#charge_phone').val().trim();

        if (!paymentType || !orderId || !amount || !firstName || !email || !phone) {
            return Swal.fire({ icon: 'warning', title: 'Input', text: 'Lengkapi semua field wajib.' });
        }

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Processing...');

        $.ajax({
            url: '{{ route("createMidtransChargeAction") }}',
            type: 'POST',
            data: {
                payment_type: paymentType,
                order_id:     orderId,
                amount:       amount,
                first_name:   firstName,
                email:        email,
                phone:        phone,
            },
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-bolt me-1"></i> Create Charge');
                if (res.success) {
                    $('#chargeResult').html('<pre class="bg-light rounded p-3 mt-2" style="font-size:12px;overflow:auto;max-height:300px">' + JSON.stringify(res.data, null, 2) + '</pre>');
                    if (window._dtTransaksiLoaded) dtTransaksi.ajax.reload(null, false);
                } else {
                    $('#chargeResult').html('<div class="alert alert-danger mt-2">' + res.message + '</div>');
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-bolt me-1"></i> Create Charge');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan jaringan.';
                if (xhr.status === 419) msg = 'CSRF token mismatch. Coba refresh halaman.';
                $('#chargeResult').html('<div class="alert alert-danger mt-2">' + msg + '</div>');
            }
        });
    });

    // ================================================================
    // TOGGLE PASSWORD VISIBILITY
    // ================================================================
    window.toggleVisibility = function (fieldId, btn) {
        var input = document.getElementById(fieldId);
        var icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa fa-eye';
        }
    };

})();
</script>
