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
            <div data-height="130" class="page-title-bg preload-image" data-src="images/pictures/20s.jpg"><!-- image --></div>
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

			<div class="map-full left-15 right-15 round-small">
				<iframe src="https://maps.google.com/?ie=UTF8&amp;ll=47.595131,-122.330414&amp;spn=0.006186,0.016512&amp;t=h&amp;z=17&amp;output=embed"></iframe>
				<div data-height="cover-title" class="caption ">
					<div class="caption-center">
						<h1 class="center-text color-white ultrabold font-36">FULL MAPS</h1>
						<p class="boxed-text-large under-heading color-white opacity-90 top-10">
							Browse Google Maps in Full Screen.<br> Just tap the button to scroll through it.
						</p>
						<a href="#" class="show-map button button-m button-center-medium bg-highlight button-round-small">ACTIVATE MAP</a>
					</div>
					<div class="caption-overlay bg-black opacity-80"></div>
				</div>
				<a href="#" class="hide-map button button-m bg-red2-dark button-round-small">DISABLE MAP</a>
			</div>
			<div class="content-boxed shadow-small">
				<div class="content">
					<h3 class="bolder">Modern Fields</h3>
					<p>
						These boxes will react to them when you type or select a value.
					</p>

					<div class="input-style input-style-2 has-icon input-required">
						<i class="input-icon fa fa-user"></i>
						<span class="input-style-1-inactive">Name</span>
						<em>(required)</em>
						<input type="name" placeholder="">
					</div>

					<div class="input-style input-style-2 input-required">
						<span class="input-style-1-inactive">Email</span>
						<em>(required)</em>
						<input type="email" placeholder="">
					</div>

					<div class="input-style input-style-2 input-required">
						<span>Password</span>
						<em>(required)</em>
						<input type="password" placeholder="">
					</div>

					<div class="input-style input-style-2 input-required">
						<span>Website</span>
						<em>(required)</em>
						<input type="url" placeholder="">
					</div>

					<div class="input-style input-style-2 input-required">
						<span>Phone</span>
						<em>(required)</em>
						<input type="tel" placeholder="">
					</div>

					<div class="input-style input-style-2 input-required">
						<span>Select a Value</span>
						<em><i class="fa fa-angle-down"></i></em>
						<select>
							<option value="default" disabled="" selected="">Select a Value</option>
							<option value="iOS">iOS</option>
							<option value="Linux">Linux</option>
							<option value="MacOS">MacOS</option>
							<option value="Android">Android</option>
							<option value="Windows">Windows</option>
						</select>
					</div>
					<div class="input-style input-style-2 input-required">
						<span>Enter your Message</span>
						<em>(required)</em>
						<textarea placeholder=""></textarea>
					</div>
				</div>
			</div>
			<div class="content content-boxed round-medium shadow-small">
				<div class="content">
					<ul class="list-group">
						<li class="list-group-items">
							{{$user->name}}
						</li>
						<li class="list-group-items">
							{{$noIjin}}
						</li>
						<li class="list-group-items">
							{{$ijin}}
						</li>
					</ul>

					<div class="divider"></div>


				</div>
			</div>
        </div>

        <!-- Footer Menu-->
        @include('mobile.partials.footer-menu')
        {{-- @include('mobile.partials.menu-main-share-highlights') --}}

        <div class="menu-hider"></div>
    </div>
@endsection

@section('scripts')
<script>
//untuk mendapatkan semua lokasi dari nomor riph terkait dalam radius
//gunakan route ini {{ route('2024.datafeeder.responseGetLocByRad') }}
</script>

@endsection
