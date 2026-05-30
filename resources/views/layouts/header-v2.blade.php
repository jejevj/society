<!DOCTYPE html>
<html lang="id">
	<head>
		<base href="../../../" />
		<title><?= env('APP_NAME', 'Society Event - Science Bank'); ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="=" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Satu Data Pertahanan - Kementrian Pertahanan" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta property="og:site_name" content="Satu Data Pertahanan" />
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
		<link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />
		<link href="{{ asset('assets/css/min/font.min.css') }}" rel="stylesheet" type="text/css" />

<head>
	<base href="../../../" />
	<title><?= env('APP_NAME', 'Society Event - Science Bank'); ?></title>
	<meta charset="utf-8" />
	<meta name="description" content="=" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="Satu Data Pertahanan - Kementrian Pertahanan" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta property="og:site_name" content="Satu Data Pertahanan" />
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />
	<link href="{{ asset('assets/css/min/font.min.css') }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('assets/css/min/datatables.bundle.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/min/style.bundle.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/min/front.min.css') }}" rel="stylesheet" type="text/css" />
	<script src="{{ asset('assets/js/min/swal.min.js') }}"></script>
	<script src="{{ asset('assets/js/min/jquery.min.js') }}"></script>
</head>

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			<div id="kt_app_header" class="app-header" data-kt-sticky="true"
				data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky"
				data-kt-sticky-offset="{default: false, lg: '300px'}">
				<div class="app-container container-xxl d-flex align-items-stretch justify-content-between"
					id="kt_app_header_container">
					<div class="d-flex align-items-center d-lg-none ms-n3 me-2" title="Show sidebar menu">
						<div class="btn btn-icon btn-color-gray-600 btn-active-color-primary w-35px h-35px"
							id="kt_app_header_menu_toggle">
							<i class="ki-outline ki-abstract-14 fs-2"></i>
						</div>
<<<<<<< HEAD
					</div>
					<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
						<a href="{{ env('APP_ROUTE') }}" class="d-flex align-items-center">
							<img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-70px d-lg-none my-5" />
							<img alt="Logo" src="{{ asset('images/logo.png') }}"
								class="h-70px d-none d-lg-inline app-sidebar-logo-default theme-light-show" />
							<div class="ms-3 d-flex flex-column">
								<span class="fw-bold text-white fs-4 d-none d-md-block">
									Society Event
								</span>
								<span class="fw-bold text-white fs-4 d-block d-md-none">
									Society Event
								</span>
								<span class="fw-bold text-white fs-6 d-none d-md-block">
									Science Bank
								</span>
								<span class="fw-bold text-white fs-6 d-block d-md-none">
									Science Bank
								</span>

							</div>
						</a>
					</div>
					<div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1"
						id="kt_app_header_wrapper">
						<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
							data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
							data-kt-drawer-overlay="true" data-kt-drawer-width="350px" data-kt-drawer-direction="start"
							data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
							data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
							data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
							<div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0 justify-content-end w-100"
								id="kt_app_header_menu" data-kt-menu="true">
								{{-- About --}}
								<div class="menu-item me-0 me-lg-2">
									<a href="{{ route('about') }}" class="menu-link">
										<span class="menu-title fs-5">
											<span class="fw-bold 
                {{ $menu_aktif == 'about' ? 'text-maroon-active' : 'text-white' }} 
                hover:text-maroon-active transition-colors">
												About
											</span>
=======
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
                            <a href="{{ env('APP_ROUTE') }}" class="d-flex align-items-center">
                                <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-70px d-lg-none my-5" />
                                <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-70px d-none d-lg-inline app-sidebar-logo-default theme-light-show" />
                                <div class="ms-3 d-flex flex-column">
									<span class="fw-bold text-white fs-4 d-none d-md-block">
										Society Event
									</span>
									<span class="fw-bold text-white fs-4 d-block d-md-none">
										Society Event
									</span>
									<span class="fw-bold text-white fs-6 d-none d-md-block">
										Science Bank
									</span>
									<span class="fw-bold text-white fs-6 d-block d-md-none">
										Science Bank
									</span>

								</div>
                            </a>
                        </div>
						<div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1" id="kt_app_header_wrapper">
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="350px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
								<div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0 justify-content-end w-100" id="kt_app_header_menu" data-kt-menu="true">
									<div class="menu-item me-0 me-lg-2">
                                        <a href="{{ route('about') }}" class="menu-link">
                                            <span class="menu-title fs-5 " >
												<span class="<?php if($menu_aktif == 'about'){ echo 'text-maroon-active' ;}else{ echo 'text-white';}?> fw-bold text-white">About</span>
											</span>
                                        </a>
                                    </div>
									<div class="menu-item me-0 me-lg-2">
                                        <a href="{{ route('about') }}" class="menu-link">
                                            <span class="menu-title fs-5 " >
												<span class="<?php if($menu_aktif == 'event'){ echo 'text-maroon-active' ;}else{ echo 'text-white';}?> fw-bold text-white">Event</span>
											</span>
                                        </a>
                                    </div>
									<div class="menu-item me-0 me-lg-2">
                                        <a href="{{ route('about') }}" class="menu-link">
                                            <span class="menu-title fs-5 " >
												<span class="<?php if($menu_aktif == 'paper'){ echo 'text-maroon-active' ;}else{ echo 'text-white';}?> fw-bold text-white">Paper</span>
											</span>
                                        </a>
                                    </div>
									
									

									<?php if(empty(session('id_user'))){?>
									<div class="menu-item me-0">
										<a href="{{ route('login') }}" class="btn btn-login">
											<i class="fa-solid fa-circle-user me-2 text-white"></i> Login / Register
										</a>
									</div>
									<?php }else {?>
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
										<span class="menu-link bg-warning">
											<span class="menu-title text-white"> <i class="fa fa-user px-2 py-4 text-white"> </i> Hallo, {{ session('nama_user')}}</span>
											<span class="menu-arrow d-lg-none"></span>
>>>>>>> ca08a53154cfa3ebb8add7a988d3c119976d658d
										</span>
									</a>
								</div>

								{{-- Event --}}
								<div class="menu-item me-0 me-lg-2">
									<a href="{{ route('about') }}" class="menu-link">
										<span class="menu-title fs-5">
											<span class="fw-bold 
                {{ $menu_aktif == 'event' ? 'text-maroon-active' : 'text-white' }} 
                hover:text-maroon-active transition-colors">
												Event
											</span>
										</span>
									</a>
								</div>

								{{-- Paper --}}
								<div class="menu-item me-0 me-lg-2">
									<a href="{{ route('about') }}" class="menu-link">
										<span class="menu-title fs-5">
											<span class="fw-bold 
                {{ $menu_aktif == 'paper' ? 'text-maroon-active' : 'text-white' }} 
                hover:text-maroon-active transition-colors">
												Paper
											</span>
										</span>
									</a>
								</div>

								<?php if (empty(session('id_user'))) {?>
								<div class="menu-item me-0">
									<a href="{{ route('login') }}" class="btn btn-login">
										<i class="fa-solid fa-circle-user me-2 text-white"></i> Login / Register
									</a>
								</div>
								<?php } else {?>
								<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
									data-kt-menu-placement="bottom-start"
									class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
									<span class="menu-link bg-warning">
										<span class="menu-title text-white"> <i class="fa fa-user px-2 py-4 text-white">
											</i> Hallo, {{ session('nama_user')}}</span>
										<span class="menu-arrow d-lg-none"></span>
									</span>
									<div
										class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
										<div class="menu-item">
											<a class="menu-link mb-2 hover:text-maroon-active transition-colors" href="{{ route('riwayat-user') }}"
												data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
												data-bs-placement="right">
												<span class="menu-icon">
													<span class="bullet w-10px h-10px"></span>
												</span>
												<span class="menu-title">Riwayat Permohonan</span>
											</a>
											<a class="menu-link mb-2 hover:text-maroon-active transition-colors" href="{{ route('profile-user') }}"
												data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
												data-bs-placement="right">
												<span class="menu-icon">
													<span class="bullet w-10px h-10px"></span>
												</span>
												<span class="menu-title">Profil Saya</span>
											</a>
											<a class="menu-link mb-2 hover:text-maroon-active transition-colors" href="{{ route('ganti-password-user') }}"
												data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
												data-bs-placement="right">
												<span class="menu-icon">
													<span class="bullet w-10px h-10px"></span>
												</span>
												<span class="menu-title">Ganti Password</span>
											</a>
										</div>
										<div class="menu-item">
											<a href="javascript:void(0)" id="btnLogout"
												class="btn btn-danger text-white btn-block w-100">
												<i class="fa-solid fa-power-off"></i> Logout
											</a>
										</div>
										<script>
											$(document).ready(function () {
												$("#btnLogout").on("click", function (e) {
													e.preventDefault();

													Swal.fire({
														title: "Konfirmasi",
														text: "Yakin ingin logout?",
														icon: "warning",
														showCancelButton: true,
														confirmButtonColor: "#d33",
														cancelButtonColor: "#3085d6",
														confirmButtonText: "Ya, Logout!"
													}).then((result) => {
														if (result.isConfirmed) {
															$.ajax({
																url: "{{ route('logout-backend-action') }}",
																type: "POST",
																data: { _token: "{{ csrf_token() }}" },
																success: function (res) {
																	if (res.status) {
																		Swal.fire("Berhasil!", res.message, "success").then(() => {
																			window.location.href = "{{ route('login') }}";
																		});
																	} else {
																		Swal.fire("Gagal!", res.message, "error");
																	}
																},
																error: function () {
																	Swal.fire("Error!", "Terjadi kesalahan, coba lagi.", "error");
																}
															});
														}
													});
												});
											});
										</script>
									</div>
								</div>
								<?php }?>

							</div>
						</div>
					</div>
				</div>
			</div>