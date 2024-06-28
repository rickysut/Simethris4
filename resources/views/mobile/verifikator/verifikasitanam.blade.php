@extends('mobile.layouts.app')

@section('content')
    <div id="page" class="bg-white">

        <div id="page-preloader">
            <div class="loader-main">
                <div class="preload-spinner border-highlight"></div>
            </div>
        </div>

        <div class="header header-fixed header-logo-app header-auto-show">
            <a href="index.html" class="header-title">VERIFIKATOR</a>
            <a href="#" data-menu="menu-main" class="header-icon header-icon-1"><i class="fas fa-bars"></i></a>
            <a href="#" data-toggle-theme class="header-icon header-icon-2"><i class="fas fa-lightbulb"></i></a>
            <a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>
        </div>

        <div class="page-content">

            <div class="page-title-small color-white">
                <h1><i class="fa fa-arrow-left back-button"></i>Verifikasi Tanam</h1>
                <a href="{{route('mobile.home')}}" data-menu="menu-main" class="shadow-huge scale-box bg-fade-gray2-dark"  onclick="event.preventDefault(); document.getElementById('logoutform').submit();"></a>
            </div>
            <div data-height="130" class="page-title-bg preload-image" data-src="/mobile/images/pictures/20s.jpg"><!-- image --></div>
            <div data-height="130" class="page-title-bg dark-mode-tint"><!-- contrast for dark mode --></div>
            <div data-height="130" class="page-title-bg opacity-90 bg-highlight bottom-30"><!-- background color --></div>


			<div class="content-boxed shadow-large">
				<div class="content bottom-0">
					<p class="bottom-20">
						Fitur ini memanfaatkan Geolokasi pada perangkat Anda.
						Aktifkan Fitur Lokasi diperangkat Anda sebelum menggunakan fitur ini.
					</p>
				</div>
			</div>

			<div class="content content-boxed round-medium shadow-small">
				<div class="content">
					<p class="location-support top-20"></p>
					<div class="input-style input-style-2 input-required">
						<span>Pilih No. RIPH</span>
						<em><i class="fa fa-angle-down"></i></em>
						<select>
							<option value="default" disabled="" selected="">pilih no RIPH</option>
							@foreach ($commitments as $commitment)
								<option value="{{$commitment->no_ijin}}">{{$commitment->no_ijin}}</option>
							@endforeach
						</select>
					</div>
					<a href="#" class="get-location button button-full bg-highlight button-m button-round-small shadow-large">Dapatkan Lokasi</a>
					<p class="location-coordinates" hidden></p>

					<div class="divider"></div>
				</div>
				<div class="responsive-iframe bottom-0 add-iframe">
					<iframe class="location-map" src='https://maps.google.com/?ie=UTF8&amp;ll=47.595131,-122.330414&amp;spn=0.006186,0.016512&amp;t=h&amp;z=17&amp;output=embed'></iframe>
				</div>
			</div>
        </div>

        <!-- Footer Menu-->
        @include('mobile.partials.footer-menu')
        @include('mobile.partials.menu-main-share-highlights')

        <div class="menu-hider"></div>
    </div>
@endsection
