<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

    @include('t2024.velzonPartials.metahead')

    <body>
		{{-- Begin page --}}
        <div id="layout-wrapper">

			{{-- page topbar --}}
            @include('t2024.velzonPartials.pagetopbar')

            {{-- removeNotificationModal --}}
            @include('t2024.velzonPartials.removeNotificationModal')

            {{-- ========== App Menu ========== --}}
            @include('t2024.velzonPartials.appMenuNavbar')

            {{-- Vertical Overlay. do not remove this!  --}}
            <div class="vertical-overlay"></div>

            {{-- ============================================================== --}}
            {{-- Start right Content here --}}
            {{-- ============================================================== --}}
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        {{-- start page title --}}
						@include('t2024.velzonPartials.pageTitle')

						{{-- start content --}}
						@yield('content')
                    </div>
                </div>

				{{-- footer --}}
				@include('t2024.velzonPartials.footer')
            </div>

        </div>

        {{-- start back-to-top --}}
        @include('t2024.velzonPartials.backToTop')

        {{-- preloader --}}
		@include('t2024.velzonPartials.preloader')

		{{-- customizer-setting --}}
		@include('t2024.velzonPartials.customizer')

        {{-- Theme Settings --}}
		@include('t2024.velzonPartials.themeSettings')

        {{-- script --}}
		@include('t2024.velzonPartials.mainScript')
		@yield('scripts')
    </body>

</html>
