<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ optional($set)->nama_app ?? config('app.name') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/web.css') }}">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html {
            height: 100%;
            overflow-x: hidden;
            overscroll-behavior: none;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overscroll-behavior-y: none;
            background-color: #f8f9fa;
        }

        /* NAVBAR */
        .navbar-v2 {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1050;
            background: #fff;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            transition: box-shadow 0.3s;
        }

        .navbar-v2 .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            padding: 0 24px;
        }

        .navbar-v2 .navbar-brand-area {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-v2 .brand-logo {
            width: 40px; height: 40px;
            object-fit: contain;
        }

        .navbar-v2 .brand-name {
            font-size: 1.05rem;
            font-weight: 800;
            color: #E62020;
            line-height: 1.2;
        }

        .navbar-v2 .brand-sub {
            font-size: 0.7rem;
            color: #888;
            font-weight: 400;
        }

        .navbar-v2 .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0; padding: 0;
        }

        .navbar-v2 .nav-links li a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #444;
            text-decoration: none;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap;
        }

        .navbar-v2 .nav-links li a:hover,
        .navbar-v2 .nav-links li a.active {
            background: rgba(230,32,32,0.08);
            color: #E62020;
        }

        .navbar-v2 .nav-links li a.active { font-weight: 700; }

        .navbar-v2 .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-v2 .btn-nav-login {
            background: #E62020;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .navbar-v2 .btn-nav-login:hover { background: #c41a1a; color: #fff; }

        .navbar-v2 .btn-nav-register {
            background: transparent;
            color: #E62020;
            border: 1.5px solid #E62020;
            border-radius: 8px;
            padding: 7px 16px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .navbar-v2 .btn-nav-register:hover { background: #E62020; color: #fff; }

        /* User Dropdown */
        .navbar-v2 .user-dropdown { position: relative; }

        .navbar-v2 .user-avatar-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(230,32,32,0.06);
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 0.875rem;
            color: #333;
            font-weight: 500;
            transition: background 0.2s;
        }

        .navbar-v2 .user-avatar-btn:hover { background: rgba(230,32,32,0.12); }

        .navbar-v2 .avatar-circle {
            width: 32px; height: 32px;
            background: #E62020;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .navbar-v2 .dropdown-menu-custom {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            min-width: 200px;
            padding: 8px;
            z-index: 2000;
        }

        .navbar-v2 .dropdown-menu-custom.show { display: block; }

        .navbar-v2 .dropdown-menu-custom a,
        .navbar-v2 .dropdown-menu-custom button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #333;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: background 0.15s;
        }

        .navbar-v2 .dropdown-menu-custom a:hover,
        .navbar-v2 .dropdown-menu-custom button:hover {
            background: rgba(230,32,32,0.06);
            color: #E62020;
        }

        .navbar-v2 .dropdown-divider-custom {
            border-top: 1px solid #f0f0f0;
            margin: 4px 0;
        }

        /* Hamburger */
        .navbar-v2 .hamburger-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            color: #333;
            font-size: 1.3rem;
            transition: background 0.2s;
        }

        .navbar-v2 .hamburger-btn:hover {
            background: rgba(230,32,32,0.08);
            color: #E62020;
        }

        /* Mobile Menu */
        .navbar-v2 .mobile-menu {
            display: none;
            flex-direction: column;
            background: #fff;
            border-top: 1px solid #f0f0f0;
            padding: 12px 16px 16px;
            gap: 4px;
        }

        .navbar-v2 .mobile-menu.open { display: flex; }

        .navbar-v2 .mobile-menu a,
        .navbar-v2 .mobile-menu button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #444;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
            text-align: left;
        }

        .navbar-v2 .mobile-menu a:hover,
        .navbar-v2 .mobile-menu a.active {
            background: rgba(230,32,32,0.08);
            color: #E62020;
        }

        .navbar-v2 .mobile-menu .mobile-divider {
            border-top: 1px solid #f0f0f0;
            margin: 6px 0;
        }

        .navbar-v2 .mobile-menu .btn-mobile-login {
            background: #E62020;
            color: #fff;
            border-radius: 8px;
            font-weight: 700;
            justify-content: center;
        }

        .navbar-v2 .mobile-menu .btn-mobile-login:hover { background: #c41a1a; }

        @media (max-width: 991.98px) {
            .navbar-v2 .nav-links,
            .navbar-v2 .nav-actions { display: none; }
            .navbar-v2 .hamburger-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<nav class="navbar-v2">
    <div class="container-fluid">
        <div class="navbar-inner">
            <a href="{{ url(env('APP_ROUTE').'/home') }}" class="navbar-brand-area">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo">
                <div>
                    <div class="brand-name">{{ optional($set)->nama_app ?? config('app.name') }}</div>
                    <div class="brand-sub">Portal Layanan</div>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="{{ url(env('APP_ROUTE').'/home') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'home' ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Beranda</a></li>
                <li><a href="{{ route('list') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'list' ? 'active' : '' }}"><i class="fa-solid fa-database"></i> Data</a></li>
                <li><a href="{{ route('list-organisasi') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'list-organisasi' ? 'active' : '' }}"><i class="fa-solid fa-building"></i> Organisasi</a></li>
                <li><a href="{{ route('list-topik') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'list-topik' ? 'active' : '' }}"><i class="fa-solid fa-tags"></i> Topik</a></li>
                <li><a href="{{ route('hubungi-kami') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'hubungi-kami' ? 'active' : '' }}"><i class="fa-solid fa-envelope"></i> Hubungi</a></li>
            </ul>

            <div class="nav-actions">
                @if(session('id_user'))
                <div class="user-dropdown" id="userDropdown">
                    <button class="user-avatar-btn" onclick="toggleDropdown()">
                        <div class="avatar-circle">{{ strtoupper(substr(session('nama_user'), 0, 1)) }}</div>
                        <span class="d-none d-lg-inline">{{ session('nama_user') }}</span>
                        <i class="fa-solid fa-chevron-down" style="font-size:0.7rem;color:#888;"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="dropdownMenu">
                        <a href="{{ route('profile-user') }}"><i class="fa-solid fa-user"></i> Profil Saya</a>
                        <a href="{{ route('monitoring-permohonan') }}"><i class="fa-solid fa-list-check"></i> Monitoring</a>
                        <a href="{{ route('riwayat-user') }}"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</a>
                        <div class="dropdown-divider-custom"></div>
                        <form action="{{ route('logout-backend-action') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-nav-login"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
                <a href="{{ route('register') }}" class="btn-nav-register"><i class="fa-solid fa-user-plus"></i> Daftar</a>
                @endif
            </div>

            <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleMobileMenu()">
                <i class="fa-solid fa-bars" id="hamburgerIcon"></i>
            </button>
        </div>

        <div class="mobile-menu" id="mobileMenu">
            <a href="{{ url(env('APP_ROUTE').'/home') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'home' ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="{{ route('list') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'list' ? 'active' : '' }}"><i class="fa-solid fa-database"></i> Data</a>
            <a href="{{ route('list-organisasi') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'list-organisasi' ? 'active' : '' }}"><i class="fa-solid fa-building"></i> Organisasi</a>
            <a href="{{ route('list-topik') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'list-topik' ? 'active' : '' }}"><i class="fa-solid fa-tags"></i> Topik</a>
            <a href="{{ route('hubungi-kami') }}" class="{{ isset($menu_aktif) && $menu_aktif == 'hubungi-kami' ? 'active' : '' }}"><i class="fa-solid fa-envelope"></i> Hubungi Kami</a>
            <div class="mobile-divider"></div>
            @if(session('id_user'))
            <a href="{{ route('profile-user') }}"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="{{ route('monitoring-permohonan') }}"><i class="fa-solid fa-list-check"></i> Monitoring</a>
            <a href="{{ route('riwayat-user') }}"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</a>
            <div class="mobile-divider"></div>
            <form action="{{ route('logout-backend-action') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" style="color:#E62020;"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn-mobile-login"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
            <a href="{{ route('register') }}" style="border:1.5px solid #E62020;color:#E62020;border-radius:8px;justify-content:center;"><i class="fa-solid fa-user-plus"></i> Daftar</a>
            @endif
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const icon = document.getElementById('hamburgerIcon');
        menu.classList.toggle('open');
        icon.className = menu.classList.contains('open') ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    }
    function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
        const dd = document.getElementById('userDropdown');
        if (dd && !dd.contains(e.target)) {
            const m = document.getElementById('dropdownMenu');
            if (m) m.classList.remove('show');
        }
    });
</script>
