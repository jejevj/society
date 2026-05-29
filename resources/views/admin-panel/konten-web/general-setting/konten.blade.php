                                                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                                                    <form id="actForm" class="mb-4" enctype="multipart/form-data">
                                                    @csrf
                                                        <div class="row">
                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <label class="fs-4 opacity-75 mb-4">Logo:</label><br>
                                                                <img src="{{ url('storage/'.$detail->logo) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                <input type="file" name="logo" class="form-control py-4" placeholder="Masukkan logo">
                                                            </div>

                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <label class="fs-4 opacity-75 mb-4">Image Dashboard:</label><br>
                                                                <img src="{{ url('storage/'.$detail->gambar_dashboard) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                <input type="file" name="gambar_dashboard" class="form-control py-4" placeholder="Masukkan logo">
                                                            </div>

                                                        
                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <div class="card">
                                                                    <div class="card-header ">
                                                                        <h3 class="fs-3 mt-4 ">Pencarian Topik</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Header:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar_topik) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" name="gambar_topik" class="form-control py-4" placeholder="Masukkan gambar">
                                                                            </div>
                                                                            <div class="col-md-12 mt-4">
                                                                                <label class="fs-4 opacity-75 mb-4">Deskripsi Header:</label><br>
                                                                                <textarea class="form-control py-4" name="deskripsi_topik" rows="3"> {{ $detail->deskripsi_topik }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h3 class="fs-3 mt-4">Organisasi/Satker</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Header:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar_organisasi) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" name="gambar_organisasi" class="form-control py-4" placeholder="Masukkan gambar">
                                                                            </div>
                                                                            <div class="col-md-12 mt-4">
                                                                                <label class="fs-4 opacity-75 mb-4">Deskripsi Header:</label><br>
                                                                                <textarea class="form-control py-4" name="deskripsi_organisasi" rows="3">{{$detail->deskripsi_organisasi}}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h3 class="fs-3 mt-4">Monitoring Permohonan</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Header:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar_permohonan) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" name="gambar_permohonan" class="form-control py-4" placeholder="Masukkan gambar">
                                                                            </div>
                                                                            <div class="col-md-12 mt-4">
                                                                                <label class="fs-4 opacity-75 mb-4">Deskripsi Header:</label><br>
                                                                                <textarea class="form-control py-4 mb-4" name="deskripsi_permohonan" rows="3">{{$detail->deskripsi_permohonan}}</textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Body:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar2_permohonan) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" class="form-control py-4" name="gambar2_permohonan" placeholder="Masukkan gambar">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <div class="card">
                                                                    <div class="card-header ">
                                                                        <h3 class="fs-3 mt-4 ">Hubungi Kami</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Header:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar_hubungi) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" class="form-control py-4" name="gambar_hubungi" placeholder="Masukkan gambar">
                                                                            </div>
                                                                            <div class="col-md-12 mt-4">
                                                                                <label class="fs-4 opacity-75 mb-4">Deskripsi Header:</label><br>
                                                                                <textarea class="form-control py-4 mb-4" name="deskripsi_hubungi" rows="3">{{$detail->deskripsi_hubungi}}</textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Body:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar2_hubungi) }}" class="" alt="Preview" style="max-height:150px;"><br><br>                                                                        
                                                                                <input type="file" class="form-control py-4" name="gambar2_hubungi" placeholder="Masukkan gambar">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <div class="card">
                                                                    <div class="card-header ">
                                                                        <h3 class="fs-3 mt-4 ">Tentang Kami</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Header:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar_tentang) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" class="form-control py-4" name="gambar_tentang" placeholder="Masukkan gambar">
                                                                            </div>
                                                                            <div class="col-md-12 mt-4">
                                                                                <label class="fs-4 opacity-75 mb-4">Deskripsi Header:</label><br>
                                                                                <textarea class="form-control py-4 mb-4" rows="3" name="deskripsi_tentang">{{ $detail->deskripsi_tentang}} </textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Body:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar2_tentang) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" name="gambar2_tentang" class="form-control py-4" placeholder="Masukkan gambar">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="col-md-6 mt-4 mb-4">
                                                                <div class="card">
                                                                    <div class="card-header ">
                                                                        <h3 class="fs-3 mt-4 ">Login</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Header:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar_login) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input name="gambar_login" type="file" class="form-control py-4" placeholder="Masukkan gambar">
                                                                            </div>
                                                                            <div class="col-md-12 mt-4">
                                                                                <label class="fs-4 opacity-75 mb-4">Deskripsi Header:</label><br>
                                                                                <textarea class="form-control py-4 mb-4" name="deskripsi_login" rows="3">{{ $detail->deskripsi_login }}</textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label class="fs-4 opacity-75 mb-4">Image Body:</label><br>
                                                                                <img src="{{ url('storage/'.$detail->gambar2_login) }}" class="" alt="Preview" style="max-height:150px;"><br><br>
                                                                                <input type="file" class="form-control py-4" name="gambar2_login" placeholder="Masukkan gambar">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-4 mb-4">
                                                                <div class="card">
                                                                    <div class="card-header ">
                                                                        <h3 class="fs-3 mt-4 ">Link Sosial Media</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label class="fs-4 opacity-75 mb-4">URL Facebook:</label><br>
                                                                                <input type="text" class="form-control py-4" name="url_facebook" value="{{ $detail->url_facebook}}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="fs-4 opacity-75 mb-4">URL Twitter/X:</label><br>
                                                                                <input type="text" class="form-control py-4" name="url_twitter" value="{{ $detail->url_twitter}}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="fs-4 opacity-75 mb-4">URL Instagram:</label><br>
                                                                                <input type="text" class="form-control py-4" name="url_instagram" value="{{ $detail->url_instagram}}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="fs-4 opacity-75 mb-4">URL Youtube:</label><br>
                                                                                <input type="text" class="form-control py-4" name="url_youtube" value="{{ $detail->url_youtube}}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="fs-4 opacity-75 mb-4">URL LinkedIn:</label><br>
                                                                                <input type="text" class="form-control py-4" name="url_linkedin" value="{{ $detail->url_linkedin}}">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 mt-4">
                                                                <button type="submit" id="btn-save" class="btn btn-marron-submit w-100"><i class="fa fa-save text-white"></i>Submit</button>
                                                            </div>
                                                        </div>
                                                    </form>
										
                                                </div>