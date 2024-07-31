@extends('t2024.layouts.admin')
@section('styles')

<style>
	.middle-align tbody td {
		vertical-align: middle;
	}
</style>

<link rel="stylesheet" media="screen, print" href="{{ asset('css/miscellaneous/lightgallery/lightgallery.bundle.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

{{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
@endsection

@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('online_access')
	<div class="row d-flex align-items-start">
		<div class="col-md-4">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>
						<span class="text-muted fw-300">Data Lokasi </span>
						<span class="fw-600">
							{{$data['spatial']->kode_spatial}}</i>
						</span>
					</h2>
					<div class="panel-toolbar">
						@include('t2024.partials.globaltoolbar')
					</div>
				</div>


				<div class="panel-container">
					<div id="myMap" cl style="height: 370px; width: 100%;"></div>
					<div class="panel-content">
						<ul class="list-group">
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">Pemilik/Pengelola</span>
								<span class="fw-500">{{$data['spatial']->nama_petani}}</span>
							</li>
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">NIK Pemilik/Pengelola</span>
								<span class="fw-500">{{$data['spatial']->ktp_petani}}</span>
							</li>
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">Luas Lahan (m2)</span>
								<span class="fw-500">{{$data['spatial']->luas_lahan}}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel" id="panel-2">
				<div class="panel-hdr">
					<h2>Log Kegiatan</h2>
					<div class="panel-toolbar">
						@include('t2024.partials.globaltoolbar')
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<input type="hidden" name="form_action" value="form1">
						<input type="hidden" name="no_ijin" value="{{$data['pks']->no_ijin}}">
						<input type="hidden" name="anggota_id" value="{{$data['spatial']->kode_spatial}}">
						<input type="hidden" value="{{$data['spatial']->latitude}}" name="latitude" id="latitude" readonly>
						<input type="hidden" value="{{$data['spatial']->longitude}}" name="longitude" id="longitude" readonly/>
						<input type="hidden" value="{{$data['spatial']->polygon}}" name="polygon" id="polygon" readonly>
						<div class="accordion accordion-hover" id="logKeg"> {{--  accordion-outline --}}
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->lahanStatus !== null) bg-success-50 @endif" data-toggle="collapse" data-target="#logKeg-lahan" aria-expanded="false">
										<i class="fal fa-shovel width-2 fs-xl"></i>
										Pengolahan Lahan
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-lahan" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->lahanfoto ? asset("storage/{$data['lokasi']->lahanfoto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->lahandate}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->lahancomment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="lahanStatusSesuai" name="lahanStatus" value="1"
																		@if ($data['lokasi']->lahanStatus == 1) checked @endif>
																	<label class="custom-control-label" for="lahanStatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="lahanStatusTidakSesuai" name="lahanStatus" value="0"
																		@if ($data['lokasi']->lahanStatus === 0) checked @endif>
																	<label class="custom-control-label" for="lahanStatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->benihStatus !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-benih" aria-expanded="false">
										<i class="fal fa-seedling width-2 fs-xl"></i>
										Persiapan Benih
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-benih" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->benihFoto ? asset("storage/{$data['lokasi']->benihFoto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->benihDate}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Volume (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->benihsize}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->benihComment}}</p>
													</li>

													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="benihStatusSesuai" name="benihStatus" value="1"
																		@if ($data['lokasi']->benihStatus == 1) checked @endif>
																	<label class="custom-control-label" for="benihStatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="benihStatusTidakSesuai" name="benihStatus" value="0"
																		@if ($data['lokasi']->benihStatus === 0) checked @endif>
																	<label class="custom-control-label" for="benihStatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->mulsaStatus !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-mulsa" aria-expanded="false">
										<i class="fal fa-blanket width-2 fs-xl"></i>
										Pemasangan Mulsa
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-mulsa" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->mulsaFoto ? asset("storage/{$data['lokasi']->mulsaFoto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->mulsaDate}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->mulsaComment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Jumlah (Roll)</span>
														<span class="ml-auto">{{$data['lokasi']->mulsaSize}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="mulsaStatusSesuai" name="mulsaStatus" value="1"
																		@if ($data['lokasi']->mulsaStatus == 1) checked @endif>
																	<label class="custom-control-label" for="mulsaStatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="mulsaStatusTidakSesuai" name="mulsaStatus" value="0"
																		@if ($data['lokasi']->mulsaStatus === 0) checked @endif>
																	<label class="custom-control-label" for="mulsaStatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->tanamStatus !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-tanam" aria-expanded="false">
										<i class="fal fa-hand-holding-seedling width-2 fs-xl"></i>
										Penanaman
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-tanam" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->tanamFoto ? asset("storage/{$data['lokasi']->tanamFoto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->tgl_tanam}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Luas Tanam (m2)</span>
														<span class="ml-auto">{{$data['lokasi']->luas_tanam}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->tanamComment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="tanamStatusSesuai" name="tanamStatus" value="1"
																		@if ($data['lokasi']->tanamStatus == 1) checked @endif>
																	<label class="custom-control-label" for="tanamStatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="tanamStatusTidakSesuai" name="tanamStatus" value="0"
																		@if ($data['lokasi']->tanamStatus === 0) checked @endif>
																	<label class="custom-control-label" for="tanamStatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->pupuk1Status !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-pupuk-1" aria-expanded="false">
										<i class="fal fa-vial width-2 fs-xl"></i>
										Pemupukan Pertama
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-pupuk-1" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->pupuk1Foto ? asset("storage/{$data['lokasi']->pupuk1Foto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->pupuk1Date}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Pupuk Organik (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->organik1}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">NPK (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->npk1}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Dolomit (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->dolomit1}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">ZA (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->za1}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->pupuk1Comment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="pupuk1StatusSesuai" name="pupuk1Status" value="1"
																		@if ($data['lokasi']->pupuk1Status == 1) checked @endif>
																	<label class="custom-control-label" for="pupuk1StatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="pupuk1StatusTidakSesuai" name="pupuk1Status" value="0"
																		@if ($data['lokasi']->pupuk1Status === 0) checked @endif>
																	<label class="custom-control-label" for="pupuk1StatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->pupuk2Status !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-pupuk-2" aria-expanded="false">
										<i class="fal fa-vials width-2 fs-xl"></i>
										Pemupukan Kedua
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-pupuk-2" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->pupuk2Foto ? asset("storage/{$data['lokasi']->pupuk2Foto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->pupuk2Date}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Pupuk Organik (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->organik2}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">NPK (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->npk2}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Dolomit (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->dolomit2}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">ZA (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->za2}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->pupuk2Comment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="pupuk2StatusSesuai" name="pupuk2Status" value="1"
																		@if ($data['lokasi']->pupuk2Status == 1) checked @endif>
																	<label class="custom-control-label" for="pupuk2StatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="pupuk2StatusTidakSesuai" name="pupuk2Status" value="0"
																		@if ($data['lokasi']->pupuk2Status === 0) checked @endif>
																	<label class="custom-control-label" for="pupuk2StatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->pupuk3Status !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-pupuk-3" aria-expanded="false">
										<i class="fal fa-fill-drip width-2 fs-xl"></i>
										Pemupukan Ketiga
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-pupuk-3" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->pupuk3Foto ? asset("storage/{$data['lokasi']->pupuk3Foto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->pupuk3Date}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Pupuk Organik (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->organik3}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">NPK (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->npk1}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Dolomit (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->dolomit3}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">ZA (kg)</span>
														<span class="ml-auto">{{$data['lokasi']->za3}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->pupuk3Comment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="pupuk3StatusSesuai" name="pupuk3Status" value="1"
																		@if ($data['lokasi']->pupuk3Status == 1) checked @endif>
																	<label class="custom-control-label" for="pupuk3StatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="pupuk3StatusTidakSesuai" name="pupuk3Status" value="0"
																		@if ($data['lokasi']->pupuk3Status === 0) checked @endif>
																	<label class="custom-control-label" for="pupuk3StatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->optStatus !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-OPT" aria-expanded="false">
										<i class="fal fa-shield-virus width-2 fs-xl"></i>
										Pengendalian OPT
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-OPT" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->optFoto ? asset("storage/{$data['lokasi']->optFoto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->optDate}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->optComment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="optStatusSesuai" name="optStatus" value="1"
																		@if ($data['lokasi']->optStatus == 1) checked @endif>
																	<label class="custom-control-label" for="optStatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="optStatusTidakSesuai" name="optStatus" value="0"
																		@if ($data['lokasi']->optStatus === 0) checked @endif>
																	<label class="custom-control-label" for="optStatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->prodStatus !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-produksi" aria-expanded="false">
										<i class="fal fa-dolly width-2 fs-xl"></i>
										Produksi/Panen
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-produksi" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->prodFoto ? asset("storage/{$data['lokasi']->prodFoto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Tanggal</span>
														<span class="ml-auto">{{$data['lokasi']->prodDate}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Volume Panen</span>
														<span class="ml-auto">{{$data['lokasi']->volume}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->prodComment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="prodStatusSesuai" name="prodStatus" value="1"
																		@if ($data['lokasi']->prodStatus == 1) checked @endif>
																	<label class="custom-control-label" for="prodStatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="prodStatusTidakSesuai" name="prodStatus" value="0"
																		@if ($data['lokasi']->prodStatus === 0) checked @endif>
																	<label class="custom-control-label" for="prodStatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed @if($data['lokasi']->distStatus !== null) bg-success-50 @endif"" data-toggle="collapse" data-target="#logKeg-distribusi" aria-expanded="false">
										<i class="fal fa-truck-container width-2 fs-xl"></i>
										Distribusi Hasil
										<span class="ml-auto">
											<span class="collapsed-reveal">
												<i class="fal fa-chevron-up fs-xl"></i>
											</span>
											<span class="collapsed-hidden">
												<i class="fal fa-chevron-down fs-xl"></i>
											</span>
										</span>
									</a>
								</div>
								<div id="logKeg-distribusi" class="collapse" data-parent="#logKeg" style="">
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
														background-image: url('{{ $data['lokasi']->distFoto ? asset("storage/{$data['lokasi']->distFoto}") : asset("img/posts_img/default-post-image-light.svg") }}');
														background-size: cover; background-repeat: no-repeat; background-position: center;">
													</div>
												</div>
											</div>
											<div class="col-md-8">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Untuk Benih</span>
														<span class="ml-auto">{{$data['lokasi']->vol_benih}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between">
														<span class="mr-auto">Untuk Dijual</span>
														<span class="ml-auto">{{$data['lokasi']->vol_jual}}</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start">
														<span class="mr-auto">Keterangan</span>
														<p class="ml-auto">{{$data['lokasi']->distComment}}</p>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-start bg-warning-50">
														<span class="mr-auto">Hasil Pemeriksaan</span>
														<span class="ml-auto">
															<form action="{{route('2024.verifikator.tanam.storePhaseCheck', [$ijin, $lokasi->tcode])}}" id="formLahan">
																@csrf
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="distStatusSesuai" name="distStatus" value="1"
																		@if ($data['lokasi']->distStatus == 1) checked @endif>
																	<label class="custom-control-label" for="distStatusSesuai">Sesuai</label>
																</div>

																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="distStatusTidakSesuai" name="distStatus" value="0"
																		@if ($data['lokasi']->distStatus === 0) checked @endif>
																	<label class="custom-control-label" for="distStatusTidakSesuai">Tidak sesuai</label>
																</div>
															</form>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<form action="{{route('2024.verifikator.tanam.storelokasicheck', [$ijin, $lokasi->tcode])}}" id="storeLokasiCheck" method="post">
					@csrf
					<div class="card-footer d-flex align-items-start">
						<div class="ml-auto">
							<a href="{{route('2024.verifikator.tanam.checkdaftarlokasi', [$ijin, $verifikasi])}}" class="btn btn-info mr-1">Kembali</a>
							<button type="submit" class="btn btn-warning">Verifikasi Lahan Selesai</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endcan

@endsection

<!-- start script for this page -->
@section('scripts')
@parent


<script>
	$(document).ready(function() {
		var latitudeInput = $('#latitude');
		var longitudeInput = $('#longitude');
		var polygonInput = $('#polygon');
		var luasLahanInput = $('#luas_lahan');
		var namaPetani = $('#nama_petani');
		var ktpPetani = $('#ktp_petani');

		function clearMarkers() {
			markers.forEach(marker => marker.setMap(null));
			markers.length = 0;
		}

		$(document).on('change', 'form input[type="radio"]', function() {
            var $radio = $(this);
            var selectedValue = $radio.val();
            var columnName = $radio.attr('name');
            var $form = $radio.closest('form');
            var formAction = $form.attr('action');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var formData = $form.serializeArray();
            formData.push({ name: 'InputField', value: selectedValue });
            formData.push({ name: 'ColumnName', value: columnName });
            formData.push({ name: '_token', value: csrfToken });

            var dataObject = {};
            $.each(formData, function(index, field) {
                dataObject[field.name] = field.value;
            });

            $.ajax({
                url: formAction,
                method: 'POST',
                data: dataObject,
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    location.reload();
                    console.error('An error occurred while updating:', xhr.responseText);
                }
            });
        });
	});

	let myMap;
	const markers = [];
	let polygon;

	function initMap() {
		myMap = new google.maps.Map(document.getElementById("myMap"), {
			center: { lat: -2.5489, lng: 118.0149 },
			zoom: 5,
			mapTypeId: google.maps.MapTypeId.SATELLITE,
			draggable: false,
			disableDefaultUI: true,
		});

		createMarker();
		createPolygon();
	}

	function createMarker() {
		const latitude = parseFloat(document.getElementById("latitude").value);
		const longitude = parseFloat(document.getElementById("longitude").value);
		if (!isNaN(latitude) && !isNaN(longitude)) {
			const position = new google.maps.LatLng(latitude, longitude);
			const marker = new google.maps.Marker({
				position: position,
				map: myMap,
				draggable: false,
			});
			markers.push(marker);
			myMap.setCenter(position);
			myMap.setZoom(18);
		}
	}

	function createPolygon() {
		let polygonCoords = document.getElementById("polygon").value;
		if (polygonCoords !== "") {
			try {
				const parsedCoords = JSON.parse(polygonCoords).map(coord => ({ lat: coord.lat, lng: coord.lng }));
				if (polygon) {
					polygon.setMap(null);
				}
				polygon = new google.maps.Polygon({
					paths: parsedCoords,
					strokeColor: "#0000FF",
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: "#FF0000",
					fillOpacity: 0.35,
					map: myMap,
					editable: false,
					draggable: false,
				});
				const bounds = new google.maps.LatLngBounds();
				parsedCoords.forEach(point => bounds.extend(point));
				myMap.fitBounds(bounds);
			} catch (e) {
				console.error("Invalid polygon coordinates: ", e);
			}
		}
	}

	window.addEventListener('load', function() {
		initMap();
	});
</script>
@endsection
