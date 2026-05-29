                                            <div class="mb-4">
                                                <div class="row g-3">

                                                    <div class="col-md-12">
                                                        <h5 class="fw-bold mb-4">
                                                            <i class="fa-solid fa-folder-tree text-dark"></i> File Data
                                                        </h5>
                                                    </div>
                                                    <div class="col-3">
                                                <button type="button" class="btn btn-marron fs-9" data-bs-toggle="modal" data-bs-target="#modalUnduh">
                                                    <i class="fa-solid fa-code-pull-request text-white"></i> Permohonan Akses Data
                                                </button>
                                                <?php
                                                    $user = DB::table('app_user')->where('id_user',session('id_user'))->first();
                                                ?>
                                            </div>
                                            <div class="modal fade" id="modalUnduh" tabindex="-1" aria-labelledby="modalUnduhLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form  method="POST" id="actForm" enctype="multipart/form-data"> 
                                                            @csrf
                                                            <input type="hidden" name="kode" value="{{ $dt->kode_data_master }}">
                                                            <input type="hidden" name="id_data" value="{{ $dt->id_master_data }}">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalUnduhLabel">Formulir Permohonan Akses Data</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                                                        <input type="text" class="form-control" id="nama" name="nama" value="<?php if(!empty($user)){ echo $user->nama_user;}?>" >
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="email" class="form-label">Email </label>
                                                                        <input type="email" class="form-control" id="email" name="email" value="<?php if(!empty($user)){ echo $user->username_user;}?>">
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">No Identitas </label>
                                                                        <input type="text" class="form-control" id="" name="identitas" value="<?php if(!empty($user)){ echo $user->identitas_user;}?>">
                                                                    </div>
                                                                    
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">No Telepon </label>
                                                                        <input type="text" class="form-control" id="" name="telepon" value="<?php if(!empty($user)){ echo $user->telepon_user;}?>">
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">Pekerjaan </label>
                                                                        <input type="text" class="form-control" id="" name="pekerjaan" value="<?php if(!empty($user)){ echo $user->pekerjaan_user;}?>">
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">Alamat </label>
                                                                        <textarea name="alamat" class="form-control" id="" rows="3"><?php if(!empty($user)){ echo $user->alamat_user;}?></textarea>
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">File Identitas</label>
                                                                        <span class="text-muted fs-7">(Format: Image)</span>

                                                                        @if(!empty($user) && !empty($user->file_identitas_user))
                                                                            <input type="hidden" name="id_user" value="{{ $user->id_user }}">
                                                                            <img src="{{ url('storage/'.$user->file_identitas_user) }}" height="150px;" loading="lazy">
                                                                            <input type="hidden" class="form-control" name="file_identitas_old" value="{{ $user->file_identitas_user }}">
                                                                            <!-- <input type="file" class="form-control mt-2" name="file_identitas"> -->
                                                                        @else
                                                                            {{-- Kalau belum ada file sama sekali --}}
                                                                            <input type="hidden" name="id_user" value="">
                                                                            <input type="hidden" name="file_identitas_old" value="">
                                                                            <input type="file"  class="form-control" name="file_identitas" accept=".jpg,.jpeg,.png">
                                                                        @endif
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">Tujuan Permintaan Data </label>
                                                                        <input type="text" class="form-control" id="" name="tujuan">
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">Dokumen Pendamping </label>
                                                                        <span class="text-muted fs-7">(Format: PDF)</span>
                                                                        <input type="file" class="form-control" id="" name="dokumen" accept=".pdf,application/pdf">
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="" class="form-label">Metode Pengambilan Data </label>
                                                                        <select name="pengambilan_data" class="form-control">
                                                                            <option value="Kirim Email">Kirim Email</option>
                                                                            <option value="Pengambilan Offline">Pengambilan Offline</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-marron-monitoring text-white" id="btnKirim">Kirim Permohonan</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            </div>
                                                        </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                </div>
                                            </div>
                                            
                                            
                                            <script>
                                                $.ajaxSetup({
                                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                                                });

                                                $('#actForm').on('submit', function (e) {
                                                    e.preventDefault();

                                                    let formData = new FormData(this);

                                                    $('#actForm button[type="submit"]').prop('disabled', true);

                                                    $.ajax({
                                                        url: "{{ route('permohonanAction') }}",
                                                        type: 'POST',
                                                        data: formData,
                                                        contentType: false,
                                                        processData: false,

                                                        beforeSend: function () {
                                                            Swal.fire({
                                                                title: "Sedang diproses...",
                                                                html: `
                                                                    <div style="font-size:14px;">
                                                                        📄 Mengirim data permohonan...<br>
                                                                        🔍 Validasi data sedang dilakukan...<br><br>
                                                                        <strong>Mohon menunggu, jangan tutup halaman.</strong>
                                                                    </div>
                                                                `,
                                                                allowOutsideClick: false,
                                                                allowEscapeKey: false,
                                                                didOpen: () => {
                                                                    Swal.showLoading();
                                                                }
                                                            });
                                                        },

                                                        success: function (response) {
                                                            Swal.close(); 

                                                            if (response.success) {

                                                                Swal.fire({
                                                                    icon: 'success',
                                                                    title: 'Berhasil',
                                                                    html: `
                                                                        <p>${response.message}</p>

                                                                        <div style="margin-top:15px; padding:12px; border:1px dashed #d4af37; border-radius:8px; text-align:center;">
                                                                            <div style="font-size:12px; color:#888;">Kode Permohonan</div>

                                                                            <div id="kodeText" style="font-size:22px; font-weight:bold; letter-spacing:2px;">
                                                                                ${response.kode ?? '-'}
                                                                            </div>

                                                                            <button id="btnCopyKode"
                                                                                style="margin-top:10px; background:#d4af37; color:white; border:none; padding:6px 14px; border-radius:6px; cursor:pointer;">
                                                                                Copy Kode
                                                                            </button>
                                                                        </div>
                                                                    `,
                                                                    confirmButtonText: 'Tutup',
                                                                    didOpen: () => {

                                                                        const btn = document.getElementById('btnCopyKode');

                                                                        if (btn) {
                                                                            btn.addEventListener('click', function () {
                                                                                const text = document.getElementById('kodeText').innerText;

                                                                                navigator.clipboard.writeText(text).then(() => {
                                                                                const btn = document.getElementById('btnCopyKode');

                                                                                btn.innerText = '✔ Disalin!';
                                                                                btn.style.background = '#198754';

                                                                                setTimeout(() => {
                                                                                    btn.innerText = 'Copy Kode';
                                                                                    btn.style.background = '#d4af37';
                                                                                }, 1500);
                                                                            }).catch(() => {
                                                                                    alert("Silakan copy manual: " + text);
                                                                                });
                                                                            });
                                                                        }
                                                                    }
                                                                }).then(() => {
                                                                    location.reload();
                                                                });

                                                            } else {
                                                                Swal.fire({
                                                                    icon: 'error',
                                                                    title: 'Gagal',
                                                                    text: response.message
                                                                });
                                                            }
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
                                                        },

                                                        complete: function () {
                                                            // 🔓 aktifkan kembali tombol
                                                            $('#actForm button[type="submit"]').prop('disabled', false);
                                                        }
                                                    });
                                                });
                                            </script>