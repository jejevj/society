@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 text-center p-10">
                    <div class="mb-6">
                        <i class="fa-solid fa-circle-check fa-5x" style="color:#059669;"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Pembayaran Berhasil!</h2>
                    <p class="text-muted mb-6">
                        Pendaftaran event Anda telah dikonfirmasi. <br>
                        Detail event dapat dilihat di halaman profil Anda.
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('riwayat-user') }}" class="btn bg-detail text-white px-6">
                            <i class="fa-solid fa-list me-2"></i>Lihat Riwayat Event
                        </a>
                        <a href="{{ route('list-event') }}" class="btn btn-light-primary px-6">
                            <i class="fa-solid fa-calendar-days me-2"></i>Jelajahi Event Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer-v2')
