@extends('mobile.layouts.app')

@section('content')
    <div id="page">

        <div id="page-preloader">
            <div class="loader-main">
                <div class="preload-spinner border-highlight"></div>
            </div>
        </div>

        <div class="page-content">

            {{-- <div data-height="150" class="page-title-bg bg-20"><!-- image --></div> --}}
            {{-- 	<div data-height="150" class="page-title-bg dark-mode-tint"><!-- contrast for dark mode --></div> --}}
            {{-- <div data-height="150" class="page-title-bg opacity-90 bg-highlight"><!-- background color --></div> --}}

            <div class="page-title-small color-white bottom-30">
                
                {{-- 	<a href="#" data-menu="menu-main" class="shadow-huge scale-box bg-fade-gray2-dark"></a> --}}
            </div>

            <div class="cover-wrapper cover-no-buttons">
                <div data-height="cover-title" class="caption caption-margins round-medium bottom-0">
                    <div class="caption-center">

                        <div class="left-50 right-50">
                            <h1 class="color-white center-text uppercase ultrabold fa-3x top-40">LOGIN</h1>
                            <p class="color-highlight center-text font-12 under-heading bottom-30 top-5">
                                Let's get you in your account
                            </p>
                            <div class="input-style input-light has-icon input-style-1 input-required">
                                <i class="input-icon fa fa-user font-11"></i>
                                <span>Username</span>
                                <em>(required)</em>
                                <input type="name" placeholder="Username">
                            </div>
                            <div class="input-style input-light has-icon input-style-1 input-required bottom-30">
                                <i class="input-icon fa fa-lock font-11"></i>
                                <span>Password</span>
                                <em>(required)</em>
                                <input type="password" placeholder="Password">
                            </div>
                            <div class="one-half">
                                <a href="pageapp-register.html" class="font-11 color-white opacity-50">Create Account</a>
                            </div>
                            <div class="one-half last-column">
                                <a href="pageapp-forgot.html" class="text-right font-11 color-white opacity-50">Fogot
                                    Credentials</a>
                            </div>
                            <div class="clear"></div>
                            <a href="#"
                                class="back-button button button-full button-m shadow-large button-round-small bg- top-30 bottom-0">LOGIN</a>
                            <div class="divider top-30"></div>
                            
                        </div>
                    </div>
                    <div class="caption-overlay bg-black opacity-90"></div>
                    <div class="caption-bg" style="background-image:url(images/pictures/29t.jpg)"></div>
                </div>
            </div>

        </div>

        <!--Footer Menu-->
        {{-- <div id="footer-menu" class="footer-menu-5-icons footer-menu-style-1">
            <a href="components.html">
                <i data-feather="heart" data-feather-line="1" data-feather-size="21" data-feather-color="red2-dark"
                    data-feather-bg="red2-fade-light"></i>
                <span>Featurehttps://simethris4.test/mobile/login#s</span>
            </a>
            <a href="media.html">
                <i data-feather="image" data-feather-line="1" data-feather-size="21" data-feather-color="green1-dark"
                    data-feather-bg="green1-fade-light"></i>
                <span>Media</span>
            </a>
            <a href="index.html">
                <i data-feather="home" data-feather-line="1" data-feather-size="21" data-feather-color="blue2-dark"
                    data-feather-bg="blue2-fade-dark"></i>
                <span>Home</span>
            </a>
            <a href="pages.html" class="active-nav4">
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
        <div id="menu-main" class="menu menu-box-right menu-box-detached round-medium" data-menu-active="nav-pages"
            data-menu-width="260" data-menu-effect="menu-over" data-menu-load="menu-main.html">
        </div>

        <!-- Share Menu-->
        <div id="menu-share" class="menu menu-box-bottom menu-box-detached round-medium" data-menu-height="400"
            data-menu-effect="menu-over" data-menu-load="menu-share.html">
        </div>

        <!-- Color Highlights Menu-->
        <div id="menu-highlights" class="menu menu-box-bottom menu-box-detached round-medium" data-menu-height="480"
            data-menu-effect="menu-over" data-menu-load="menu-colors.html">
        </div> --}}



        {{-- <div class="menu-hider"></div> --}}
    </div>
@endsection
