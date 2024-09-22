<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="root-text-sm">

<head>
    <meta charset="utf-8">
    <title>
        {{env('APP_NAME')}} | {{ $pagedata['controller'] ?? config('app.name', 'Application') }}
    </title>
    <meta name="description" content="Login">
	<meta name="theme-color" content="#886ab5">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <meta name="description" content="Page Title">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- smartadmin base css -->
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/vendors.bundle.css') }}">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/app.bundle.css') }}">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
    <link id="myskin" rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/skins/skin-master.css') }}">

    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon.png') }}">
    <link rel="mask-icon" href="{{ asset('img/safari-pinned-tab.svg') }}">

    <!-- Font Awsome -->
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/fa-light.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/fa-regular.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/fa-solid.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/fa-brands.css') }}">

	<link rel="manifest" href="{{asset('manifest.json')}}">

	<link rel="stylesheet" media="screen, print" href="{{ asset('css/smartadmin/notifications/sweetalert2/sweetalert2.bundle.css') }}">

    @yield('style')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
	{{-- <script src="https://www.google.com/recaptcha/api.js"></script> --}}
</head>

<body class="">
    <div class="page-wrapper auth">
        <div class="page-inner bg-brand-gradient">
            <div class="page-content-wrapper bg-transparent m-0">
                <div class="height-10 w-100 shadow-lg px-4 bg-brand-gradient hidden-sm-down">
                    <div class="d-flex align-items-center container p-0">
                        <div class="page-logo width-mobile-auto m-0 align-items-center justify-content-center p-0 bg-transparent bg-img-none shadow-0 height-9 border-0">
                            <img src="{{ asset('img/favicon.png') }}" alt="simethris" aria-roledescription="logo">
                            <span class="page-logo-text mr-1 hidden-sm-down">
                                <img src="{{ asset('img/logo-icon.png') }}" alt="simethris" aria-roledescription="logo" style="width:150px; height:auto;">
                            </span>
                            <span class="page-logo-text mr-1 d-sm-block d-md-none">Simethris MobileApp</span>
                        </div>

                        <div class="ml-auto">
                            <ol class="nav">
                                @guest
                                @if (Route::has('login'))
                                <li class="nav-item" {{ request()->is('*login*') ? 'hidden' : '' }}>
                                    <a class="btn-link text-white nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @endif

                                @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn-link text-white" href="https://riph.pertanian.go.id/">{{ __('Daftar') }}</a>
                                </li>
                                @endif

                                @endguest
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="flex-1" style="background: url(/img/simet-bawang-pagi.webp) no-repeat center top fixed; background-size: cover;">
                    <div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
                        @yield('content')
                    </div>
                </div>

                <div class="position-absolute pos-bottom pos-left pos-right p-3 text-center text-white">
                    2024 © Direktorat Jenderal Hortikultura - Kementerian Pertainan RI&nbsp;<a href='' class='text-white opacity-40 fw-500' title='web application developer' target='_blank'></a>
                </div>
            </div>
        </div>
    </div>
    @include('partials.pagesettings')
	@stack('scripts')
    <script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
	<script src="{{ asset('js/smartadmin/notifications/sweetalert2/sweetalert2.bundle.js') }}"></script>
	{{-- <script src="{{asset('sw.js')}}"></script> --}}
	<script>
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.register('sw.js')
				.then(function(registration) {
					console.log('Service Worker registered with scope:', registration.scope);
				}).catch(function(error) {
					console.log('Service Worker registration failed:', error);
				});
		}
        $("#js-login-btn").click(function(event) {

            // Fetch form to apply custom Bootstrap validation
            var form = $("#js-login")

            if (form[0].checkValidity() === false) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.addClass('was-validated');
            // Perform ajax submit here...
        });

	</script>

    @yield('scripts')
</body>

</html>
