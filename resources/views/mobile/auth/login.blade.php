@extends('mobile.layouts.app')

@section('content')
    <div class="page-content">

        <div data-height="150" class="page-title-bg bg-20" style="height: 150px;"><!-- image --></div>
        <div data-height="150" class="page-title-bg dark-mode-tint" style="height: 150px;"><!-- contrast for dark mode -->
        </div>
        <div data-height="150" class="page-title-bg opacity-90 bg-highlight" style="height: 150px;"><!-- background color -->
        </div>

        <div class="page-title-small color-white bottom-30">
            <h1></h1>
            {{-- <a  data-menu="menu-main" class="shadow-huge scale-box bg-fade-gray2-dark"></a> --}}
        </div>



        <div class="content-boxed content-boxed-full left-20 right-20 shadow-large">
            <div class="content top-60 bottom-20">
                <div class="title-center" style="display: ruby-text; align-items: center">
                    <img src="/img/favicon.png" alt="">
                </div>
                <div class="input-style has-icon input-style-1 input-required">
                    <i class="input-icon fa fa-user font-12"></i>
                    <span>Username</span>
                    <em>(required)</em>
                    <input type="name" placeholder="Username">
                </div>
                <div class="input-style has-icon input-style-1 input-required">
                    <i class="input-icon fa fa-lock font-12"></i>
                    <span>Password</span>
                    <em>(required)</em>
                    <input type="password" placeholder="Password">
                </div>
                <div class="clear"></div>
                <a href="#"
                    class="button button-full button-m shadow-large button-round-small bg-green1-dark top-30 bottom-0">LOGIN</a>
                <div class="divider top-30"></div>
                
                {{-- <div class="one-half">
                    <a href="pageapp-register.html" class="font-11 color-theme opacity-50">Create Account</a>
                </div>
                <div class="one-half last-column">
                    <a href="pageapp-forgot.html" class="text-right font-11 color-theme opacity-50">Fogot Credentials</a>
                </div> --}}
                <div class="clear"></div>

            </div>
        </div>




    </div>
@endsection
