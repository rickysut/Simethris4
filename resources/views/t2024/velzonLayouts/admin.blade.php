<!doctype html>
<html lang="en" data-layout="semibox" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover" data-sidebar-image="none" data-preloader="disable" data-sidebar-visibility="show" data-layout-style="default" data-bs-theme="light" data-layout-width="fluid" data-layout-position="scrollable">

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
						{{-- @include('t2024.velzonPartials.pageTitle') --}}

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
		{{-- @include('t2024.velzonPartials.customizer') --}}

        {{-- Theme Settings --}}
		{{-- @include('t2024.velzonPartials.themeSettings') --}}

		<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
			{{ csrf_field() }}
		</form>

        {{-- script --}}
		@include('t2024.velzonPartials.mainScript')

		<script type="text/javascript">
			console.log("Init Language");
			if (!$.i18n) {
				initApp.loadScript("/js/i18n/i18n.js",
					function activateLang () {
						$.i18n.init({
							resGetPath: '/media/data/__lng__.json',
							load: 'unspecific',
							fallbackLng: false,
							lng: '{{ app()->getLocale() }}'
						}, function (t){
							$('[data-i18n]').i18n();
							$('[data-lang]').removeClass('active');
							$('[data-lang="{{ app()->getLocale() }}"]').addClass('active');
							console.log("Init language to: " + "{{ app()->getLocale() }}");
						});

					}

				);

			} else {
				i18n.setLng('{{ app()->getLocale() }}', function(){
					$('[data-i18n]').i18n();
					$('[data-lang]').removeClass('active');
					$('[data-lang="{{ app()->getLocale() }}"]').addClass('active');
					console.log("setting language to: " + "{{ app()->getLocale() }}");
				});

			}

			$(document).ready(function() {
				$('.searchable-field').select2({
					minimumInputLength: 3,
					ajax: {
					url: '{{ route("admin.globalSearch") }}',
					dataType: 'json',
					type: 'GET',
					delay: 200,
					templateResult: formatItem,
					templateSelection: formatItemSelection,
					placeholder: "{{ trans('global.search') }} ...",
					data: function(term) {
						return {
						search: term
						};
					},
					results: function(data) {
						return {
						data
						};
					}
					},
					escapeMarkup: function(markup) {
						return markup;
					},
					language: {
						inputTooShort: function(args) {
							var remainingChars = args.minimum - args.input.length;
							var translation = "{{ trans('global.search_input_too_short') }}";

							return translation.replace(':count', remainingChars);
						},
						errorLoading: function() {
							return "{{ trans('global.results_could_not_be_loaded') }}";
						},
						searching: function() {
							return "{{ trans('global.searching') }}";
						},
						noResults: function() {
							return "{{ trans('global.no_results') }}";
						},
					}

				});

				function formatItem(item) {
					if (item.loading) {
					return "{{ trans('global.searching') }}...";
					}
					var markup = "<div class='searchable-link' href='" + item.url + "'>";
					markup += "<div class='searchable-title'>" + item.model + "</div>";
					$.each(item.fields, function(key, field) {
					markup += "<div class='searchable-fields'>" + item.fields_formated[field] + " : " + item[field] + "</div>";
					});
					markup += "</div>";

					return markup;
				}

				function formatItemSelection(item) {
					if (!item.model) {
					return "{{ trans('global.search') }}...";
					}
					return item.model;
				}
				$(document).delegate('.searchable-link', 'click', function() {
					var url = $(this).attr('href');
					window.location = url;
				});
			});
		</script>


		@yield('scripts')
    </body>

</html>
