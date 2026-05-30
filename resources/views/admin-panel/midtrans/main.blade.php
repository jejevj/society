@include('admin-panel.layouts.header')
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<div class="d-flex flex-column flex-row-fluid">
								<div class="d-flex align-items-center pt-1">
									@include('admin-panel.layouts._breadcrumb', ['items' => [
										['label' => 'Settings', 'url' => null],
										['label' => 'Midtrans Configurations', 'url' => null],
									]])
								</div>
								<div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
									<div class="page-title d-flex align-items-center me-3">
										<h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{ $menu }}
											<span class="page-desc opacity-50 fs-6 fw-bold pt-4"></span>
										</h1>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="app-container container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">

									{{-- Status Alert --}}
									@if($config && $config->is_active == 'Y')
									<div class="alert alert-success d-flex align-items-center mb-6">
										<i class="fa fa-check-circle fs-2 me-3 text-success"></i>
										<div>
											<strong>Midtrans Active</strong> &mdash; Environment:
											<span class="badge badge-{{ $config->environment == 'production' ? 'danger' : 'warning' }}">
												{{ strtoupper($config->environment) }}
											</span>
										</div>
									</div>
									@else
									<div class="alert alert-warning d-flex align-items-center mb-6">
										<i class="fa fa-exclamation-triangle fs-2 me-3 text-warning"></i>
										<div><strong>Midtrans Not Active</strong> &mdash; Please configure and activate below.</div>
									</div>
									@endif

									{{-- MAIN TABS --}}
									<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 mb-6" id="mainMidtransTabs">
										<li class="nav-item">
											<a class="nav-link active" data-bs-toggle="tab" href="#tab_konfigurasi">
												<i class="fa fa-cog me-2"></i> Konfigurasi
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#tab_transaksi" id="tabTransaksiLink">
												<i class="fa fa-list me-2"></i> Transaksi
												<span class="badge badge-light-primary ms-1">{{ $tabCounts['all'] }}</span>
											</a>
										</li>
									</ul>

									<div class="tab-content">

										{{-- TAB KONFIGURASI --}}
										<div class="tab-pane fade show active" id="tab_konfigurasi">
											<div class="row g-6">
												{{-- CARD API CONFIG --}}
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

												{{-- CARD PAYMENT TYPES --}}
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

												{{-- TRANSACTION TOOLS --}}
												<div class="row g-6 mt-2">
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
													<div class="col-xl-6">
														<div class="card card-flush">
															<div class="card-header"><h3 class="card-title"><i class="fa fa-key text-primary me-2"></i> Create SNAP Token</h3></div>
															<div class="card-body">
																<div class="row g-3">
																	<div class="col-md-6"><label class="form-label fw-bold">Order ID</label><input type="text" class="form-control" id="snap_order_id" placeholder="order-001"></div>
																	<div class="col-md-6"><label class="form-label fw-bold">Amount (IDR)</label><input type="number" class="form-control" id="snap_amount" placeholder="100000" min="1"></div>
																	<div class="col-md-6"><label class="form-label fw-bold">First Name</label><input type="text" class="form-control" id="snap_first_name" placeholder="John"></div>
																	<div class="col-md-6"><label class="form-label fw-bold">Last Name</label><input type="text" class="form-control" id="snap_last_name" placeholder="Doe"></div>
																	<div class="col-md-6"><label class="form-label fw-bold">Email</label><input type="email" class="form-control" id="snap_email" placeholder="john@example.com"></div>
																	<div class="col-md-6"><label class="form-label fw-bold">Phone</label><input type="text" class="form-control" id="snap_phone" placeholder="08xxxxxxxxxx"></div>
																</div>
																<button class="btn btn-primary mt-3" id="btnCreateSnap"><i class="fa fa-key me-1"></i> Generate Token</button>
																<div id="snapResult" class="mt-3"></div>
															</div>
														</div>
													</div>
													<div class="col-xl-12">
														<div class="card card-flush">
															<div class="card-header"><h3 class="card-title"><i class="fa fa-bolt text-warning me-2"></i> Create Charge (Core API)</h3></div>
											