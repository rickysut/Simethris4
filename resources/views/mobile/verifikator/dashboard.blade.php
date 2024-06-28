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

            <div class="page-title-small color-white bottom-30">
                <h1 data-username="{{$user}}" class="greeting-text"></h1>
                <a href="#" data-menu="menu-main" class="shadow-huge scale-box bg-fade-gray2-dark"  onclick="event.preventDefault(); document.getElementById('logoutform').submit();"></a>
            </div>
            <div data-height="130" class="page-title-bg preload-image" data-src="images/pictures/20s.jpg"><!-- image -->
            </div>
            <div data-height="130" class="page-title-bg dark-mode-tint"><!-- contrast for dark mode --></div>
            <div data-height="130" class="page-title-bg opacity-90 bg-highlight"><!-- background color --></div>

            <!-- Welcome Area -->
            {{-- <div class="single-slider slider-full owl-no-dots owl-carousel">
                <div class="caption bottom-0 round-medium shadow-large" data-height="350">
                    <div class="caption-bottom bottom-10 center-text">
                        <h1 class="bolder font-28">VERIFIKATOR</h1>
                        <p class="color-theme boxed-text-huge opacity-60">
                            Azures brings beauty and colors to your Mobile device with a stunning user interface to match.
                        </p>
                    </div>
                    <div class="caption-overlay bg-gradient-fade"></div>
                    <div class="caption-bg owl-lazy" data-src="images/pictures/17m.jpg"></div>
                </div>
                <div class="caption bottom-0 round-medium shadow-large" data-height="350">
                    <div class="caption-bottom bottom-10 center-text">
                        <h1 class="bolder font-24">Beyond Powerful</h1>
                        <p class="color-theme boxed-text-huge opacity-60">
                            Azures is a Mobile Web App Kit, fully featured, supporting PWA and Native Dark Mode!
                        </p>
                    </div>
                    <div class="caption-overlay bg-gradient-fade"></div>
                    <div class="caption-bg owl-lazy" data-src="images/pictures/8m.jpg"></div>
                </div>
                <div class="caption bottom-0 round-medium shadow-large" data-height="350">
                    <div class="caption-bottom bottom-10 center-text">
                        <h1 class="bolder font-24">A-Level Quality</h1>
                        <p class="color-theme boxed-text-huge opacity-60">
                            We build custom, premium products, that are easy to use and provide all features for you!
                        </p>
                    </div>
                    <div class="caption-overlay bg-gradient-fade"></div>
                    <div class="caption-bg owl-lazy" data-src="images/pictures/14m.jpg"></div>
                </div>
            </div> --}}
			<div class="content top-60">
				<div class="one-half">
					<a href="{{route('mobile.verifikasi.tanam.')}}" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i class="fas fa-seedling" style="font-size: 50px; color:#64a321"></i>
							</h1>
							<h4 class="center-text color-theme">TANAM</h4>
							<p class="under-heading color-highlight center-text font-11 color-highlight">
								Verifikasi Tanam
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Sentuh untuk melihat</p>
						</div>
					</a>
				</div>
				<div class="one-half last-column">
					<a href="javascript:void(0)" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i data-feather="box"
								   data-feather-line="1"
								   data-feather-size="50"
								   data-feather-color="gray1-dark"
								   data-feather-bg="gray1-fade-light">
								</i>
							</h1>
							<h4 class="opacity-20 center-text color-theme">Produksi</h4>
							<p class="opacity-20 under-heading color-highlight center-text font-11 color-highlight">
								Verifikasi Produksi
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Sentuh untuk melihat</p>
						</div>
					</a>
				</div>
				<div class="clear"></div>
				<div class="one-half">
					<a href="javascript:void(0)" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i data-feather="award"
								   data-feather-line="1"
								   data-feather-size="50"
								   data-feather-color="gray1-dark"
								   data-feather-bg="gray1-fade-light">
								</i>
							</h1>
							<h4 class="center-text color-theme opacity-20">Segera Hadir</h4>
							<p class="opacity-20 under-heading color-highlight center-text font-11 color-highlight">
								Mohon ditunggu
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Belum ada fungsi</p>
						</div>
					</a>
				</div>
				<div class="one-half last-column">
					<a href="javascript:void(0)" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i data-feather="zap"
								   data-feather-line="1"
								   data-feather-size="50"
								   data-feather-color="gray1-dark"
								   data-feather-bg="gray1-fade-light">
								</i>
							</h1>
							<h4 class="center-text color-theme opacity-20">Segera Hadir</h4>
							<p class="opacity-20 under-heading color-highlight center-text font-11 color-highlight">
								Mohon ditunggu
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Belum ada fungsi</p>
						</div>
					</a>
				</div>
				<div class="clear"></div>
			</div>

            {{-- <div class="footer" data-footer-load="menu-footer.html"></div> --}}
        </div>

        <!-- Footer Menu-->
        @include('mobile.partials.footer-menu')
        @include('mobile.partials.menu-main-share-highlights')

        <!-- Be sure this is on your main visiting page, for example, the index.html page-->
        <!-- These modals will asure your page fires the PWA install notification boxes-->
        <!-- Install Prompt for Android -->
        @include('mobile.partials.menu-install-pwa-android')

        <!-- Install instructions for iOS -->
        {{-- @include('mobile.partials.menu-install-pwa-ios') --}}

        <div class="menu-hider"></div>
    </div>
@endsection
