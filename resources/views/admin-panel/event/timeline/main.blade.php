@extends('admin-panel.layouts.app')

@section('title', 'Event Timeline')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Event Timeline</h4>
            <small class="text-muted">Kelola jadwal sesi untuk setiap event</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTimeline">
            <i class="fas fa-plus me-1"></i> Tambah Sesi
        </button>
    </div>

    {{-- Filter --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <select id="filterEvent" class="form-select form-select-sm">
                        <option value="">-- Semua Event --</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->kode_event }}">{{ $ev->judul_event }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="filterHari" class="form-select form-select-sm">
                        <option value="">-- Semua Hari --</option>
                        @for($h = 1; $h <= 7; $h++)
                            <option value="{{ $h }}">Hari ke-{{ $h }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="filterJudul" class="form-control form-control-sm" placeholder="Cari judul sesi...">
                </div>
                <div class="col-md-2">
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="Y">Aktif</option>
                        <option value="N">Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button id="btnReset" class="btn btn-secondary btn-sm w-100">Reset</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tableTimeline" class="table table-bordered table-hover w-100">
                <thead class="table-dark">
                    <tr>
                        <th width="40">No</th>
                        <th>Event</th>
                        <th width="80">Hari</th>
                        <th width="110">Tanggal</th>
                        <th width="120">Waktu</th>
                        <th>Judul Sesi</th>
                        <th>Deskripsi</th>
                        <th width="80">Status</th>
                        <th width="90">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== MODAL TAMBAH ===== --}}
<div class="modal fade" id="modalTambahTimeline" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formTambahTimeline">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Sesi Timeline</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Event <span class="text-danger">*</span></label>
                            <select name="kode_event" class="form-select" required>
                                <option value="">-- Pilih Event --</option>
                                @foreach($events as $ev)
                                    <option value="{{ $ev->kode_event }}">{{ $ev->judul_event }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Hari ke- <span class="text-danger">*</span></label>
                            <input type="number" name="hari_ke" class="form-control" min="1" max="30" placeholder="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_timeline" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status_timeline" class="form-select">
                                <option value="Y">Aktif</option>
                                <option value="N">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Judul Sesi <span class="text-danger">*</span></label>
                            <input type="text" name="judul_sesi" class="form-control" placeholder="Contoh: Opening Ceremony" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi_sesi" class="form-control" rows="3" placeholder="Detail atau deskripsi sesi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div class="modal fade" id="modalEditTimeline" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formEditTimeline">
            @csrf
            <input type="hidden" name="kode_timeline" id="edit_kode_timeline">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Sesi Timeline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Event <span class="text-danger">*</span></label>
                            <select name="kode_event" id="edit_kode_event" class="form-select" required>
                                <option value="">-- Pilih Event --</option>
                                @foreach($events as $ev)
                                    <option value="{{ $ev->kode_event }}">{{ $ev->judul_event }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Hari ke-</label>
                            <input type="number" name="hari_ke" id="edit_hari_ke" class="form-control" min="1" max="30" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal_timeline" id="edit_tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status_timeline" id="edit_status" class="form-select">
                                <option value="Y">Aktif</option>
                                <option value="N">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="edit_jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="edit_jam_selesai" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Judul Sesi</label>
                            <input type="text" name="judul_sesi" id="edit_judul_sesi" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi_sesi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i>Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL HAPUS ===== --}}
<div class="modal fade" id="modalHapusTimeline" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Hapus Sesi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus sesi <strong id="hapus_judul_sesi"></strong>?</p>
                <p class="text-danger small">Data tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnKonfirmasiHapus" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    var table = $('#tableTimeline').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("getTableTimeline") }}',
            data: function (d) {
                d.kode_event  = $('#filterEvent').val();
                d.hari_ke     = $('#filterHari').val();
                d.judul_sesi  = $('#filterJudul').val();
                d.status      = $('#filterStatus').val();
            }
        },
        columns: [
            { data: 'no',             orderable: false },
            { data: 'judul_event',    orderable: false },
            { data: 'hari_ke',        orderable: false },
            { data: 'tanggal',        orderable: false },
            { data: 'waktu',          orderable: false },
            { data: 'judul_sesi',     orderable: false },
            { data: 'deskripsi_sesi', orderable: false, render: function(d){ return d ? (d.length > 60 ? d.substr(0,60)+'...' : d) : '-'; } },
            { data: 'status',         orderable: false },
            { data: 'aksi',           orderable: false },
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        pageLength: 10,
    });

    // Filter
    $('#filterEvent, #filterHari, #filterStatus').on('change', function(){ table.draw(); });
    $('#filterJudul').on('keyup', function(){ table.draw(); });
    $('#btnReset').on('click', function(){
        $('#filterEvent, #filterHari, #filterStatus').val('');
        $('#filterJudul').val('');
        table.draw();
    });

    // TAMBAH
    $('#formTambahTimeline').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("addTimelineAction") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.status === 'success'){
                    $('#modalTambahTimeline').modal('hide');
                    $('#formTambahTimeline')[0].reset();
                    table.draw();
                    Swal.fire('Berhasil', res.message, 'success');
                }
            },
            error: function(xhr){
                Swal.fire('Gagal', xhr.responseJSON?.message ?? 'Terjadi kesalahan.', 'error');
            }
        });
    });

    // BUKA MODAL EDIT
    $(document).on('click', '.btn-edit-timeline', function(){
        var btn = $(this);
        $('#edit_kode_timeline').val(btn.data('kode'));
        $('#edit_kode_event').val(btn.data('event'));
        $('#edit_hari_ke').val(btn.data('hari'));
        $('#edit_tanggal').val(btn.data('tanggal'));
        $('#edit_jam_mulai').val(btn.data('mulai'));
        $('#edit_jam_selesai').val(btn.data('selesai'));
        $('#edit_judul_sesi').val(btn.data('judul'));
        $('#edit_deskripsi').val(btn.data('deskripsi'));
        $('#edit_status').val(btn.data('status'));
        $('#modalEditTimeline').modal('show');
    });

    // UPDATE
    $('#formEditTimeline').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("updateTimelineAction") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.status === 'success'){
                    $('#modalEditTimeline').modal('hide');
                    table.draw();
                    Swal.fire('Berhasil', res.message, 'success');
                }
            },
            error: function(xhr){
                Swal.fire('Gagal', xhr.responseJSON?.message ?? 'Terjadi kesalahan.', 'error');
            }
        });
    });

    // BUKA MODAL HAPUS
    var kodeHapus = '';
    $(document).on('click', '.btn-delete-timeline', function(){
        kodeHapus = $(this).data('kode');
        var judul = $(this).closest('tr').find('td:eq(5)').text();
        $('#hapus_judul_sesi').text(judul);
        $('#modalHapusTimeline').modal('show');
    });

    // KONFIRMASI HAPUS
    $('#btnKonfirmasiHapus').on('click', function(){
        $.ajax({
            url: '{{ route("deleteTimelineAction") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', kode_timeline: kodeHapus },
            success: function(res){
                if(res.status === 'success'){
                    $('#modalHapusTimeline').modal('hide');
                    table.draw();
                    Swal.fire('Berhasil', res.message, 'success');
                }
            },
            error: function(){
                Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus.', 'error');
            }
        });
    });

});
</script>
@endpush
