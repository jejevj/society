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
															{{-- Environment --}}
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
															{{-- Server Key --}}
															<div class="col-md-6 mb-5">
																<label class="form-label required fw-bold">Server Key</label>
																<div class="input-group">
																	<input type="password" class="form-control" id="server_key" name="server_key"
																		placeholder="SB-Mid-server-xxxxx" value="{{ $config->server_key ?? '' }}">
																	<button class="btn btn-light-secondary" type="button" onclick="toggleVisibility('server_key', this)"><i class="fa fa-eye"></i></button>
																</div>
															</div>
															{{-- Client Key --}}
															<div class="col-md-6 mb-5">
																<label class="form-label required fw-bold">Client Key</label>
																<div class="input-group">
																	<input type="password" class="form-control" id="client_key" name="client_key"
																		placeholder="SB-Mid-client-xxxxx" value="{{ $config->client_key ?? '' }}">
																	<button class="btn btn-light-secondary" type="button" onclick="toggleVisibility('client_key', this)"><i class="fa fa-eye"></i></button>
																</div>
															</div>
															{{-- Merchant ID --}}
															<div class="col-md-12 mb-5">
																<label class="form-label fw-bold">Merchant ID <span class="text-muted">(optional)</span></label>
																<input type="text" class="form-control" name="merchant_id"
																	placeholder="e.g. G123456789" value="{{ $config->merchant_id ?? '' }}">
															</div>
															{{-- Webhook URL --}}
															<div class="col-md-12 mb-5">
																<label class="form-label fw-bold">Webhook / Notification URL <span class="text-muted">(optional)</span></label>
																<input type="url" class="form-control" name="webhook_url"
																	placeholder="https://yourdomain.com/midtrans/webhook" value="{{ $config->webhook_url ?? '' }}">
															</div>
															{{-- Redirect URLs --}}
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
															{{-- Status --}}
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
									</div>

									{{-- TRANSACTION TOOLS --}}
									<div class="row g-6 mt-2">
										{{-- Get Status --}}
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
										{{-- Transaction Actions --}}
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
										{{-- Refund --}}
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
										{{-- Snap Token --}}
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
										{{-- Create Charge --}}
										<div class="col-xl-12">
											<div class="card card-flush">
												<div class="card-header"><h3 class="card-title"><i class="fa fa-bolt text-warning me-2"></i> Create Charge (Core API)</h3></div>
												<div class="card-body">
													<div class="row g-3">
														<div class="col-md-4">
															<label class="form-label fw-bold">Payment Type</label>
															<select class="form-select" id="charge_payment_type">
																<option value="">-- Select Payment Type --</option>
																@foreach($allPaymentTypes as $typeKey => $typeLabel)
																<option value="{{ $typeKey }}">{{ $typeLabel }}</option>
																@endforeach
															</select>
														</div>
														<div class="col-md-4"><label class="form-label fw-bold">Order ID</label><input type="text" class="form-control" id="charge_order_id" placeholder="order-001"></div>
														<div class="col-md-4"><label class="form-label fw-bold">Amount (IDR)</label><input type="number" class="form-control" id="charge_amount" placeholder="100000" min="1"></div>
														<div class="col-md-4"><label class="form-label fw-bold">First Name</label><input type="text" class="form-control" id="charge_first_name" placeholder="John"></div>
														<div class="col-md-4"><label class="form-label fw-bold">Email</label><input type="email" class="form-control" id="charge_email" placeholder="john@example.com"></div>
														<div class="col-md-4"><label class="form-label fw-bold">Phone</label><input type="text" class="form-control" id="charge_phone" placeholder="08xxxxxxxxxx"></div>
													</div>
													<button class="btn btn-warning mt-3" id="btnCreateCharge"><i class="fa fa-bolt me-1"></i> Create Charge</button>
													<div id="chargeResult" class="mt-3"></div>
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
		<!--end::App-->
		<script>
		function toggleVisibility(fieldId, btn) {
			const input = document.getElementById(fieldId);
			if (input.type === 'password') { input.type = 'text'; btn.innerHTML = '<i class="fa fa-eye-slash"></i>'; }
			else { input.type = 'password'; btn.innerHTML = '<i class="fa fa-eye"></i>'; }
		}
		function showResult(id, data, ok) {
			document.getElementById(id).innerHTML = `<div class="alert alert-${ok?'success':'danger'} mt-2"><pre class="mb-0 fs-7" style="white-space:pre-wrap;">${JSON.stringify(data,null,2)}</pre></div>`;
		}

		document.querySelectorAll('.payment-type-checkbox').forEach(cb => {
			cb.addEventListener('change', function () {
				const lbl = this.closest('.payment-type-item');
				if (this.checked) { lbl.classList.add('border-primary','bg-light-primary'); lbl.classList.remove('border-gray-200'); }
				else { lbl.classList.remove('border-primary','bg-light-primary'); lbl.classList.add('border-gray-200'); }
			});
		});
		document.getElementById('btnSelectAll').addEventListener('click',()=>document.querySelectorAll('.payment-type-checkbox').forEach(cb=>{cb.checked=true;cb.dispatchEvent(new Event('change'));}));
		document.getElementById('btnDeselectAll').addEventListener('click',()=>document.querySelectorAll('.payment-type-checkbox').forEach(cb=>{cb.checked=false;cb.dispatchEvent(new Event('change'));}));

		document.getElementById('formMidtransConfig').addEventListener('submit', function(e) {
			e.preventDefault();
			const checked = Array.from(document.querySelectorAll('.payment-type-checkbox:checked')).map(cb=>cb.value);
			if (!checked.length) { Swal.fire('Warning','Please select at least one payment type.','warning'); return; }
			const formData = new FormData(this);
			checked.forEach(v => formData.append('payment_types[]', v));
			$.ajax({
				url: '{{ route("updateMidtransConfigAction") }}', type: 'POST', data: formData,
				processData: false, contentType: false, headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
				success: res => Swal.fire('Success', res.message, 'success').then(()=>location.reload()),
				error: xhr => Swal.fire('Error', xhr.responseJSON?.message ?? 'Failed', 'error')
			});
		});

		document.getElementById('btnTestConnection').addEventListener('click',()=>{
			$.post('{{ route("testMidtransConnectionAction") }}',{_token:'{{ csrf_token() }}'},
				res=>Swal.fire(res.success?'Success':'Failed',res.message,res.success?'success':'error')
			).fail(()=>Swal.fire('Error','Request failed','error'));
		});
		document.getElementById('btnGetStatus').addEventListener('click',()=>{
			const id=$('#status_order_id').val().trim(); if(!id){Swal.fire('Warning','Order ID required','warning');return;}
			$.post('{{ route("getMidtransStatusAction") }}',{_token:'{{ csrf_token() }}',order_id:id},res=>showResult('statusResult',res.data??res,res.success)).fail(xhr=>showResult('statusResult',xhr.responseJSON,false));
		});
		document.getElementById('btnApprove').addEventListener('click',()=>{
			const id=$('#action_order_id').val().trim(); if(!id){Swal.fire('Warning','Order ID required','warning');return;}
			$.post('{{ route("approveMidtransAction") }}',{_token:'{{ csrf_token() }}',order_id:id},res=>showResult('actionResult',res.data??res,res.success)).fail(xhr=>showResult('actionResult',xhr.responseJSON,false));
		});
		document.getElementById('btnCancel').addEventListener('click',()=>{
			const id=$('#action_order_id').val().trim(); if(!id){Swal.fire('Warning','Order ID required','warning');return;}
			Swal.fire({title:'Cancel Transaction?',text:'Order: '+id,icon:'warning',showCancelButton:true,confirmButtonText:'Yes, Cancel'})
				.then(r=>{if(r.isConfirmed) $.post('{{ route("cancelMidtransAction") }}',{_token:'{{ csrf_token() }}',order_id:id},res=>showResult('actionResult',res.data??res,res.success)).fail(xhr=>showResult('actionResult',xhr.responseJSON,false));});
		});
		document.getElementById('btnExpire').addEventListener('click',()=>{
			const id=$('#action_order_id').val().trim(); if(!id){Swal.fire('Warning','Order ID required','warning');return;}
			$.post('{{ route("expireMidtransAction") }}',{_token:'{{ csrf_token() }}',order_id:id},res=>showResult('actionResult',res.data??res,res.success)).fail(xhr=>showResult('actionResult',xhr.responseJSON,false));
		});
		document.getElementById('btnRefund').addEventListener('click',()=>{
			const oid=$('#refund_order_id').val().trim(),amt=$('#refund_amount').val().trim(),rsn=$('#refund_reason').val().trim();
			if(!oid||!amt||!rsn){Swal.fire('Warning','All fields required','warning');return;}
			$.post('{{ route("refundMidtransAction") }}',{_token:'{{ csrf_token() }}',order_id:oid,amount:amt,reason:rsn},res=>showResult('refundResult',res.data??res,res.success)).fail(xhr=>showResult('refundResult',xhr.responseJSON,false));
		});
		document.getElementById('btnCreateSnap').addEventListener('click',()=>{
			$.post('{{ route("createMidtransSnapTokenAction") }}',{
				_token:'{{ csrf_token() }}',order_id:$('#snap_order_id').val().trim(),amount:$('#snap_amount').val().trim(),
				first_name:$('#snap_first_name').val().trim(),last_name:$('#snap_last_name').val().trim(),
				email:$('#snap_email').val().trim(),phone:$('#snap_phone').val().trim()
			},res=>showResult('snapResult',res.data??res,res.success)).fail(xhr=>showResult('snapResult',xhr.responseJSON,false));
		});
		document.getElementById('btnCreateCharge').addEventListener('click',()=>{
			$.post('{{ route("createMidtransChargeAction") }}',{
				_token:'{{ csrf_token() }}',payment_type:$('#charge_payment_type').val(),
				order_id:$('#charge_order_id').val().trim(),amount:$('#charge_amount').val().trim(),
				first_name:$('#charge_first_name').val().trim(),email:$('#charge_email').val().trim(),phone:$('#charge_phone').val().trim()
			},res=>showResult('chargeResult',res.data??res,res.success)).fail(xhr=>showResult('chargeResult',xhr.responseJSON,false));
		});
		</script>

@include('admin-panel.layouts.footer')
