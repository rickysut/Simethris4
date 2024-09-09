@extends('t2024.layouts.admin')
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
		<div class="row" id="contentToPrint">
			<div class="col-lg-3">
				@include('t2024.verifikasi.tanam.infocard')
			</div>
			<div class="col-lg-9">
				<div id="panel-5" class="panel">
					<div class="panel-container show">
						@include('t2024.verifikasi.tanam.checkmenu')
					</div>
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
								<form action="{{route('2024.verifikator.tanam.storeFinalCheck', [$ijin, $tcode])}}" method="POST" enctype="multipart/form-data">
									@csrf
									<div class="panel-content">
										<input type="hidden" name="no_ijin" value="{{$verifikasi->no_ijin}}">
										<input type="hidden" name="tcode" value="{{$verifikasi->tcode}}">
										<input type="hidden" name="npwp" value="{{$verifikasi->npwp}}">

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
													<input type="file" accept=".pdf" class="custom-file-input" name="ndhprt" id="ndhprt" value="{{ old('fileNdhp', $verifikasi ? $verifikasi->fileNdhp : '') }}" >
													<label class="custom-file-label" for="fileNdhp">{{ old('fileNdhp', $verifikasi ? $verifikasi->fileNdhp : 'pilih berkas') }}</label>
												</div>
												@if ($verifikasi->fileNdhp)
													<a href="{{ $verifikasi->fileNdhp }}" class="help-block" target="blank">
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
													<input type="file" accept=".pdf" class="custom-file-input" name="batanam" id="batanam" value="{{ old('fileBa', $verifikasi ? $verifikasi->fileBa : '') }}">
													<label class="custom-file-label" for="batanam">{{ old('fileBa', $verifikasi ? $verifikasi->fileBa : 'pilih berkas') }}</label>
												</div>
												@if ($verifikasi->fileBa)
													<a href="{{ $verifikasi->fileBa }}" class="help-block" target="blank">
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
	@endcan
@endsection

@section('scripts')
	<script src="{{ asset('js/miscellaneous/lightgallery/lightgallery.bundle.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-editor/2.3.1/dataTables.editor.js" integrity="sha512-BKsIfYRuTSaLQncTO/3CUtWr6zko7hbmxWYcBhJ7YqVB1zPIcG0S7hCNf3PLcQds22RlBaVHnKkKLxjSmn9hZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	@parent

	<script>
		$(document).ready(function() {

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
					$('#tgl_ijin').text(formatDate(response.data.tgl_ijin));
					$('#tgl_akhir').text(formatDate(response.data.tgl_akhir));
					$('#created_at').text(formatDate(response.data.created_at));
					$('#jml_anggota').text(response.data.countAnggota + ' orang');

					var kemitraan = response.data.countPks + ' berkas / ' + response.data.countPoktan + ' kelompok';
					if (response.data.countPoktan / response.data.countPks == 1){
						$('#countPks').html('<span class="text-success">' + kemitraan + '</span>');
					}else{
						$('#countPks').html('<span class="text-danger">' + kemitraan + '</span>');
					}

					var realisasitanam = response.data.sumLuasTanam + ' / ' + response.data.sumLuas + ' m2';
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
		});
	</script>
	<script>
		function handleStatusChange() {
			var status = document.getElementById("status").value;
			var ndhprtInput = document.getElementById("ndhprt");
			var batanamInput = document.getElementById("batanam");
			var ndhprtContainer = document.getElementById("ndhprtContainer");
			var batanamContainer = document.getElementById("batanamContainer");

			if (status === "7") { // Jika status adalah 'Perbaikan Data' (5)
				ndhprtInput.disabled = true;
				batanamInput.disabled = true;
				ndhprtContainer.hidden = true;
				batanamContainer.hidden = true;
			} else if (status === "6") { // Jika status adalah 'Sesuai' (4)
				ndhprtContainer.hidden = false;
				batanamContainer.hidden = false;
				ndhprtInput.disabled = false;
				batanamInput.disabled = false;
			}
		}
		function validateInput() {
			// get the input value and the current username from the page
			var status = document.getElementById("status").value;
			var inputVal = document.getElementById('validasi').value;
			var currentUsername = '{{ Auth::user()->username }}';
			var status = document.getElementById("status").value;
			var ndhprtInput = document.getElementById("ndhprt").value;
			var batanamInput = document.getElementById("batanam").value;

			// check if the input is not empty and matches the current username
			if (inputVal !== '' && inputVal === currentUsername) {
				// Jika status = 4, lakukan validasi tambahan
				if (status === "4") {
					if (ndhprtInput === '' || batanamInput === '') {
						alert("Nota Dinas dan Berita Acara harus diunggah jika status adalah 'Sesuai' (4).");
						return false; // Menghentikan pengiriman formulir
					}
				}
				return true; // Lanjutkan pengiriman formulir jika status adalah 'Tidak Sesuai' (5) atau kondisi lainnya
			} else {
				alert('Isi kolom Konfirmasi dengan username Anda!.');
				return false; // prevent form submission
			}
		}
	</script>
@endsection
