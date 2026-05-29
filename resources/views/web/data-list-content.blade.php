<div class="row g-4">
    @foreach($data as $dt)
     
    <div class="col-12 col-sm-6 col-md-4">
        <a href="{{ route('detail-data', ['url_data' => $dt->kode_data_master]) }}"  class="text-decoration-none d-block h-100" title="{{ $dt->judul_master }}">
            <div class="card h-100 border-0 shadow-sm card-hover">
                @if($dt->prioritas_master == 'Y')
                <div class="position-absolute top-0 start-0 m-2 z-3">
                    <i class="fa-solid fa-bookmark text-gold fs-2hx"></i>                           
                </div>
                @endif
                <div class="card-body d-flex flex-column">
                    @php
                        $img = 'satu-data-pertahanan-data.jpeg';
                    @endphp
                    <div class="text-center mb-3">
                        @if(!empty($dt->thumbnail_master))
                            <img src="{{ asset('storage/'.$dt->thumbnail_master) }}" class="me-2 img-fluid rounded w-220" loading="lazy">
                        @else
                            <img src="{{ asset('assets/image/' . $img) }}" class="img-fluid rounded w-220" loading="lazy">

                        @endif
                        
                    </div>
                    <div class="d-flex flex-wrap gap-2 my-2">
                        @if($dt->sifat_master == 'TERBUKA')
                            <span class="badge badge-success">
                                {{ $dt->sifat_master }}
                            </span>
                        @else
                            <span class="badge badge-danger">
                                {{ $dt->sifat_master }}
                            </span>
                        @endif
                        @if(!empty($dt->keterangan_status_kategori))
                            <span class="badge bg-marron">
                                {{ $dt->keterangan_status_kategori }}
                            </span>
                        @endif
                        <span class="badge bg-marron">
                            {{ $dt->keterangan_status }}
                        </span>
                    </div>

                    <h6 class="fw-semibold mb-2">
                        {{ \Illuminate\Support\Str::limit($dt->judul_master, 80) }}
                    </h6>
                    <div class="flex-grow-1"></div>
                    <div class="d-flex justify-content-between align-items-center small text-muted pt-3 border-top">
                        <span>
                            <i class="fa fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($dt->created_at)->translatedFormat('d F Y') }}
                        </span>

                        <div class="d-flex gap-3">
                            <span>
                                <i class="fa fa-download"></i>
                                {{ $dt->jumlah_download }}
                            </span>

                            <span>
                                <i class="fa fa-eye"></i>
                                {{ $dt->jumlah_lihat }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="mt-4 d-flex justify-content-end">
    {!! $data->links('pagination::bootstrap-5') !!}
</div>