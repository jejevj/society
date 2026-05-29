<form id="actForm" class="mb-4" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="key" value="{{ $id_data }}">
							<input type="hidden" name="key_kode" value="{{ $detail->kode_data_master }}">
							<div class="row">
								<div class="col-md-12 alert alert-warning"><h4 class="text-center">Metadata Dataset</h4></div>
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Judul:
										<span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalJudul">
											<i class="bi bi-question-circle"></i>
										</span>
									</label>
									<input type="text" class="form-control py-4" placeholder="Masukkan judul"
										name="judul" value="<?= $detail->judul_master ?>">
								</div>

								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Topik:
										<span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalTopik">
											<i class="bi bi-question-circle"></i>
										</span>
									</label>
									<select name="topik[]" id="" class="form-control py-4" data-control="select2"
										multiple>
										<option class="text-muted">- Pilih Topik -</option>
										<?php 
															
										$selected_topik = array_map(function ($v) {
											return $v->kode_tag;
										}, $tag);

										foreach ($topik as $t) {
											$selected = in_array($t->id_topik, $selected_topik) ? 'selected' : '';
										?>
										<option value="<?= $t->id_topik ?>" <?= $selected ?>><?= $t->nama_topik ?>
										</option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Kategori:
										<span class="text-primary" data-bs-toggle="modal" data-bs-target="#modalTopik">
											<i class="bi bi-question-circle"></i>
										</span>
									</label>
									<select name="kategori" id="" class="form-control py-4" data-control="select2">
										<option class="text-muted">- Pilih Kategori -</option>
										<?php foreach ($kategori as $t) {?>
										<option <?php if ($detail->kategori_master == $t->kode_status) { echo 'selected';} ?> value="<?= $t->kode_status ?>">
											<?= $t->keterangan_status ?>
										</option>
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
										<option class="text-muted">- Pilih Organisasi/Satker -</option>
										<?php foreach ($organisasi as $o) {?>
										<option <?php if ($detail->organisasi_master == $o->id_organisasi) { echo 'selected'; } ?> value="<?= $o->id_organisasi ?>">
											<?= $o->nama_organisasi ?>
										</option>
										<?php }?>
									</select>
								</div>

								<!-- Jenis Data Field -->
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Sifat Data:
										<span class="text-primary" data-bs-toggle="modal"
											data-bs-target="#modalJenisData">
											<i class="bi bi-question-circle"></i>
										</span>
									</label>
									<select name="sifat" id="" class="form-control py-4">
										<option class="text-muted">- Pilih Sifat Data -</option>
										<option <?php if ($detail->sifat_master == 'TERBUKA') { echo 'selected'; } ?> value="TERBUKA">TERBUKA</option>
										<option <?php if ($detail->sifat_master == 'TERBATAS') { echo 'selected'; } ?> value="TERBATAS">TERBATAS</option>
										<option <?php if ($detail->sifat_master == 'TERTUTUP') { echo 'selected'; } ?> value="TERTUTUP">TERTUTUP</option>
										<option <?php if ($detail->sifat_master == 'RAHASIA') { echo 'selected'; } ?> value="RAHASIA">RAHASIA</option>
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
										<option class="text-muted">- Pilih Frekuensi -</option>
										<?php foreach ($frekuensi as $o) {?>
										<option <?php if ($detail->frekuensi_master == $o->kode_status) {
												echo 'selected';
											} ?> value="<?= $o->kode_status ?>">
											<?= $o->keterangan_status ?>
										</option>
										<?php }?>
									</select>
								</div>

								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Data Prioritas:</label>
									<select name="prioritas" id="" class="form-control py-4">
									<option value="N" <?php if ($detail->prioritas_master == 'N') { echo 'selected'; } ?>>Tidak</option>
									<option value="Y" <?php if ($detail->prioritas_master == 'Y') { echo 'selected'; } ?>>Ya</option>
									</select>
								</div>

								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Metodologi Data:</label>
									<input type="text" class="form-control py-4" name="metodologi" value="<?= $detail->metodologi_master ?>">
								</div>
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Jenis Data:
									</label>
									<input type="text" class="form-control py-4" name="jenis" value="<?= $detail->jenis_master ?>">
								</div>
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Eselon 1:
									</label>
									<input type="text" class="form-control py-4" name="eselon1" value="<?= $detail->eselon1_master ?>">
								</div>
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Eselon 2:
									</label>
									<input type="text" class="form-control py-4" name="eselon2" value="<?= $detail->eselon2_master ?>">
								</div>
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Nama Penanggung Jawab:
									</label>
									<input type="text" class="form-control py-4" name="penanggung_jawab" value="<?= $detail->penanggung_jawab_master ?>">
								</div>
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Cakupan Wilayah Pengumpulan Data:
									</label>
									<input type="text" class="form-control py-4" name="cakupan" value="<?= $detail->cakupan_wilayah_master ?>">
								</div>
								
								
								<!-- Deskripsi Field -->
								<div class="col-md-4 mt-4">
									<label class="fs-4 opacity-75 mb-4">Deskripsi:
										<span class="text-primary" data-bs-toggle="modal"
											data-bs-target="#modalDeskripsi">
											<i class="bi bi-question-circle"></i>
										</span>
									</label>
									<textarea name="deskripsi" id="deskripsi" class="form-control py-4"
										rows="5"><?= $detail->deskripsi_master ?></textarea>
								</div>
								<div class="col-md-4 mb-4">
									<label class="fs-4 opacity-75 mb-4">Gambar:</label> <br>
									<img src="{{ url('storage/'.$detail->thumbnail_master) }}" class="img-fluid rounded" alt="Preview" style="max-height:150px;">
									<input type="file" name="gambar" class="form-control py-4 mt-2">
								</div>	
                                <div class="row">
									<div class="col-md-12 mt-4">
										<button type="submit" id="btn-save" class="btn btn-marron-submit w-100"><i class="fa fa-save text-white"></i>Simpan Metadata</button>
									</div>
								</div>
							</div>
                        </form>