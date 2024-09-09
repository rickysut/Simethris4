@extends('t2024.layouts.admin')

@section('styles')
<style>
	.android-button {
		padding: 10px;
		margin: 10px;
		font-size: 20px;
		font-weight: bold;
		text-align: center;
		border-radius: 10px;
		cursor: pointer;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
	}
</style>
@endsection

@section('content')
	@can('landing_access')
		<div class="row mb-5">
			<div class="col text-center">
				<h2 class="display-4">Hallo, <span class="fw-700">{{ Auth::user()->name }} </span></h2>
				<h4 class="">
					<p class="text-muted">Selamat Datang di Simethris Mobiles</p>
				</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-12 text-center">
				<span class="d-inline-flex flex-column justify-content-center ">
					<a href="javascript:void(0)" class="android-button btn-default">
						<img src="{{ asset('favicon.png') }}" alt="" style="width: 4rem; height: 4rem;">
					</a>
					<span>Verifikasi</span>
				</span>
				<span class="d-inline-flex flex-column justify-content-center">
					<a href="{{route('2024.verifikator.mobile.findmarker')}}" class="btn android-button btn-outline-warning">
						<img src="{{ asset('logoicon.png') }}" alt="" style="width: 4rem; height: 4rem;">
					</a>
					<span>Simulasi</span>
				</span>
				<span class="d-inline-flex flex-column justify-content-center">
					<a href="javascript:void(0)" class="android-button btn-default">
						<img src="{{ asset('favicon.png') }}" alt="" style="width: 4rem; height: 4rem; filter: grayscale(100%)">
					</a>
					<span>Sample</span>
				</span>
			</div>
		</div>
		<!-- Page Content -->
	@endcan
@endsection
@section('scripts')
	@parent

	<script>
		$(document).ready(function() {

			function markAsRead(sklId) {
				var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Ambil token CSRF

				// Kirim permintaan Ajax ke metode controller untuk menandai SKL sebagai sudah dibaca
				$.ajax({
					type: 'POST',
					url: '{{ route('admin.sklReads') }}', // Menggunakan route yang sesuai
					data: {
						skl_id: sklId,
						_token: csrfToken // Sertakan token CSRF di sini
					},
					success: function(response) {
						// Setelah berhasil menandai, buka URL tautan
						window.location.href = event.target.getAttribute('href');
					}
				});
			}

		});
	</script>
	<script>
		// function setScreenSizeAndRedirect() {
		// 	var screenSize = (window.innerWidth <= 992) ? 'mobile' : 'desktop';
		// 	document.cookie = "screen_size=" + screenSize + "; path=/";

		// 	var userRole = "{{ Auth::user()->roles[0]->title }}";

		// 	if (userRole === 'Verifikator') {
		// 		if (screenSize === 'mobile' && window.location.pathname !== '/2024/verifikator/mobile') {
		// 			window.location.href = "/2024/verifikator/mobile";
		// 		} else if (screenSize === 'desktop' && window.location.pathname !== '/2024/verifikator') {
		// 			window.location.href = "/2024/verifikator";
		// 		}
		// 	}
		// }

		// document.addEventListener('DOMContentLoaded', function() {
		// 	setScreenSizeAndRedirect();
		// });

		// window.addEventListener('resize', function() {
		// 	setScreenSizeAndRedirect();
		// });
	</script>

@endsection
