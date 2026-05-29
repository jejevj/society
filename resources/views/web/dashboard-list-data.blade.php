<div class="row g-4">
@foreach($data as $d)
    <div class="col-12 col-sm-6 col-md-3">
        <a href="{{ route('detail-data', ['url_data' => $d->kode_data_master]) }}" 
        class="text-dark text-decoration-none h-100 d-block"
        title="{{ $d->judul_master }}">
            <div class="card h-100 hover-shadow-ds">
                @if($d->prioritas_master == 'Y')
                <div class="position-absolute top-0 start-0 m-2 z-3">
                    <i class="fa-solid fa-bookmark text-gold fs-2hx"></i>                           
                </div>
                @endif
                <div class="card-body d-flex flex-column h-100">
                    <div class="text-center mb-3 position-relative">
                        @if(!empty($d->thumbnail_master))
                            <img src="{{ asset('storage/'.$d->thumbnail_master) }}"
                                class="me-2 img-fluid rounded w-220"
                                loading="lazy" alt="{{ $d->judul_master ?? 'Thumbnail' }}">
                        @else
                            <img src="{{ asset('assets/image/' . $img) }}"
                                class="img-fluid rounded w-220"
                                loading="lazy" alt="{{ $d->judul_master ?? 'Thumbnail' }}">
                        @endif

                    </div>
                    <div class="d-flex flex-wrap gap-2 my-2">
                        @if($d->sifat_master == 'TERBUKA')
                            <span class="badge badge-success">
                                {{ $d->sifat_master }}
                            </span>
                        @else
                            <span class="badge badge-danger">
                                {{ $d->sifat_master }}
                            </span>
                        @endif
                        
                        @if(!empty($d->kategori_data_desc))
                            <span class="badge bg-marron">
                                {{ $d->kategori_data_desc }}
                            </span>
                        @endif
                    </div>
                    <h6 class="fw-semibold">
                        {{ \Illuminate\Support\Str::limit($d->judul_master, 80, '...') }}
                    </h6>
                    <div class="flex-grow-1"></div>
                    <div class="d-flex justify-content-between align-items-center small text-muted pt-3 border-top">
                        <span>
                            <i class="fa fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($d->created_at)->translatedFormat('d F Y') }}
                        </span>

                        <div class="d-flex gap-3">
                            <span>
                                <i class="fa fa-download"></i>
                                {{ $d->jumlah_download }}
                            </span>

                            <span>
                                <i class="fa fa-eye"></i>
                                {{ $d->jumlah_lihat }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach
</div>