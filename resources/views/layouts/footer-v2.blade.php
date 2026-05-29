<footer class="footer-v2">
    <div class="footer-main">
        <div class="container">
            <div class="row g-4">

                <div class="col-12 col-md-4">
                    <div class="footer-brand">
                        <img src="{{ asset('ldt-asset/images/logo.png') }}" alt="Logo" class="footer-logo">
                        <span class="footer-brand-name">{{ optional($set)->nama_app ?? config('app.name') }}</span>
                    </div>
                    <p class="footer-desc">
                        {{ optional($set)->deskripsi_app ?? 'Platform layanan data dan informasi terintegrasi.' }}
                    </p>
                    <div class="footer-socials">
                        <a href="#" class="social-btn" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" title="Twitter/X"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="social-btn" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-btn" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <h6 class="footer-heading">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ url(env('APP_ROUTE').'/home') }}"><i class="fa-solid fa-chevron-right"></i> Beranda</a></li>
                        <li><a href="{{ route('list') }}"><i class="fa-solid fa-chevron-right"></i> Data</a></li>
                        <li><a href="{{ route('list-organisasi') }}"><i class="fa-solid fa-chevron-right"></i> Organisasi</a></li>
                        <li><a href="{{ route('list-topik') }}"><i class="fa-solid fa-chevron-right"></i> Topik</a></li>
                        <li><a href="{{ route('tentang-kami') }}"><i class="fa-solid fa-chevron-right"></i> Tentang Kami</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2">
                    <h6 class="footer-heading">Layanan</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('login') }}"><i class="fa-solid fa-chevron-right"></i> Login</a></li>
                        <li><a href="{{ route('register') }}"><i class="fa-solid fa-chevron-right"></i> Registrasi</a></li>
                        <li><a href="{{ route('monitoring-permohonan') }}"><i class="fa-solid fa-chevron-right"></i> Monitoring</a></li>
                        <li><a href="{{ route('hubungi-kami') }}"><i class="fa-solid fa-chevron-right"></i> Hubungi Kami</a></li>
                        <li><a href="{{ route('lupa-password') }}"><i class="fa-solid fa-chevron-right"></i> Lupa Password</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-4">
                    <h6 class="footer-heading">Kontak</h6>
                    <ul class="footer-contact">
                        @if(optional($set)->alamat_app)
                        <li><span class="contact-icon"><i class="fa-solid fa-location-dot"></i></span><span>{{ $set->alamat_app }}</span></li>
                        @endif
                        @if(optional($set)->telpon_app)
                        <li><span class="contact-icon"><i class="fa-solid fa-phone"></i></span><span>{{ $set->telpon_app }}</span></li>
                        @endif
                        @if(optional($set)->email_app)
                        <li><span class="contact-icon"><i class="fa-solid fa-envelope"></i></span><span>{{ $set->email_app }}</span></li>
                        @endif
                        @if(!optional($set)->alamat_app && !optional($set)->telpon_app && !optional($set)->email_app)
                        <li><span class="contact-icon"><i class="fa-solid fa-circle-info"></i></span><span>Informasi kontak belum diatur.</span></li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <span>&copy; {{ date('Y') }} {{ optional($set)->nama_app ?? config('app.name') }}. Hak cipta dilindungi.</span>
                <span class="footer-bottom-right">Dibuat dengan <i class="fa-solid fa-heart" style="color:#f87171;"></i> untuk pelayanan publik</span>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-v2 { background: #1a1a2e; color: #ccc; margin-top: auto; }
    .footer-main { padding: 56px 0 40px; }
    .footer-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
    .footer-logo { width: 38px; height: 38px; object-fit: contain; filter: brightness(0) invert(1); }
    .footer-brand-name { font-size: 1.05rem; font-weight: 800; color: #fff; }
    .footer-desc { font-size: 0.875rem; color: #aaa; line-height: 1.75; margin-bottom: 18px; }
    .footer-socials { display: flex; gap: 8px; flex-wrap: wrap; }
    .social-btn {
        width: 36px; height: 36px; border-radius: 8px;
        background: rgba(255,255,255,0.07); color: #ccc;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; text-decoration: none;
        transition: background 0.2s, color 0.2s; flex-shrink: 0;
    }
    .social-btn:hover { background: #E62020; color: #fff; }
    .footer-heading {
        font-size: 0.875rem; font-weight: 700; color: #fff;
        text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 16px; padding-bottom: 8px;
        border-bottom: 2px solid #E62020; display: inline-block;
    }
    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-links li { margin-bottom: 9px; }
    .footer-links li a {
        display: flex; align-items: center; gap: 7px;
        font-size: 0.86rem; color: #aaa; text-decoration: none;
        transition: color 0.2s, gap 0.2s;
    }
    .footer-links li a i { font-size: 0.65rem; color: #E62020; flex-shrink: 0; }
    .footer-links li a:hover { color: #fff; gap: 10px; }
    .footer-contact { list-style: none; padding: 0; margin: 0; }
    .footer-contact li {
        display: flex; align-items: flex-start; gap: 10px;
        font-size: 0.86rem; color: #aaa; margin-bottom: 12px; line-height: 1.6;
    }
    .contact-icon { color: #E62020; font-size: 0.95rem; flex-shrink: 0; margin-top: 2px; width: 16px; text-align: center; }
    .footer-bottom { background: rgba(0,0,0,0.25); border-top: 1px solid rgba(255,255,255,0.06); padding: 16px 0; }
    .footer-bottom-inner {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 8px; font-size: 0.8rem; color: #888;
    }
    .footer-bottom-right { display: flex; align-items: center; gap: 5px; }
    @media (max-width: 767.98px) {
        .footer-main { padding: 36px 0 28px; }
        .footer-bottom-inner { justify-content: center; text-align: center; }
        .footer-bottom-right { justify-content: center; }
    }
</style>

<script src="{{ asset('ldt-asset/assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('ldt-asset/assets/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

</body>
</html>
