                                                <?php if($cek_permit['c']){?>

                                                <div class="card mb-5">
                                                    <div class="card-header cursor-pointer d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#formTambahSlider">
                                                        <h3 class="card-title m-0">
                                                            <i class="fa fa-plus-circle me-2 text-primary"></i>
                                                            Tambah Slider Gambar
                                                        </h3>
                                                        <i class="fa fa-chevron-down"></i>
                                                    </div>
                                                    <div id="formTambahSlider" class="collapse">
                                                        <div class="card-body">
                                                            <form id="actForm2" class="mb-4" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-6 mt-4 mb-2">
                                                                        <label class="fs-4 opacity-75 mb-2">Gambar:</label><br>
                                                                        <input type="file"
                                                                            name="gambar"
                                                                            class="form-control py-4"
                                                                            placeholder="Masukkan gambar baru">
                                                                    </div>

                                                                    <div class="col-md-6 mt-4 mb-2">
                                                                        <label class="fs-4 opacity-75 mb-2">Judul</label><br>
                                                                        <input type="text"
                                                                            name="judul"
                                                                            class="form-control py-4"
                                                                            placeholder="Masukkan judul carousel">
                                                                    </div>

                                                                    <div class="col-md-6 mt-4 mb-2">
                                                                        <label class="fs-4 opacity-75 mb-2">Urutan</label><br>
                                                                        <input type="number"
                                                                            name="urutan"
                                                                            class="form-control py-4"
                                                                            placeholder="Masukkan urutan carousel">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 mt-4">
                                                                        <button type="submit"
                                                                            id="btn-save-new"
                                                                            class="btn btn-marron-submit w-100">
                                                                            <i class="fa fa-save text-white"></i>
                                                                            Tambah Slider Gambar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="card mb-5">
                                                    <div class="card-header cursor-pointer d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#formTambahSlider2">
                                                        <h3 class="card-title m-0">
                                                            <i class="fa fa-plus-circle me-2 text-primary"></i>
                                                            Tambah Slider Text
                                                        </h3>
                                                        <i class="fa fa-chevron-down"></i>
                                                    </div>
                                                    <div id="formTambahSlider2" class="collapse">
                                                        <div class="card-body">
                                                            <form id="actForm3" class="mb-4" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row">
                                                                   

                                                                    <div class="col-md-6 mt-4 mb-2">
                                                                        <label class="fs-4 opacity-75 mb-2">Judul</label><br>
                                                                        <input type="text"
                                                                            name="judul"
                                                                            class="form-control py-4"
                                                                            placeholder="Masukkan judul carousel">
                                                                    </div>

                                                                    <div class="col-md-6 mt-4 mb-2">
                                                                        <label class="fs-4 opacity-75 mb-2">Urutan</label><br>
                                                                        <input type="number"
                                                                            name="urutan"
                                                                            class="form-control py-4"
                                                                            placeholder="Masukkan urutan carousel">
                                                                    </div>
                                                                    <div class="col-md-12 mt-4 mb-2">
                                                                        <label class="fs-4 opacity-75 mb-2">Deskripsi</label><br>
                                                                        <textarea name="deskripsi" class="form-control py-4"></textarea>
                                                                    
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 mt-4">
                                                                        <button type="submit"
                                                                            id="btn-save-new2"
                                                                            class="btn btn-marron-submit w-100">
                                                                            <i class="fa fa-save text-white"></i>
                                                                            Tambah Slider Text
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-flush mt-2">
                                                            <div class="card-body">
                                                                <h3>Slider Gambar</h3>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <input type="text" id="search-nama-slider" class="form-control ps-12" placeholder="Masukkan judul slider">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-6 mt-1">
                                                                        <button id="searchSlider" class="btn btn-marron-submit btn-sm w-100 mt-6"><i class="fa fa-search text-white"></i>Cari</button>
                                                                        
                                                                    </div>
                                                                    <div class="col-md-6 mt-1">
                                                                        <button id="resetSearchSlider" class="btn btn-warning btn-sm w-100 mt-6"><i class="fa fa-rotate"></i>Reset</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body pt-0">
                                                                <table id="mainTable" class="display table align-middle table-row-dashed fs-6 gy-5">
                                                                    <thead>
                                                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                                            <th class="">No</th>
                                                                            <th class="min-w-100px">Judul</th>
                                                                            <th class="min-w-100px">Image</th>
                                                                            <th class="min-w-10px">Urutan</th>
                                                                            <th class="min-w-70px">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-flush mt-2">
                                                            <div class="card-body">
                                                                <h3>Slider Text</h3>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <input type="text" id="search-nama-slider" class="form-control ps-12" placeholder="Masukkan judul slider">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-6 mt-1">
                                                                        <button id="searchSlider" class="btn btn-marron-submit btn-sm w-100 mt-6"><i class="fa fa-search text-white"></i>Cari</button>
                                                                        
                                                                    </div>
                                                                    <div class="col-md-6 mt-1">
                                                                        <button id="resetSearchSlider" class="btn btn-warning btn-sm w-100 mt-6"><i class="fa fa-rotate"></i>Reset</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body pt-0">
                                                                <table id="mainTable2" class="display table align-middle table-row-dashed fs-6 gy-5">
                                                                    <thead>
                                                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                                            <th class="">No</th>
                                                                            <th class="min-w-100px">Judul</th>
                                                                            <th class="min-w-100px">Deskripsi</th>
                                                                            <th class="min-w-10px">Urutan</th>
                                                                            <th class="min-w-70px">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                