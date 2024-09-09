@extends('t2024.layouts.admin')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    async function updateAllDesa() {
		try {
			const response = await axios.get('/2024/spatial/master-wilayah/updateAllDesaFromBPS');
			const provinsiIds = response.data.provinsiIds;
			if (!provinsiIds.length) {
				alert('Tidak ada provinsi untuk diperbarui.');
				return;
			}

			let progressElement = document.getElementById('progress');
			let progressBarElement = document.getElementById('progressBar');
			let statusElement = document.getElementById('status');
			let waitTimeElement = document.getElementById('waitTime');

			for (let i = 0; i < provinsiIds.length; i++) {
				let provinsiId = provinsiIds[i];
				statusElement.innerText = `Memperbarui provinsi ID ${provinsiId}...`;

				let updateResponse = await axios.get(`/2024/spatial/master-wilayah/updateDesaFromBPS/${provinsiId}`);
				let updateResult = updateResponse.data;

				if (updateResult.success) {
					statusElement.innerText = `Sukses: ${updateResult.message}`;
				} else {
					statusElement.innerText = `Gagal: ${updateResult.message}`;
				}

				let progress = Math.round(((i + 1) / provinsiIds.length) * 100);
				progressElement.innerText = `Progress: ${progress}%`;
				progressBarElement.style.width = `${progress}%`;
				progressBarElement.setAttribute('aria-valuenow', progress);

				if (i < provinsiIds.length - 1) {
					let waitTime = 60;
					while (waitTime > 0) {
						waitTimeElement.innerText = `Menunggu ${waitTime} detik sebelum melanjutkan...`;
						await new Promise(resolve => setTimeout(resolve, 1000));
						waitTime--;
					}
					waitTimeElement.innerText = 'Menunggu selesai. Melanjutkan...';
				}
			}

			statusElement.innerText = 'Proses pembaruan selesai.';
			progressBarElement.classList.remove('bg-success');
			progressBarElement.classList.add('bg-primary');
		} catch (error) {
			console.error('Terjadi kesalahan:', error);
			alert('Terjadi kesalahan saat memperbarui data.');
		}
	}
</script>

{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('spatial_data_access')
	<div class="row">
		<div class="col">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>
						Daftar <span class="fw-300"><i>Wilayah</i></span>
					</h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<h3>Pembaruan Data Desa</h3>
						<button onclick="updateAllDesa()" class="btnUpdateDesa" id="btnUpdateDesa">Mulai Pembaruan</button>
						<div id="status">Status: </div>
						<div class="progress">
							<div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="d-flex justify-content-between">
							<div id="progress">Progress: 0%</div>
							<div id="waitTime">Menunggu...</div>
						</div>
					</div>
					<div class="panel-content">
						<div class="row justify-content-between">
							<div class="form-group col-lg-4">
								<label class="form-label" for="provinsi_id">
									Provinsi
								</label>
								<select class="select2 form-control w-100" id="provinsi_id" name="provinsi_id">
									<option value=""></option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="form-label" for="kabupaten_id">
									Kabupaten
								</label>
								<select class="select2 form-control w-100" id="kabupaten_id" name="kabupaten_id">
									<option value=""></option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="form-label" for="kecamatan_id">
									Kecamatan
								</label>
								<select class="select2 form-control w-100" id="kecamatan_id" name="kecamatan_id">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
					<div class="panel-content" id="panelProv">
						<!-- datatable start -->
						<table id="tblWilayah" class="table table-bordered table-hover table-sm table-striped w-100">
							<thead class="thead-themed">
								<th style="width:15%">Kode Wilayah</th>
								<th>Nama Wilayah</th>
								<th>Tindakan</th>
							</thead>
							<tbody>

							</tbody>
						</table>
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
		var provinsiSelect = $('#provinsi_id');
		var kabupatenSelect = $('#kabupaten_id');
		var kecamatanSelect = $('#kecamatan_id');

		var urlProvinsi = "{{ route('wilayah.getAllProvinsi') }}";
		var urlKabupaten = function(provinsiId) { return "{{ route('wilayah.getKabupatenByProvinsi', '') }}/" + provinsiId; };
		var urlKecamatan = function(kabupatenId) { return "{{ route('wilayah.getKecamatanByKabupaten', '') }}/" + kabupatenId; };
		var urlDesa = function(kecamatanId) { return "{{ route('wilayah.getDesaByKecamatan', '') }}/" + kecamatanId; };

		var urlUpdateProv = "{{ route('2024.spatial.updateProvinsiFromBPS', '') }}";
		var urlUpdateKab = function(provinsiId) { return "{{ route('2024.spatial.updateKabupatenFromBPS', '') }}/" + provinsiId; };
		var urlUpdateKec = function(provinsiId) { return "{{ route('2024.spatial.updateKecamatanFromBPS', '') }}/" + provinsiId; };
		var urlUpdateDesa = function(provinsiId) { return "{{ route('2024.spatial.updateDesaFromBPS', '') }}/" + provinsiId; };

		var status = 'need update';

		// Inisialisasi Select2
		$("#provinsi_id, #kabupaten_id, #kecamatan_id").select2({
			placeholder: "-- pilih --"
		});

		// Fungsi untuk mengosongkan dan menambahkan placeholder ke Select2
		function resetSelect2(selectElement, placeholderText) {
			selectElement.empty();
			selectElement.append($('<option>', { value: '', text: placeholderText }));
		}

		// Fungsi untuk mengisi data dropdown
		function populateDropdown(url, selectElement, placeholderText, idField, nameField) {
			$.ajax({
				url: url,
				type: "GET",
				dataType: 'json',
				success: function(response) {
					resetSelect2(selectElement, placeholderText);
					$.each(response.data, function(index, item) {
						selectElement.append(new Option(item[nameField], item[idField]));
					});
				},
				error: function(xhr, status, error) {
					console.error('Gagal mengambil data:', status, error);
				}
			});
		}

		// Mengisi dropdown provinsi
		populateDropdown(urlProvinsi, provinsiSelect, '-- pilih provinsi', 'provinsi_id', 'nama');

		// inisiasi table
		var tblWilayah = $('#tblWilayah').DataTable({
			responsive: true,
			lengthChange: false,
			paging: false,
			ordering: true,
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			ajax: {
				url: urlProvinsi,
				type: "GET",
				dataSrc: "data"
			},
			columns: [
				{data: 'provinsi_id', title: 'Kode BPS'},
				{data: 'nama', title: 'Nama BPS (PROVINSI)'},
				{
					data: 'provinsi_id',
					width: '20%',
					title: 'Tindakan',
					render: function (data, type, row) {
						let url1 = `{{ route('2024.spatial.updateKabupatenFromBPS', ':id') }}`;
						url1 = url1.replace(':id', data);
						let url2 = `{{ route('2024.spatial.updateKecamatanFromBPS', ':id') }}`;
						url2 = url2.replace(':id', data);
						let url3 = `{{ route('2024.spatial.updateDesaFromBPS', ':id') }}`;
						url3 = url3.replace(':id', data);

						return `
							<a href="${url1}" type="button" class="btn btn-icon btn-info btn-xs">1</a>
							<a href="${url2}" type="button" class="btn btn-icon btn-warning btn-xs">2</a>
							<a href="${url3}" type="button" class="btn btn-icon btn-danger btn-xs">3</a>
						`;
					}
				}
			],
			buttons: [
				{
					text: '<small>Prov</small>',
					title: 'Synchronize Provinsi dengan Data BPS',
					titleAttr: 'Synchronize Provinsi dengan Data BPS',
					className: 'btn-outline-success btn-icon btn-sm btnUpdateProv ',
				},
				{
					text: '<i class="fal fa-plus mr-1"></i> Peta Baru',
					className: 'btn btn-xs btn-primary',
					extend: 'collection',
					buttons: [
						{
							text: 'Impor Peta Tunggal',
							action: function (e, dt, node, config) {
								window.location.href = '{{ route('2024.spatial.createsingle') }}';
							}
						},
						{
							text: 'Impor Peta Jamak',
							action: function (e, dt, node, config) {
								$('#modalMultiUpload').modal('show');
							}
						}
					],
					dropup: true // Optional: if you want to drop up
				},
			]
		});

		// pembaruan table
		function updateTable(url, idField, nameField, level) {
			tblWilayah.clear().destroy();
			tblWilayah = $('#tblWilayah').DataTable({
				responsive: true,
				lengthChange: false,
				paging: false,
				ordering: true,
				dom:
					"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
					"<'row'<'col-sm-12'tr>>" +
					"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
				ajax: {
					url: url,
					type: "GET",
					dataSrc: "data"
				},
				columns: [
					{ data: idField, title: idField.replace('_', ' ').toUpperCase(), width: '15%' },
					{ data: nameField, title: 'NAMA BPS (' + idField.split('_')[0].toUpperCase() +')'},
					{ data: idField, title: 'Tindakan', width: '20%',
						render: function (data, type, row) {
							return '';
						}
					}
				],
				buttons: [
					{
						extend: 'pdfHtml5',
						text: '<i class="fa fa-file-pdf"></i>',
						title: 'Daftar Wilayah',
						titleAttr: 'Generate PDF',
						className: 'btn-outline-danger btn-sm btn-icon mr-1',
						exportOptions: {
							columns: [0, 1]
						}
					},
					{
						extend: 'excelHtml5',
						text: '<i class="fa fa-file-excel"></i>',
						title: 'Daftar Wilayah',
						titleAttr: 'Generate Excel',
						className: 'btn-outline-success btn-sm btn-icon mr-1',
						exportOptions: {
							columns: [0, 1]
						}
					},
					{
						text: '<i class="fa fa-undo"></i>',
						title: 'Reset',
						titleAttr: 'Reset',
						className: 'btn-outline-info btn-sm btn-icon mr-1',
						action: function (e, dt, button, config) {
							location.reload();
						}
					}
				]
			});
		}

		provinsiSelect.change(function() {
			var provinsiId = provinsiSelect.val();
			updateTable(urlKabupaten(provinsiId), 'kabupaten_id', 'nama_kab', 'kabupaten');
			resetSelect2(kabupatenSelect, '-- pilih kabupaten');
			resetSelect2(kecamatanSelect, '-- pilih kecamatan');

			if (provinsiId) {
				populateDropdown(urlKabupaten(provinsiId), kabupatenSelect, '-- pilih kabupaten', 'kabupaten_id', 'nama_kab');
			}
		});

		kabupatenSelect.change(function() {
			var kabupatenId = kabupatenSelect.val();
			console.log('id kabupaten: ', kabupatenId);
			updateTable(urlKecamatan(kabupatenId), 'kecamatan_id', 'nama_kecamatan', 'kecamatan');
			resetSelect2(kecamatanSelect, '-- pilih kecamatan');

			if (kabupatenId) {
				populateDropdown(urlKecamatan(kabupatenId), kecamatanSelect, '-- pilih kecamatan', 'kecamatan_id', 'nama_kecamatan');
			}
		});

		kecamatanSelect.change(function() {
			var kecamatanId = kecamatanSelect.val();
			updateTable(urlDesa(kecamatanId), 'kelurahan_id', 'nama_desa', 'desa');
		});

		$('#tblWilayah').on('click', '.btnUpdate', function() {
			var dataId = $(this).data('id');
			var level = $(this).data('level');
			var updateUrl;
			switch (level) {
				case 'provinsi':
					updateUrl = urlUpdateKab(dataId);
					break;
				case 'kabupaten':
					updateUrl = urlUpdateKec(dataId);
					break;
				case 'kecamatan':
					updateUrl = urlUpdateDesa(dataId);
					break;
				default:
					break;
			}

			if (updateUrl) {
				$.ajax({
					url: updateUrl,
					type: "GET",
					dataType: 'json',
					success: function(response) {
						Swal.fire({
							icon: 'success',
							title: 'Sukses!',
							text: 'Data wilayah berhasil diselaraskan!'
						});
						$('#tblWilayah').DataTable().ajax.reload();
					},
					error: function(xhr, status, error) {
						console.error('Gagal memperbarui data:', status, error);
						Swal.fire({
							icon: 'error',
							title: 'Gagal!',
							text: 'Terjadi kesalahan saat memperbarui data.'
						});
					}
				});
			}
		});

		$('.btnUpdateProv').on('click', function(){
			$.ajax({
				url: urlUpdateProv,
				type: "GET",
				dataType: 'json',
				success: function(response) {
					Swal.fire({
						icon: 'success',
						title: 'Sukses!',
						text: 'Data wilayah berhasil diselaraskan!'
					});
					$('#tblWilayah').DataTable().ajax.reload();
				},
				error: function(xhr, status, error) {
					console.error('Gagal memperbarui data:', status, error);
					Swal.fire({
						icon: 'error',
						title: 'Gagal!',
						text: 'Terjadi kesalahan saat memperbarui data.'
					});
				}
			});
		});
	});
</script>
@endsection
