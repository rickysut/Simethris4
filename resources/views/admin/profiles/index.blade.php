@extends('layouts.admin')
@section('content')
{{-- @include('partials.breadcrumb') --}}
@include('partials.subheader')
<style>
	.error {
		color: #F00;
		background-color: #FFF;
	}
</style>
@if (Auth::user()->roles[0]->title == 'Admin' || Auth::user()->roles[0]->title == 'Verifikator')
<div class="row mb-5">
	<div class="col text-center">
		<span class="h3">Maaf, Anda tidak memerlukan halaman ini</span><br>
		<i class="fal fa-grin-tongue-squint text-warning display-2"></i>
	</div>
</div>
@else
<div class="alert alert-warning" role="alert">
	<strong>Info!</strong> Perubahan data profile hanya dapat dilakukan melalui aplikasi RIPH (SIAP RIPH).
</div>
<div class="panel" >
	<div class="panel-hdr">
		<h2></h2>
		<div class="panel-toolbar">
			@include('partials.globaltoolbar')
		</div>
	</div>
	<div class="panel-container">
		<form id="profileform" method="POST" action="{{ route('admin.profile.update', [auth()->user()->id]) }}" enctype="multipart/form-data">
			@csrf
			<div class="panel-content">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div name="panel-1" class="panel" data-title="Panel Data" data-intro="Panel ini berisi data-data" data-step="2">
									<div class="panel-hdr">
										<h2>
											Informasi Perusahaan <span class="fw-300"></span>
										</h2>
									</div>
									<div class="panel-container show row">
										<div class="col-md-3">
											<div class="panel-container show">
												<div class="panel-content">
													<div class="d-flex flex-column align-items-center justify-content-center">
														<div class="d-flex flex-column align-items-center justify-content-center">
															<img id="imgavatar" src="{{ asset(optional($data_user)->avatar ? 'storage/'.$data_user->avatar : 'img/avatars/user.png') }}" class="img-thumbnail rounded-circle shadow-2" alt="" style="width: 90px; height: 90px">
															<h5 class="mb-0 fw-700 text-center mt-3 mb-3">
																Foto Anda
															</h5>
														</div>
														<div class="form-group">
															<label class="form-label" for="firstname">Ganti foto</label>
															<div class="custom-file">
																<input type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" name="avatar" aria-describedby="avatar" onchange="readURL(this,1);">
																<label class="custom-file-label" for="avatar"></label>
															</div>
															<span class="help-block">Klik browse untuk memilih file</span>
														</div>
													</div>
												</div>

												<div class="panel-content">
													<div class="d-flex flex-column align-items-center justify-content-center">
														<div class="d-flex flex-column align-items-center justify-content-center">
															<img id="imglogo" src="{{ asset(optional($data_user)->logo ? 'storage/'.$data_user->logo : 'img/avatars/farmer.png') }}" class="img-thumbnail rounded-circle shadow-2" alt="" style="width: 90px; height: 90px">
															<h5 class="mb-0 fw-700 text-center mt-3 mb-3">
																Logo Perusahaan
															</h5>
														</div>
														<div class="form-group">
															<label class="form-label" for="firstname">Ganti Logo Perusahaan</label>
															<div class="custom-file">
																<input type="file" class="custom-file-input" name="logo" aria-describedby="logo" onchange="readURL(this,2);" accept=".jpeg, .jpg, .png">
																<label class="custom-file-label" for="logo"></label>
															</div>
															<span class="help-block">Klik browse untuk mengganti logo</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-9">
											<div class="panel-container show">
												<div class="panel-content">
													<div class="form-group row">
														<label class="col-xl-12 form-label" for="company_name">Nama Perusahaan</label>
														<div class="col-md-12">
															<input type="text" name="company_name" class="form-control" placeholder="Nama Perusahaan" value="{{ ($data_user->company_name??'') }}" readonly>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-md-6">
															<label class="form-label" for="pic_name">Penanggung Jawab</label>
															<input type="text" name="pic_name" class="form-control" placeholder="Nama Penanggung Jawab" value="{{ ($data_user->pic_name??'') }}" readonly>
														</div>
														<div class="col-md-6">
															<label class="form-label" for="jabatan">Jabatan</label>
															<input type="text" name="jabatan" class="form-control" placeholder="Jabatan Di Perusahaan" value="{{ ($data_user->jabatan??'') }}" readonly>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-md-6">
															<label class="form-label" for="npwp_company">Nomor Pokok Wajib Pajak (NPWP)</label>
															<input type="text" name="npwp_company" class="form-control npwp_company" placeholder="00.000.000.0-000.000"  value="{{ ($data_user->npwp_company??'') }}" readonly>
														</div>
														<div class="col-md-6">
															<label class="form-label" for="nib_company">Nomor Induk Berusaha (NIB)</label>
															<input type="text" name="nib_company" class="form-control nib_company" placeholder="Nomor Induk Berusaha" value="{{ ($data_user->nib_company??'') }}" readonly>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-md-6">
															<label class="form-label" for="fix_phone">No. Telepon</label>
															<input type="text" name="fix_phone" class="form-control" placeholder="Nomor Telepon Perusahaan" value="{{ ($data_user->fix_phone??'') }}" readonly>
														</div>
														<div class="col-md-6">
															<label class="form-label" for="fax">No. Fax</label>
															<input type="text" name="fax" class="form-control" placeholder="Nomor Fax Perusahaan" value="{{ ($data_user->fax??'') }}" readonly>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-md-6">
															<label class="form-label" for="email_company">Email Perusahaan</label>
															<input type="text" name="email_company" class="form-control email_company" placeholder="Email Perusahaan" value="{{ ($data_user->email_company??'') }}" readonly>
														</div>
														<div class="col-md-6">
															<label class="form-label" for="kodepos">Kode Pos</label>
															<input type="text" name="kodepos" class="form-control kodepos" placeholder="Kode Pos" value="{{ ($data_user->kodepos??'') }}" readonly>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-xl-12 form-label" for="address_company">Alamat </label>
														<div class="col-md-12">
															<textarea type="text" name="address_company" class="form-control" placeholder="Alamat" rows="2" readonly>{{ ($data_user->address_company??'') }}</textarea>
														</div>
													</div>

													<div class="form-group row">
														<div class="col-md-6">
															<label class="form-label" for="provinsi">Provinsi</label>
															<select id="province" class="select2-prov form-control w-100 disabled" name="provinsi" disabled>
																<option value="">-- Pilih Provinsi --</option>
															</select>
														</div>
														<div class="col-md-6">
															<label class="form-label" for="kabupaten">Kabupaten</label>
															<select id="kabupaten" class="select2-kab form-control w-100 disabled" name="kabupaten" disabled>
																<option value="">-- Pilih Kabupaten --</option>
															</select>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-md-6">
															<label class="form-label" for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
															<select id="kecamatan" class="select2-kec form-control w-100" name="kecamatan" required>
																<option value="">-- Pilih Kecamatan --</option>
															</select>
														</div>
														<div class="col-md-6">
															<label class="form-label" for="desa">Desa <span class="text-danger">*</span></label>
															<select id="desa" class="select2-des form-control w-100" name="desa" required>
																<option value="">-- Pilih Desa --</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div name="panel-2" class="panel" data-title="Panel Data" data-intro="Panel ini berisi data-data" data-step="2">
									<div class="panel-hdr">
										<h2>
											Data Operator <span class="fw-300"></span>
										</h2>

									</div>
									<div class="panel-container show">
										<div class="panel-content">
											<div class="form-group row">
												<div class="col-md-6">
													<label class="form-label" for="name">Nama Lengkap</label>
													<input type="text" id="name" name="name"  class="form-control" placeholder="Nama Lengkap" value="{{ ($data_user->name??'') }}" readonly>
												</div>
												<div class="col-md-6">
													<label class="form-label" for="email">Email</label>
													<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ auth()->user()->email }}" readonly autocomplete="email">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label class="form-label" for="mobile_phone">No. Handphone</label>
													<input type="text" name="mobile_phone" class="form-control" placeholder="No. Handphone" value="{{ ($data_user->mobile_phone??'') }}" readonly>
													<div class="help-block">Jangan menggunakan no. pribadi.</div>
												</div>
												<div class="col-md-6">
													<label class="form-label" for="ktp">No. KTP</label>
													<input type="text" name="ktp" class="form-control ktp" placeholder="No. KTP" value="{{ ($data_user->ktp??'') }}" readonly>
													<div class="help-block">Diisi digit no KTP</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div name="panel-3" class="panel" data-title="Panel Data" data-intro="Panel ini berisi data-data" data-step="2">
									<div class="panel-hdr">
										<h2>
											Berkas-berkas <span class="fw-300"></span>
										</h2>

									</div>
									<div class="panel-container show">
										<div class="panel-content">
											<div class="form-group">
												<label class="form-label" for="imagektp">ID Card/KTP</label>
												<div class="custom-file">
													<input type="file" accept=".jpg, .png" class="custom-file-input" name="imagektp" aria-describedby="imagektp" value="">
													<label class="custom-file-label" for="imagektp"></label>
												</div>
												<span class="help-block">
													@if($data_user->ktp_image)
														<a href="{{ asset($data_user->ktp_image) }}" target="blank">Lihat KTP</a>
													@else
														Unggah foto KTP. JPG atau PNG, max 2Mb.
													@endif
												</span>
											</div>
											<div class="form-group">
												<label class="form-label" for="assignment">Assignment/Surat Tugas</label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="assignment" aria-describedby="assignment" value="" accept=".pdf">
													<label class="custom-file-label" for="assignment"></label>
												</div>
												<span class="help-block">
													@if($data_user->assignment)
														<a href="{{ asset($data_user->assignment) }}" target="blank">Lihat Surat Tugas</a>
													@else
														Unggah surat tugas. PDF max 2Mb.
													@endif
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row no-gutters">
				<div class="col-md-1 ml-auto mr-3 text-right">
					<button  type="submit" class="btn btn-block btn-danger btn-xm  mb-3 mr-2">SIMPAN</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endif

@endsection

@section('scripts')
@parent
<script src="{{ asset('js/jquery/jquery.validate.js') }}"></script>
{{-- <script src="{{ asset('js/jquery/additional-methods.js') }}"></script> --}}
<script src="{{ asset('js/formplugins/inputmask/inputmask.bundle.js') }}"></script>

	<script>
		$(document).ready(function() {
			$(":input").inputmask();
			$('.npwp_company').mask('00.000.000.0-000.000');
			$('.nib_company').mask('0000000000000');
			$('.kodepos').mask('00000');
			$('.ktp').mask('0000000000000000');
			var $validator = $("#profileform").validate({
				rules: {
					kecamatan: {
						required: true
					},
					desa: {
						required: true
					}
				},
				messages:{
					kecamatan: {
						required: "Pilih kecamatan"
					},
					desa: {
						required: "Pilih Desa / Kelurahan"
					}
				}
			});

			var provinsiSelect = $('#province');
			var kabupatenSelect = $('#kabupaten');
			var kecamatanSelect = $('#kecamatan');
			var desaSelect = $('#desa');

			// Memuat daftar provinsi dan mengisi dropdown provinsi
			$.get('/2024/datafeeder/getAllProvinsi', function(response) {
				var data = response.data;

				if (data && data.length) {
					$.each(data, function(index, value) {
						var option = $('<option>', {
							value: value.provinsi_id,
							text: value.nama
						});

						if ('{{ $data_user->provinsi }}' == value.provinsi_id) {
							option.attr('selected', 'selected');
						}

						provinsiSelect.append(option);
					});

					// Memuat kabupaten jika provinsi sudah dipilih
					var initialProvinsiId = provinsiSelect.val();
					if (initialProvinsiId) {
						loadKabupaten(initialProvinsiId);
					}
				} else {
					provinsiSelect.append($('<option>', { value: '', text: 'Data provinsi tidak tersedia' }));
				}
			});

			// Fungsi untuk memuat kabupaten berdasarkan provinsi
			function loadKabupaten(provinsiId) {
				kabupatenSelect.empty().append($('<option>', { value: '', text: '-- Pilih Kabupaten --' }));
				kecamatanSelect.empty().append($('<option>', { value: '', text: '-- Pilih Kecamatan --' }));
				desaSelect.empty().append($('<option>', { value: '', text: '-- Pilih Desa --' }));

				if (provinsiId) {
					$.get('/2024/datafeeder/getKabByProv/' + provinsiId, function(response) {
						var data = response.data;

						if (data && data.length) {
							$.each(data, function(index, value) {
								var option = $('<option>', {
									value: value.kabupaten_id,
									text: value.nama_kab
								});

								if ('{{ $data_user->kabupaten }}' == value.kabupaten_id) {
									option.attr('selected', 'selected');
								}

								kabupatenSelect.append(option);
							});

							// Memuat kecamatan jika kabupaten sudah dipilih
							var initialKabupatenId = kabupatenSelect.val();
							if (initialKabupatenId) {
								loadKecamatan(initialKabupatenId);
							}
						} else {
							kabupatenSelect.append($('<option>', { value: '', text: 'Data kabupaten tidak tersedia' }));
						}
					});
				}
			}

			// Fungsi untuk memuat kecamatan berdasarkan kabupaten
			function loadKecamatan(kabupatenId) {
				kecamatanSelect.empty().append($('<option>', { value: '', text: '-- Pilih Kecamatan --' }));
				desaSelect.empty().append($('<option>', { value: '', text: '-- Pilih Desa --' }));

				if (kabupatenId) {
					$.get('/2024/datafeeder/getKecByKab/' + kabupatenId, function(response) {
						var data = response.data;

						if (data && data.length) {
							$.each(data, function(index, value) {
								var option = $('<option>', {
									value: value.kecamatan_id,
									text: value.nama_kecamatan
								});

								if ('{{ $data_user->kecamatan }}' == value.kecamatan_id) {
									option.attr('selected', 'selected');
								}

								kecamatanSelect.append(option);
							});

							// Memuat desa jika kecamatan sudah dipilih
							var initialKecamatanId = kecamatanSelect.val();
							if (initialKecamatanId) {
								loadDesa(initialKecamatanId);
							}
						} else {
							kecamatanSelect.append($('<option>', { value: '', text: 'Data kecamatan tidak tersedia' }));
						}
					});
				}
			}

			// Fungsi untuk memuat desa berdasarkan kecamatan
			function loadDesa(kecamatanId) {
				desaSelect.empty().append($('<option>', { value: '', text: '-- Pilih Desa --' }));

				if (kecamatanId) {
					$.get('/2024/datafeeder/getKelByKec/' + kecamatanId, function(response) {
						var data = response.data;

						if (data && data.length) {
							$.each(data, function(index, value) {
								var option = $('<option>', {
									value: value.kelurahan_id,
									text: value.nama_desa
								});

								if ('{{ $data_user->desa }}' == value.kelurahan_id) {
									option.attr('selected', 'selected');
								}

								desaSelect.append(option);
							});
						} else {
							desaSelect.append($('<option>', { value: '', text: 'Data desa tidak tersedia' }));
						}
					});
				}
			}

			// Event listener untuk perubahan pada elemen <select> provinsi
			provinsiSelect.change(function() {
				var selectedProvinsiId = provinsiSelect.val();
				loadKabupaten(selectedProvinsiId);
			});

			// Event listener untuk perubahan pada elemen <select> kabupaten
			kabupatenSelect.change(function() {
				var selectedKabupatenId = kabupatenSelect.val();
				loadKecamatan(selectedKabupatenId);
			});

			// Event listener untuk perubahan pada elemen <select> kecamatan
			kecamatanSelect.change(function() {
				var selectedKecamatanId = kecamatanSelect.val();
				loadDesa(selectedKecamatanId);
			});

			$(".select2-prov").select2({
				placeholder: "Select Province"
			});
			$(".select2-kab").select2({
				placeholder: "Select Kabupaten"
			});
			$(".select2-kec").select2({
				placeholder: "Select Kecamatan"
			});
			$(".select2-des").select2({
				placeholder: "Select Desa"
			});
		});
	</script>


	<script>
			function readURL(input, id) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();

					reader.onload = function (e) {
						if (id == 1){
							$('#imgavatar')
								.attr('src', e.target.result)
								.width(90)
								.height(90);
						}
						if (id == 2){
							$('#imglogo')
								.attr('src', e.target.result)
								.width(90)
								.height(90);
						}

					};

					reader.readAsDataURL(input.files[0]);
				}
			}

	</script>
@endsection
