@extends('mobile.layouts.app')

@section('content')
    <div id="page">

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

            <div class="page-title-large color-white bottom-30">
                <h1 data-username="Enabled!" class="greeting-text"></h1>
                <a href="#" data-menu="menu-main" class="shadow-huge scale-box bg-fade-gray2-dark"></a>
            </div>
            <div data-height="210" class="page-title-bg preload-image" data-src="images/pictures/20s.jpg"><!-- image -->
            </div>
            <div data-height="210" class="page-title-bg dark-mode-tint"><!-- contrast for dark mode --></div>
            <div data-height="210" class="page-title-bg opacity-90 bg-highlight"><!-- background color --></div>

            <!-- Welcome Area -->
            <div class="single-slider slider-full owl-no-dots owl-carousel">
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
            </div>

            <div class="content">
                <div class="one-half">
                    <a href="#"
                        class="top-30 button button-m round-small button-full shadow-small bg-highlight bottom-40">Purchase</a>
                </div>
                <div class="one-half last-column">
                    <a href="#"
                        class="top-30 button button-m round-small button-full shadow-small button-border color-highlight border-highlight bottom-40">Contact
                        Us</a>
                </div>
            </div>

            <!-- Double Slider Area-->
            <div class="content bottom-10">
                <h5 class="float-left font-500">Quality Features</h5>
                <a href="#" class="float-right opacity-70">View All</a>
                <div class="clear"></div>
            </div>

            <div class="double-slider owl-carousel owl-no-dots">
                <div class="item bg-theme round-small shadow-small center-text bottom-30">
                    <i class="top-20 bottom-10" data-feather="shield" data-feather-line="1" data-feather-size="45"
                        data-feather-color="blue2-dark" data-feather-bg="blue2-fade-light"></i>
                    <h5>Elite Quality</h5>
                    <p class="line-height-small font-11 bottom-20">
                        Built with care and <br>every detail in mind
                    </p>
                </div>
                <div class="item bg-theme round-small shadow-small center-text">
                    <i class="top-20 bottom-10" data-feather="smartphone" data-feather-line="1" data-feather-size="45"
                        data-feather-color="brown1-dark" data-feather-bg="brown1-fade-light"></i>
                    <h5>PWA Ready</h5>
                    <p class="font-11 line-height-small bottom-20">
                        Just add it to your <br>Home Screen
                    </p>
                </div>
                <div class="item bg-theme round-small shadow-small center-text">
                    <i class="top-20 bottom-10" data-feather="sun" data-feather-line="1" data-feather-size="45"
                        data-feather-color="yellow1-dark" data-feather-bg="yellow1-fade-light"></i>
                    <h5>Eye Friendly</h5>
                    <p class="font-11 line-height-small bottom-20">
                        Light & Dark and <br> Auto Dark Detection
                    </p>
                </div>
                <div class="item bg-theme round-small shadow-small center-text">
                    <i class="top-20 bottom-10" data-feather="smile" data-feather-line="1" data-feather-size="45"
                        data-feather-color="green1-dark" data-feather-bg="green1-fade-light"></i>
                    <h5>Easy Code</h5>
                    <p class="font-11 line-height-small bottom-20">
                        Built for you and me <br> copy and paste code.
                    </p>
                </div>
            </div>


            <!-- Built For You Area-->
            <div data-height="300" class="content-bg round-none top-10 bg-bg preload-image"
                data-src="images/pictures/20s.jpg"><!-- image --></div>
            <div data-height="300" class="content-bg round-none top-10 dark-mode-tint"><!-- contrast for dark mode -->
            </div>
            <div data-height="300" class="content-bg round-none top-10 opacity-90 bg-highlight"><!-- background color -->
            </div>

            <div class="content bottom-0">
                <h4 class="color-white top-30 bottom-0">Built For You</h4>
                <p class="color-white opacity-80 bottom-30">Our products suit your website, running incredibly fast and
                    provide an unmatched UX and UI.</p>
            </div>

            <div class="content-boxed shadow-small">
                <div class="content bottom-15">
                    <div class="one-half">
                        <i class="float-left top-0 right-20" data-feather="globe" data-feather-line="1"
                            data-feather-size="35" data-feather-color="blue2-dark"
                            data-feather-bg="blue2-fade-light"></i>
                        <h6 class="top-0 bottom-10 font-500 font-13 line-height-small">Mobile <br> Website</h6>
                    </div>
                    <div class="one-half last-column">
                        <i class="float-left top-0 right-20" data-feather="smartphone" data-feather-line="1"
                            data-feather-size="35" data-feather-color="dark3-dark"
                            data-feather-bg="dark3-fade-light"></i>
                        <h6 class="top-0 bottom-10 font-500 font-13 line-height-small">Mobile <br> PWA</h6>
                    </div>
                    <div class="clear bottom-10"></div>
                    <div class="one-half">
                        <i class="float-left top-10 right-20" data-feather="user" data-feather-line="1"
                            data-feather-size="35" data-feather-color="brown2-dark"
                            data-feather-bg="brown2-fade-light"></i>
                        <h6 class="top-10 bottom-10 font-500 font-13 line-height-small">Intuitive <br> Interface</h6>
                    </div>
                    <div class="one-half last-column">
                        <i class="float-left top-10 right-20" data-feather="box" data-feather-line="1"
                            data-feather-size="35" data-feather-color="green1-dark"
                            data-feather-bg="green1-fade-light"></i>
                        <h6 class="top-10 bottom-10 font-500 font-13 line-height-small">Highly <br> Flexible</h6>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <!-- Welcome Area -->
            <div class="content-boxed shadow-small bottom-40 top-50">
                <div class="content">
                    <h2 class="center-text">Ready in 3 Steps</h2>
                    <p class="boxed-text-huge bottom-30">
                        Our products are designed to simplify the way you code a page, with
                        focus on easy, copy and paste.
                    </p>

                    <div class="divider divider-small bg-highlight bottom-30"></div>

                    <div class="list-columns-left bottom-25">
                        <div>
                            <i class="far fa-star color-yellow1-dark fa-3x"></i>
                            <h1 class="bold font-16">Find your Style</h1>
                            <p>
                                We've included multiple styles you can choose to match your exact project needs.
                            </p>
                        </div>
                    </div>
                    <div class="list-columns-left bottom-25">
                        <div>
                            <i class="fa fa-mobile-alt color-highlight fa-3x"></i>
                            <h1 class="bold font-16">Paste your Blocks</h1>
                            <p>
                                Just choose the blocks you like, copy and past them, add your text and that's it!
                            </p>
                        </div>
                    </div>
                    <div class="list-columns-left bottom-25">
                        <div>
                            <i class="far fa-check-circle color-green1-dark fa-3x"></i>
                            <h1 class="bold font-16">Publish your Page</h1>
                            <p>
                                Done with copy pasting? Your mobile site is now ready! Publish it or create an app!
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Testimonial Area-->

            <div data-height="350" class="caption caption-margins round-small shadow-small bottom-30">
                <div class="caption-center content center-text">
                    <p class="color-white opacity-95 font-19 line-height-huge font-300 bottom-10 top-10">
                        This is a great product! Many components that we can use, and I really appreciate the support from
                        Enabled. Very responsive and provides great solutions.
                    </p>
                    <span class="center-text font-12 color-white opacity-50 bottom-30">Envato Customer</span>
                    <a href="#"
                        class="button button-s bg-transparent button-border border-gray1-light color-white round-small bottom-0">View
                        Testimonials</a>
                </div>
                <div class="caption-overlay bg-gradient-highlight opacity-95"></div>
                <div class="caption-bg preload-image" data-src="images/pictures/20.jpg"></div>
            </div>
            <!-- Recent Customers Area-->
            <div class="content-boxed shadow-small bottom-30">
                <div class="content">
                    <h5 class="float-left font-500">Happy Customers</h5>
                    <a href="#" class="float-right opacity-70">View All</a>
                    <div class="clear"></div>
                    <p>
                        Over 30.000 people use our products, and we're always happy to see the positiv impact our products
                        have had! Thank you!
                    </p>
                </div>

                <div class="user-slider owl-carousel bottom-0">
                    <div class="user-follow">
                        <img data-src="images/avatars/1s.png" width="55" height="55"
                            class="owl-lazy shadow-small bg-gradient-blue2">
                        <p>Jane</p>
                    </div>
                    <div class="user-follow">
                        <img data-src="images/avatars/2s.png" width="55" height="55"
                            class="owl-lazy shadow-small bg-gradient-red2">
                        <p>Craig</p>
                    </div>
                    <div class="user-follow">
                        <img data-src="images/avatars/1s.png" width="55" height="55"
                            class="owl-lazy shadow-small bg-gradient-green1">
                        <p>Jane</p>
                    </div>
                    <div class="user-follow">
                        <img data-src="images/avatars/2s.png" width="55" height="55"
                            class="owl-lazy shadow-small bg-gradient-brown1">
                        <p>Craig</p>
                    </div>
                </div>
            </div>


            <!-- Recommemnded Products-->
            <div class="content bottom-10">
                <h5 class="float-left font-500">Products we Love</h5>
                <a href="#" class="float-right opacity-70">View All</a>
                <div class="clear"></div>
            </div>

            <div class="double-slider owl-carousel owl-no-dots bottom-30">
                <div class="item bg-theme round-small shadow-small center-text">
                    <div data-height="200" class="caption bottom-15">
                        <h5 class="caption-bottom color-white bottom-15 center-text">Sticky Mobile</h5>
                        <div class="caption-overlay bg-gradient opacity-70"></div>
                        <div class="caption-bg owl-lazy" data-src="images/pictures/29s.jpg"></div>
                    </div>
                    <p class="boxed-text-huge font-12 bottom-20">
                        Classic, elegant and powerful. A best seller.
                    </p>
                    <a href="#"
                        class="button button-xs bg-highlight button-center-medium round-small shadow-small bottom-20">View</a>
                </div>
                <div class="item bg-theme round-small shadow-small center-text">
                    <div data-height="200" class="caption bottom-15">
                        <h5 class="caption-bottom color-white bottom-15 center-text">Eazy Mobile</h5>
                        <div class="caption-overlay bg-gradient opacity-70"></div>
                        <div class="caption-bg owl-lazy" data-src="images/pictures/18s.jpg"></div>
                    </div>
                    <p class="boxed-text-huge font-12 bottom-20">
                        A best seller, elegant multi use design.
                    </p>
                    <a href="#"
                        class="button button-xs bg-highlight button-center-medium round-small shadow-small bottom-20">View</a>
                </div>
                <div class="item bg-theme round-small shadow-small center-text">
                    <div data-height="200" class="caption bottom-15">
                        <h5 class="caption-bottom color-white bottom-15 center-text">Bars Mobile</h5>
                        <div class="caption-overlay bg-gradient opacity-70"></div>
                        <div class="caption-bg owl-lazy" data-src="images/pictures/11s.jpg"></div>
                    </div>
                    <p class="boxed-text-huge font-12 bottom-20">
                        Modern sidebars and a very intuitive interface.
                    </p>
                    <a href="#"
                        class="button button-xs bg-highlight button-center-medium round-small shadow-small bottom-20">View</a>
                </div>
            </div>

            <!-- Did you know area-->
            <div data-height="130" class="caption caption-margins round-small shadow-small">
                <div class="caption-center content">
                    <h4 class="bottom-5 color-white">Did you know?</h4>
                    <p class="bottom-0 color-white opacity-80">
                        We're the top selling Mobile Author on Envato. We value the quality of products and efficiency of
                        our support!
                    </p>
                </div>
                <div class="caption-overlay bg-gradient-highlight opacity-90"></div>
                <div class="caption-bg owl-lazy" data-src="images/pictures/20.jpg"></div>
            </div>


            <!-- Purchase Today Area-->


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
