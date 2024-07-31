<head>

	<meta charset="utf-8" />
	<title>Starter | Velzon - Admin & Dashboard Template</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
	<meta content="Themesbrand" name="author" />
	<!-- App favicon -->
	<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

	<!-- Layout config Js -->
	<script src="{{asset('velzon/js/layout.js')}}"></script>
	<!-- Bootstrap Css -->
	<link href="{{asset('velzon/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
	<!-- Icons Css -->
	<link href="{{asset('velzon/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
	<!-- App Css-->
	<link href="{{asset('velzon/css/app.min.css')}}" rel="stylesheet" type="text/css" />
	<!-- custom Css-->
	<link href="{{asset('velzon/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
	<script src="https://maps.googleapis.com/maps/api/js?key={{ isset($mapkey) ? $mapkey->key : 'Default Key' }}&libraries=drawing,geometry&callback=initMap" async defer></script>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@yield('styles')
</head>
