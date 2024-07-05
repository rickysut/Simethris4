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
@can('commitment_show')

	@php
		$npwp = str_replace(['.', '-'], '', $data['npwpCompany']);
	@endphp
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


				<div class="panel-container collapse">
					<div id="myMap" cl style="height: 370px; width: 100%;"></div>
					<div class="panel-content">
						<ul class="list-group">
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">Pemilik/Pengelola</span>
								<span class="fw-500">{{$data['lokasi']->nama_petani}}</span>
							</li>
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">NIK Pemilik/Pengelola</span>
								<span class="fw-500">{{$data['lokasi']->ktp_petani}}</span>
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
						<input type="hidden" name="npwp_company" value="{{$data['pks']->npwp}}">
						<input type="hidden" name="no_ijin" value="{{$data['pks']->no_ijin}}">
						<input type="hidden" name="poktan_id" value="{{$data['pks']->poktan_id}}">
						<input type="hidden" name="pks_id" value="{{$data['pks']->id}}">
						<input type="hidden" name="anggota_id" value="{{$data['spatial']->kode_spatial}}">
						<input type="hidden" name="lokasi_id" value="{{$data['lokasi']->id}}">
						<input type="hidden" value="{{$data['spatial']->latitude}}" name="latitude" id="latitude" readonly>
						<input type="hidden" value="{{$data['spatial']->longitude}}" name="longitude" id="longitude" readonly/>
						<input type="hidden" value="{{$data['spatial']->polygon}}" name="polygon" id="polygon" readonly>
						<div class="accordion accordion-hover accordion-outline" id="logKeg">
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-lahan" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="camlahan">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="lahandate">Tanggal</label>
														<input class="form-control" id="lahandate" type="date" name="lahandate" value="{{$data['lokasi']->lahandate}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="lahancomment">Keterangan</label>
														<textarea class="form-control" id="lahancomment" name="lahancomment" rows="2">{{$data['lokasi']->lahancomment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="lahanfoto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="lahanfoto" name="lahanfoto" aria-describedby="lahanfoto" accept=".jpeg, .jpg, .png" value="$data['lokasi']->lahanfoto">
																<label class="custom-file-label" for="lahanfoto">Cari foto</label>
															</div>
														</div>
														<label for="lahanfoto" class="help-block text-truncate text-truncate-lg">{{$data['lokasi']->lahanfoto }}</label>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="saveLahan" data-field="lahanfoto">Simpan Persiapan Lahan</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-benih" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary"  id="camBenih">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="benihDate">Tanggal</label>
														<input class="form-control" id="benihDate" type="date" name="benihDate" value="{{$data['lokasi']->benihDate}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="benihComment">Keterangan</label>
														<textarea class="form-control" id="benihComment" name="benihComment" rows="2">{{$data['lokasi']->benihComment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="benihFoto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="benihFoto" name="benihFoto" aria-describedby="benihFoto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="benihFoto">input foto kegiatan</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="saveBenih" data-field="benihFoto">Simpan Kegiatan Benih</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-mulsa" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="mulsaCam">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="mulsaDate">Tanggal</label>
														<input class="form-control" id="mulsaDate" type="date" name="mulsaDate" value="{{$data['lokasi']->mulsaDate}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="mulsaComment">Keterangan</label>
														<textarea class="form-control" id="mulsaComment" name="mulsaComment" rows="2">{{$data['lokasi']->mulsaComment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="mulsaFoto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="mulsaFoto" name="mulsaFoto" aria-describedby="mulsaFoto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="mulsaFoto">input foto kegiatan</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="saveMulsa"  data-field="mulsaFoto">Simpan Kegiatan Mulsa</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-tanam" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="tanamCam">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="tanamDate">Tanggal</label>
														<input class="form-control" id="tanamDate" type="date" name="tanamDate" value="{{$data['lokasi']->tgl_tanam}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="tanamLuas">Luas Tanam (m)</label>
														<input class="form-control" id="tanamLuas" type="number" name="tanamLuas" value="{{$data['lokasi']->luas_tanam}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="tanamComment">Keterangan</label>
														<textarea class="form-control" id="tanamComment" name="tanamComment" rows="2">{{$data['lokasi']->tanamComment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="tanamFoto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="tanamFoto" name="tanamFoto" aria-describedby="tanamFoto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="tanamFoto">input foto kegiatan</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="saveTanam">Simpan Kegiatan Tanam</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-pupuk-1" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="pupuk1Cam">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="pupuk1Date">Tanggal</label>
														<input class="form-control" id="pupuk1Date" type="date" name="pupuk1Date" value="{{$data['lokasi']->pupuk1Date}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="pupuk1Comment">Keterangan</label>
														<textarea class="form-control" id="pupuk1Comment" name="pupuk1Comment" rows="2">{{$data['lokasi']->pupuk1Comment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="pupuk1Foto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="pupuk1Foto" name="pupuk1Foto" aria-describedby="pupuk1Foto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="pupuk1Foto">input foto kegiatan</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="savePupuk1"  data-field="pupuk1Foto">Simpan Pemupukan 1</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-pupuk-2" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="pupuk2Cam">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="pupuk2Date">Tanggal</label>
														<input class="form-control" id="pupuk2Date" type="date" name="pupuk2Date" value="{{$data['lokasi']->pupuk2Date}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="pupuk2Comment">Keterangan</label>
														<textarea class="form-control" id="pupuk2Comment" name="pupuk2Comment" rows="2">{{$data['lokasi']->pupuk2Comment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="pupuk2Foto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="pupuk2Foto" name="pupuk2Foto" aria-describedby="pupuk2Foto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="pupuk2Foto">input foto kegiatan</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="savePupuk2"  data-field="pupuk2Foto">Simpan Pemupukan 2</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-pupuk-3" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="pupuk3Cam">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="pupuk3Date">Tanggal</label>
														<input class="form-control" id="pupuk3Date" type="date" name="pupuk3Date" value="{{$data['lokasi']->pupuk3Date}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="pupuk3Comment">Keterangan</label>
														<textarea class="form-control" id="pupuk3Comment" name="pupuk3Comment" rows="2">{{$data['lokasi']->pupuk3Comment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="pupuk3Foto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="pupuk3Foto" name="pupuk3Foto" aria-describedby="pupuk3Foto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="pupuk3Foto">input foto kegiatan</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="savePupuk3"  data-field="pupuk3Foto">Simpan Pemupukan 3</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-OPT" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="optCam">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="optDate">Tanggal</label>
														<input class="form-control" id="optDate" type="date" name="optDate" value="{{$data['lokasi']->optDate}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="optComment">Keterangan</label>
														<textarea class="form-control" id="optComment" name="optComment" rows="2">{{$data['lokasi']->optComment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="optFoto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="optFoto" name="optFoto" aria-describedby="optFoto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="optFoto">input foto kegiatan</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="saveOPT" data-field="optFoto">Simpan Pengendalian OPT</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-produksi" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="prodCam">
															<i class="fal fa-camera mr-1"></i> Foto Kegiatan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="prodDate">Tanggal</label>
														<input class="form-control" id="prodDate" type="date" name="prodDate" value="{{$data['lokasi']->tgl_panen}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="prodVol">Volume Panen</label>
														<input class="form-control" id="prodVol" type="number" name="prodVol" value="{{$data['lokasi']->volume}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="prodComment">Keterangan</label>
														<textarea class="form-control" id="prodComment" name="prodComment" rows="2">{{$data['lokasi']->prodComment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="prodFoto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="prodFoto" name="prodFoto" aria-describedby="prodFoto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="prodFoto">Cari foto</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="saveProd" data-field="prodFoto">Simpan Data Produksi</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-distribusi" aria-expanded="false">
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
									<form action="{{ route('2024.user.commitment.storefoto', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" enctype="multipart/form-data" method="post">
										@csrf
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
													<div class="fom-group mb-3 d-md-none d-block">
														<button class="btn btn-block btn-primary" id="distCam">
															<i class="fal fa-camera mr-1"></i> Foto/Bukti Penjualan
														</button>
													</div>
													<div class="form-group">
														<label class="form-label" for="distStored">Untuk Benih</label>
														<input class="form-control" id="distStored" type="number" name="distStored" value="{{$data['lokasi']->vol_benih}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="distSale">Untuk Dijual</label>
														<input class="form-control" id="distSale" type="number" name="distSale" value="{{$data['lokasi']->vol_jual}}">
													</div>
													<div class="form-group">
														<label class="form-label" for="distComment">Keterangan</label>
														<textarea class="form-control" id="distComment" name="distComment" rows="2">{{$data['lokasi']->distComment}}</textarea>
													</div>
													<div class="form-group d-none d-lg-block">
														<label class="form-label" for="distFoto">Bukti Kegiatan</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="distFoto" name="distFoto" aria-describedby="distFoto" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="distFoto">Cari foto</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer d-flex justify-content-between">
											<div></div>
											<div class="ml-auto">
												<button class="btn btn-warning waves-effect waves-themed btnSave" type="submit" id="saveDist" data-field="distFoto">Simpan Distribusi Produksi</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
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

		spatialSelect.change(function() {
			var selectedSpatialKode = spatialSelect.val();
			var realKode = selectedSpatialKode.replace(/-/g, '');

			$.get('/2024/datafeeder/getSpatialByKode/' + realKode, function(data) {
				latitudeInput.val(data.latitude);
				longitudeInput.val(data.longitude);
				polygonInput.val(data.polygon);
				luasLahanInput.val(data.luas_lahan);
				namaPetani.text(data.nama_petani);
				ktpPetani.val(data.ktp_petani);

				clearMarkers();
				createMarker();
				createPolygon();
			});
		});

		$('#distStored').prop('disabled', true);
    	// $('#vol_jual').prop('readonly', true);

		function updateVolJual() {
			var volume = parseFloat($('#prodVol').val());
			var volBenih = parseFloat($('#distStored').val()) || 0; // Treat null as 0

			if (!isNaN(volume) && volume >= 0 && volBenih >= 0 && volBenih <= volume) {
				$('#distStored').val(volume - volBenih);
			} else {
				$('#distStored').val('');
			}
		}

		// Reset vol_benih and vol_jual on change of volume
		$('#prodVol').on('input', function() {
			var volume = parseFloat($(this).val());
			if (isNaN(volume) || volume < 0) {
				$(this).val('').attr('placeholder', 'Masukkan nilai volume yang valid.');
				$('#distStored').val('').prop('disabled', true);
				$('#vol_jual').val('').prop('readonly', true);
			} else {
				$(this).attr('placeholder', '');
				$('#distStored').prop('disabled', false);
				$('#distSale').prop('readonly', false);
				updateVolJual();
			}
		});

		// Reset vol_jual if vol_benih is changed
		$('#distStored').on('input', function() {
			var volBenih = parseFloat($(this).val());
			var volume = parseFloat($('#prodVol').val());
			if (isNaN(volBenih) || volBenih < 0 || volBenih > volume) {
				$(this).val('').attr('placeholder', 'Masukkan nilai vol benih yang valid.');
			} else {
				$(this).attr('placeholder', '');
				updateVolJual();
			}
		});

		function clearMarkers() {
			markers.forEach(marker => marker.setMap(null));
			markers.length = 0;
		}

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

	$('#tblLogbook').dataTable({
		responsive: true,
	});
</script>



{{-- aktifkan untuk pembatasan max input luas lahan
	 --}}
@endsection
