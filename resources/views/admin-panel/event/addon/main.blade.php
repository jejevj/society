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

                    {{-- Event Info Card --}}
                    <div class="card card-flush mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="fs-6 opacity-75">Code:</label>
                                    <input type="text" readonly class="form-control" value="{{ $detail->kode_event }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="fs-6 opacity-75">Title:</label>
                                    <input type="text" readonly class="form-control" value="{{ $detail->judul_event }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="fs-6 opacity-75">Location & Date:</label>
                                    <input type="text" readonly class="form-control" value="{{ $detail->lokasi_event }}, {{ \Carbon\Carbon::parse($detail->tanggal_awal_event)->format('d F Y') }} - {{ \Carbon\Carbon::parse($detail->tanggal_akhir_event)->format('d F Y') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table Card --}}
                    <div class="card card-flush">
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <div class="card-title fw-bold fs-5">Add-On List</div>
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-3">
                                @if($cek_permit['c'])
                                <a href="{{ route('tambahAddonEvent', $kode_event) }}" class="btn btn-marron-submit btn-sm">
                                    <i class="fa fa-plus text-white"></i> Add New Add-On
                                </a>
                                @endif
                                <a href="{{ route('addonEventRegistrasi', $kode_event) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-list"></i> View Registrations
                                </a>
                                <a href="{{ route('event') }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-backward"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>No</th>
                                        <th class="min-w-200px">Name</th>
                                        <th class="min-w-120px">Featured Image</th>
                                        <th class="min-w-300px">Description</th>
                                        <th class="min-w-100px">Price</th>
                                        <th class="min-w-80px">Status</th>
                                        <th class="text-center min-w-100px">Actions</th>
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

<script>
    $(document).ready(function () {
        $('#mainTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('getTableAddonEvent') }}",
                data: function (d) { d.key = '{{ $kode_event }}'; }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_addon', name: 'nama_addon'},
                {data: 'gambar', name: 'gambar', orderable: false, searchable: false},
                {data: 'deskripsi_addon', name: 'deskripsi_addon'},
                {data: 'harga', name: 'harga', orderable: false, searchable: false},
                {data: 'status_badge', name: 'status_badge', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $(document).on('click', '.btn-delete-addon', function () {
            var key = $(this).data('id');
            Swal.fire({
                title: 'Confirm', text: 'Delete this add-on? All related registrations will also be removed.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: 'Yes', cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteAddonEventAction') }}",
                        type: 'POST',
                        data: { _token: "{{ csrf_token() }}", key: key },
                        success: function (res) {
                            Swal.fire('Success', res.message, 'success').then(() => location.reload());
                        },
                        error: function () {
                            Swal.fire('Error', 'Failed to delete add-on.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>

@include('admin-panel.layouts.footer')
