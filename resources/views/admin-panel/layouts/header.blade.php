
<!DOCTYPE html>

<html lang="id">
	<head>
<base href="../../../" />
		<title><?= env('APP_NAME', 'Society Event - Science Bank'); ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="=" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?= env('APP_NAME', 'Society Event - Science Bank'); ?>" />
		<meta property="og:site_name" content="<?= env('APP_NAME', 'Society Event - Science Bank'); ?>" />
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
		<link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />
		<link href="{{ asset('assets/css/min/font.min.css') }}" rel="stylesheet" type="text/css" />
		<!-- <link href="{{ asset('assets/css/min/datatables.bundle.min.css') }}" rel="stylesheet" type="text/css" /> -->
		<link href="{{ asset('assets/css/min/style_back.bundle.min.css') }}" rel="stylesheet" type="text/css" />

		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/datatable.css') }}" rel="stylesheet" type="text/css" />
		<script src="{{ asset('assets/js/min/jquery.min.js') }}"></script>
		<!-- <script src="{{ asset('assets/js/jquery-datatable.js') }}"></script> -->
		<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
		<link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/min/back.min.css') }}" rel="stylesheet" type="text/css" />
		<script src="{{ asset('assets/js/select2.js') }}"></script>



	</head>
	<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '400px'}">
					<div class="app-container container-xxl d-flex align-items-stretch justify-content-between py-5" id="kt_app_header_container">
						<div class="d-flex align-items-center d-lg-none ms-n3 me-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-color-gray-600 btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
								<i class="ki-outline ki-abstract-14 fs-2"></i>
							</div>
						</div>
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
                            <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                                <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-70px d-lg-none my-5" />
                                <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-70px d-none d-lg-inline app-sidebar-logo-default theme-light-show" />
                                <div class="ms-3 d-flex flex-column">
                                    <span class="fw-bold text-white fs-5 fw-bold mt-5">Society Event</span>
                                    <span class="fw-bold text-white fs-5 fw-bold mb-5">Science Bank</span>
                                </div>
                            </a>
                        </div>
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="350px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
								<div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
									{!! $navbar !!}
									<?php
									$p_menu = explode("||", $menu_aktif);
									?>
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
										<span class="menu-link">
											<span class="menu-title <?php if($p_menu[1] == 'akun'){ echo 'text-maroon-active';}?>">Settings</span>
											<span class="menu-arrow d-lg-none"></span>
										</span>
										<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
											<div class="menu-item">
                                                <a class="menu-link" href="{{ route('profile') }}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                                    <span class="menu-icon">
                                                        <span class="bullet w-10px h-10px"></span>
                                                    </span>
                                                    <span class="menu-title <?php if($p_menu[0] == 'profil'){ echo 'text-maroon-child-active';}?>">Profile</span>
                                                </a>
												<a class="menu-link" href="{{ route('ganti-password') }}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                                    <span class="menu-icon">
                                                        <span class="bullet w-10px h-10px"></span>
                                                    </span>
                                                    <span class="menu-title <?php if($p_menu[0] == 'password'){ echo 'text-maroon-child-active';}?>">Change Password</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
												<a href="javascript:void(0)" id="btnLogout" class="btn btn-danger text-white btn-block w-100">
													<i class="fa-solid fa-power-off"></i> Logout
												</a>
											</div>
											<script>
												$(document).ready(function(){
													$("#btnLogout").on("click", function(e){
														e.preventDefault();

														Swal.fire({
															title: "Confirm",
															text: "Are you sure you want to log out?",
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
																	data: {_token: "{{ csrf_token() }}"},
																	success: function(res){
																		if(res.status){
																			Swal.fire("Berhasil!", res.message, "success").then(() => {
																				window.location.href = "{{ route('login-backend') }}";
																			});
																		} else {
																			Swal.fire("Gagal!", res.message, "error");
																		}
																	},
																	error: function(){
																		Swal.fire("Error!", "An error occurred, please try again.", "error");
																	}
																});
															}
														});
													});
												});
											</script>
										</div>
									</div>
                                   
								</div>
							</div>							
						</div>
						
					</div>
				</div>
