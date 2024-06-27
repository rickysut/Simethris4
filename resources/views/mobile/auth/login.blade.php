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
                    <img src="{{ asset('/mobile/img/logo-simet.png') }}" alt="simethris" style="width: 60%; height: 60px;">
                </div>
                <form id="js-login" novalidate="" method="POST" action="{{ route('mobile.login') }}">
                    @csrf
                    <div class="input-style has-icon input-style-1 input-required">
                        <i class="input-icon fa fa-user font-12"></i>
                        <span>Username</span>

                        <input id="username" name="username" type="name"
                            class="{{ $errors->has('username') ? ' is-invalid' : '' }}" required
                            autocomplete="{{ trans('global.login_username') }}" autofocus
                            placeholder="{{ trans('global.login_username') }}" value="{{ old('username', null) }}">
                        @if ($errors->has('username'))
                            <div class="color-red2-dark">
                                {{ $errors->first('username') }}
                            </div>

                        @endif
                    </div>
                    <div class="input-style has-icon input-style-1 input-required">
                        <i class="input-icon fa fa-lock font-12"></i>
                        <span>Password</span>
                        
                        <input id="password" name="password" type="password"
                            class="{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                            autocomplete="{{ trans('global.login_password') }}" autofocus
                            placeholder="{{ trans('global.login_password') }}" value="">
                        @if ($errors->has('password'))
                            <div class="color-red2-dark">
                                {{ $errors->first('password') }}
                            </div>  
                        @endif
                        
                    </div>
                    <div class="clear"></div>
                    <button type="submit" style="width: -webkit-fill-available;"
                        class="button  button-margins button-full button-m shadow-large button-round-small bg-twitter top-30 bottom-0">{{ trans('global.login') }}</button>
                    <div class="divider top-30"></div>
                </form>

                <div class="clear"></div>

            </div>
        </div>




    </div>
@endsection
