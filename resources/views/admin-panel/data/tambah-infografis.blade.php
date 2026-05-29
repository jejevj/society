@include('admin-panel.layouts.header')
<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
  <div id="kt_app_toolbar" class="app-toolbar py-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
      <div class="d-flex flex-column flex-row-fluid">
        <div class="d-flex align-items-center pt-1">
          {!! $breadcrumb !!}
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
                <i class="fa fa-backward"></i> Kembali
              </a>
            </div>
          </div>
          <div class="card card-flush">
            <div class="card-body">
              <form id="actForm" class="mb-4" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-md-12 alert alert-warning"><h4 class="text-center">Meta Data Infografis</h4></div>
                  <!-- Judul Field -->
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Judul:
                      <span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalJudul">
                        <i class="bi bi-question-circle"></i>
                      </span>
                    </label>
                    <input type="text" class="form-control py-4" placeholder="Masukkan judul" name="judul">
                  </div>

                  <!-- Topik Field -->
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Topik:
                      <span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalTopik">
                        <i class="bi bi-question-circle"></i>
                      </span>
                    </label>
                    <select name="topik[]" id="" class="form-control py-4" data-control="select2" multiple>
                      <!-- <option class="text-muted">- Pilih Topik -</option> -->
                      <?php foreach ($topik as $t) {?>
                      <option value="<?= $t->id_topik;?>"><?= $t->nama_topik;?></option>
                      <?php }?>

                    </select>
                  </div>

                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Kategori:
                      <span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalTopik">
                        <i class="bi bi-question-circle"></i>
                      </span>
                    </label>
                    <select name="kategori" id="" class="form-control py-4" data-control="select2">
                      <!-- <option class="text-muted">- Pilih Kategori -</option> -->
                      <?php foreach ($kategori as $t) {?>
                      <option value="<?= $t->kode_status;?>"><?= $t->keterangan_status;?></option>
                      <?php }?>

                    </select>
                  </div>

                  <!-- Organisasi/Satker Field -->
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Organisasi/Satker:
                      <span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalOrg">
                        <i class="bi bi-question-circle"></i>
                      </span>
                    </label>
                    <select name="organisasi" data-control="select2" class="form-control py-4">
                      <!-- <option class="text-muted">- Pilih Organisasi/Satker -</option> -->
                      <?php foreach ($organisasi as $o) {?>
                      <option value="<?= $o->id_organisasi;?>"><?= $o->nama_organisasi;?></option>
                      <?php }?>
                    </select>
                  </div>

                  <!-- Jenis Data Field -->
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Sifat Data:
                      <span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalJenisData">
                        <i class="bi bi-question-circle"></i>
                      </span>
                    </label>
                    <select name="sifat" id="" class="form-control py-4">
                      <!-- <option class="text-muted">- Pilih Sifat Data -</option> -->
                      <option value="TERBUKA">TERBUKA</option>
                      <option value="TERBATAS">TERBATAS</option>
                      <option value="TERTUTUP">TERTUTUP</option>
                      <option value="RAHASIA">RAHASIA</option>
                    </select>
                  </div>

                  <!-- Organisasi/Satker Field -->
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Frekuensi Data:
                      <span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalOrg">
                        <i class="bi bi-question-circle"></i>
                      </span>
                    </label>
                    <select name="frekuensi" data-control="select2" class="form-control py-4">
                      <!-- <option class="text-muted">- Pilih Frekuensi -</option> -->
                      <?php foreach ($frekuensi as $o) {?>
                      <option value="<?= $o->kode_status;?>"><?= $o->keterangan_status;?></option>
                      <?php }?>
                    </select>
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Metodologi Data:
                    </label>
                    <input type="text" class="form-control py-4" name="metodologi">
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Jenis Data:
                    </label>
                    <input type="text" class="form-control py-4" name="jenis">
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Eselon 1:
                    </label>
                    <input type="text" class="form-control py-4" name="eselon1">
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Eselon 2:
                    </label>
                    <input type="text" class="form-control py-4" name="eselon2">
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Nama Penanggung Jawab:
                    </label>
                    <input type="text" class="form-control py-4" name="penanggung_jawab">
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Cakupan Wilayah Pengumpulan Data:
                    </label>
                    <input type="text" class="form-control py-4" name="cakupan">
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Data Prioritas:</label>
                    <select name="prioritas" id="" class="form-control py-4">
                      <option value="N">Tidak</option>
                      <option value="Y">Ya</option>
                    </select>
                  </div>
                  <div class="col-md-4 mt-4">
										<label class="fs-4 opacity-75 mb-4">Thumbnail:</label>
                    <input type="file" id="fileUpload" name="gambar" class="form-control py-4">
									</div>	
                  <!-- Deskripsi Field -->
                  <div class="col-md-4 mt-4">
                    <label class="fs-4 opacity-75 mb-4">Deskripsi:
                      <span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalDeskripsi">
                        <i class="bi bi-question-circle"></i>
                      </span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control py-4" rows="5"></textarea>
                  </div>

                  <div id="file-section">
                    <div class="col-md-12 alert alert-warning mt-4"><h4 class="text-center">File Infografis</h4></div>
                    <div id="form-wrapper" class="mt-4">
                      <div class="upload-form border p-3 mb-3 rounded position-relative">
                        <button type="button"
                          class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-form mb-1"
                          style="display:none;">
                          <i class="bi bi-x-circle"></i> Hapus
                        </button>

                        <div class="row">
                          <div class="col-md-4 mt-3">
                            <label>Judul File</label>
                            <input type="text" class="form-control" name="judul_file[]">
                          </div>

                          <div class="col-md-4 mt-3">
                            <label>Deskripsi File</label>
                            <textarea name="deskripsi_file[]" class="form-control"></textarea>
                          </div>
                          <div class="col-md-4 mt-3">
                            <label>Tipe</label>
                            <select class="form-control tipe-input" name="is_embed[]">
                              <option value="N">Upload File</option>
                              <option value="Y">Embed Link</option>
                            </select>
                          </div>
                          <div class="col-md-4 mt-3 input-wrapper">
                            <label class="label-input">Unggah Gambar</label>
                            <input type="file" class="form-control input-file" name="file[]" accept=".jpg,.jpeg,.png,.pdf">
                            <textarea class="form-control input-link d-none" name="embed_link[]" placeholder="Masukkan script embed"></textarea>
                            <span class="fs-7 text-danger info-text">*Ekstensi: .jpg, .jpeg, .png, .pdf</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mt-3">
                      <button type="button" id="add-form" class="btn btn-warning mt-3">
                        <i class="bi bi-plus-circle"></i> Tambah Form File
                      </button>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12 mt-4">
                      <button type="submit" id="btn-save" class="btn btn-marron-submit w-100"><i
                          class="fa fa-save text-white"></i>Submit</button>
                    </div>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('admin-panel.data.info-form')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const sifatSelect = document.querySelector("select[name='sifat']");
    const fileSection = document.getElementById("file-section");

    function toggleFileSection() {
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
    let parent = $(this).closest('.upload-form');
                    
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

  document.addEventListener("DOMContentLoaded", function () {
    const formWrapper = document.getElementById("form-wrapper");
    const addFormBtn = document.getElementById("add-form");

    addFormBtn.addEventListener("click", function () {
      let originalForm = formWrapper.querySelector(".upload-form");
      let newForm = originalForm.cloneNode(true);

      // Reset input value
      newForm.querySelectorAll("input, textarea, select").forEach(el => {
        if (el.type === "file" || el.type === "text" || el.tagName === "TEXTAREA") {
          el.value = "";
        }
      });
      newForm.querySelector(".remove-form").style.display = "block";
      formWrapper.appendChild(newForm);
      newForm.querySelector(".remove-form").addEventListener("click", function () {
        newForm.remove();
      });
    });
  });


  $('#btn-save').on('click', function (e) {
    e.preventDefault();

    if (typeof tinymce !== "undefined") {
      tinymce.triggerSave();
    }
    let formData = new FormData($('#actForm')[0]);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    $.ajax({
      url: "{{ route('addDataInfografisAction') }}",
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
        Swal.close(); 
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message
        }).then(() => {
          window.location.href = "{{ route('data') }}";
        });
      },
      error: function (xhr) {
        Swal.close(); 
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
</script>

@include('admin-panel.layouts.footer')