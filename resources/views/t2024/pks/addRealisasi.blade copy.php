@extends('layouts.admin')
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
								<div id="logKeg-lahan" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Persiapan Lahan</button>
										</div>
									</div>
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
								<div id="logKeg-benih" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Kegiatan Benih</button>
										</div>
									</div>
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
								<div id="logKeg-mulsa" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Kegiatan Mulsa</button>
										</div>
									</div>
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
								<div id="logKeg-tanam" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Kegiatan Tanam</button>
										</div>
									</div>
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
								<div id="logKeg-pupuk-1" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Pemupukan 1</button>
										</div>
									</div>
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
								<div id="logKeg-pupuk-2" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Pemupukan 2</button>
										</div>
									</div>
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
								<div id="logKeg-pupuk-3" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Pemupukan 3</button>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse" data-target="#logKeg-pupuk-3" aria-expanded="false">
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
								<div id="logKeg-pupuk-3" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">ambil foto</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Pengendalian OPT</button>
										</div>
									</div>
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
								<div id="logKeg-produksi" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">Choose file</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Data Produksi</button>
										</div>
									</div>
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
								<div id="logKeg-distribusi" class="collapse" data-parent="#logKeg" >
									<div class="card-body">
										<div class="row d-flex">
											<div class="col-md-4 mb-3">
												<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
													<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://simethris4.test/img/card-backgrounds/cover-2-lg.png'); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label class="form-label" for="example-date">Tanggal</label>
													<input class="form-control" id="example-date" type="date" name="date" value="2023-07-23">
												</div>
												<div class="form-group">
													<label class="form-label" for="example-textarea">Keterangan</label>
													<textarea class="form-control" id="example-textarea" rows="2"></textarea>
												</div>
												<div class="form-group">
													<label class="form-label" for="inputGroupFile01">Bukti Foto</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
															<label class="custom-file-label" for="inputGroupFile04">Choose file</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex justify-content-between">
										<div></div>
										<div class="ml-auto">
											<button class="btn btn-warning waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Simpan Distribusi Produksi</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			<div class="panel" id="panel-3" hidden>
				<div class="card-header">
					<form action="{{ route('2024.user.commitment.storerealisasi', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="form-group col-md">
								<label class="form-label" for="mulai_tanam">Tanggal Tanam<sup class="text-danger"> *</sup></label>
								<div class="input-group">
									<input type="date" value="{{$data['lokasi']->tgl_tanam}}" name="mulai_tanam" id="mulai_tanam" class="font-weight-bold form-control bg-white" />
								</div>
								<span class="help-block">Tanggal penanaman.</span>
							</div>
							<div class="form-group col-md">
								<label class="form-label" for="luas_tanam">Luas Tanam (m2)<sup class="text-danger"> *</sup></label>
								<div class="input-group">
									<input type="number" value="{{$data['lokasi']->luas_tanam}}" name="luas_tanam" id="luas_tanam" class="font-weight-bold form-control bg-white" />
									<div class="input-group-append">
										<button class="btn btn-primary waves-effect waves-themed" type="submit" id="btnTanam"><i class="fa fa-save"></i> Simpan</button>
									</div>
								</div>
								<span class="help-block">Luas lahan yang ditanami.</span>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-container">
					<div class="panel-content">
						<div class="row d-flex justify-content-start">
							<div class="col-lg-4">
								image
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label class="form-label" for="example-palaceholder">Placeholder</label>
									<input type="text" id="example-palaceholder" class="form-control" placeholder="placeholder">
								</div>
								<div class="form-group">
									<label class="form-label" for="example-textarea">Text area</label>
									<textarea class="form-control" id="example-textarea" rows="5"></textarea>
								</div>
								<div class="form-group">
									<label class="form-label" for="inputGroupFile01">Button &amp; select on right</label>
									<div class="input-group">
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
											<label class="custom-file-label" for="inputGroupFile04">Pilih foto</label>
										</div>
										<div class="input-group-append">
											<button class="btn btn-outline-primary waves-effect waves-themed" type="button" id="inputGroupFileAddon04">Catat Kegiatan</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="panel-content">
						<table id="tblLogbook" class="table table-bordered table-hover table-responsive table-sm table-striped w-100">
							<thead class="thead-themed">
								<th width="3%">No</th>
								<th width="15%">Kegiatan</th>
								<th width="15%">Tanggal</th>
								<th width="32%">Keterangan</th>
								<th width="10%">Luas</th>
								<th width="20%">Foto</th>
								<th width="5%">Status</th>
							</thead>
							<tbody>
								<tr>
									<td class="text-right">1</td>
									<td>Penyiapan Lahan</td>
									<td>
										<input type="date" value="{{$data['lokasi']->tgl_tanam}}" name="mulai_tanam" id="mulai_tanam" class="form-control bg-white" />
									</td>
									<td>
										<textarea class="form-control" id="example-textarea" rows="1"></textarea>
									</td>
									<td></td>
									<td>
										<div class="form-group mb-0">
											<div class="custom-file">
												<input type="file" class="custom-file-input" id="customFile">
												<label class="custom-file-label" for="customFile">Choose file</label>
											</div>
										</div>
									</td>
									<td>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
										</div>
									</td>
								<tr>
								<tr>
									<td class="text-right">2</td>
									<td>Penanaman</td>
									<td>
										<input type="date" value="{{$data['lokasi']->tgl_tanam}}" name="mulai_tanam" id="mulai_tanam" class="form-control bg-white" />
									</td>
									<td>
										<textarea class="form-control" id="example-textarea" rows="1"></textarea>
									</td>
									<td>
										<input type="number" value="{{$data['lokasi']->vol_benih}}" name="vol_benih" id="vol_benih" class="font-weight-bold form-control bg-white" />
									</td>
									<td></td>
									<td></td>
								<tr>
								<tr>
									<td class="text-right">3</td>
									<td>Pemupukan Pertama</td>
									<td>
										<input type="date" value="{{$data['lokasi']->tgl_tanam}}" name="mulai_tanam" id="mulai_tanam" class="form-control bg-white" />
									</td>
									<td>
										<textarea class="form-control" id="example-textarea" rows="1"></textarea>
									</td>
									<td></td>
									<td></td>
									<td></td>
								<tr>
								<tr>
									<td class="text-right">4</td>
									<td>Pemupukan Kedua</td>
									<td>
										<input type="date" value="{{$data['lokasi']->tgl_tanam}}" name="mulai_tanam" id="mulai_tanam" class="form-control bg-white" />
									</td>
									<td>
										<textarea class="form-control" id="example-textarea" rows="1"></textarea>
									</td>
									<td></td>
									<td></td>
									<td></td>
								<tr>
								<tr>
									<td class="text-right">5</td>
									<td>Pemupukan Ketiga</td>
									<td>
										<input type="date" value="{{$data['lokasi']->tgl_tanam}}" name="mulai_tanam" id="mulai_tanam" class="form-control bg-white" />
									</td>
									<td>
										<textarea class="form-control" id="example-textarea" rows="1"></textarea>
									</td>
									<td></td>
									<td></td>
									<td></td>
								<tr>
								<tr>
									<td class="text-right">6</td>
									<td>Penyiangan</td>
									<td>
										<input type="date" value="{{$data['lokasi']->tgl_tanam}}" name="mulai_tanam" id="mulai_tanam" class="form-control bg-white" />
									</td>
									<td>
										<textarea class="form-control" id="example-textarea" rows="1"></textarea>
									</td>
									<td></td>
									<td></td>
									<td></td>
								<tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="panel" id="panel-4" hidden>
				<div class="panel-hdr">
					<h2>Produksi</h2>
					<div class="panel-toolbar">
						@include('t2024.partials.globaltoolbar')
					</div>
				</div>
				<div class="panel-container">
					<form action="{{ route('2024.user.commitment.storerealisasi', ['noIjin' => $ijin, 'spatial' => $data['spatial']->kode_spatial]) }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="panel-content">
							<div class="row">
								<div class="form-group col-lg-3">
									<label for="mulai_tanam">Tanggal Produksi<sup class="text-danger"> *</sup></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fal fa-calendar-day"></i></span>
										</div>
										<input type="date" value="{{$data['lokasi']->tgl_panen}}" name="mulai_panen" id="mulai_panen" class="font-weight-bold form-control bg-white" />
									</div>
									<span class="help-block">Tanggal pemanenan.</span>
								</div>
								<div class="form-group col-lg-3">
									<label for="volume">Volume Produksi (ton)<sup class="text-danger"> *</sup></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fal fa-balance-scale"></i></span>
										</div>
										{{-- tambahkan ini
										max="{{ $anggota->luas_lahan - $anggota->datarealisasi->sum('luas_lahan') }}"
										untuk pembatasan dan aktifkan script --}}
										<input type="number" step="1" value="{{$data['lokasi']->volume}}" name="volume" id="volume" class="font-weight-bold form-control bg-white" />
									</div>
									<span class="help-block">Total produksi yang diperoleh.</span>
								</div>
								<div class="form-group col-lg-3">
									<label for="volume">Untuk Benih (ton)</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fal fa-seedling"></i></span>
										</div>
										<input type="number" value="{{$data['lokasi']->vol_benih}}" name="vol_benih" id="vol_benih" class="font-weight-bold form-control bg-white" />
									</div>
									<span class="help-block">Total produksi yang disimpan sebagai benih.</span>
								</div>
								<div class="form-group col-lg-3">
									<label for="volume">Untuk Dijual (ton)</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fal fa-truck-loading"></i></span>
										</div>
										<input type="number" value="{{$data['lokasi']->vol_jual}}" name="vol_jual" id="vol_jual" class="font-weight-bold form-control bg-white" />
										<div class="input-group-append">
											<button class="btn btn-outline-primary waves-effect waves-themed" type="submit" id="inputGroupFileAddon04">Simpan</button>
										</div>
									</div>
									<span class="help-block">Total produksi yang dilepas ke konsumsi.</span>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endcan

@endsection

<!-- start script for this page -->
@section('scripts')
@parent
{{-- <script src="{{ asset('js/gmap/map.js') }}"></script> --}}


<script>
	$(document).ready(function() {
		$("#kabupaten_id").select2({
			placeholder: "pilih kabupaten",
			allowClear: true
		});
		$("#kecamatan_id").select2({
			placeholder: "pilih kecamatan",
			allowClear: true
		});
		$("#spatial_data").select2({
			placeholder: "pilih lokasi",
			allowClear: true
		});

		var kabupatenSelect = $('#kabupaten_id');
		var kecamatanSelect = $('#kecamatan_id');
		var spatialSelect = $('#spatial_data');

		$.get('/wilayah/getAllKabupaten', function(data) {
			$.each(data, function(key, value) {
				var option = $('<option>', {
					value: value.kabupaten_id,
					text: value.nama
				});
				kabupatenSelect.append(option);
			});
		});

		kabupatenSelect.change(function() {
			var selectedKabupatenId = kabupatenSelect.val();
			kecamatanSelect.empty();
			spatialSelect.empty();

			kecamatanSelect.append($('<option>', {
				value: '',
				text: 'pilih kec'
			}));

			spatialSelect.append($('<option>', {
				value: '',
				text: 'pilih lokasi'
			}));
			$.get('/wilayah/getKecamatanByKabupaten/' + selectedKabupatenId, function(data) {
				$.each(data, function(key, value) {
					var option = $('<option>', {
						value: value.kecamatan_id,
						text: value.nama_kecamatan
					});
					kecamatanSelect.append(option);
				});
			});
		});

		kecamatanSelect.change(function() {
			var selectedKecamatanId = kecamatanSelect.val();
			spatialSelect.empty();

			spatialSelect.append($('<option>', {
				value: '',
				text: 'pilih spatial'
			}));

			$.get('/2024/datafeeder/getSpatialByKecamatan/' + selectedKecamatanId, function(data) {
				$.each(data, function(key, value) {
					var option = $('<option>', {
						value: value.kode_spatial,
						text: value.kode_spatial
					});
					spatialSelect.append(option);
				});
			});
		});

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

		$('#vol_benih').prop('disabled', true);
    	// $('#vol_jual').prop('readonly', true);

		function updateVolJual() {
			var volume = parseFloat($('#volume').val());
			var volBenih = parseFloat($('#vol_benih').val()) || 0; // Treat null as 0

			if (!isNaN(volume) && volume >= 0 && volBenih >= 0 && volBenih <= volume) {
				$('#vol_jual').val(volume - volBenih);
			} else {
				$('#vol_jual').val('');
			}
		}

		// Reset vol_benih and vol_jual on change of volume
		$('#volume').on('input', function() {
			var volume = parseFloat($(this).val());
			if (isNaN(volume) || volume < 0) {
				$(this).val('').attr('placeholder', 'Masukkan nilai volume yang valid.');
				$('#vol_benih').val('').prop('disabled', true);
				$('#vol_jual').val('').prop('readonly', true);
			} else {
				$(this).attr('placeholder', '');
				$('#vol_benih').prop('disabled', false);
				$('#vol_jual').prop('readonly', false);
				updateVolJual();
			}
		});

		// Reset vol_jual if vol_benih is changed
		$('#vol_benih').on('input', function() {
			var volBenih = parseFloat($(this).val());
			var volume = parseFloat($('#volume').val());
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
