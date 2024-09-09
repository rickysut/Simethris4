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
		@php($unreadmsg = \App\Models\QaTopic::unreadCount())
		@php($msgs = \App\Models\QaTopic::unreadMsg())

		@if (Auth::user()->roles[0]->title == 'Admin' || Auth::user()->roles[0]->title == 'Pejabat' || Auth::user()->roles[0]->title == 'Verifikator')
			@php($cntAjuVerifikasi = \App\Models2024\AjuVerifikasi::newPengajuanCount())
			@php($getAjuVerifikasi = \App\Models2024\AjuVerifikasi::getNewPengajuan())
			{{-- skl --}}
			@php($cntAjuVerifSkl = \App\Models2024\AjuVerifSkl::newPengajuanCount())
			@php($getAjuVerifSkl = \App\Models2024\AjuVerifSkl::getNewPengajuan())
			@php($cntpengajuan = $cntAjuVerifikasi + (Auth::user()->roles[0]->title == 'Admin' ? $cntAjuVerifSkl : 0))
			{{-- rekomendasi --}}
			@php($cntRecomendations = \App\Models\Skl::newPengajuanCount())
			@php($getRecomendations = \App\Models\Skl::getNewPengajuan())

		@else
			@php($cntAjuVerifikasi = 0)
			@php($cntAjuVerifikasi = null)
			@php($cntAjuVerifSkl = 0)
			@php($getAjuVerifSkl = null)
			@php($cntRecomendations = 0)
			@php($getRecomendations = null)
		@endif

		@if (Auth::user()->roles[0]->title == 'User' || Auth::user()->roles[0]->title == 'Pejabat' )
			@php($getNewSkl = \App\Models\Skl::getNewSkl())
			@php($cntgetNewSkl = \App\Models\SklReads::getNewSklCount())
		@endif

		<div class="row mb-5 hidden-md-down">
			<div class="col text-center">
				<h1 class="hidden-md-down">Selamat Datang di Simethris,</h1><br>
				<span class="display-4 fw-700 hidden-md-down">{{ Auth::user()->data_user->company_name ?? Auth::user()->name }}</span>
				<h2 class="display-4 hidden-sm-up">Hallo, <span class="fw-700">{{ Auth::user()->name }}</span></h2>
				<h4 class="hidden-md-down">
					<p class="text-muted">{!! $quote !!}</p>
				</h4>
			</div>
		</div>

		{{-- @if (Auth::user()->roles[0]->title == 'User') --}}
			{{-- <div class="row mb-5">
				<div class="col-12">
					<div class="alert alert-info fade show" role="alert">
						<div class="d-flex align-items-top">
							<div class="alert-icon">
								<span class="icon-stack icon-stack-md">
									<i class="base-2 icon-stack-3x color-info-400"></i>
									<i class="base-10 text-white icon-stack-1x"></i>
									<i class="fal fa-info-circle color-info-800 icon-stack-2x"></i>
								</span>
							</div>
							<div class="flex-1">
								<span class="h3">Informasi!!</span>
								<br>
									Pemeliharaan telah selesai. Anda dapat melanjutkan kembali.
								<br><br>
								Terima Kasih.
								<br><br>
								<strong>Administrator.</strong> at <span class="nav-link-text js-get-date"></span>
							</div>
						</div>
					</div>
				</div>
			</div> --}}
		{{-- @endif --}}

		@if (Auth::user()->roles[0]->title == 'Pejabat')
			@if (!$profile || (!$profile->jabatan || !$profile->nip))
				<div class="row mb-5">
					<div class="col-md">
					<div class="alert alert-danger">
						<div class="d-flex flex-start w-100">
							<div class="mr-2 hidden-md-down">
								<span class="icon-stack icon-stack-lg">
									<i class="base base-7 icon-stack-3x opacity-100 color-error-500"></i>
									<i class="base base-7 icon-stack-2x opacity-100 color-error-300 fa-flip-vertical"></i>
									<i class="fas fa-exclamation icon-stack-1x opacity-100 color-white"></i>
								</span>
							</div>
							<div class="d-flex flex-fill">
								<div class="flex-fill">
									<span class="h5">Perhatian</span>
									<p>
										Anda belum melengkapi data Profile Pejabat. Silahkan lengkapi <a href="{{ route('admin.profile.pejabat') }}" class="fw-500 text-uppercase">di sini</a>.
									</p>
								</div>
							</div>
						</div>
					</div>

					</div>
				</div>
			@endif
		@endif
		<!-- Page Content Daftar Pesan baru dan Pengajuan -->
		<div class="row hidden-md-down">
			<div class="col-lg-6">
				{{-- <div id="panel-1" class="panel">
					<div class="panel-hdr">
						<h2>
							<i class="subheader-icon fal fa-rss mr-1 text-muted"></i>
							<span class="text-primary fw-700" style="text-transform: uppercase">BERITA</span>
						</h2>
						<div class="panel-toolbar">
							<a href="{{ route('admin.posts.index') }}" data-toggle="tooltip" title
								data-original-title="Lihat semua Feeds" class="btn btn-xs btn-primary waves-effect waves-themed"
								type="button" href="/">Lihat semua</a>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content p-0">
							<ul class="notification">
								@foreach ($posts as $post)
									<li>
										<a href="{{ route('admin.posts.show', $post['id']) }}"
											class="d-flex align-items-center">

											<span class="d-flex flex-column flex-1 ml-1">
												<span class="fw-700 fs-md text-primary" style="text-transform: uppercase">
													{{ $post['title'] }}
												</span>
												<span class="name fs-xs text-muted small mb-2">
													create by: {{ $post->user->name }} |
													@if ($post['created_at']->isToday())
														@if ($post['created_at']->diffInHours(date('Y-m-d H:i:s')) > 1)
															<span
																class="fs-nano text-muted mt-1">{{ $post['created_at']->diffInHours(date('Y-m-d H:i:s')) }}
																jam yang lalu</span>
														@else
															@if ($post['created_at']->diffInMinutes(date('Y-m-d H:i:s')) > 1)
																<span
																	class="fs-nano text-muted mt-1">{{ $post['created_at']->diffInMinutes(date('Y-m-d H:i:s')) }}
																	menit yang lalu</span>
															@else
																<span
																	class="fs-nano text-muted mt-1">{{ $post['created_at']->diffInSeconds(date('Y-m-d H:i:s')) }}
																	detik yang lalu</span>
															@endif
														@endif
													@else
														@if ($post['created_at']->isYesterday())
															<span class="fs-nano text-muted mt-1">Kemarin</span>
														@else
															<span
																class="fs-nano text-muted mt-1">{{ $post['created_at'] }}</span>
														@endif
													@endif
													| {{ ($post->category->name ?? '') }}
												</span>
												<span class="text-muted">{{ $post['exerpt'] }}</span>
											</span>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div> --}}
				<div id="panel-2" class="panel">
					<div class="panel-hdr">
						<h2>
							<i class="subheader-icon fal fa-envelope mr-1"></i><span class="color-warning-700 fw-700"
								style="text-transform:uppercase">Pesan baru</span>
						</h2>
						<div class="panel-toolbar">
							<a href="{{ route('admin.messenger.index') }}" data-toggle="tooltip" title
								data-original-title="Lihat semua pesan" class="btn btn-xs btn-warning waves-effect waves-themed"
								type="button" href="/">Lihat</a>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content p-0">
							<ul class="notification">
								@foreach ($msgs as $item)
									<li>
										<a href="{{ route('admin.messenger.showMessages', $item['id']) }}"
											class="d-flex align-items-center">
											<span class="mr-2">
												@php($user = \App\Models\User::getUserById($item['sender']))
												@if (!empty($user[0]->data_user->logo))
													<img src="{{ Storage::disk('public')->url($user[0]->data_user->logo) }}"
														class="profile-image rounded-circle" alt="">
												@else
													<img src="{{ asset('/img/favicon.png') }}"
														class="profile-image rounded-circle" alt="">
												@endif
											</span>
											<span class="d-flex flex-column flex-1 ml-1">
												<span class="name">{{ $user[0]->name }}<span
														class="badge badge-danger fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
												<span class="msg-a fs-sm">{{ $item['subject'] }}</span>
												<span class="msg-b fs-xs">{{ $item['content'] }}</span>
												@if ($item['create_at']->isToday())
													@if ($item['create_at']->diffInHours(date('Y-m-d H:i:s')) > 1)
														<span
															class="fs-nano text-muted mt-1">{{ $item['create_at']->diffInHours(date('Y-m-d H:i:s')) }}
															jam yang lalu</span>
													@else
														@if ($item['create_at']->diffInMinutes(date('Y-m-d H:i:s')) > 1)
															<span
																class="fs-nano text-muted mt-1">{{ $item['create_at']->diffInMinutes(date('Y-m-d H:i:s')) }}
																menit yang lalu</span>
														@else
															<span
																class="fs-nano text-muted mt-1">{{ $item['create_at']->diffInSeconds(date('Y-m-d H:i:s')) }}
																detik yang lalu</span>
														@endif
													@endif
												@else
													@if ($item['create_at']->isYesterday())
														<span class="fs-nano text-muted mt-1">Kemarin</span>
													@else
														<span class="fs-nano text-muted mt-1">{{ $item['create_at'] }}</span>
													@endif
												@endif
											</span>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				@if (Auth::user()->roles[0]->title == 'Admin')
					<div id="panel-2" class="panel">
						<div class="panel-hdr">
							<h2>
								<i class="subheader-icon fal fa-ballot-check mr-1"></i>
								<span class="text-info fw-700 text-uppercase">
									Pengajuan Verifikasi
								</span>
							</h2>
							<div class="panel-toolbar">
								@if ($cntpengajuan)
									<a href="javascript:void(0);" class="mr-1 btn btn-danger btn-xs waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Anda memiliki {{$cntpengajuan}} Pengajuan Verifikasi">
										{{$cntpengajuan}}
									</a>
								@else
								@endif
							</div>
						</div>
						<div class="panel-container collapse">
							<div class="panel-content p-0">
								<ul class="notification">
									@foreach ($getAjuVerifikasi as $item)
										<li>
											<a href="{{ route('2024.admin.pengajuan.assignment', [(preg_replace('/[^a-zA-Z0-9]/', '', $item->no_ijin)), $item->tcode]) }}"  class="d-flex align-items-center show-child-on-hover">
												<span class="mr-2">
													@if (!empty($item->data_user->logo))
														<img src="{{ Storage::disk('public')->url($item->data_user->logo) }}"
															class="profile-image rounded-circle" alt="">
													@else
														<img src="{{ asset('/img/avatars/farmer.png') }}"
															class="profile-image rounded-circle" alt="">
													@endif
												</span>
												<span class="d-flex flex-column flex-1">
													<span class="name">{{ $item->datauser->company_name }} <span
														class="badge badge-success fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
													<span class="msg-a fs-sm">
														<span class="badge badge-{{ $item->kind === 'TANAM' ? 'success' : 'warning' }}">{{$item->kind}}</span>
													</span>
													<span class="fs-nano text-muted mt-1">{{ $item->created_at->diffForHumans() }}</span>
												</span>
											</a>
										</li>
									@endforeach
									@if (Auth::user()->roles[0]->title == 'Admin')
										@foreach ($getAjuVerifSkl as $item)
											<li>
												<a href="{{ route('verification.skl.check', [$item->id]) }}"  class="d-flex align-items-center show-child-on-hover">
													<span class="mr-2">
														@if (!empty($item->data_user->logo))
															<img src="{{ Storage::disk('public')->url($item->data_user->logo) }}"
																class="profile-image rounded-circle" alt="">
														@else
															<img src="{{ asset('/img/avatars/farmer.png') }}"
																class="profile-image rounded-circle" alt="">
														@endif
													</span>
													<span class="d-flex flex-column flex-1">
														<span class="name">{{ $item->datauser->company_name }} <span
															class="badge badge-danger fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
														<span class="msg-a fs-sm">
															<span class="badge badge-danger">Penerbitan SKL</span>
														</span>
														<span class="fs-nano text-muted mt-1">{{ $item->created_at->diffForHumans() }}</span>
													</span>
												</a>
											</li>
										@endforeach
									@endif
								</ul>
							</div>
						</div>
					</div>
				@endif
				@if (Auth::user()->roles[0]->title == 'Pejabat')
					<div id="panel-3" class="panel">
						<div class="panel-hdr">
							<h2>
								<i class="subheader-icon fal fa-file-certificate mr-1"></i>
								<span class="text-primary fw-700 text-uppercase">
									Permohonan Penerbitan SKL
								</span>
							</h2>
							<div class="panel-toolbar">
								@if ($cntRecomendations > 0)
									<a href="javascript:void(0);" class="mr-1 btn btn-danger btn-xs waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Terdapat {{$cntRecomendations}} Rekomendasi Penerbitan yang perlu Anda tindaklanjuti.">
										{{$cntRecomendations}}
									</a>
								@else
								@endif
							</div>
						</div>
						<div class="panel-container collapse">
							<div class="panel-content p-0">
								<ul class="notification">
									@foreach ($getRecomendations as $item)
										<li>
											<a href="{{ route('verification.skl.recomendation.show', [$item->id]) }}"  class="d-flex align-items-center show-child-on-hover">
												<span class="mr-2">
													@if (!empty($item->data_user->logo))
														<img src="{{ Storage::disk('public')->url($item->data_user->logo) }}"
															class="profile-image rounded-circle" alt="">
													@else
														<img src="{{ asset('/img/avatars/farmer.png') }}"
															class="profile-image rounded-circle" alt="">
													@endif
												</span>
												<span class="d-flex flex-column flex-1">
													<span class="name">{{ $item->datauser->company_name }} <span
														class="badge badge-success fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
													<span class="msg-a fs-sm">
														<span class="badge badge-success">Direkomendasikan</span>
													</span>
													<span class="fs-nano text-muted mt-1">{{ $item->created_at->diffForHumans() }}</span>
												</span>
											</a>
										</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
					<div id="panel-3" class="panel">
						<div class="panel-hdr">
							<h2>
								<i class="subheader-icon fal fa-file-certificate mr-1"></i>
								<span class="text-primary fw-700 text-uppercase">
									SKL Terbit
								</span>
							</h2>
							<div class="panel-toolbar">
								@if ($cntgetNewSkl > 0)
									<a href="javascript:void(0);" class="mr-1 btn btn-danger btn-xs waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Terdapat {{$cntgetNewSkl}} SKL baru diterbitkan.">
										{{$cntgetNewSkl}}
									</a>
								@else
								@endif
							</div>
						</div>
						<div class="panel-container collapse">
							<div class="panel-content p-0">
								<ul class="notification">
									@foreach ($getNewSkl as $item)
										<li>
											<a href="{{route('skl.arsip')}}" onClick="markAsRead({{ $item->id }})" class="d-flex align-items-center show-child-on-hover">
												<span class="mr-2">
													<i class="fal fa-award fa-4x text-success"></i>
												</span>
												<span class="d-flex flex-column flex-1">
													<span class="name">{{ $item->no_ijin }} <span
														class="badge badge-success fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
													<span class="msg-a fs-sm">
														<span class="badge badge-success">TERBIT!</span>
													</span>

													<span class="fs-nano text-muted mt-1">{{ $item->published_date->format('d F Y') }} ({{ $item->published_date->diffForHumans() }})</span>
												</span>
											</a>
										</li>

									@endforeach
								</ul>
							</div>
						</div>
					</div>
					<div id="panel-4" class="panel" hidden>
						<div class="panel-hdr">
							<h2>
								<i class="subheader-icon fal fa-ballot-check mr-1"></i>
								<span class="text-info fw-700 text-uppercase">
									Pengajuan Verifikasi
								</span>
							</h2>
							<div class="panel-toolbar">
								@if ($cntAjuVerifikasi > 0 || $cntAjuVerifSkl > 0)
									<a href="javascript:void(0);" class="mr-1 btn btn-danger btn-xs waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Terdapat {{$cntAjuVerifikasi + $cntAjuVerifSkl}} Pengajuan Verifikasi yang perlu ditindaklanjut oleh para Verifikator.">
										{{$cntAjuVerifikasi + $cntAjuVerifSkl}}
									</a>
								@else
								@endif
							</div>
						</div>
						<div class="panel-container collapse">
							<div class="panel-content p-0">
								<ul class="notification">
									<li>
										<span class="d-flex align-items-center justify-content-between show-child-on-hover">
											<span>Verifikasi</span>
											<span>{{$cntAjuVerifikasi}} ajuan</span>
										</span>
									</li>
									<li>
										<span class="d-flex align-items-center justify-content-between show-child-on-hover">
											<span>Penerbitan SKL</span>
											<span>{{$cntAjuVerifSkl}} ajuan</span>
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				@endif
				@if (Auth::user()->roles[0]->title == 'User')
					<div id="panel-3" class="panel">
						<div class="panel-hdr">
							<h2>
								<i class="subheader-icon fal fa-file-certificate mr-1"></i>
								<span class="text-primary fw-700 text-uppercase">
									SKL Baru Terbit
								</span>
							</h2>
							<div class="panel-toolbar">
								@if ($cntgetNewSkl > 0)
									<a href="javascript:void(0);" class="mr-1 btn btn-danger btn-xs waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Terdapat {{$cntgetNewSkl}} SKL baru diterbitkan.">
										{{$cntgetNewSkl}}
									</a>
								@else
								@endif
							</div>
						</div>
						<div class="panel-container collapse">
							<div class="panel-content p-0">
								<ul class="notification">
									@foreach ($getNewSkl as $item)
										<li>
											<a href="{{route('admin.task.skl.arsip')}}" class="d-flex align-items-center show-child-on-hover">
												<span class="mr-2">
													<i class="fal fa-award fa-4x text-success"></i>
												</span>
												<span class="d-flex flex-column flex-1">
													<span class="name">{{ $item->no_ijin }} <span
														class="badge badge-success fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
													<span class="msg-a fs-sm">
														<span class="badge badge-success">TERBIT!</span>
													</span>

													<span class="fs-nano text-muted mt-1">{{ $item->published_date->format('d F Y') }} ({{ $item->published_date->diffForHumans() }})</span>
												</span>
											</a>
										</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
		<!-- Page Content Khusus Mobile-->
		<div class="hidden-sm-up">
			<div class="row mb-5">
				<div class="col text-center">
					<h2 class="display-4">Hallo, <span class="fw-700">{{ Auth::user()->name }} </span></h2>
					<h4 class="">
						<p class="text-muted">Selamat Datang di Simethris Mobile</p>
					</h4>
					<p id="gpstatus"></p>
				</div>
			</div>
			@can('verificator_task_access')
				<div class="row">
					<div class="col-12 text-center">
						<h3>Menu Verifikasi</h3>
						<span class="d-inline-flex flex-column justify-content-center ">
							<a href="{{ route('2024.verifikator.tanam.home') }}" class="btn android-button btn-outline-warning">
								<img src="{{ asset('logoicon.png') }}" alt="" style="width: 4rem; height: 4rem;">
							</a>
							<span>Tanam</span>
						</span>
						<span class="d-inline-flex flex-column justify-content-center ">
							<a href="{{ route('2024.verifikator.produksi.home') }}" class="btn android-button btn-outline-warning">
								<img src="{{ asset('logoicon.png') }}" alt="" style="width: 4rem; height: 4rem;">
							</a>
							<span>Produksi</span>
						</span>
						<span class="d-inline-flex flex-column justify-content-center">
							<a href="{{route('2024.verifikator.mobile.findmarker')}}" class="android-button btn-outline-warning">
								<img src="{{ asset('favicon.png') }}" alt="" style="width: 4rem; height: 4rem; filter: grayscale(100%)">
							</a>
							<span>Simulasi Jarak</span>
						</span>
					</div>
				</div>
			@endcan
			@can('user_task_access')
				<div class="row">
					<div class="col-12 text-center">
						<span class="d-inline-flex flex-column justify-content-center">
							<a href="{{route('2024.user.mobile.findmarker')}}" class="btn android-button btn-outline-warning">
								<img src="{{ asset('logoicon.png') }}" alt="" style="width: 4rem; height: 4rem;">
							</a>
							<span>Realisasi Tanam</span>
						</span>
						<span class="d-inline-flex flex-column justify-content-center ">
							<a href="javascript:void(0)" class="android-button btn-default">
								<img src="{{ asset('favicon.png') }}" alt="" style="width: 4rem; height: 4rem; filter: grayscale(100%)">
							</a>
							<span>Sample</span>
						</span>
						<span class="d-inline-flex flex-column justify-content-center">
							<a href="javascript:void(0)" class="android-button btn-default">
								<img src="{{ asset('favicon.png') }}" alt="" style="width: 4rem; height: 4rem; filter: grayscale(100%)">
							</a>
							<span>Sample</span>
						</span>
					</div>
				</div>
			@endcan
		</div>
	@endcan
@endsection
@section('scripts')
	@parent

	<script>
		$(document).ready(function() {
			checkGeolocationPermission();
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

		function checkGeolocationPermission() {
            if (!navigator.geolocation) {
                console.log("Geolocation is not supported by this browser.");
                $('#gpstatus').html('Perangkat <span class="text-danger font-weight-bold">Tidak mendukung</span> Fitur ini.');
                return;
            }

            navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                if (permissionStatus.state === 'granted') {
                    getCurrentPosition();
                } else if (permissionStatus.state === 'prompt') {
                    getCurrentPosition();
                } else {
                    $('#gpstatus').html('GPS status <span class="text-danger font-weight-bold">Tidak Diijinkan</span>. Mohon izinkan akses lokasi untuk menggunakan fitur ini.');
                    // Optionally, you could provide a button or a link to open browser settings
                }

                permissionStatus.onchange = function() {
                    if (permissionStatus.state === 'granted') {
                        getCurrentPosition();
                    } else {
                        $('#gpstatus').html('GPS status <span class="text-danger font-weight-bold">Tidak Diijinkan</span>. Mohon izinkan akses lokasi untuk menggunakan fitur ini.');
                    }
                };
            });
        }

		function getCurrentPosition() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    console.log("Latitude: " + position.coords.latitude);
                    console.log("Longitude: " + position.coords.longitude);

                    var thisLat = position.coords.latitude;
                    var thisLong = position.coords.longitude;
                    $('#latitude').val(thisLat);
                    $('#longitude').val(thisLong);
                    $('#gpstatus').html('GPS status <span class="text-success font-weight-bold">Aktif</span>');

                    initMap(thisLat, thisLong);
                },
                function(error) {
                    console.error("Error Code = " + error.code + " - " + error.message);
                    $('#gpstatus').html('GPS status <span class="text-danger font-weight-bold">Tidak Aktif/Tidak Diijinkan</span>');
                }
            );
        }
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
