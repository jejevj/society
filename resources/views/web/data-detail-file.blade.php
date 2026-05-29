<div style="max-height: 600px; overflow-y: auto;">
    <div class="row g-3">

        <div class="col-md-12">
            <h5 class="fw-bold mb-4">
                <i class="fa-solid fa-folder-tree text-dark"></i> File Data
            </h5>
        </div>

        @foreach($dt_file as $df)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm file-item">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8 d-flex flex-column align-items-center text-center h-100">

                            <!-- PALING ATAS -->
                            <img src="{{ asset($df->path_image_file ?? 'assets/logo-v2/chart.png') }}"
                                width="70"
                                class="mb-2"
                                loading="lazy">

                            <!-- TENGAH -->
                            <div class="text-muted small d-flex gap-3 justify-content-center mb-2">
                                {{ strtoupper($df->tipe_file_data) }} 
                                {{ $df->ukuran_data ?? '0' }} KB
                                &emsp;
                                <span><i class="fa fa-eye"></i> {{ $df->jumlah_lihat }}</span>
                                <span><i class="fa fa-download"></i> {{ $df->jumlah_download }}</span>
                            </div>

                            <!-- BAGIAN BAWAH -->
                            <div class="mt-auto w-100">

                                <h6 class="fw-semibold text-dark mb-3">
                                    {{ $df->judul_data }}
                                </h6>

                                <div class="text-muted small fs-9 text-justify mb-0"> Keterangan:
                                    {{ \Illuminate\Support\Str::limit($df->deskripsi_data, 80) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex flex-column gap-2 align-items-end">
                                    @if($dt->tipe_master == 'DT')
                                        @php
                                            $isProcessing = empty($df->json_data);
                                        @endphp
                                        <div 
                                            @if($isProcessing) data-bs-toggle="tooltip" data-bs-placement="top" title="Data sedang diproses" @endif >
                                            <button class="btn btn-sm btn-light border preview-csv-btn w-100 fs-9 mb-2"
                                                data-id="{{ $df->id_data }}"
                                                data-kode="{{ $df->kode_data }}"
                                                {{ $isProcessing ? 'disabled' : '' }}>
                                                <i class="fa fa-eye"></i> Preview
                                            </button>

                                            <button type="button"
                                                class="btn btn-sm btn-light border w-100 fs-9 btn-diagram mb-2"
                                                data-url="{{ route('data-json', ['url_data' => $df->kode_data.'~'.$df->encrypted_id]) }}"
                                                {{ $isProcessing ? 'disabled' : '' }}>

                                                <i class="fa fa-chart-bar"></i> Diagram
                                            </button>

                                            <a href="{{ $isProcessing ? 'javascript:void(0)' : route('data-json', ['url_data' => $df->kode_data.'~'.$df->encrypted_id]) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-light border w-100 fs-9 {{ $isProcessing ? 'disabled' : '' }}">
                                                <i class="fa fa-code"></i> JSON
                                            </a>
                                        </div>

                                    @else
                                        @if($df->is_embed == 'Y')
                                            <button class="btn btn-sm btn-light border preview-embed-btn w-100 fs-9"
                                                data-embed='{{ $df->embed_data }}'>
                                                <i class="fa fa-eye"></i> Preview
                                            </button>
                                          
                                        @else
                                            <button class="btn btn-sm btn-light border preview-btn w-100 fs-9"
                                                data-url="{{ route('show-file', [
                                                    'sifat' => $dt->sifat_master,
                                                    'file'  => ltrim($df->file_data, '/')
                                                ]) }}"

                                                @if($df->tipe_file_data == 'pdf')
                                                data-type="pdf"
                                                @else
                                                data-type="image"
                                                @endif

                                                >
                                                <i class="fa fa-eye"></i> Preview
                                            </button>
                                        @endif
                                        

                                    @endif
                                    <a href="{{ route('detail-dcat', ['url_data' => $df->kode_data.'~'.$df->encrypted_id]) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-light border w-100 fs-9">
                                            <i class="fa fa-link"></i> DCAT
                                    </a>

                                    <a href="{{ route('unduh-terbuka', ['kode_data' => $df->kode_data.'~'.$df->encrypted_id]) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-light border w-100 fs-9">
                                            <i class="fa fa-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endforeach

    </div>
</div>