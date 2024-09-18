@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
{{-- @can('spatial_data_access') --}}
	<div class="row">
		<div class="col">
			<div name="panel-2" class="panel" data-title="Panel Data" data-intro="Panel ini berisi data-data" data-step="2">
				<div class="panel-hdr">
					<h2>
						Informasi Biodata <span class="fw-300"></span>
					</h2>

				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form action="">
							<div class="form-group row">
								<div class="col-12">
									<label class="form-label" for="nama_kelompok">Nama Kelompok Tani <span class="text-danger">*</span></label>
									<input type="text" id="nama_kelompok" name="nama_kelompok"  class="form-control @error('nama_kelompok') is-invalid @enderror" placeholder="nama kelompok tani" value="{{ ($poktan->nama_kelompok) }}" required>
									<span class="help-block" id="help-nama"></span>
								</div>
							</div>

							<div class="form-group row">
								<div class="col-md-6">
									<label class="form-label" for="nama_pimpinan">Nama Pimpinan <span class="text-danger">*</span></label>
									<input id="nama_pimpinan" type="nama_pimpinan" class="form-control @error('nama_pimpinan') is-invalid @enderror" placeholder="nama pimpinan kelompok tani" name="nama_pimpinan" value="{{ ($poktan->nama_pimpinan) }}" required>
									<span class="help-block" id="help-pimpinan"></span>
								</div>
								<div class="col-md-6">
									<label class="form-label" for="hp_pimpinan">No. Handphone <span class="text-danger">*</span></label>
									<input type="text" id="hp_pimpinan" name="hp_pimpinan" class="form-control @error('hp_pimpinan') is-invalid @enderror" placeholder="No. Handphone" value="{{ ($poktan->hp_pimpinan) }}" required>
									<div class="help-block" id="help-hp"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-6">
									<label class="form-label" for="id_provinsi">Provinsi <span class="text-danger">*</span></label>
									<select id="id_provinsi" name="id_provinsi" class="form-control w-100 @error('id_provinsi') is-invalid @enderror" required>
										<option value="{{$poktan->provinsi_id}}" selected>{{$poktan->provinsi->nama}}</option>
									</select>
									<span class="help-block" id="help-provinsi"></span>
								</div>
								<div class="col-md-6">
									<label class="form-label" for="id_kabupaten">Kabupaten <span class="text-danger">*</span></label>
									<select id="id_kabupaten" name="id_kabupaten" class="form-control w-100 @error('id_kabupaten') is-invalid @enderror" required>
										<option value="{{$poktan->kabupaten_id}}" selected>{{$poktan->kabupaten->nama_kab}}</option>
									</select>
									<div class="help-block" id="help-kabupaten"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-6">
									<label class="form-label" for="id_kecamatan">Kecamatan <span class="text-danger">*</span></label>
									<select id="id_kecamatan" name="id_kecamatan" class="form-control @error('id_kecamatan') is-invalid @enderror" required>
										<option value="{{$poktan->kecamatan_id}}" selected>{{$poktan->kecamatan->nama_kecamatan}}</option>
									</select>
									<div class="help-block" id="help-kecamatan"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label" for="id_kelurahan">Desa/Kelurahan <span class="text-danger">*</span></label>
									<select id="id_kelurahan" name="id_kelurahan" class="form-control @error('id_kelurahan') is-invalid @enderror" required>
										<option value="{{$poktan->kelurahan_id}}" selected>{{$poktan->desa->nama_desa}}</option>
									</select>
									<div class="help-block" id="help-desa"></div>
								</div>
							</div>
							<div class="form-group row d-flex align-items-end">
								<div class="col-lg-4">
									<label class="form-label" for="kode_register">Kode Registrasi <span class="text-danger">*</span></label>
									<input type="text" id="kode_register" name="kode_register"  class="form-control @error('kode_register') is-invalid @enderror" placeholder="kode registrasi kelompok tani" value="{{$poktan->kode_register}}">
									<span class="help-block" id="help-poktanid"></span>
								</div>
								<div class="col-lg-2">
									<label class="form-label" for="status">Status</label>
									<select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
										<option value="" hidden>-- pilih status</option>
										<option value="Aktif" {{ $poktan->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
										<option value="Tidak Aktif" {{ $poktan->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
									</select>
									<span class="help-block" id="help-status"></span>
								</div>
								<div class="col-lg-6 text-right">
									<button class="btn btn-primary btn-sm" type="submit">Simpan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
{{-- @endcan --}}

@endsection

<!-- start script for this page -->
@section('scripts')
@parent
<script>
	$(document).ready(function() {
		var provinsiSelect = $('#id_provinsi');
		var kabupatenSelect = $('#id_kabupaten');
		var kecamatanSelect = $('#id_kecamatan');
		var desaSelect = $('#id_kelurahan');

		$("#id_provinsi").select2({
			placeholder: "-- pilih provinsi"
		});
		$("#id_kabupaten").select2({
			placeholder: "-- pilih kabupaten"
		});
		$("#id_kecamatan").select2({
			placeholder: "-- pilih kecamatan"
		});
		$("#id_kelurahan").select2({
			placeholder: "-- pilih desa"
		});

		$.get('/2024/datafeeder/getAllProvinsi', function(response) {
			var data = response.data;

			if (data && data.length) {
				$.each(data, function(index, value) {
					var option = $('<option>', {
						value: value.provinsi_id,
						text: value.nama
					});

					if ('{{ $poktan->provinsi }}' == value.provinsi_id) {
						option.attr('selected', 'selected');
					}

					provinsiSelect.append(option);
				});

				// Memuat kabupaten jika provinsi sudah dipilih
				var initialProvinsiId = provinsiSelect.val();
				if (initialProvinsiId) {
					// loadKabupaten(initialProvinsiId);
				}
			} else {
				provinsiSelect.append($('<option>', { value: '', text: 'Data provinsi tidak tersedia' }));
			}
		});

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

							if ('{{ $poktan->kabupaten }}' == value.kabupaten_id) {
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

							if ('{{ $poktan->kecamatan }}' == value.kecamatan_id) {
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

							if ('{{ $poktan->desa }}' == value.kelurahan_id) {
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
	});
</script>
@endsection
