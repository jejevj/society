
<!DOCTYPE html>

<html lang="id">
	<!--begin::Head-->
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
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid bg-login" id="kt_app_page">
			