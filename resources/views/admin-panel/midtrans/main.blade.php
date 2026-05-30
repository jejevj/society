@extends('admin-panel.layout.main')
@section('title', 'Midtrans Configurations')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Midtrans Configurations</h1>
                {!! $breadcrumb !!}
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            {{-- ALERT STATUS --}}
            @if($config && $config->is_active == 'Y')
            <div class="alert alert-success d-flex align-items-center mb-6">
                <i class="fa fa-check-circle fs-2 me-3 text-success"></i>
                <div>
                    <strong>Midtrans Active</strong> — Environment: <span class="badge badge-{{ $config->environment == 'production' ? 'danger' : 'warning' }}">{{ strtoupper($config->environment) }}</span>
                </div>
            </div>
            @else
            <div class="alert alert-warning d-flex align-items-center mb-6">
                <i class="fa fa-exclamation-triangle fs-2 me-3 text-warning"></i>
                <div><strong>Midtrans Not Active</strong> — Please configure and activate below.</div>
            </div>
            @endif

            <div class="row g-6">
                {{-- ============ CARD KONFIGURASI ============ --}}
                <div class="col-xl-7">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-cog text-primary me-2"></i> API Configuration</h3>
                        </div>
                        <div class="card-body">
                            <form id="formMidtransConfig">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label required fw-bold">Environment</label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" name="environment" id="env_sandbox" value="sandbox" {{ (!$config || $config->environment == 'sandbox') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="env_sandbox">
                                                    <span class="badge badge-warning fs-7 px-3 py-2"><i class="fa fa-flask me-1"></i> Sandbox (Testing)</span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" name="environment" id="env_production" value="production" {{ ($config && $config->environment == 'production') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="env_production">
                                                    <span class="badge badge-danger fs-7 px-3 py-2"><i class="fa fa-rocket me-1"></i> Production (Live)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label required fw-bold">Server Key</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="server_key" name="server_key" placeholder="SB-Mid-server-xxxxx or Mid-server-xxxxx" value="{{ $config->server_key ?? '' }}">
                                            <button class="btn btn-light-secondary" type="button" onclick="toggleVisibility('server_key', this)"><i class="fa fa-eye"></i></button>
                                        </div>
                                        <span class="text-muted fs-7">From Midtrans Dashboard &gt; Access Keys</span>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label required fw-bold">Client Key</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="client_key" name="client_key" placeholder="SB-Mid-client-xxxxx or Mid-client-xxxxx" value="{{ $config->client_key ?? '' }}">
                                            <button class="btn btn-light-secondary" type="button" onclick="toggleVisibility('client_key', this)"><i class="fa fa-eye"></i></button>
                                        </div>
                                        <span class="text-muted fs-7">Used on frontend Snap.js</span>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold">Merchant ID <span class="text-muted">(optional)</span></label>
                                        <input type="text" class="form-control" name="merchant_id" placeholder="e.g. G123456789" value="{{ $config->merchant_id ?? '' }}">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold">Webhook / Notification URL <span class="text-muted">(optional)</span></label>
                                        <input type="url" class="form-control" name="webhook_url" placeholder="https://yourdomain.com/midtrans/webhook" value="{{ $config->webhook_url ?? '' }}">
                                        <span class="text-muted fs-7">Set this URL in Midtrans Dashboard &gt; Settings &gt; Payment &gt; Notification URL</span>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold">Finish Redirect URL</label>
                                        <input type="url" class="form-control" name="finish_redirect_url" placeholder="https://..." value="{{ $config->finish_redirect_url ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold">Unfinish Redirect URL</label>
                                        <input type="url" class="form-control" name="unfinish_redirect_url" placeholder="https://..." value="{{ $config->unfinish_redirect_url ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold">Error Redirect URL</label>
                                        <input type="url" class="form-control" name="error_redirect_url" placeholder="https://..." value="{{ $config->error_redirect_url ?? '' }}">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label required fw-bold">Status</label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" name="is_active" id="status_active" value="Y" {{ ($config && $config->is_active == 'Y') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_active">Active</label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" name="is_active" id="status_inactive" value="N" {{ (!$config || $config->is_active == 'N') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_inactive">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($cek['u'])
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save me-2"></i> Save Configuration
                                    </button>
                                    <button type="button" class="btn btn-light-info" id="btnTestConnection">
                                        <i class="fa fa-plug me-2"></i> Test Connection
                                    </button>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ============ CARD PAYMENT TYPES ============ --}}
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
                            <p class="text-muted fs-7 mb-4">Select payment methods to enable. These will appear in the Snap checkout page.</p>
                            <div class="row g-3" id="paymentTypesContainer">
                                @foreach($allPaymentTypes as $typeKey => $typeLabel)
                                <div class="col-12">
                                    <label class="d-flex align-items-center cursor-pointer border rounded px-4 py-3 payment-type-item {{ in_array($typeKey, $selectedTypes) ? 'border-primary bg-light-primary' : 'border-gray-200' }}">
                                        <input type="checkbox" class="form-check-input me-3 payment-type-checkbox" name="payment_types_check[]"
                                            data-key="{{ $typeKey }}" value="{{ $typeKey }}"
                                            {{ in_array($typeKey, $selectedTypes) ? 'checked' : '' }}>
                                        <span class="fs-6">{{ $typeLabel }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ TRANSACTION TOOLS ============ --}}
            <div class="row g-6 mt-2">
                {{-- Get Status --}}
                <div class="col-xl-6">
                    <div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-search text-info me-2"></i> Get Transaction Status</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control" id="status_order_id" placeholder="Enter Order ID">
                                <button class="btn btn-info" id="btnGetStatus"><i class="fa fa-search me-1"></i> Check</button>
                            </div>
                            <div id="statusResult" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                {{-- Approve / Cancel / Expire --}}
                <div class="col-xl-6">
                    <div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-tasks text-warning me-2"></i> Transaction Actions</h3>
                        </div>
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
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-undo text-danger me-2"></i> Refund Transaction</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Order ID</label>
                                <input type="text" class="form-control" id="refund_order_id" placeholder="Enter Order ID">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Refund Amount (IDR)</label>
                                <input type="number" class="form-control" id="refund_amount" placeholder="e.g. 50000" min="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reason</label>
                                <input type="text" class="form-control" id="refund_reason" placeholder="Customer request refund">
                            </div>
                            <button class="btn btn-danger" id="btnRefund"><i class="fa fa-undo me-1"></i> Process Refund</button>
                            <div id="refundResult" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                {{-- Create Snap Token --}}
                <div class="col-xl-6">
                    <div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-key text-primary me-2"></i> Create SNAP Token</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Order ID</label>
                                    <input type="text" class="form-control" id="snap_order_id" placeholder="order-001">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Amount (IDR)</label>
                                    <input type="number" class="form-control" id="snap_amount" placeholder="100000" min="1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">First Name</label>
                                    <input type="text" class="form-control" id="snap_first_name" placeholder="John">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Last Name</label>
                                    <input type="text" class="form-control" id="snap_last_name" placeholder="Doe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="snap_email" placeholder="john@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone</label>
                                    <input type="text" class="form-control" id="snap_phone" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" id="btnCreateSnap"><i class="fa fa-key me-1"></i> Generate Token</button>
                            <div id="snapResult" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                {{-- Create Charge --}}
                <div class="col-xl-12">
                    <div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-bolt text-warning me-2"></i> Create Charge (Core API)</h3>
                        </div>
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
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Order ID</label>
                                    <input type="text" class="form-control" id="charge_order_id" placeholder="order-001">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Amount (IDR)</label>
                                    <input type="number" class="form-control" id="charge_amount" placeholder="100000" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">First Name</label>
                                    <input type="text" class="form-control" id="charge_first_name" placeholder="John">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="charge_email" placeholder="john@example.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Phone</label>
                                    <input type="text" class="form-control" id="charge_phone" placeholder="08xxxxxxxxxx">
                                </div>
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

{{-- Hidden payment_types input (auto-populated by JS) --}}
<div id="paymentTypesHidden"></div>

@push('scripts')
<script>
function toggleVisibility(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="fa fa-eye"></i>';
    }
}

function showResult(containerId, data, isSuccess) {
    const el = document.getElementById(containerId);
    const cls = isSuccess ? 'alert-success' : 'alert-danger';
    el.innerHTML = `<div class="alert ${cls} mt-2"><pre class="mb-0 fs-7" style="white-space:pre-wrap;">${JSON.stringify(data, null, 2)}</pre></div>`;
}

// Payment type checkbox visual toggle
document.querySelectorAll('.payment-type-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        const label = this.closest('.payment-type-item');
        if (this.checked) {
            label.classList.add('border-primary', 'bg-light-primary');
            label.classList.remove('border-gray-200');
        } else {
            label.classList.remove('border-primary', 'bg-light-primary');
            label.classList.add('border-gray-200');
        }
    });
});

document.getElementById('btnSelectAll').addEventListener('click', function () {
    document.querySelectorAll('.payment-type-checkbox').forEach(cb => {
        cb.checked = true;
        cb.dispatchEvent(new Event('change'));
    });
});

document.getElementById('btnDeselectAll').addEventListener('click', function () {
    document.querySelectorAll('.payment-type-checkbox').forEach(cb => {
        cb.checked = false;
        cb.dispatchEvent(new Event('change'));
    });
});

// Save Config
document.getElementById('formMidtransConfig').addEventListener('submit', function (e) {
    e.preventDefault();
    const checked = Array.from(document.querySelectorAll('.payment-type-checkbox:checked')).map(cb => cb.value);
    if (checked.length === 0) {
        Swal.fire('Warning', 'Please select at least one payment type.', 'warning');
        return;
    }
    const formData = new FormData(this);
    checked.forEach(v => formData.append('payment_types[]', v));

    $.ajax({
        url: '{{ route("updateMidtransConfigAction") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function (res) {
            Swal.fire('Success', res.message, 'success').then(() => location.reload());
        },
        error: function (xhr) {
            Swal.fire('Error', xhr.responseJSON?.message ?? 'Failed to save', 'error');
        }
    });
});

// Test Connection
document.getElementById('btnTestConnection').addEventListener('click', function () {
    $.ajax({
        url: '{{ route("testMidtransConnectionAction") }}',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function (res) {
            Swal.fire(res.success ? 'Success' : 'Failed', res.message, res.success ? 'success' : 'error');
        },
        error: function () { Swal.fire('Error', 'Request failed', 'error'); }
    });
});

// Get Status
document.getElementById('btnGetStatus').addEventListener('click', function () {
    const orderId = document.getElementById('status_order_id').value.trim();
    if (!orderId) { Swal.fire('Warning', 'Order ID required', 'warning'); return; }
    $.post('{{ route("getMidtransStatusAction") }}', { _token: '{{ csrf_token() }}', order_id: orderId },
        function (res) { showResult('statusResult', res.data ?? res, res.success); }
    ).fail(xhr => showResult('statusResult', xhr.responseJSON, false));
});

// Approve
document.getElementById('btnApprove').addEventListener('click', function () {
    const orderId = document.getElementById('action_order_id').value.trim();
    if (!orderId) { Swal.fire('Warning', 'Order ID required', 'warning'); return; }
    $.post('{{ route("approveMidtransAction") }}', { _token: '{{ csrf_token() }}', order_id: orderId },
        function (res) { showResult('actionResult', res.data ?? res, res.success); }
    ).fail(xhr => showResult('actionResult', xhr.responseJSON, false));
});

// Cancel
document.getElementById('btnCancel').addEventListener('click', function () {
    const orderId = document.getElementById('action_order_id').value.trim();
    if (!orderId) { Swal.fire('Warning', 'Order ID required', 'warning'); return; }
    Swal.fire({ title: 'Cancel Transaction?', text: 'Order: ' + orderId, icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, Cancel' }).then(r => {
        if (r.isConfirmed) {
            $.post('{{ route("cancelMidtransAction") }}', { _token: '{{ csrf_token() }}', order_id: orderId },
                function (res) { showResult('actionResult', res.data ?? res, res.success); }
            ).fail(xhr => showResult('actionResult', xhr.responseJSON, false));
        }
    });
});

// Expire
document.getElementById('btnExpire').addEventListener('click', function () {
    const orderId = document.getElementById('action_order_id').value.trim();
    if (!orderId) { Swal.fire('Warning', 'Order ID required', 'warning'); return; }
    $.post('{{ route("expireMidtransAction") }}', { _token: '{{ csrf_token() }}', order_id: orderId },
        function (res) { showResult('actionResult', res.data ?? res, res.success); }
    ).fail(xhr => showResult('actionResult', xhr.responseJSON, false));
});

// Refund
document.getElementById('btnRefund').addEventListener('click', function () {
    const orderId = document.getElementById('refund_order_id').value.trim();
    const amount  = document.getElementById('refund_amount').value.trim();
    const reason  = document.getElementById('refund_reason').value.trim();
    if (!orderId || !amount || !reason) { Swal.fire('Warning', 'All fields required', 'warning'); return; }
    $.post('{{ route("refundMidtransAction") }}', { _token: '{{ csrf_token() }}', order_id: orderId, amount: amount, reason: reason },
        function (res) { showResult('refundResult', res.data ?? res, res.success); }
    ).fail(xhr => showResult('refundResult', xhr.responseJSON, false));
});

// Create Snap Token
document.getElementById('btnCreateSnap').addEventListener('click', function () {
    $.post('{{ route("createMidtransSnapTokenAction") }}', {
        _token:     '{{ csrf_token() }}',
        order_id:   document.getElementById('snap_order_id').value.trim(),
        amount:     document.getElementById('snap_amount').value.trim(),
        first_name: document.getElementById('snap_first_name').value.trim(),
        last_name:  document.getElementById('snap_last_name').value.trim(),
        email:      document.getElementById('snap_email').value.trim(),
        phone:      document.getElementById('snap_phone').value.trim(),
    }, function (res) { showResult('snapResult', res.data ?? res, res.success); }
    ).fail(xhr => showResult('snapResult', xhr.responseJSON, false));
});

// Create Charge
document.getElementById('btnCreateCharge').addEventListener('click', function () {
    $.post('{{ route("createMidtransChargeAction") }}', {
        _token:       '{{ csrf_token() }}',
        payment_type: document.getElementById('charge_payment_type').value,
        order_id:     document.getElementById('charge_order_id').value.trim(),
        amount:       document.getElementById('charge_amount').value.trim(),
        first_name:   document.getElementById('charge_first_name').value.trim(),
        email:        document.getElementById('charge_email').value.trim(),
        phone:        document.getElementById('charge_phone').value.trim(),
    }, function (res) { showResult('chargeResult', res.data ?? res, res.success); }
    ).fail(xhr => showResult('chargeResult', xhr.responseJSON, false));
});
</script>
@endpush
@endsection
