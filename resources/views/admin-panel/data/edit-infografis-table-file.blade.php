                                        <div class="col-md-12 mt-4 text-end">
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahFile">
                                                <i class="fa fa-plus"></i> Tambah File Infografis
                                            </button>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <table class="table table-bordered table-striped" id="table-file">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Judul</th>
                                                        <th class="min-w-100px">Deskripsi</th>
                                                        <th>Tipe</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($file_list as $i => $files)
                                                    <tr>
                                                        <td>{{ $i+1 }}</td>
                                                        <td>{{ $files->judul_data }}</td>
                                                        <td>{{ $files->deskripsi_data }}</td>
                                                        <td>
                                                            {{ $files->is_embed == 'Y' ? 'Embed Link' : 'Upload Image' }}
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-dark btn-sm btn-detail"
                                                                data-file='@json($files)'>
                                                                <i class="fa fa-edit"></i>Ubah
                                                            </button>

                                                            @if($files->is_embed == 'N')
                                                                <a class="btn btn-secondary btn-sm"
                                                                href="{{ route('file-preview-show', ['sifat' => $detail->sifat_master, 'file' => ltrim($files->file_data, '/') ]) }}"
                                                                target="_blank">
                                                                <i class="fa-solid fa-image"></i> Lihat
                                                                </a>
                                                            @endif

                                                            <button type="button"
                                                                class="btn btn-danger btn-sm btn-delete-data"
                                                                data-id="{{ $files->id_data }}">
                                                                <i class="fa fa-trash"></i>Hapus
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal fade" id="modalDetailFile" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail File</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <input type="hidden" id="modal_id">

                                                        <div class="mb-3">
                                                            <label>Judul</label>
                                                            <input type="text" id="modal_judul" class="form-control">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Deskripsi</label>
                                                            <textarea id="modal_deskripsi" class="form-control"></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Tipe</label>
                                                            <select id="modal_tipe" class="form-control">
                                                                <option value="N">Upload File</option>
                                                                <option value="Y">Embed Link</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Upload File</label>
                                                            <input type="file" id="modal_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                                            <span class="fs-7 text-danger info-text">*Ekstensi: .jpg, .jpeg, .png, .pdf</span>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Embed Link</label>
                                                            <textarea id="modal_embed" class="form-control"></textarea>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-marron-submit" id="btn-save-detail">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal fade" id="modalTambahFile" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tambah File Infografis</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" name="key" value="{{$detail->kode_data_master}}">
                                                        <div class="mb-3">
                                                            <label>Judul File</label>
                                                            <input type="text" class="form-control" id="m_judul">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Tipe</label>
                                                            <select class="form-control" id="m_tipe">
                                                                <option value="N">Upload File</option>
                                                                <option value="Y">Embed Link</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3" id="wrapper_file">
                                                            <label class="label-input">Unggah Gambar</label>
                                                            <input type="file" class="form-control" id="m_file" accept=".jpg,.jpeg,.png,.pdf">
                                                            <span class="fs-7 text-danger">*Ekstensi: .jpg, .jpeg, .png, .pdf</span>
                                                        </div>

                                                        <div class="mb-3 d-none" id="wrapper_embed">
                                                            <label>Embed Link</label>
                                                            <textarea class="form-control" id="m_embed"></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Deskripsi</label>
                                                            <textarea class="form-control" id="m_deskripsi"></textarea>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-marron-submit" id="btn-add-file">Tambah</button>
                                                    </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>