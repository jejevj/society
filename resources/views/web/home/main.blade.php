@include('layouts.header-front')

<div class="banner-hero" style="margin-top: 80px;">
    <div class="h-100 my-4">
        <div class="row align-items-center">
            <div class="col-md-12 banner-hero-slider">
                <div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($event as $key => $e)
                            <button type="button"
                                    data-bs-target="#heroSlider"
                                    data-bs-slide-to="{{ $key }}"
                                    class="{{ $key == 0 ? 'active' : '' }}">
                            </button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($event as $key => $e)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="position-relative overflow-hidden"
                                style="
                                    height:820px;
                                    background-image:url('{{ asset('storage/' . $e->background_event) }}');
                                    background-size:cover;
                                    background-position:center;
                                    background-repeat:no-repeat;">
                                <div class="position-absolute top-50 start-0 translate-middle-y w-100 px-20">
                                    <div class="row align-items-center">
                                        <div class="col-lg-7 text-white">
                                            <h2 class="fw-bold text-white mb-5 fs-slide-home-sub">
                                                {{ $e->sub_judul_event }}
                                            </h2>
                                            <h1 class="fw-bold text-white mb-5 fs-slide-home">
                                                {{ $e->judul_event }}
                                            </h1>
                                            <div class="fs-slide-home-detail fw-bold mb-2">
                                                <img src="{{ asset('images/location.png') }}" alt="{{ $e->lokasi_event }}" height="60" width="60">
                                                {{ $e->lokasi_event }}
                                            </div>
                                            <div class="fs-slide-home-detail fw-bold mb-2">
                                                <img src="{{ asset('images/calendar.png') }}" alt="{{ $e->lokasi_event }}" height="50" width="60">
                                                {{ date('d M Y', strtotime($e->tanggal_awal_event)) }}
                                                -
                                                {{ date('d M Y', strtotime($e->tanggal_akhir_event)) }}
                                            </div>
                                            <div class="fs-slide-home-detail-kolaborasi mb-2 d-flex align-items-start gap-3">
                                                <img src="{{ asset('images/hand.png') }}"
                                                    alt="{{ $e->lokasi_event }}"
                                                    height="60"
                                                    width="65">
                                                <div class="d-flex flex-column">
                                                    @foreach($e->kolaborasi as $kolaborasi)
                                                        <span>{{ $kolaborasi->nama_kolaborasi }}</span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="fs-5 text-gray-200 mb-8">
                                                {!! Str::limit(strip_tags($e->keterangan_event), 250) !!}
                                            </div>

                                            <a href="#" class="btn btn-warning btn-lg px-8">
                                                Register Now
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="position-absolute bottom-0 start-0 w-100 px-20 pb-10">
                                    <div class="row g-4">
                                        @foreach($e->paket as $paket)
                                        <div class="col-lg-2 col-md-6">
                                            <div class="card bg-dark bg-opacity-75 border border-warning border-opacity-25 h-100">
                                                <div class="card-body text-white py-4">
                                                    <div class="row align-items-center h-100">
                                                        <div class="col-3 text-center">
                                                            <img src="{{ url('storage/' . $paket->icon_paket) }}"
                                                                alt="{{ $paket->judul_paket }}"
                                                                width="70">
                                                        </div>
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h3 class="text-white mb-0 fs-slide-home-package text-center">
                                                                {{ $paket->judul_paket }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer-front')
