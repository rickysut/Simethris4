@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" media="screen, print" href="{{ asset('css/miscellaneous/lightgallery/lightgallery.bundle.css') }}">
@endsection
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@can('online_access')
	@include('t2024.partials.sysalert')
		<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-right modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Verifikasi Detail Lokasi</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
						<button type="button" class="btn btn-primary" id="btnLokasiCheck">Simpan</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="contentToPrint">
			<div class="col-lg-3">
				<div class="card mb-g">
					<div class="card-header">
						<div class="d-flex flex-row pt-2  border-top-0 border-left-0 border-right-0">
							<div class="d-inline-block align-middle mr-3">
								<span class="profile-image rounded-circle d-block" style="background-image:url(); background-size: cover;" id="companyLogo"></span>
							</div>
							<h3 class="mb-0 flex-1 text-dark fw-500">
								<span id="companytitle"></span>
								<small class="m-0 l-h-n font-weight-bold"></small>
							</h3>
							<span class="">
							</span>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="form-group col-12">
								<label class="text-muted" for="no_ijin">Nomor RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="no_ijin" name="no_ijin"></span>
								</div>
							</div>

							<div class="form-group col-12">
								<label class="text-muted" for="tgl_ijin">Tanggal Ijin RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="tgl_ijin" name="tgl_ijin"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="tgl_akhir">Tanggal Akhir RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="tgl_akhir" name="tgl_akhir"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="created_at">Tanggal Pengajuan Verifikasi</label>
								<div class="input-group">
									<span class="font-weight-bold" id="created_at" name="created_at"></span>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12"><h5 class="font-weight-bold">Ringkasan</h5></div>
							<div class="form-group col-12">
								<label class="text-muted" for="countPks">Kemitraan</label>
								<div class="input-group">
									<span class="font-weight-bold" id="countPks" name="countPks"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="jml_anggota">Jumlah Anggota</label>
								<div class="input-group">
									<span class="font-weight-bold" id="jml_anggota" name="jml_anggota"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="luas_tanam">Realisasi Tanam</label>
								<div class="input-group">
									<span class="font-weight-bold" id="luas_tanam" name="luas_tanam"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="jml_titik">Lokasi Tanam</label>
								<div class="input-group">
									<span class="font-weight-bold" id="jml_titik" name="jml_titik"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-9">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item checkBerkas" >
						<a class="nav-link" data-toggle="tab" href="#panel-1" role="tab" aria-selected="true">Berkas-Berkas</a>
					</li>
					<li class="nav-item checkPks" style="display: none">
						<a class="nav-link" data-toggle="tab" href="#panel-2" role="tab" aria-selected="true">Perjanjian Kemitraan</a>
					</li>
					<li class="nav-item checkTimeline" style="display: none">
						<a class="nav-link" data-toggle="tab" href="#panel-3" role="tab" aria-selected="true">Timeline Realisasi</a>
					</li>
					<li class="nav-item checkLokasi" style="display: none">
						<a class="nav-link" data-toggle="tab" href="#panel-4" role="tab" aria-selected="true">Lokasi Tanam</a>
					</li>
					<li class="nav-item checkFinal" style="display: none">
						<a class="nav-link text-danger" data-toggle="tab" href="#panel-5" role="tab" aria-selected="true">Hasil Pemeriksaan</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade" id="panel-1" role="tabpanel" aria-labelledby="panel-1">
						<div id="panel-1" class="panel">
							<div class="panel-container show">
								<div class="panel-tag fade show">
									<div class="d-flex align-items-center">
										<i class="fal fa-info-circle mr-1"></i>
										<div class="flex-1">
											<small class="text-danger">AWAL PEMERIKSAAN. Jika selesai pemeriksaan bagian ini, klik tombol di bawah untuk menandai progress pemeriksaan. Set status ke "2".</small>
										</div>
									</div>
								</div>
							</div>
							<form action="{{ route("2024.verifikator.tanam.markStatus", [$ijin, $tcode, "2"]) }}" method="post" enctype="multipart/form-data">
								@csrf
								<div class="panel-container show">
									<div class="panel-content">
										<table class="table table-striped table-bordered table-sm w-100" id="attchCheck">
											<thead class="thead-themed text-uppercase text-muted">
												<tr>
													<th>Form</th>
													<th>Nama Berkas</th>
													<th>Tindakan</th>
													<th>Hasil Periksa</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Surat Pertanggungjawaban Mutlak</td>
													<td>{{$userDocs->sptjmtanam}}</td>
													<td>
														@if ($userDocs->sptjmtanam)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->sptjmtanam) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm saveCheckBerkas" name="sptjmtanamcheck" id="sptjmtanamcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->sptjmtanamcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->sptjmtanamcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Surat Pengajuan Verifikasi Tanam</td>
													<td>{{$userDocs->spvt}}</td>
													<td>
														@if ($userDocs->spvt)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->spvt) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm saveCheckBerkas" name="spvtcheck" id="spvtcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->spvtcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->spvtcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Form Realisasi Tanam</td>
													<td>{{$userDocs->rta}}</td>
													<td>
														@if ($userDocs->rta)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->rta) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm saveCheckBerkas" name="rtacheck" id="rtacheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->rtacheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->rtacheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Perbaikan</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Form SPH-SBS</td>
													<td>{{$userDocs->sphtanam}}</td>
													<td>
														@if ($userDocs->sphtanam)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->sphtanam) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm saveCheckBerkas" name="sphtanamcheck" id="sphtanamcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->sphtanamcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->sphtanamcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Logbook (s.d tanam)</td>
													<td>{{$userDocs->logbooktanam}}</td>
													<td>
														@if ($userDocs->logbooktanam)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->logbooktanam) }}"  download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm saveCheckBerkas" name="logbooktanamcheck" id="logbooktanamcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->logbooktanamcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->logbooktanamcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="card-footer d-flex justify-content-between align-items-center">
									<div class="help-block col-md-7">
									</div>
									<div class="col-md text-right">

											<button type="submit" class="btn btn-success btn-sm" id="btnStatus-2" data-status="2">
												<i class="fal fa-save"></i> Tandai progress di sini
											</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="tab-pane fade" id="panel-2" role="tabpanel" aria-labelledby="panel-2">
						<div id="panel-2" class="panel">
							<div class="panel-container show">
								<div class="panel-tag fade show">
									<div class="d-flex align-items-center">
										<i class="fal fa-info-circle mr-1"></i>
										<div class="flex-1">
											<small class="text-danger">klik tombol di bawah untuk menandai proress pemeriksaan di sini. Set status ke "3".</small>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-container show">
								<div class="panel-content">
									<table class="table table-striped table-bordered table-sm w-100" id="pksCheck">
										<thead class="thead-themed text-uppercase text-muted">
											<tr>
												<th style="width: 20%">No Perjanjian</th>
												<th>Kelompok Tani</th>
												<th>Berlaku Sejak</th>
												<th>Berakhir Pada</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<div class="card-footer d-flex justify-content-between align-items-center">
								<div class="help-block col-md-7">
								</div>
								<div class="col-md text-right">
									<form action="{{ route("2024.verifikator.tanam.markStatus", [$ijin, $tcode, "3"]) }}" method="post" enctype="multipart/form-data">
										@csrf
										<button type="submit" class="btn btn-success btn-sm" id="btnStatus-3" data-status="3">
											<i class="fal fa-save"></i> Tandai progress di sini
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="panel-3" role="tabpanel" aria-labelledby="panel-3">
						<div id="panel-3" class="panel">
							<div class="panel-container show">
								<div class="panel-tag fade show">
									<div class="d-flex align-items-start">
										<i class="fal fa-info-circle mr-1"></i>
										<div class="flex-1">
											<p class="text-danger small">
												klik tombol di bawah untuk menandai progres pemeriksaan di sini. Set status ke "4".
											</p>
											<p class="text-danger small">
												<ul class="small">
													<li>Tanggal berwarna merah memiliki arti : Tanggal berada di luar rentang semestinya</li>
													<li>Tanggal berwarna hijau memiliki arti : Tanggal berada di dalam rentang semestinya</li>
													<li>Tanggal kosong/tidak ada data : data tidak diisi oleh pelaku usaha</li>
													<li>invalid date : Data petani atau poktan tidak tersedia di database</li>
												</ul>
											</p>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-container show">
								<div class="panel-content">
									<table class="table table-striped table-bordered table-sm w-100" id="timeLine">
										<thead class="thead-themed text-muted">
											<tr>
												<th style="width: 15%">Lokasi</th>
												<th>Awal PKS</th>
												<th>Akhir PKS</th>
												<th>Awal Tanam</th>
												<th>Akhir Tanam</th>
												<th>Awal Panen</th>
												<th>Akhir Panen</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<div class="card-footer d-flex justify-content-between align-items-center">
								<div class="help-block col-md-7">
								</div>
								<div class="col-md text-right">
									<form action="{{ route("2024.verifikator.tanam.markStatus", [$ijin, $tcode, "4"]) }}" method="post" enctype="multipart/form-data">
										@csrf
										<button type="submit" class="btn btn-success btn-sm" id="btnStatus-4" data-status="4">
											<i class="fal fa-save"></i> Tandai progress di sini
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="panel-4" role="tabpanel" aria-labelledby="panel-4">
						<div id="panel-4" class="panel">
							<div class="panel-container show">
								<div class="panel-tag fade show">
									<div class="d-flex align-items-center">
										<i class="fal fa-info-circle mr-1"></i>
										<div class="flex-1">
											<small class="text-danger">klik tombol di bawah untuk menandai progress pemeriksaan di sini. Set status ke "5". verifikasi pertama wajib di lapangan, tabel di sini tidak dapat dilihat apabila belum melakukan verifikasi di lapangan minimal 1x.</small>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-container show">
								{{-- <div class="row d-flex justify-content-between">
									<div class="col-lg-5 mb-5">
										<div id="myMap" style="height: 400px; width: 100%;"></div>
									</div>
								</div> --}}
								<div class="panel-content">
									<table class="table table-striped table-bordered table-sm w-100" id="lokasiCheck">
										<thead>
											<tr>
												<th>Kode Lokasi</th>
												<th>Luas Lahan</th>
												<th>Luas Tanam</th>
												<th>Poktan</th>
												<th>Pengelola</th>
												<th>Status</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
							<div class="card-footer d-flex justify-content-between align-items-center">
								<div class="help-block col-md-7">
								</div>
								<div class="col-md text-right">
									<form action="{{ route("2024.verifikator.tanam.markStatus", [$ijin, $tcode, "5"]) }}" method="post" enctype="multipart/form-data">
										@csrf
										<button type="submit" class="btn btn-success btn-sm" id="btnStatus-5" data-status="5">
											<i class="fal fa-save"></i> Tandai progress di sini
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="panel-5" role="tabpanel" aria-labelledby="panel-5">
						<div id="panel-5" class="panel">
							<div class="panel-container show">
								<div class="panel-content">
									<div class="panel-container show">
										<div class="panel-tag fade show">
											<div class="d-flex align-items-center">
												<i class="fal fa-info-circle mr-1"></i>
												<div class="flex-1">
													<small class="text-danger">AKHIR PEMERIKSAAN. Pastikan pemeriksaan menyeluruh telah selesai. Kemudian tetapkan hasil akhir pemeriksaan di sini.</small>
												</div>
											</div>
										</div>
										<form action="{{route('verification.tanam.storeCheck', $verifikasi->id)}}" method="POST" enctype="multipart/form-data">
											@csrf
											@method('PUT')
											<div class="panel-content">
												<input type="text" name="no_ijin" value="{{$verifikasi->no_ijin}}" hidden>
												<input type="text" name="no_pengajuan" value="{{$verifikasi->no_pengajuan}}" hidden>
												<input type="text" name="npwp" value="{{$verifikasi->npwp}}" hidden>

												<div class="form-group row">
													<label class="col-md-3 col-lg-2 col-form-label">Hasil Pemeriksaan<sup class="text-danger"> *</sup></label>
													<div class="col-md-9 col-lg-10">
														<select name="status" id="status" class="form-control custom-select" onchange="handleStatusChange()" required>
															<option value="" hidden>-- pilih status --</option>
															<option value="6" {{ old('status', $verifikasi ? $verifikasi->status : '') == '6' ? 'selected' : '' }}>Sesuai</option>
															<option value="7" {{ old('status', $verifikasi ? $verifikasi->status : '') == '7' ? 'selected' : '' }}>Perbaikan Data</option>
														</select>
														<small id="helpId" class="text-muted">Pilih hasil pemeriksaan</small>
													</div>
												</div>
												<div class="form-group row" id="ndhprtContainer" hidden>
													<label class="col-md-3 col-lg-2 col-form-label">Nota Dinas<sup class="text-danger"> *</sup></label>
													<div class="col-md-9 col-lg-10">
														<div class="custom-file input-group">
															<input type="file" accept=".pdf" class="custom-file-input" name="ndhprt" id="ndhprt" value="{{ old('ndhprt', $verifikasi ? $verifikasi->ndhprt : '') }}">
															<label class="custom-file-label" for="ndhprt">{{ old('ndhprt', $verifikasi ? $verifikasi->ndhprt : 'pilih berkas') }}</label>
														</div>
														@if ($verifikasi->ndhprt)
															<a href="#" class="help-block" data-toggle="modal" data-target="#viewDocs" data-doc="{{ asset('storage/uploads/'.$npwp.'/'.$commitment->periodetahun.'/'.$verifikasi->ndhprt) }}">
																<i class="fas fa-search mr-1"></i>
																Lihat Nota Dinas.
															</a>
														@else
															<span class="help-block fw-500">Nota Dinas Hasil Pemeriksaan Realisasi Tanam. <span class="text-danger">(wajib)</span>. PDF, max 2Mb.</span>
														@endif
													</div>
												</div>
												<div class="form-group row" id="batanamContainer" hidden>
													<label class="col-md-3 col-lg-2 col-form-label">Berita Acara<sup class="text-danger">*</sup></label>
													<div class="col-md-9 col-lg-10">
														<div class="custom-file input-group">
															<input type="file" accept=".pdf" class="custom-file-input" name="batanam" id="batanam" value="{{ old('batanam', $verifikasi ? $verifikasi->batanam : '') }}">
															<label class="custom-file-label" for="batanam">{{ old('batanam', $verifikasi ? $verifikasi->batanam : 'pilih berkas') }}</label>
														</div>
														@if ($verifikasi->batanam)
															<a href="#" class="help-block" data-toggle="modal" data-target="#viewDocs" data-doc="{{ asset('storage/uploads/'.$npwp.'/'.$commitment->periodetahun.'/'.$verifikasi->batanam) }}">
																<i class="fas fa-search mr-1"></i>
																Lihat Berita Acara.
															</a>
														@else
															<span class="help-block">Berita Acara Pemeriksaan Realisasi Tanam. PDF, max 2Mb.<span class="text-danger"></span></span>
														@endif
													</div>
												</div>
												<div class="form-group row">
													<label class="col-md-3 col-lg-2 col-form-label">Metode Pemeriksaan<sup class="text-danger"> *</sup></label>
													<div class="col-md-9 col-lg-10">
														<select name="metode" id="metode" class="form-control custom-select" required>
															<option value="" hidden>-- pilih metode --</option>
															<option value="Dokumen" {{ old('metode', $verifikasi ? $verifikasi->metode : '') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
															<option value="Lapangan" {{ old('metode', $verifikasi ? $verifikasi->metode : '') == 'Lapangan' ? 'selected' : '' }}>Lapangan</option>
															<option value="Wawancara" {{ old('metode', $verifikasi ? $verifikasi->metode : '') == 'Wawancara' ? 'selected' : '' }}>Wawancara</option>
														</select>
														<small id="helpId" class="text-muted">Pilih metode pemeriksaan</small>
													</div>
												</div>
												<div class="form-group row">
													<label class="col-md-3 col-lg-2">Catatan Pemeriksaan <sup class="text-danger"> *</sup></label>
													<div class="col-md-9 col-lg-10">
														<textarea name="note" id="note" rows="3" class="form-control form-control-sm">{{ old('note', $verifikasi ? $verifikasi->note : '') }}</textarea>
													</div>
												</div>
											</div>
											<div class="card-footer">
												<div class="form-group">
													<label>Dengan ini kami menyatakan verifikasi tanam telah <span class="text-danger fw-500">SELESAI</span> dilaksanakan.</label>
													<div class="input-group">
														<input type="text" class="form-control" placeholder="ketik username Anda di sini" id="validasi" name="validasi"required>
														<div class="input-group-append">
															<button class="btn btn-danger" type="submit" onclick="return validateInput()" id="btnSubmit">
																<i class="fas fa-save text-align-center mr-1"></i>Simpan
															</button>
														</div>
													</div>
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

@section('scripts')
	<script src="{{ asset('js/miscellaneous/lightgallery/lightgallery.bundle.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-editor/2.3.1/dataTables.editor.js" integrity="sha512-BKsIfYRuTSaLQncTO/3CUtWr6zko7hbmxWYcBhJ7YqVB1zPIcG0S7hCNf3PLcQds22RlBaVHnKkKLxjSmn9hZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	@parent

	<script>
		function initMap(latitude, longitude, polygon) {
			const myLatLng = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
			var myMap = new google.maps.Map(document.getElementById("myMap"), {
				center: myLatLng,
				zoom: 15,
				mapTypeId: google.maps.MapTypeId.HYBRID,
				fullscreenControl: true,
				mapTypeControl: false,
				streetViewControl: false,
				zoomControl: false,
				scaleControl: false,
				rotateControl: false
			});

			// Tambahkan polygon non-editable jika ada
			if (polygon) {
				var poly = new google.maps.Polygon({
					paths: JSON.parse(polygon),
					strokeColor: "#FF0000",
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: "#FF0000",
					fillOpacity: 0.35,
					map: myMap
				});

				// Mengatur batas peta ke polygon
				var bounds = new google.maps.LatLngBounds();
				poly.getPath().forEach(function (latLng) {
					bounds.extend(latLng);
				});

				myMap.fitBounds(bounds); // Mengatur peta agar zoom dan center sesuai dengan batas polygon
			} else {
				myMap.setCenter(myLatLng); // Jika tidak ada polygon, tetapkan pusat peta ke lokasi petani
			}
		};

		$(document).ready(function() {

			function formatListItem(label, value) {
				return '<li class="list-group-item d-flex justify-content-between">' +
					'<span class="text-muted">' + label + '</span>' +
					'<span>' + value + '</span>' +
				'</li>';
			}

			var noIjin = '{{$ijin}}';
			var tcode = '{{$verifikasi->tcode}}';
			var formattedNoIjin = noIjin.replace(/[\/.]/g, '');
			function formatDate(dateString) {
				if (!dateString) return '';
				var date = new Date(dateString);
				var options = { year: 'numeric', month: 'long', day: 'numeric' };
				return new Intl.DateTimeFormat('id-ID', options).format(date);
			}
			$.ajax({
				url: "{{ route('2024.datafeeder.getVerifTanamByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
				type: "GET",
				success: function(response) {
					$('#companytitle').text(response.data.perusahaan);
					$('#no_ijin').text(response.data.no_ijin);
					$('#tgl_ijin').text(response.data.tgl_ijin);
					$('#tgl_akhir').text(response.data.tgl_akhir);
					$('#created_at').text(response.data.created_at);
					$('#jml_anggota').text(response.data.countAnggota + ' orang');

					$('.checkBerkas').hide();
					$('.checkPks').hide();
					$('.checkTimeline').hide();
					$('.checkLokasi').hide();
					$('.checkFinal').hide();

					if (response.data.status > 0) {
						$('.checkBerkas').show();
					}
					if (response.data.status > 1) {
						$('.checkPks').show();
						$('#pksCheck').dataTable({
							responsive: true,
							lengthChange: false,
							ordering: true,
							processing: true,
							serverSide: true,
							ajax: {
								url: "{{ route('2024.datafeeder.getPksByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
								type: "GET",
							},
							columns: [
								{data: 'no_perjanjian'},
								{data: 'nama_poktan'},
								{
									data: 'tgl_perjanjian_start',
									render: function(data, type, row) {
										return formatDate(data);
									}
								},
								{
									data: 'tgl_perjanjian_end',
									render: function(data, type, row) {
										return formatDate(data);
									}
								},
								{
									data: 'status',
									render: function(data, type, row) {
										if (data === null) {
											return '<span class="btn btn-icon btn-xs btn-warning "><i class="fal fa-exclamation-circle"></i></span>';
										} else if (data === 'Sesuai') {
											return '<span class="btn btn-icon btn-xs btn-success"><i class="fal fa-check"></i></span>';
										} else if (data === 'Tidak Sesuai') {
											return '<span class="btn btn-icon btn-xs btn-danger"><i class="fas fa-ban"></i></span>';
										} else {
											return ''; // Jika nilai status tidak sesuai dengan kondisi yang diberikan
										}
									}
								},
							],
							rowCallback: function(row, data) {
								$('td:eq(2)', row).addClass('text-right');
								$('td:eq(3)', row).addClass('text-right');
								$('td:eq(4)', row).addClass('text-center');
								$(row).css('cursor', 'pointer');
								$(row).on('click', function() {
									$('#pksCheck tbody tr').removeClass('selected');
									$(this).addClass('selected');

									if ($(this).next().hasClass('expanded-row')) {
										$(this).next().remove();
									} else {
										var formHTML = '<tr class="expanded-row"><td colspan="5">';
										var berkasUrl = '{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun) }}/' + data.berkas_pks;
											formHTML += '<form class="" id="expandForm">';
												formHTML += '<ul class="list-group">';
													//link berkas
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Berkas:</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<span class="text-left"><a href="' + berkasUrl + '" download>' + data.berkas_pks + '</a></div></span>';
														formHTML += '</div>';
													formHTML += '</li>';

													//status periksa
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Hasil Pemeriksaan:</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<div class="form-group">';
																formHTML += '<label for="status" class="sr-only">Status Pemeriksaan</label>';
																formHTML += '<select id="status" name="status" class="form-control form-control-sm" required>';
																	formHTML += '<option hidden value="">- pilih status periksa -</option>';
																	formHTML += '<option value="Sesuai"' + (data.status === 'Sesuai' ? ' selected' : '') + '>Sesuai</option>';
																	formHTML += '<option value="Tidak Sesuai"' + (data.status === 'Tidak Sesuai' ? ' selected' : '') + '>Tidak Sesuai</option>';
																formHTML += '</select>';
																formHTML += '<small id="helpId" class="text-muted">Status hasil pemeriksaan.</small>';
															formHTML += '</div>';
														formHTML += '</div>';
													formHTML += '</li>';

													//Catatan periksa
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Catatan Verifikasi:</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<div class="form-group">';
																formHTML += '<label for="note" class="sr-only">Catatan Verifikasi:</label>';
																formHTML += '<textarea id="note" name="note" class="form-control form-control-sm" rows="3">' + (data.note || '') + '</textarea>';
																formHTML += '<small id="helpId" class="text-muted">Catatan pemeriksaan.</small>';
															formHTML += '</div>';
														formHTML += '</div>';
													formHTML += '</li>';

													//Submit hasil
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Tindakan</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<button class="btn btn-warning btn-block btn-xs" type="button" id="submitCheckPks" data-code="'+ data.kode_poktan +'">Simpan</button>';
														formHTML += '</div>';
													formHTML += '</li>';
												formHTML += '</ul>';
											formHTML += '</form>';
										formHTML += '</td></tr>';

										$(this).after(formHTML);
									}
								});
							}
						});
					}
					if (response.data.status > 2) {
						$('.checkTimeline').show();
						$('#timeLine').dataTable({
							responsive: true,
							lengthChange: false,
							ordering: true,
							processing: true,
							serverSide: true,
							ajax: {
								url: "{{ route('2024.datafeeder.timeline', [':noIjin']) }}".replace(':noIjin', noIjin),
								type: "GET",
							},
							columns: [

								{data: 'kode_spatial'},
								{
									data: 'awal_pks',
									render: function(data, type, row) {
										var ijinStart = new Date(row.mulai_ijin);
										var ijinEnd = new Date(row.akhir_ijin);
										var awalPks = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAwalPks = awalPks.toLocaleDateString('id-ID', options);
										var textClass = (awalPks >= ijinStart && awalPks <= ijinEnd) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAwalPks}</span>`;
									}
								},
								{
									data: 'akhir_pks',
									render: function(data, type, row) {
										var ijinStart = new Date(row.mulai_ijin);
										var ijinEnd = new Date(row.akhir_ijin);
										var akhirPks = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAkhirPks = akhirPks.toLocaleDateString('id-ID', options);
										var textClass = (akhirPks >= ijinStart && akhirPks <= ijinEnd) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAkhirPks}</span>`;
									}
								},
								{
									data: 'awal_tanam',
									render: function(data, type, row) {
										var ijinStart = new Date(row.mulai_ijin);
										var ijinEnd = new Date(row.akhir_ijin);
										var pksStart = new Date(row.awal_pks);
										var pksEnd = new Date(row.akhir_pks);

										if (!data) {
											return '<span class="text-danger"></span>';
										}

										var awalTanam = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAwalTanam = awalTanam.toLocaleDateString('id-ID', options);
										var textClass = (awalTanam >= ijinStart && awalTanam <= ijinEnd || awalTanam >= pksStart && awalTanam <= pksEnd) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAwalTanam}</span>`;
									}
								},
								{
									data: 'akhir_tanam',
									render: function(data, type, row) {
										var ijinStart = new Date(row.mulai_ijin);
										var ijinEnd = new Date(row.akhir_ijin);
										var pksStart = new Date(row.awal_pks);
										var pksEnd = new Date(row.akhir_pks);

										if (!data) {
											return '<span class="text-danger"></span>';
										}

										var akhirTanam = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAkhirTanam = akhirTanam.toLocaleDateString('id-ID', options);
										var textClass = (akhirTanam >= ijinStart && akhirTanam <= ijinEnd || akhirTanam >= pksStart && akhirTanam <= pksEnd) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAkhirTanam}</span>`;
									}
								},
								{
									data: 'awal_panen',
									render: function(data, type, row) {
										var akhirTanam = new Date(row.akhir_tanam);

										if (!data) {
											return '<span class="text-danger"></span>';
										}

										var awalPanen = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAwalPanen = awalPanen.toLocaleDateString('id-ID', options);
										var textClass = (akhirTanam && awalPanen > akhirTanam) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAwalPanen}</span>`;
									}
								},
								{
									data: 'akhir_panen',
									render: function(data, type, row) {
										var akhirTanam = new Date(row.akhir_tanam);
										var awalPanen = new Date(row.awal_panen);

										if (!data) {
											return '<span class="text-danger"></span>';
										}

										var akhirPanen = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAkhirPanen = akhirPanen.toLocaleDateString('id-ID', options);
										var textClass = (akhirTanam && awalPanen && akhirPanen >= awalPanen) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAkhirPanen}</span>`;
									}
								}
							],
						});
					}
					if (response.data.status > 3) {
						$('.checkLokasi').show();
						$('#lokasiCheck').dataTable({
							responsive: true,
							lengthChange: false,
							ordering: true,
							processing: true,
							serverSide: true,
							ajax: {
								url: "{{ route('2024.datafeeder.getLokasiByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
								type: "GET",
							},
							columns: [
								{data: 'kode_spatial'},
								{
									data: 'luas_lahan',
									render: function(data, type, row) {
										var luasHa = data ? data / 10000 : 0;
										return luasHa + ' ha';
									}
								},
								{
									data: 'luas_tanam',
									render: function(data, type, row) {
										var luasHa = data ? data / 10000 : 0;
										return luasHa + ' ha';
									}
								},
								{data: 'nama_kelompok'},
								{data: 'nama_petani'},
								{
									data: 'status',
									render: function(data, type, row) {
										var status;
										if (data === 1) {
											status = '<span class="badge badge-success btn-xs btn-icon text"><i class="fal fa-check"></i></span>';
										} else if (data === 0) {
											status = '<span class="badge badge-danger btn-xs btn-icon text"><i class="fal fa-ban"></i></span>';
										} else {
											status = '<span class="badge badge-warning btn-xs btn-icon text"><i class="fal fa-exclamation-circle"></i></span>';
										}
										return status;
									}
								},
							],
							rowCallback: function(row, data) {
								$(row).css('cursor', 'pointer');
								$('td:eq(1)', row).addClass('text-right');
								$('td:eq(2)', row).addClass('text-right');
								$('td:eq(5)', row).addClass('text-center');
							}
						});

						$('#lokasiCheck tbody').on('click', 'tr', function () {
							var data = $('#lokasiCheck').DataTable().row(this).data();
							$('#modalDetail').modal('show');
							$('#modalDetail .modal-body').html(
								'<div class="d-flex">Memuat data dan peta<span id="progressText" class="d-inline-block ml-auto">0%</span></div>' +
								'<div class="progress progress-sm mb-3">' +
								'<div id="progressBar" class="progress-bar bg-info-400" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>' +
								'</div>'
							);

							$('#btnLokasiCheck').hide();
							var nik = data.ktp_petani;
							$.ajax({
								url: "{{ route('2024.datafeeder.getLokasiByIjinNik', [':noIjin', ':nik']) }}".replace(':noIjin', noIjin).replace(':nik', nik),
								type: "GET",
								xhrFields: {
									onprogress: function(e) {
										if (e.lengthComputable) {
											var percentComplete = (e.loaded / e.total) * 50;
											$('#progressBar').css('width', percentComplete + '%');
											$('#progressText').text(percentComplete.toFixed(0) + '%');
										}
									}
								},
								success: function(response) {
									var progressStep = 50;
									var interval = setInterval(function() {
										if (progressStep <= 100) {
											$('#progressBar').css('width', progressStep + '%');
											$('#progressText').text(progressStep.toFixed(0) + '%');
											progressStep += 1;
										} else {
											clearInterval(interval);
											$('#btnLokasiCheck').show();
											var luasHa = response[0].luas_lahan / 10000;
											var luasTanam = response[0].luas_tanam ? response[0].luas_tanam : 0;
											var awalTanam = response[0].awal_tanam ? response[0].awal_tanam : 'no date';
											var akhirTanam = response[0].akhir_tanam ? response[0].akhir_tanam : 'no date';


											var html = '<div id="myMap" style="width: 100%; height: 250px;"></div>';
											html += '<ul class="list-group">';
											html += formatListItem('Kode Spatial', data.kode_spatial);
											html += formatListItem('Nama Pengelola', response[0].nama_petani);
											html += formatListItem('KTP Pengelola', response[0].ktp_petani);
											html += formatListItem('Luas Lahan', luasHa + ' ha');
											html += formatListItem('Luas Tanam', luasTanam + ' ha');
											html += formatListItem('Awal Tanam', awalTanam);
											html += formatListItem('Akhir Tanam', akhirTanam);
											html += '</ul>';

											html += '<div class="row mt-5"><span class="col-12 h6">Gallery</span></div>';

											html += '<div id="js-lightgallery" class="mb-3">';
												if (response[0].fototanam && response[0].fototanam.length > 0) {
													response[0].fototanam.forEach(function(foto) {
														var fotoUrl = "{{ asset('storage/uploads/' . $npwp . '/' . $periodetahun) }}/" + foto.url;
														html += '<a href="' + fotoUrl + '" data-sub-html="' + foto.url + '">';
														html += '<img class="img-responsive" src="' + fotoUrl + '" alt="Tanam">';
														html += '</a>';
													});
												}
												if (response[0].fotoProduksi && response[0].fotoProduksi.length > 0) {
													response[0].fotoProduksi.forEach(function(foto) {
														var fotoUrl = "{{ asset('storage/uploads/' . $npwp . '/' . $periodetahun) }}/" + foto.url;
														html += '<a href="' + fotoUrl + '" data-sub-html="' + foto.url + '">';
														html += '<img class="img-responsive" src="' + fotoUrl + '" alt="Produksi">';
														html += '</a>';
													});
												}
											html += '</div>';

											html += '<div class="form-group mt-3">';
											html += '<label>Hasil Pemeriksaan</label>';
											html += '<select class="form-control form-control-sm" name="statuslokasi" id="statuslokasi">';
											html += '<option value="">- Pilih status -</option>';
											html += '<option value="1"' + (response[0].status === 1 ? ' selected' : '') + '>Sesuai</option>';
											html += '<option value="0"' + (response[0].status === 0 ? ' selected' : '') + '>Tidak Sesuai</option>';
											html += '</select></div>';
											$('#modalDetail .modal-body').html(html);

											// Inisialisasi peta dengan marker dan polygon
											initMap(response[0].latitude, response[0].longitude, response[0].polygon);

											// Inisialisasi LightGallery
											var $initScope = $('#js-lightgallery');
											if ($initScope.length) {
												$initScope.justifiedGallery({
													border: -1,
													rowHeight: 150,
													margins: 8,
													waitThumbnailsLoad: true,
													randomize: false,
												}).on('jg.complete', function () {
													$initScope.lightGallery({
														thumbnail: true,
														animateThumb: true,
														showThumbByDefault: true,
													});
												});
											}

											$initScope.on('onAfterOpen.lg', function (event) {
												$('body').addClass("overflow-hidden");
											});

											$initScope.on('onCloseAfter.lg', function (event) {
												$('body').removeClass("overflow-hidden");
											});
										}
									}, 20);
								},
								error: function(xhr, status, error) {
									console.error(xhr.responseText);
									// Tampilkan pesan error jika ada masalah dalam memuat data
									$('#modalDetail .modal-body').html('<p class="text-danger">Integritas data tidak memenuhi syarat menyebabkan kesalahan saat memuat data.</p>');
									$('#btnLokasiCheck').hide();
								}
							});
							$('#btnLokasiCheck').off('click').on('click', function() {
								var statuslokasi = $('#statuslokasi').val();
								$.ajax({
									url: "{{ route('2024.verifikator.tanam.storelokasicheck', [':noIjin', ':kdspatial']) }}".replace(':noIjin', noIjin).replace(':kdspatial', data.kode_spatial),
									type: "POST",
									data: {
										statuslokasi: statuslokasi,
										_token: '{{ csrf_token() }}'
									},
									success: function(response) {
										$('#modalDetail').modal('hide');
										$('#lokasiCheck').DataTable().ajax.reload();
										Swal.fire({
											title: 'Sukses',
											text: 'Data berhasil disimpan!',
											icon: 'success',
											confirmButtonText: 'OK'
										}).then((result) => {
											if (result.isConfirmed) {
												console.log('datatable reloaded');
											}
										});
									},
									error: function(xhr, status, error) {
										console.error(xhr.responseText);
										Swal.fire({
											title: 'Error',
											text: 'Terjadi kesalahan saat menyimpan data.',
											icon: 'error',
											confirmButtonText: 'OK'
										});
									}
								});
							});
						});
					}

					if (response.data.status > 4){
						$('.checkFinal').show();
					}

					var kemitraan = response.data.countPks + ' berkas / ' + response.data.countPoktan + ' kelompok';
					if (response.data.countPoktan / response.data.countPks == 1){
						$('#countPks').html('<span class="text-success">' + kemitraan + '</span>');
					}else{
						$('#countPks').html('<span class="text-danger">' + kemitraan + '</span>');
					}

					var realisasitanam = response.data.sumLuasTanam + ' / ' + response.data.sumLuas + ' ha';
					if (response.data.sumLuasTanam / response.data.sumLuas < 1){
						$('#luas_tanam').html('<span class="text-danger">' + realisasitanam + '</span>');
					}else{
						$('#luas_tanam').html('<span class="text-success">' + realisasitanam + '</span>');
					}

					var lokasiTanam = response.data.countTanam + ' / ' + response.data.countSpatial + ' titik';
					if (response.data.countTanam / response.data.countSpatial < 1){
						$('#jml_titik').html('<span class="text-danger">' + lokasiTanam + '</span>');
					}else{
						$('#jml_titik').html('<span class="text-success">' + lokasiTanam + '</span>');
					}

					var logoUrl = response.data.logo;
					$('#companyLogo').css('background-image', 'url(' + logoUrl + ')');
				}
			});

			//simpan hasil pemeriksaan berkas PKS
			$(document).on('click', '#submitCheckPks', function() {
				var tcode = $(this).data('code');
				var status = $('#status').val(); // Mengambil nilai dari input status
				var note = $('#note').val(); // Mengambil nilai dari input note

				$.ajax({
					url: "{{ route('2024.verifikator.tanam.verifPksStore', [':noIjin', ':tcode']) }}".replace(':noIjin', noIjin).replace(':tcode', tcode),
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						status: status,
						note: note
					},
					success: function(response) {
						Swal.fire({
							icon: 'success',
							title: 'Progress Pemeriksaan',
							text: 'Status pemeriksaan ditandai sebagai ' + status,
						}).then((result) => {
							if (result.isConfirmed) {
								$('#pksCheck').DataTable().ajax.reload();
							}
						});
					},
					error: function(xhr, status, error) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: error,
						});
					}
				});
			});

			//simpan hasi pemeriksaan tahap berkas
			$(document).on('change', '.saveCheckBerkas', function() {
				var field = $(this).attr('id');
				var url = '{{ route("2024.verifikator.tanam.saveCheckBerkas", ":noIjin") }}'.replace(':noIjin', noIjin);

				var data = {
					_token: '{{ csrf_token() }}'
				};

				// Mengambil nilai dari select yang diubah
				data[field] = $(this).val();

				$.ajax({
					url: url,
					type: 'POST',
					data: data,
					success: function(response) {
						var statusText = $('#' + field + ' option:selected').text();
						Swal.fire({
							icon: 'success',
							title: 'Progress Pemeriksaan',
							text: 'Berkas sudah diperiksa dengan status ' + statusText,
						}).then((result) => {
							if (result.isConfirmed) {
								// Reload DataTable atau tindakan lain
								$('#lokasiCheck').DataTable().ajax.reload();
							}
						});
					},
					error: function(xhr, status, error) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'There was an error updating the status.',
						});
					}
				});
			});

			//menandai fase/tahap pemeriksaan
			$(document).on('click', '.btnStatus', function() {
				var status = $(this).data('status');
				var url = '{{ route("2024.verifikator.tanam.markStatus", [":noIjin", ":tcode", ":status"]) }}'
					.replace(':noIjin', noIjin)
					.replace(':tcode', tcode)
					.replace(':status', status);

				$.ajax({
					url: url,
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}'
					},
					success: function(response) {
						console.log('Response Status:', response.status);
						var statusText;
						var statusInt = parseInt(response.status);
						switch (statusInt) {
							case 2:
								statusText = 'BERKAS-BERKAS';
								break;
							case 3:
								statusText = 'PKS';
								break;
							case 4:
								statusText = 'TIMELINE REALISASI';
								break;
							case 5:
								statusText = 'LOKASI TANAM';
								break;
							default:
								statusText = 'Unknown Status';
								break;
						}

						Swal.fire({
							icon: 'success',
							title: 'Progress Pemeriksaan',
							text: 'Status pemeriksaan ' + statusText + ' ditandai SELESAI',
						}).then((result) => {
							if (result.isConfirmed) {

								$('#lokasiCheck').DataTable().ajax.reload();
							}
						});
					},
					error: function(xhr, status, error) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'There was an error updating the status.',
						});
					}
				});
			});
		});
	</script>
@endsection
