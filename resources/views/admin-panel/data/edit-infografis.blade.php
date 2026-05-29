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
						<h1
							class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">
							{{ $menu }}
							<span class="page-desc text-white opacity-50 fs-6 fw-bold pt-4"></span>
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
					<div class="mb-4">
						<div class="d-flex justify-content-end">
							<a class="btn btn-warning btn-sm" href="{{ route('data') }}">
								<i class="fa fa-arrow-left	"></i> Kembali
							</a>
						</div>
					</div>
					<div class="">
						@include('admin-panel.data.edit-metadata')
                        <div id="file-section">
                            <div class="col-md-12 alert alert-warning mt-4"><h4 class="text-center">File Infografis</h4></div>
                            <div class="col-md-12 mt-4">
                                <div id="form-wrapper" class="container">
                                    @include('admin-panel.data.edit-infografis-table-file')
                                    <hr>							
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    $('#m_tipe').on('change', function () {
        if ($(this).val() === 'Y') {
            $('#wrapper_file').addClass('d-none');
            $('#wrapper_embed').removeClass('d-none');
        } else {
            $('#wrapper_file').removeClass('d-none');
            $('#wrapper_embed').addClass('d-none');
        }
    });
    $(document).on('click', '.btn-detail', function () {
        let data = $(this).data('file');

        $('#modal_id').val(data.id_data);
        $('#modal_judul').val(data.judul_data);
        $('#modal_deskripsi').val(data.deskripsi_data);
        $('#modal_tipe').val(data.is_embed);
        $('#modal_embed').val(data.embed_data);

        toggleModalInput(data.is_embed);

        $('#modalDetailFile').modal('show');
    });
    function toggleModalInput(val) {
        if (val === 'Y') {
            $('#modal_file').closest('.mb-3').hide();
            $('#modal_embed').closest('.mb-3').show();
        } else {
            $('#modal_file').closest('.mb-3').show();
            $('#modal_embed').closest('.mb-3').hide();
        }
    }

    $('#modal_tipe').on('change', function () {
        toggleModalInput($(this).val());
    });


    document.addEventListener("DOMContentLoaded", function () {
        const sifatSelect = document.querySelector("select[name='sifat']");
        const fileSection = document.getElementById("file-section");

        function toggleFileSection() {
            if (!fileSection) return; // 

            if (sifatSelect.value === "TERBUKA") {
                fileSection.style.display = "block";
            } else {
                fileSection.style.display = "none";
            }
        }
        toggleFileSection();
        sifatSelect.addEventListener("change", toggleFileSection);
    });

    $(document).on('change', '.tipe-input', function () {
        let parent = $(this).closest('.row'); 

        let fileInput = parent.find('.input-file');
        let linkInput = parent.find('.input-link');
        let label = parent.find('.label-input');
        let info = parent.find('.info-text');

        if ($(this).val() === 'Y') {
            fileInput.addClass('d-none');
            linkInput.removeClass('d-none');
            label.text('Link Embed');
            info.text('*Masukkan URL embed');
        } else {
            fileInput.removeClass('d-none');
            linkInput.addClass('d-none');
            label.text('Unggah Gambar');
            info.text('*Ekstensi: .jpg, .jpeg, .png');
        }
    });

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$('#btn-save').on('click', function (e) {
		e.preventDefault();
		if (typeof tinymce !== "undefined") {
			tinymce.triggerSave();
		}

		let formData = new FormData($('#actForm')[0]);
		formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

		$.ajax({
			url: "{{ route('updateMetadataAction') }}",
			type: 'POST',
			data: formData,
            beforeSend: function () {
                Swal.fire({
                title: "Sedang diproses...",
                text: "Mohon tunggu sebentar",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
                });
            },
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: response.message
				}).then(() => {
					location.reload();
				});
			},
			error: function (xhr) {
				let message = 'Terjadi kesalahan.';
				if (xhr.responseJSON && xhr.responseJSON.message) {
					message = xhr.responseJSON.message;
				}
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: message
				});
			}
		});
	});

	$(document).on('click', '.btn-delete-data', function() {
    	var keypost = $(this).data('id');
        Swal.fire({
            title: 'Konfirmasi', text: 'Apakah Anda yakin menghapus data file ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                	url: "{{ route('deleteDataFileAction') }}", 
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",  
                        key: keypost
                    },
                    success: function(response) {
                        Swal.fire({
    	                title: 'Success',
                            text: 'Data Berhasil Dihapus',
                            icon: 'success'
                        }).then(function() {
        			        location.reload();  
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'Data Gagal Dihapus', 'error');
                    }
                });
            }
        });
    });
    $(document).ready(function () {
        $('#table-file').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: [0, 3] }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                },
                zeroRecords: "Data tidak ditemukan"
            }
        });
    });
    $('#btn-save-detail').on('click', function () {
        let formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); 

        formData.append('id', $('#modal_id').val());
        formData.append('judul', $('#modal_judul').val());
        formData.append('deskripsi', $('#modal_deskripsi').val());
        formData.append('is_embed', $('#modal_tipe').val());
        formData.append('embed', $('#modal_embed').val());

        let file = $('#modal_file')[0].files[0];
        if (file) {
            formData.append('file', file);
        }

        $.ajax({
            url: "{{ route('updateFileDetailInfografis') }}",
            type: "POST",
            data: formData,
            beforeSend: function () {
                Swal.fire({
                title: "Sedang diproses...",
                text: "Mohon tunggu sebentar",
                allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            contentType: false,
            processData: false,
            success: function (res) {
                if(res.success === true){
                    Swal.fire('Success', res.message, 'success').then(() => {
                        location.reload();
                    });
                }else{
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(){
                Swal.fire('Error', 'Gagal update', 'error');
            }
        });
    });

    $('#btn-add-file').on('click', function () {
        let formData = new FormData();

        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('key', $('input[name="key"]').val());
        formData.append('judul', $('#m_judul').val());
        formData.append('deskripsi', $('#m_deskripsi').val());
        formData.append('is_embed', $('#m_tipe').val());
        formData.append('embed', $('#m_embed').val());

        let file = $('#m_file')[0].files[0];
        if (file) {
            formData.append('file', file);
        }

        $.ajax({
            url: "{{ route('addFileInfografis') }}",
            type: "POST",
            data: formData,
            beforeSend: function () {
                Swal.fire({
                title: "Sedang diproses...",
                text: "Mohon tunggu sebentar",
                allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            contentType: false,
            processData: false,
            success: function (res) {
                if(res.success === true){
                    Swal.fire('Success', res.message, 'success').then(() => {
                        location.reload();
                    });
                }else{
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function (xhr) {
                let msg = 'Gagal tambah file';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>

@include('admin-panel.layouts.footer')