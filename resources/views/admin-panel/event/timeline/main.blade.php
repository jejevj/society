@extends('admin-panel.layouts.main')

@section('title', 'Timeline Event')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Timeline: {{ $detail->judul_event ?? '' }}</h4>
            <small class="text-muted">Kelola jadwal sesi event ini</small>
        </div>
        <div>
            <a href="{{ route('event') }}" class="btn btn-secondary btn-sm me-2">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
            @if($cek_permit['c'])
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTimeline">
                <i class="fa fa-plus me-1"></i> Tambah Sesi
            </button>
            @endif
        </div>
    </div>

    {{-- Info Event --}}
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body py-2 px-3">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">Kode Event</small>
                    <div class="fw-semibold">{{ $kode_event }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">Tanggal</small>
                    <div class="fw-semibold">
                        {{ $detail ? \Carbon\Carbon::parse($detail->tanggal_awal_event)->translatedFormat('j F Y') : '-' }}
                        &mdash;
                        {{ $detail ? \Carbon\Carbon::parse($detail->tanggal_akhir_event)->translatedFormat('j F Y') : '-' }}
                    </div>
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
            <input type="hidden" name="key" value="{{ $kode_event }}">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-plus-circle me-2"></i>Tambah Sesi Timeline</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
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
                            <textarea name="deskripsi_sesi" class="form-control" rows="3" placeholder="Detail sesi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan</button>
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
                    <h5 class="modal-title"><i class="fa fa-edit me-2"></i>Edit Sesi Timeline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
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
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i>Update</button>
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
                <h5 class="modal-title"><i class="fa fa-trash me-2"></i>Hapus Sesi</h5>
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

    var kodeEvent = '{{ $kode_event }}';

    var table = $('#tableTimeline').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("getTableTimeline") }}',
            data: function (d) { d.key = kodeEvent; }
        },
        columns: [
            { data: 'DT_RowIndex',   orderable: false },
            { data: 'hari_label',    orderable: false },
            { data: 'tanggal_label', orderable: false },
            { data: 'waktu',         orderable: false },
            { data: 'judul_sesi',    orderable: false },
            { data: 'deskripsi_sesi',orderable: false, render: function(d){ return d ? (d.length > 60 ? d.substr(0,60)+'...' : d) : '-'; } },
            { data: 'status_label',  orderable: false },
            { data: 'action',        orderable: false },
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        pageLength: 10,
    });

    // TAMBAH
    $('#formTambahTimeline').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("addTimelineAction") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
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
                if(res.success){
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
        var judul = $(this).closest('tr').find('td:eq(4)').text();
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
                if(res.success){
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
