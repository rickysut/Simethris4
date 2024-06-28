@extends('t2024.layouts.admin')

@section('styles')
<style>
	/* Gaya Dasar Tombol */
	.android-button {
		display: inline-block;
		padding: 20px 40px; /* Sesuaikan ukuran sesuai kebutuhan */
		font-size: 20px;
		font-weight: bold;
		text-align: center;
		text-decoration: none;
		border-radius: 10px;
		cursor: pointer;
		background-color: #4CAF50; /* Warna latar belakang */
		color: white; /* Warna teks */
		border: none;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
	}

	/* Efek Hover */
	.android-button:hover {
		background-color: #45a049;
	}

	/* Efek Klik */
	.android-button:active {
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
		transform: translateY(2px);
	}
</style>
@endsection

@section('content')
	@can('landing_access')
		@php($unreadmsg = \App\Models\QaTopic::unreadCount())
		@php($msgs = \App\Models\QaTopic::unreadMsg())

		@if (Auth::user()->roles[0]->title == 'Admin' || Auth::user()->roles[0]->title == 'Pejabat' || Auth::user()->roles[0]->title == 'Verifikator')
			{{-- tanam --}}
			@php($cntAjuVerifTanam = \App\Models\AjuVerifTanam::newPengajuanCount())
			@php($getAjuVerifTanam = \App\Models\AjuVerifTanam::getNewPengajuan())
			{{-- produksi --}}
			@php($cntAjuVerifProduksi = \App\Models\AjuVerifProduksi::newPengajuanCount())
			@php($getAjuVerifProduksi = \App\Models\AjuVerifProduksi::getNewPengajuan())
			{{-- skl --}}
			@php($cntAjuVerifSkl = \App\Models\AjuVerifSkl::newPengajuanCount())
			@php($getAjuVerifSkl = \App\Models\AjuVerifSkl::getNewPengajuan())
			@php($cntpengajuan = $cntAjuVerifTanam + $cntAjuVerifProduksi + (Auth::user()->roles[0]->title == 'Admin' ? $cntAjuVerifSkl : 0))
			{{-- rekomendasi --}}
			@php($cntRecomendations = \App\Models\Skl::newPengajuanCount())
			@php($getRecomendations = \App\Models\Skl::getNewPengajuan())

		@else
			@php($cntAjuVerifTanam = 0)
			@php($cntAjuVerifTanam = null)
			@php($cntAjuVerifProduksi = 0)
			@php($getAjuVerifProduksi = null)
			@php($cntAjuVerifSkl = 0)
			@php($getAjuVerifSkl = null)
			@php($cntRecomendations = 0)
			@php($getRecomendations = null)
		@endif

		@if (Auth::user()->roles[0]->title == 'User' || Auth::user()->roles[0]->title == 'Pejabat' )
			@php($getNewSkl = \App\Models\Skl::getNewSkl())
			@php($cntgetNewSkl = \App\Models\SklReads::getNewSklCount())
		@endif

		<div class="row mb-5">
			<div class="col text-center">
				<h2 class="display-4">Hallo, <span class="fw-700">{{ Auth::user()->name }} </span></h2>
				<h4 class="">
					<p class="text-muted">Selamat Datang di Mobile Version</p>
				</h4>
			</div>
		</div>
		<div class="row">
			<a href="javascript:void(0);" class="d-flex flex-row align-items-center">
				<div class="icon-stack display-3 flex-shrink-0">
					<i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
					<i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
				</div>
			</a>
			<div class="col-4 text-center">
				<span class="d-inline-flex flex-column justify-content-center">
					<a href="{{route('2024.verifikator.mobile.findmarker')}}" class="btn-xxl  btn-outline-warning btn-icon waves-effect waves-themed">
						<img src="{{ asset('logoicon.png') }}" alt="" style="width: 5rem; height: 5rem;">
					</a>
					<span>Verifikasi</span>
				</span>
			</div>
			<div class="col-4 text-center">
				<span class="d-inline-flex flex-column justify-content-center ">
					<a href="" class="btn-xxl  btn-outline-warning btn-icon waves-effect waves-themed">
						<img src="{{ asset('favicon.png') }}" alt="" style="width: 5rem; height: 5rem;">
					</a>
					<span>Sample</span>
				</span>
			</div>
			<div class="col-4 text-center">
				<span class="d-inline-flex flex-column justify-content-center">
					<a href="" class="btn-xl btn-outline-warning btn-icon waves-effect waves-themed">
						<img src="{{ asset('favicon.png') }}" alt="" style="width: 3rem; height: 3rem;">
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
		function setScreenSizeAndRedirect() {
			var screenSize = (window.innerWidth <= 992) ? 'mobile' : 'desktop';
			document.cookie = "screen_size=" + screenSize + "; path=/";

			var userRole = "{{ Auth::user()->roles[0]->title }}";

			if (userRole === 'Verifikator') {
				if (screenSize === 'mobile' && window.location.pathname !== '/2024/verifikator/mobile') {
					window.location.href = "/2024/verifikator/mobile";
				} else if (screenSize === 'desktop' && window.location.pathname !== '/2024/verifikator') {
					window.location.href = "/2024/verifikator";
				}
			}
		}

		document.addEventListener('DOMContentLoaded', function() {
			setScreenSizeAndRedirect();
		});

		window.addEventListener('resize', function() {
			setScreenSizeAndRedirect();
		});
	</script>

@endsection
