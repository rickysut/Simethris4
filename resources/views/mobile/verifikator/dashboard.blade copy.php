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
			<div data-height="130" class="page-title-bg bg-20"><!-- image --></div>
			<div data-height="130" class="page-title-bg dark-mode-tint"><!-- contrast for dark mode --></div>
			<div data-height="130" class="page-title-bg opacity-90 bg-highlight"><!-- background color --></div>

			<div class="page-title-small color-white bottom-30">
				<h1><i class="fa fa-arrow-left back-button"></i>Pages</h1>
				<a href="#" data-menu="menu-main" class="shadow-huge scale-box bg-fade-gray2-dark"></a>
			</div>

			<div class="content-boxed shadow-small">
				<div class="content bottom-0">
					<p class="bottom-20">
						Packed with powerful built pages that are highly customizable and blazing fast to load. We've categorized our pages by purpose to make it easier for you to find them.
					</p>
				</div>
			</div>

			<div class="content">
				<div class="one-half">
					<a href="pages-list.html" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i data-feather="file"
								   data-feather-line="1"
								   data-feather-size="50"
								   data-feather-color="blue2-dark"
								   data-feather-bg="blue2-fade-light">
								</i>
							</h1>
							<h4 class="center-text color-theme">General</h4>
							<p class="under-heading color-highlight center-text font-11 color-highlight">
								Multi Purpose Pages
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Tap to View</p>
						</div>
					</a>
				</div>
				<div class="one-half last-column">
					<a href="pages-appstyled-list.html" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i data-feather="smartphone"
								   data-feather-line="1"
								   data-feather-size="50"
								   data-feather-color="green1-dark"
								   data-feather-bg="green1-fade-light">
								</i>
							</h1>
							<h4 class="center-text color-theme">App Styled</h4>
							<p class="under-heading color-highlight center-text font-11 color-highlight">
								Designed Like Apps
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Tap to View</p>
						</div>
					</a>
				</div>
				<div class="clear"></div>
				<div class="one-half">
					<a href="pages-starters-list.html" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i data-feather="box"
								   data-feather-line="1"
								   data-feather-size="50"
								   data-feather-color="magenta1-dark"
								   data-feather-bg="magenta1-fade-light">
								</i>
							</h1>
							<h4 class="center-text color-theme">Starters</h4>
							<p class="under-heading color-highlight center-text font-11 color-highlight">
								Walkthrough & Splash
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Tap to View</p>
						</div>
					</a>
				</div>
				<div class="one-half last-column">
					<a href="component-action-sheets.html" data-height="180" class="caption round-medium shadow-large bg-theme bottom-15">
						<div class="caption-center">
							<h1 class="center-text">
								<i data-feather="zap"
								   data-feather-line="1"
								   data-feather-size="50"
								   data-feather-color="yellow1-dark"
								   data-feather-bg="yellow1-fade-light">
								</i>
							</h1>
							<h4 class="center-text color-theme">Actions</h4>
							<p class="under-heading color-highlight center-text font-11 color-highlight">
								Modal Menus & Actions
							</p>
						</div>
						<div class="caption-bottom">
							<p class="bottom-10 opacity-40 font-10 center-text">Tap to View</p>
						</div>
					</a>
				</div>
				<div class="clear"></div>
				<a href="components.html" data-height="100" class="caption round-medium shadow-large bg-theme">
					<div class="caption-center">
						<h1 class="left-20 top-10">
							<i data-feather="heart"
							   data-feather-line="1"
							   data-feather-size="45"
							   data-feather-color="red2-dark"
							   data-feather-bg="red2-fade-light">
							</i>
						</h1>
					</div>
					<div class="caption-center">
						<h4 class="color-theme left-90 top-30">Components</h4>
						<p class="under-heading color-highlight font-11 color-highlight left-90">
							Build your pages! It's Copy & Paste
						</p>
					</div>
				</a>
			</div>


			 <div class="footer" data-footer-load="menu-footer.html"></div>
		</div>

        <!-- Footer Menu-->
        <div id="footer-menu" class="footer-menu-5-icons footer-menu-style-1">
            <a href="components.html">
                <i data-feather="heart" data-feather-line="1" data-feather-size="21" data-feather-color="red2-dark"
                    data-feather-bg="red2-fade-light"></i>
                <span>Features</span>
            </a>
            <a href="media.html">
                <i data-feather="image" data-feather-line="1" data-feather-size="21" data-feather-color="green1-dark"
                    data-feather-bg="green1-fade-light"></i>
                <span>Media</span>
            </a>
            <a href="index.html" class="active-nav4">
                <i data-feather="home" data-feather-line="1" data-feather-size="21" data-feather-color="blue2-dark"
                    data-feather-bg="blue2-fade-dark"></i>
                <span>Home</span>
            </a>
            <a href="pages.html">
                <i data-feather="file" data-feather-line="1" data-feather-size="21" data-feather-color="brown1-dark"
                    data-feather-bg="brown1-fade-light"></i>
                <span>Pages</span>
            </a>
            <a href="settings.html">
                <i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="gray2-dark"
                    data-feather-bg="gray2-fade-light"></i>
                <span>Settings</span>
            </a>
            <div class="clear"></div>
        </div>

        <!-- Main Sidebar Menu-->
        <div id="menu-main" class="menu menu-box-right menu-box-detached round-medium" data-menu-active="nav-welcome"
            data-menu-width="260" data-menu-effect="menu-over" data-menu-load="menu-main.html">
        </div>

        <!-- Share Menu-->
        <div id="menu-share" class="menu menu-box-bottom menu-box-detached round-medium" data-menu-height="400"
            data-menu-effect="menu-over" data-menu-load="menu-share.html">
        </div>

        <!-- Color Highlights Menu-->
        <div id="menu-highlights" class="menu menu-box-bottom menu-box-detached round-medium" data-menu-height="480"
            data-menu-effect="menu-over" data-menu-load="menu-colors.html">
        </div>


        <!-- Be sure this is on your main visiting page, for example, the index.html page-->
        <!-- These modals will asure your page fires the PWA install notification boxes-->
        <!-- Install Prompt for Android -->
        <div id="menu-install-pwa-android" class="menu menu-box-bottom menu-box-detached round-large"
            data-menu-height="340" data-menu-effect="menu-parallax">
            <div class="boxed-text-huge top-25">
                <img class="round-medium preload-image center-horizontal" data-src="app/icons/icon-128x128.png"
                    alt="img" width="90">
                <h4 class="center-text bolder top-20 bottom-10">Add Azures on your Home Screen</h4>
                <p>
                    Install Azures on your home screen, and access it just like a regular app. It really is that simple!
                </p>
                <a href="#"
                    class="pwa-install button button-xs button-round-medium button-center-large shadow-large bg-highlight bottom-0">Add
                    to Home Screen</a><br>
                <a href="#"
                    class="pwa-dismiss close-menu center-text color-gray2-light uppercase ultrabold opacity-60 under-heading font-10">Maybe
                    later</a>
                <div class="clear"></div>
            </div>
        </div>

        <!-- Install instructions for iOS -->
        <div id="menu-install-pwa-ios" class="menu menu-box-bottom menu-box-detached round-large" data-menu-height="320"
            data-menu-effect="menu-parallax">
            <div class="boxed-text-huge top-25">
                <img class="round-medium preload-image center-horizontal" data-src="app/icons/icon-128x128.png"
                    alt="img" width="90">
                <h4 class="center-text bolder top-20 bottom-10">Add Azures on your Home Screen</h4>
                <p class="bottom-15">
                    Install Azures on your home screen, and access it just like a regular app. Open your Safari menu and tap
                    "Add to Home Screen".
                </p>
                <div class="clear"></div>
                <a href="#"
                    class="pwa-dismiss close-menu center-text color-highlight uppercase ultrabold opacity-80 top-25">Maybe
                    later</a>
                <i class="fa-ios-arrow fa fa-caret-down font-40"></i>
            </div>
        </div>

        <div class="menu-hider"></div>
    </div>
@endsection
