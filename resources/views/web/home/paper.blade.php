@include('layouts.header-v2')

<style>
.paper-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 60px 0 40px;
    margin-bottom: 40px;
}
.paper-hero h1 { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 6px; }
.paper-hero p  { color: rgba(255,255,255,0.6); font-size: 0.95rem; }

/* Accordion event card */
.event-accordion-card {
    border: 1.5px solid #e8e8e8;
    border-radius: 14px;
    margin-bottom: 16px;
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.event-accordion-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.event-accordion-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 20px;
    background: #fff;
    cursor: pointer;
    user-select: none;
    border-bottom: 1.5px solid transparent;
    transition: background 0.2s;
}
.event-accordion-header.open {
    background: #fafafa;
    border-bottom-color: #e8e8e8;
}
.event-thumb {
    width: 56px;
    height: 56px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
    background: #f0f0f0;
}
.event-thumb-placeholder {
    width: 56px;
    height: 56px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.event-acc-info { flex: 1; min-width: 0; }
.event-acc-info .ev-title { font-weight: 700; color: #1a1a1a; font-size: 0.97rem; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.event-acc-info .ev-meta  { font-size: 0.78rem; color: #888; }
.ev-badge-paper {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    white-space: nowrap;
    flex-shrink: 0;
}
.ev-badge-paper.uploaded { background: #d1fae5; color: #065f46; }
.ev-badge-paper.empty    { background: #fef9c3; color: #78350f; }
.acc-chevron {
    color: #aaa;
    font-size: 0.85rem;
    transition: transform 0.25s;
    flex-shrink: 0;
}
.acc-chevron.open { transform: rotate(180deg); color: #E62020; }

/* Accordion body */
.event-accordion-body {
    display: none;
    padding: 20px;
    background: #fafafa;
}

/* Upload button area */
.upload-area {
    background: #fff;
    border: 1.5px dashed #d1d5db;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}
.upload-area .ua-text  { font-size: 0.85rem; color: #555; }
.upload-area .ua-text strong { color: #1a1a1a; display: block; font-size: 0.92rem; margin-bottom: 2px; }
.btn-upload-paper {
    background: #E62020;
    color: #fff;
    border: none;
    border-radius: 9px;
    padding: 9px 18px;
    font-weight: 700;
    font-size: 0.84rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    white-space: nowrap;
    transition: background 0.2s;
}
.btn-upload-paper:hover { background: #c41a1a; }

/* Empty state */
.empty-state {
    text-align: center;
    padding: 30px 20px;
    color: #aaa;
    font-size: 0.88rem;
}
.empty-state i { font-size: 2.5rem; margin-bottom: 10px; display: block; }

@media (max-width: 576px) {
    .upload-area { flex-direction: column; align-items: flex-start; }
    .btn-upload-paper { width: 100%; justify-content: center; }
}
</style>

<div class="paper-hero">
    <div class="container">
        <h1><i class="fa fa-file-text-o me-3"></i>My Papers</h1>
        <p>Kelola dan upload paper Anda untuk setiap event yang diikuti</p>
    </div>
</div>

<div class="container pb-10">

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-6" style="border-radius:12px;">
            <i class="fa fa-check-circle me-2 fs-5"></i> {{ session('success') }}
        </div>
    @endif

    @if($events->isEmpty())
        <div class="empty-state" style="margin-top:60px;">
            <i class="fa fa-calendar-times-o"></i>
            <div class="fw-bold fs-5 mb-2 text-dark">Belum terdaftar di event apapun</div>
            <p>Daftar ke event terlebih dahulu untuk bisa mengupload paper.</p>
            <a href="{{ route('list-event') }}" class="btn btn-danger mt-3">Lihat Event</a>
        </div>
    @else
        <div class="mb-4">
            <div class="text-muted" style="font-size:0.85rem;">
                Anda terdaftar di <strong>{{ $events->count() }}</strong> event. Klik event untuk melihat atau mengupload paper.
            </div>
        </div>

        @foreach($events as $index => $ev)
        <div class="event-accordion-card" id="card-{{ $ev->kode_event }}">

            {{-- Header --}}
            <div class="event-accordion-header" onclick="toggleAccordion('{{ $ev->kode_event }}')" id="header-{{ $ev->kode_event }}">
                @if($ev->gambar_event)
                    <img src="{{ asset('storage/'.$ev->gambar_event) }}" alt="" class="event-thumb">
                @else
                    <div class="event-thumb-placeholder"><i class="fa fa-calendar"></i></div>
                @endif

                <div class="event-acc-info">
                    <div class="ev-title">{{ $ev->judul_event }}</div>
                    <div class="ev-meta">
                        <i class="fa fa-map-marker me-1"></i>{{ $ev->lokasi_event ?? '-' }}
                        &nbsp;&bull;&nbsp;
                        <i class="fa fa-calendar me-1"></i>{{ date('d M Y', strtotime($ev->tanggal_awal_event)) }}
                    </div>
                </div>

                <span class="ev-badge-paper {{ $ev->has_paper ? 'uploaded' : 'empty' }}">
                    {{ $ev->has_paper ? '&#10003; Ada Paper' : 'Belum Upload' }}
                </span>

                <i class="fa fa-chevron-down acc-chevron" id="chevron-{{ $ev->kode_event }}"></i>
            </div>

            {{-- Body (hidden by default) --}}
            <div class="event-accordion-body" id="body-{{ $ev->kode_event }}"
                 data-kode-event="{{ $ev->kode_event }}"
                 data-kode-registrasi="{{ $ev->kode_registrasi }}"
                 data-loaded="0">

                {{-- Upload area --}}
                @if(!$ev->has_paper)
                <div class="upload-area">
                    <div class="ua-text">
                        <strong>Belum ada paper yang diupload</strong>
                        Upload paper Anda untuk event ini (PDF / PPT / PPTX, maks 20MB)
                    </div>
                    <button class="btn-upload-paper"
                            onclick="openUploadModal('{{ $ev->kode_event }}', '{{ $ev->kode_registrasi }}', '{{ addslashes($ev->judul_event) }}')"
                    >
                        <i class="fa fa-upload"></i> Upload Paper
                    </button>
                </div>
                @else
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="fw-semibold" style="font-size:0.88rem;color:#555;">Daftar paper yang disubmit di event ini:</span>
                    <button class="btn-upload-paper"
                            onclick="openUploadModal('{{ $ev->kode_event }}', '{{ $ev->kode_registrasi }}', '{{ addslashes($ev->judul_event) }}')"
                            style="padding:7px 14px;font-size:0.8rem;"
                    >
                        <i class="fa fa-plus"></i> Upload Lagi
                    </button>
                </div>
                @endif

                {{-- DataTable paper --}}
                <div class="table-responsive">
                    <table id="dt-paper-{{ $ev->kode_event }}" class="table table-sm table-hover align-middle" style="width:100%;font-size:0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Judul Paper</th>
                                <th>Submitter</th>
                                <th width="100">File</th>
                                <th width="110">Status</th>
                                <th width="130">Tanggal Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="6" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin me-1"></i>Klik event untuk memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        @endforeach
    @endif

</div>

{{-- ===================== MODAL UPLOAD PAPER ===================== --}}
<div class="modal fade" id="modalUploadPaper" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold mb-0"><i class="fa fa-upload me-2 text-danger"></i>Upload Paper</h5>
                    <small class="text-muted" id="modal-event-name" style="font-size:0.8rem;"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">

                <div id="upload-alert" style="display:none;"></div>

                <form id="formUploadPaper" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="up_kode_event" name="kode_event">
                    <input type="hidden" id="up_kode_registrasi" name="kode_registrasi">

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">Judul Paper <span class="text-danger">*</span></label>
                        <input type="text" name="judul_paper" id="up_judul" class="form-control"
                               placeholder="Masukkan judul paper Anda" required
                               style="border-radius:9px;border:1.5px solid #e0e0e0;font-size:0.88rem;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">
                            File Paper <span class="text-danger">*</span>
                            <small class="text-muted fw-normal">(PDF / PPT / PPTX, maks 20MB)</small>
                        </label>
                        <input type="file" name="file_paper" id="up_file" class="form-control"
                               accept=".pdf,.ppt,.pptx" required
                               style="border-radius:9px;border:1.5px solid #e0e0e0;font-size:0.88rem;">
                        <div id="file-info" class="mt-2" style="font-size:0.78rem;color:#888;"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal" style="border-radius:9px;font-weight:600;">Batal</button>
                        <button type="submit" class="btn btn-danger flex-fill fw-bold" id="btnSubmitUpload" style="border-radius:9px;">
                            <i class="fa fa-upload me-1"></i>Upload Paper
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var _currentEventKode = null;

// ── Accordion toggle ──────────────────────────────────────────────────
function toggleAccordion(kodeEvent) {
    var body    = document.getElementById('body-' + kodeEvent);
    var header  = document.getElementById('header-' + kodeEvent);
    var chevron = document.getElementById('chevron-' + kodeEvent);
    var isOpen  = body.style.display === 'block';

    // Tutup semua dulu
    document.querySelectorAll('.event-accordion-body').forEach(function(el) {
        el.style.display = 'none';
    });
    document.querySelectorAll('.event-accordion-header').forEach(function(el) {
        el.classList.remove('open');
    });
    document.querySelectorAll('.acc-chevron').forEach(function(el) {
        el.classList.remove('open');
    });

    if (!isOpen) {
        body.style.display = 'block';
        header.classList.add('open');
        chevron.classList.add('open');

        // Load datatable hanya sekali
        if (body.dataset.loaded === '0') {
            body.dataset.loaded = '1';
            loadPaperDatatable(kodeEvent);
        }
    }
}

// ── Load DataTable per event ──────────────────────────────────────────
function loadPaperDatatable(kodeEvent) {
    var tableId = '#dt-paper-' + kodeEvent;

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("web.paper.datatable") }}',
            type: 'GET',
            data: { kode_event: kodeEvent }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'judul_paper' },
            { data: 'peserta_info', orderable: false },
            { data: 'file_col', orderable: false, className: 'text-center' },
            { data: 'status_badge', orderable: false, className: 'text-center' },
            { data: 'created_at', className: 'text-center' }
        ],
        language: {
            emptyTable: '<div class="text-center text-muted py-3"><i class="fa fa-inbox me-1"></i>Belum ada paper di event ini</div>',
            processing: '<div class="text-center py-3"><i class="fa fa-spinner fa-spin me-1"></i>Memuat data...</div>'
        },
        dom: 'rtip',
        pageLength: 10,
        scrollX: true,
        drawCallback: function() {
            // Re-init tooltips if any
        }
    });
}

// ── Upload modal ──────────────────────────────────────────────────────
function openUploadModal(kodeEvent, kodeRegistrasi, namaEvent) {
    _currentEventKode = kodeEvent;
    document.getElementById('up_kode_event').value = kodeEvent;
    document.getElementById('up_kode_registrasi').value = kodeRegistrasi;
    document.getElementById('modal-event-name').textContent = namaEvent;
    document.getElementById('up_judul').value = '';
    document.getElementById('up_file').value = '';
    document.getElementById('file-info').textContent = '';
    document.getElementById('upload-alert').style.display = 'none';
    document.getElementById('upload-alert').innerHTML = '';
    var modal = new bootstrap.Modal(document.getElementById('modalUploadPaper'));
    modal.show();
}

// Preview nama file
document.getElementById('up_file').addEventListener('change', function() {
    var info = document.getElementById('file-info');
    if (this.files.length > 0) {
        var f    = this.files[0];
        var size = (f.size / 1024 / 1024).toFixed(2);
        var icon = f.name.endsWith('.pdf') ? '&#128196;' : '&#128207;';
        info.innerHTML = icon + ' <strong>' + f.name + '</strong> &mdash; ' + size + ' MB';
        info.style.color = f.size > 20 * 1024 * 1024 ? '#dc2626' : '#16a34a';
    } else {
        info.textContent = '';
    }
});

// Submit upload
$('#formUploadPaper').on('submit', function(e) {
    e.preventDefault();
    var btn    = document.getElementById('btnSubmitUpload');
    var alert  = document.getElementById('upload-alert');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Mengupload...';
    alert.style.display = 'none';

    var fd = new FormData(this);

    $.ajax({
        url: '{{ route("web.paper.upload") }}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(r) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-upload me-1"></i>Upload Paper';
            if (r.success) {
                alert.innerHTML = '<div class="alert alert-success p-3" style="border-radius:9px;font-size:0.85rem;"><i class="fa fa-check me-1"></i>' + r.message + '</div>';
                alert.style.display = 'block';
                // Reload datatable
                setTimeout(function() {
                    bootstrap.Modal.getInstance(document.getElementById('modalUploadPaper')).hide();
                    // Reload page untuk update badge "Ada Paper"
                    location.reload();
                }, 1500);
            } else {
                alert.innerHTML = '<div class="alert alert-danger p-3" style="border-radius:9px;font-size:0.85rem;"><i class="fa fa-times me-1"></i>' + r.message + '</div>';
                alert.style.display = 'block';
            }
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-upload me-1"></i>Upload Paper';
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal mengupload paper.';
            alert.innerHTML = '<div class="alert alert-danger p-3" style="border-radius:9px;font-size:0.85rem;"><i class="fa fa-times me-1"></i>' + msg + '</div>';
            alert.style.display = 'block';
        }
    });
});
</script>

@include('layouts.footer-v2')
