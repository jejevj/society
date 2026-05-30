@include('admin-panel.layouts.header')
<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div id="kt_app_toolbar" class="app-toolbar py-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
            <div class="d-flex flex-column flex-row-fluid">
                <div class="d-flex align-items-center pt-1">
                    @include('admin-panel.layouts._breadcrumb', ['items' => [
                        ['label' => 'Event', 'url' => route('event')],
                        ['label' => 'Add-On', 'url' => route('addonEvent', $kode_event)],
                        ['label' => 'Registrations', 'url' => null],
                    ]])
                </div>
                <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
                    <div class="page-title d-flex align-items-center me-3">
                        <h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{ $menu }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content">

                    {{-- Filter Card --}}
                    <div class="card card-flush mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="fs-6 fw-semibold mb-2">Participant Name</label>
                                    <input type="text" id="filter-nama" class="form-control" placeholder="Participant name...">
                                </div>
                                <div class="col-md-4">
                                    <label class="fs-6 fw-semibold mb-2">Status</label>
                                    <select id="filter-status" class="form-select">
                                        <option value="">-- All Status --</option>
                                        <option value="P">Pending</option>
                                        <option value="A">Approved</option>
                                        <option value="R">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="fs-6 fw-semibold mb-2">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button id="btnSearch" class="btn btn-marron-submit w-100"><i class="fa fa-search"></i> Search</button>
                                        <button id="btnReset" class="btn btn-warning w-100"><i class="fa fa-rotate"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table Card --}}
                    <div class="card card-flush">
                        <div class="card-header align-items-center py-5">
                            <div class="card-title fw-bold fs-5">Add-On Registration List — <span class="text-muted fs-6">{{ $detail->judul_event }}</span></div>
                            <div class="card-toolbar">
                                <a href="{{ route('addonEvent', $kode_event) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-backward"></i> Back to Add-On
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>No</th>
                                        <th class="min-w-150px">Participant</th>
                                        <th class="min-w-120px">Email</th>
                                        <th class="min-w-120px">Institution</th>
                                        <th class="min-w-150px">Add-On</th>
                                        <th class="min-w-100px">Price</th>
                                        <th class="min-w-80px">Status</th>
                                        <th class="text-center min-w-150px">Actions</th>
                                    </tr>
                                </thead>
                            </table>
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

{{-- Confirm modal --}}
<div class="modal fade" id="modalConfirm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-key">
                <input type="hidden" id="modal-status">
                <div class="mb-3">
                    <label class="form-label">Notes (optional)</label>
                    <textarea id="modal-catatan" class="form-control" rows="3" placeholder="Add notes..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-marron-submit" id="btnSubmitStatus">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var table = $('#mainTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('getTableAddonEventRegistrasi') }}",
                data: function (d) {
                    d.kode_event = '{{ $kode_event }}';
                    d.nama = $('#filter-nama').val();
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_peserta'},
                {data: 'email_peserta'},
                {data: 'instansi_peserta'},
                {data: 'nama_addon'},
                {data: 'harga', orderable: false, searchable: false},
                {data: 'status_badge', orderable: false, searchable: false},
                {data: 'action', orderable: false, searchable: false},
            ]
        });

        $('#btnSearch').click(() => table.ajax.reload());
        $('#btnReset').click(() => {
            $('#filter-nama').val('');
            $('#filter-status').val('');
            table.ajax.reload();
        });

        $(document).on('click', '.btn-approve-addon', function () {
            $('#modal-key').val($(this).data('id'));
            $('#modal-status').val('A');
            $('#modalConfirmLabel').text('Approve Add-On Registration');
            $('#modal-catatan').val('');
            $('#modalConfirm').modal('show');
        });

        $(document).on('click', '.btn-reject-addon', function () {
            $('#modal-key').val($(this).data('id'));
            $('#modal-status').val('R');
            $('#modalConfirmLabel').text('Reject Add-On Registration');
            $('#modal-catatan').val('');
            $('#modalConfirm').modal('show');
        });

        $('#btnSubmitStatus').click(function () {
            $.ajax({
                url: "{{ route('updateStatusAddonRegistrasiAction') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    key: $('#modal-key').val(),
                    status: $('#modal-status').val(),
                    catatan: $('#modal-catatan').val(),
                },
                success: function (res) {
                    $('#modalConfirm').modal('hide');
                    Swal.fire('Success', res.message, 'success').then(() => table.ajax.reload());
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    });
</script>

@include('admin-panel.layouts.footer')
