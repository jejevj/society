@php

                    $topik = DB::table('reff_topik')
                            ->leftJoin('t_data_tag', DB::raw('t_data_tag.kode_tag::bigint'), '=', 'reff_topik.id_topik')
                            ->where('t_data_tag.kode_data_tag', $dt->kode_data_master)
                            ->orderBy('urutan_topik','asc')
                            ->get();
                @endphp
                <div class="card-body p-5">
                    <div class="mb-4">
                        <h2 class="fw-bold mb-2">{{ $dt->judul_master }}</h2>

                        <!-- TOPIK & TAG -->
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-marron">
                                <i class="fa fa-layer-group me-1 text-white"></i> {{ $dt->keterangan_status }}
                            </span>

                            @foreach($topik as $t)
                                <span class="badge bg-marron">
                                    <i class="fa fa-tag me-1 text-white"></i> {{ $t->nama_topik }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="mb-2">
                                <h5 class="fw-bold mb-3 fs-6"> <i class="fa fa-align-left me-2 text-primary"></i> Deskripsi Data </h5>
                                <div id="descWrapper">
                                    <div id="descContent" class="text-desc text-justify fs-6 collapsed">
                                        {!! $dt->deskripsi_master !!}
                                    </div>
                                    <a href="javascript:void(0)" id="toggleDesc" class="text-primary small fw-semibold">
                                        Lihat Selengkapnya
                                    </a>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h5 class="fw-bold mb-3 fs-6"><i class="fa fa-flask me-2 text-warning"></i>Metodologi</h5>
                                <div class="text-desc fs-6">
                                    {{ $dt->metodologi_master ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="shadow-sm border-0">
                                <div class="p-4">
                                    <h5 class="fw-bold mb-4">
                                        <i class="fa fa-info-circle me-2 text-success"></i>
                                        Metadata
                                    </h5>
                                    <div id="metaWrapper">
                                        <div id="metaContent" class="meta-list collapsed-meta">
                                            <div class="meta-item">
                                                <span>Sifat Data</span>
                                                @if($dt->sifat_master == 'TERBUKA')
                                                    <span class="badge bg-marron text-white">Terbuka</span>
                                                @else
                                                    <span class="badge bg-marron text-white">Terbatas</span>
                                                @endif
                                            </div>

                                            <div class="meta-item">
                                                <span>Satuan Kerja</span>
                                                <span class="text-desc">{{ $dt->nama_organisasi }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Dipublikasi</span>
                                                <span class="text-desc">{{ \Carbon\Carbon::parse($dt->created_at)->translatedFormat('j F Y') }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Dimodifikasi</span>
                                                <span class="text-desc">
                                                    {{ $dt->updated_at ? \Carbon\Carbon::parse($dt->updated_at)->translatedFormat('j F Y') : '-' }}
                                                </span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Frekuensi</span>
                                                <span class="text-desc">{{ $dt->keterangan_status_frekuensi }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Jenis Data</span>
                                                <span class="text-desc">{{ $dt->jenis_master ?? '-' }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Eselon I</span>
                                                <span class="text-desc">{{ $dt->eselon1_master ?? '-' }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Eselon II</span>
                                                <span class="text-desc">{{ $dt->eselon2_master ?? '-' }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Penanggung Jawab</span>
                                                <span class="text-desc">{{ $dt->penanggung_jawab_master ?? '-' }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Cakupan Wilayah</span>
                                                <span class="text-desc">{{ $dt->cakupan_wilayah_master ?? '-' }}</span>
                                            </div>

                                            <div class="meta-item">
                                                <span>Sumber</span>
                                                <span class="text-desc">Tim {{ $dt->nama_organisasi }}</span>
                                            </div>

                                        </div>

                                        <div class="text-center mt-2">
                                            <a href="javascript:void(0)" id="toggleMeta" class="text-primary small fw-semibold">
                                                Lihat Selengkapnya
                                            </a>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>