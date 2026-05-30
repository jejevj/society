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

                    <div class="card card-flush">
                        <div class="card-header align-items-center py-5">
                            <div class="card-title"><span class="fs-5 fw-bold">Add New Add-On</span></div>
                            <div class="card-toolbar">
                                <a href="{{ route('addonEvent', $detail->kode_event) }}" class="btn btn-sm btn-light">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="actForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="kode_event" value="{{ $detail->kode_event }}">

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-5">
                                            <label class="form-label required">Add-On Name</label>
                                            <input type="text" name="nama_addon" class="form-control" placeholder="e.g. Golf Experience, Beach Activity">
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label">Description</label>
                                            <textarea name="deskripsi_addon" class="form-control" rows="5" placeholder="Describe this add-on activity..."></textarea>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label">Price (Rp)</label>
                                            <input type="number" name="harga_addon" class="form-control" placeholder="0" min="0">
                                            <small class="text-muted">Leave 0 if included / free</small>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label required">Status</label>
                                            <select name="status_addon" class="form-select">
                                                <option value="A">Active</option>
                                                <option value="N">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-5">
                                            <label class="form-label">Featured Image</label>
                                            <input type="file" name="gambar_addon" id="inputGambar" class="form-control" accept=".jpg,.jpeg,.png">
                                            <small class="text-muted">JPG / PNG, max 2MB</small>
                                        </div>
                                        <div id="previewWrapper" class="mt-3 d-none">
                                            <img id="imgPreview" src="" class="img-fluid rounded" style="max-height:200px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" id="btnSave" class="btn btn-marron-submit">
                                        <i class="fa fa-save"></i> Save Add-On
                                    </button>
                                </div>
                            </form>
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
    $('#inputGambar').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                $('#imgPreview').attr('src', e.target.result);
                $('#previewWrapper').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#btnSave').on('click', function () {
        var formData = new FormData($('#actForm')[0]);
        $.ajax({
            url: "{{ route('addAddonEventAction') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            },
            success: function (res) {
                Swal.fire('Success', res.message, 'success').then(() => {
                    window.location.href = "{{ route('addonEvent', $detail->kode_event) }}";
                });
            },
            error: function (xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>

@include('admin-panel.layouts.footer')
