@include('admin-panel.layouts.header')
<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div id="kt_app_toolbar" class="app-toolbar py-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
            <div class="d-flex flex-column flex-row-fluid">
                <div class="d-flex align-items-center pt-1">
                    {!! $breadcrumb !!}
                </div>
                <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
                    <div class="page-title d-flex align-items-center me-3">
                        <h1 class="page-heading d-flex fw-bolder fs-2 flex-column justify-content-center my-0">{{ $menu }}
                            <span class="page-desc opacity-50 fs-6 fw-bold pt-4">{{ $detail->judul_event ?? '' }}</span>
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

                    <div class="card card-flush mt-4">
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <div class="card-title">
                                <a href="{{ route('event') }}" class="btn btn-light btn-sm"><i class="fa fa-arrow-left"></i> Back to Events</a>
                            </div>
                            <?php if($cek_permit['c']){ ?>
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                <button class="btn btn-marron-submit" id="btnTambahTimeline"><i class="fa fa-plus text-white"></i> Add Session</button>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="card-body pt-0">
                            <table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>No</th>
                                        <th>Day</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Session Title</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
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

{{-- Modal Add --}}
<div class="modal fade" id="modalTambahTimeline" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Session Timeline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="add_kode_event" value="{{ $kode_event }}">
                <div class="mb-3">
                    <label class="form-label">Day</label>
                    <input type="number" id="add_hari_ke" class="form-control" min="1" placeholder="e.g. 1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" id="add_tanggal" class="form-control">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" id="add_mulai" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" id="add_selesai" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Session Title</label>
                    <input type="text" id="add_judul" class="form-control" placeholder="Session title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-muted">(optional)</span></label>
                    <textarea id="add_deskripsi" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="add_status" class="form-select">
                        <option value="Y">Active</option>
                        <option value="N">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-marron-submit" id="btnSimpanTimeline">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEditTimeline" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Session Timeline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_kode_timeline">
                <div class="mb-3">
                    <label class="form-label">Day</label>
                    <input type="number" id="edit_hari_ke" class="form-control" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" id="edit_tanggal" class="form-control">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" id="edit_mulai" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" id="edit_selesai" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Session Title</label>
                    <input type="text" id="edit_judul" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-muted">(optional)</span></label>
                    <textarea id="edit_deskripsi" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="edit_status" class="form-select">
                        <option value="Y">Active</option>
                        <option value="N">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-marron-submit" id="btnUpdateTimeline">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var kodeEvent = $('#add_kode_event').val();

    var table = $('#mainTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('getTableTimeline') }}",
            data: function (d) { d.key = kodeEvent; }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'hari_label', orderable: false },
            { data: 'tanggal_label', orderable: false },
            { data: 'waktu', orderable: false },
            { data: 'judul_sesi' },
            { data: 'status_label', orderable: false },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    // Add
    $('#btnTambahTimeline').click(function () {
        $('#modalTambahTimeline').modal('show');
    });

    $('#btnSimpanTimeline').click(function () {
        $.ajax({
            url: "{{ route('addTimelineAction') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                key: kodeEvent,
                hari_ke: $('#add_hari_ke').val(),
                tanggal_timeline: $('#add_tanggal').val(),
                jam_mulai: $('#add_mulai').val(),
                jam_selesai: $('#add_selesai').val(),
                judul_sesi: $('#add_judul').val(),
                deskripsi_sesi: $('#add_deskripsi').val(),
                status_timeline: $('#add_status').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#modalTambahTimeline').modal('hide');
                    Swal.fire('Success', res.message, 'success').then(function () { table.ajax.reload(); });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to save';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // Edit
    $(document).on('click', '.btn-edit-timeline', function () {
        $('#edit_kode_timeline').val($(this).data('kode'));
        $('#edit_hari_ke').val($(this).data('hari'));
        $('#edit_tanggal').val($(this).data('tanggal'));
        $('#edit_mulai').val($(this).data('mulai'));
        $('#edit_selesai').val($(this).data('selesai'));
        $('#edit_judul').val($(this).data('judul'));
        $('#edit_deskripsi').val($(this).data('deskripsi'));
        $('#edit_status').val($(this).data('status'));
        $('#modalEditTimeline').modal('show');
    });

    $('#btnUpdateTimeline').click(function () {
        $.ajax({
            url: "{{ route('updateTimelineAction') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                kode_timeline: $('#edit_kode_timeline').val(),
                hari_ke: $('#edit_hari_ke').val(),
                tanggal_timeline: $('#edit_tanggal').val(),
                jam_mulai: $('#edit_mulai').val(),
                jam_selesai: $('#edit_selesai').val(),
                judul_sesi: $('#edit_judul').val(),
                deskripsi_sesi: $('#edit_deskripsi').val(),
                status_timeline: $('#edit_status').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#modalEditTimeline').modal('hide');
                    Swal.fire('Success', res.message, 'success').then(function () { table.ajax.reload(); });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // Delete
    $(document).on('click', '.btn-delete-timeline', function () {
        var kode = $(this).data('kode');
        Swal.fire({
            title: 'Confirm', text: 'Delete this session?', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33', confirmButtonText: 'Yes', cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteTimelineAction') }}",
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}", kode_timeline: kode },
                    success: function (res) {
                        if (res.success) {
                            Swal.fire('Deleted', res.message, 'success').then(function () { table.ajax.reload(); });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>

@include('admin-panel.layouts.footer')
