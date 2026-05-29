<div class="mt-4">
    <div class="p-3">
        <div class="row g-3">
            <div class="col-md-12">
                <h5 class="fw-bold mb-4"><i class="fa fa-list me-2 text-dark"></i>Data Terkait</h5>
            </div>
            @forelse($dt_terkait as $t)
            @php
                if($t->sifat_master == 'DT'){
                    $img = 'dataset-img.png';
                }else{
                    $img = 'infografis-img.png';
                }
            @endphp
            <div class="col-md-2">
                <a href="{{ route('detail-data', ['url_data' => $t->kode_data_master]) }}"
                   class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 related-card">
                        <div class="card-body text-center p-3">
                            <!-- ICON -->
                            <div class="mb-2">
                                <img src="{{ asset('assets/image/' . $img) }}" 
                                    class="img-fluid rounded" 
                                    width="55" 
                                    loading="lazy">
                                
                            </div>
                            <h6 class="fw-semibold text-dark small mb-1">
                                {{ \Illuminate\Support\Str::limit($t->judul_master, 45) }}
                            </h6>
                            <small class="text-muted">
                                Lihat Detail
                            </small>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-4">
                Tidak ada data terkait
            </div>
            @endforelse

        </div>
    </div>

</div>