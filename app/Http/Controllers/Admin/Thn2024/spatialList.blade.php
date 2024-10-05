@extends('layouts.admin')
@section('styles')
<style>
	.display-5{
		font-size: 1.8rem;
		font-weight: 300;
		line-height: 1.25;
	}
	.select2-container--default .select2-results {
		z-index: 1050; /* Pastikan dropdown muncul di atas modal */
	}
	.line-clamp-1 {
		display: -webkit-box;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}
</style>
@endsection
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('spatial_data_access')
	<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="detailModalLabel">Detail Data</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Konten akan dimuat di sini -->
					<div id="infoLahan" class="mb-5"></div>
					<div id="kemitraanAktif" class="mb-5"></div>
					<div id="historyKemitraan" class="mb-5"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalMultiUpload" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Unggah Berkas</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{ route('2024.spatial.storesingle') }}" enctype="multipart/form-data" method="POST" id="formSubmit">
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<div class="input-group bg-white shadow-inset-2">
								<div class="input-group-prepend">
									<span class="input-group-text bg-transparent border-right-0 py-1 px-3 text-success">
										<i class="fal fa-upload"></i>
									</span>
								</div>
								<div class="custom-file">
									<input type="file" accept=".kml" id="kml_file" name="kml_url[]" class="custom-file-input" multiple required style="display: none;">
    								<label for="kml_file" class="custom-file-label line-clamp-1">Pilih berkas KML...</label>
								</div>
							</div>
							<span class="help-block" id="fileCount" style="display: none;">Unggah berkas KML <span class="text-danger">*</span></span>
						</div>
						<div hidden>
							<div class="form-group row">
								<label for="kode_spatial" class="col-sm-3 col-form-label">Kode Spatial</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="kode_spatial" name="kode_spatial" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="latitude" class="col-sm-3 col-form-label">Latitude</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="latitude" name="latitude" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="longitude" class="col-sm-3 col-form-label">Longitude</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="longitude" name="longitude" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="polygon" class="col-sm-3 col-form-label">Polygon</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="polygon" name="polygon" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="altitude" class="col-sm-3 col-form-label">Altitude (mdpl)</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="altitude" name="altitude" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="luas_lahan" class="col-sm-3 col-form-label">Luas Lahan (m2)</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="luas_lahan" name="luas_lahan" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="poktan_name" class="col-sm-3 col-form-label">Kelompok Tani</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="poktan_name" name="poktan_name" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="ktp_petani" class="col-sm-3 col-form-label">NIK Petani</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="ktp_petani" name="ktp_petani" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="nama_petani" class="col-sm-3 col-form-label">Nama Petani</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" id="nama_petani" name="nama_petani" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="provinsi_nama" class="col-sm-3 col-form-label">Provinsi</label>
								<div class="col-sm-9">
									<input class="form-control" id="provinsi_id" name="provinsi_id" value="">
								</div>
							</div>
							<div class="form-group row">
								<label for="kabupaten_nama" class="col-sm-3 col-form-label">Kabupaten</label>
								<div class="col-sm-9">
									<input class="form-control" id="kabupaten_id" name="kabupaten_id" value="">
								</div>
							</div>
							<div class="form-group row">
								<label for="kecamatan_nama" class="col-sm-3 col-form-label">Kecamatan</label>
								<div class="col-sm-9">
									<input class="form-control" id="kecamatan_id" name="kecamatan_id" value="">
								</div>
							</div>
							<div class="form-group row">
								<label for="kelurahan_nama" class="col-sm-3 col-form-label">Kelurahan</label>
								<div class="col-sm-9">
									<input class="form-control" id="kelurahan_id" name="kelurahan_id" value="">
								</div>
							</div>
						</div>
						{{-- style="display: none;" --}}

						<div id="progressContainer" style="display: none;">
							<span>Mengunggah: <span id="fileName"></span></span>
							<div class="progress">
								<div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<p id="progressText">0%</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeBtn">Close</button>
						<button type="button" class="btn btn-primary" id="uploadBtn">Save</button>
					</div>
				</form>


			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>
						Tabel <span class="fw-300"><i>Spatial</i></span>
					</h2>
					<div class="panel-toolbar">
						<div class="btn-group">
							<button type="button" class="btn btn-xs btn-primary waves-effect waves-themed">
								<i class="fal fa-plus mr-1"></i>
								Peta Baru
							</button>
							<button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-themed" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="sr-only">Toggle Dropdown</span>
								<i class="fal fa-chevron-down"></i>
							</button>
							<div class="dropdown-menu" style="">
								<a href="{{route('2024.spatial.createsingle')}}" onclick="navigateBack();" class="dropdown-item" title="import berkas KML peta satu persatu">
									<i class="fal fa-plus"></i>
									Impor Peta Tunggal
								</a>
								<a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" title="Import berkas KML sekaligus" data-target="#modalMultiUpload">
									<i class="fal fa-layer-plus"></i>
									Impor Peta Jamak
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="card-header">
					<div class="panel-content">
						<h4 class="text-muted">Pencarian Data</h4>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="idKab">Kabupaten</label>
								<select name="idKab" id="idKab" class="custom-select form-control" aria-describedby="helpKab">
									<option value="" hidden>pilih kabupaten</option>
									<option value="" >Semua kabupaten</option>
									@foreach ($indexKabupaten as $kabupaten)
									<option value="{{$kabupaten['kabupaten_id']}}">{{$kabupaten['kabupaten_id']}} - {{$kabupaten['nama_kab']}}</option>
									@endforeach
								</select>
								<small id="helpKab" class="text-muted">saring data sesuai kabupaten dipilih</small>
							</div>
							<div class="form-group col-md-3">
								<label for="status_lahan">Status Lahan</label>
								<select name="status_lahan" id="status_lahan" class="custom-select form-control" aria-describedby="helpStatus">
									<option value="" hidden>pilih status</option>
									<option value="" >Semua status</option>
									<option value="1">Aktif</option>
									<option value="0">Tidak Aktif</option>
								</select>
								<small id="helpStatus" class="text-muted">saring data sesuai status lahan</small>
							</div>
							<div class="form-group col-md-3">
								<label for="status_mitra">Status Kemitraan</label>
								<select name="status_mitra" id="status_mitra" class="custom-select form-control" aria-describedby="helpMitra">
									<option value="" hidden>pilih status</option>
									<option value="" >Semua status</option>
									<option value="1">Bermitra</option>
									<option value="0">Tanpa mitra</option>
								</select>
								<small id="helpMitra" class="text-muted">saring data sesuai status kemitraan</small>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<!-- datatable start -->
						<table id="tblSpatial" class="table table-bordered table-hover table-sm table-striped w-100">
							<thead class="thead-themed">
								<th>Kode Lokasi</th>
								<th>Pengelola</th>
								<th>Luas</th>
								<th>Wilayah</th>
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
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
@parent

{{-- data --}}
<script>
	$(document).ready(function(){
		$('#tblSpatial').dataTable({
			responsive: true,
			lengthChange: false,
			ordering: true,
			processing: true,
			serverSide: true,
			ajax: {
				url: "{{ route('2024.datafeeder.getAllSpatials') }}",
				type: "GET",
				data: function(d) {
					d.kabupaten_id = $('#idKab').val();
					d.status_lahan = $('#status_lahan').val();
					d.status_mitra = $('#status_mitra').val();
					console.log('intip: ', d);
				},
			},
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			"columnDefs": [
				{ "targets": [2, 3], "className": "text-right" },
				{ "targets": [4], "className": "text-center" },
			],
			columns: [
				{ data: 'kode_spatial' },
				{ data: 'nama_anggota' },
				{ data: 'luas_lahan' },
				{
					data: 'kabupaten_id',
					render: function (data, type, row) {
						return row.nama_kabupaten;
					}
				},
				{
					data: 'status',
					render: function(data, type, row) {
						var kdSpatial = row.kode_spatial;
						var kode = kdSpatial.replace(/[^a-zA-Z0-9]/g, '');
						var url = "{{ route('2024.spatial.edit', ':kode') }}";
						var kmlFile = row.kml_url;
						var kmlPath = `{{ asset('storage') }}/${kmlFile}`;
						url = url.replace(':kode', kode);

						var checked = data == 1 ? 'checked' : '';

						var actionBtn = `
							<div class="justify-content-center fs-sm d-flex align-items-center">
								<a href="${url}" class="btn btn-icon btn-xs btn-default waves-effect waves-themed" data-toggle="tooltip" data-offset="0,10" data-original-title="Lihat Peta">
									<i class="fal fa-edit"></i>
								</a>
								<a href="${kmlPath}" class="btn btn-icon btn-xs btn-default waves-effect waves-themed ml-1" title="unduh kml" download>
									<i class="fal fa-download"></i>
								</a>
								<div class="custom-control custom-switch ml-1">
									<input type="checkbox" class="custom-control-input form-control-sm status-switch" id="customSwitch_${kode}" ${checked} data-kode="${kdSpatial}">
									<label class="custom-control-label" for="customSwitch_${kode}"><span class="sr-only">Open - Close</span></label>
								</div>
							</div>
						`;
						return actionBtn;
					}
				},
			],
			buttons: [
				{
					extend: 'pdfHtml5',
					text: '<i class="fa fa-file-pdf"></i>',
					title: 'Daftar Lahan Wajib Tanam Produksi Bawang Putih',
					titleAttr: 'Generate PDF',
					className: 'btn-outline-danger btn-sm btn-icon mr-1'
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					title: 'Daftar Lahan Wajib Tanam Produksi Bawang Putih',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					title: 'Daftar Lahan Wajib Tanam Produksi Bawang Putih',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				}
			]
		});

		$('#idKab, #status_lahan, #status_mitra').change(function() {
			console.log('Kabupaten ID:', $('#idKab').val());  // Logging nilai kabupaten_id
			console.log('Status Lahan:', $('#status_lahan').val());  // Logging nilai status_lahan
			console.log('Status Mitra:', $('#status_mitra').val());  // Logging nilai status_mitra
			$('#tblSpatial').DataTable().draw();
		});
	});

	$(document).on('change', '.status-switch', function() {
		var kode = $(this).data('kode');
		var status = $(this).is(':checked') ? 1 : 0;
		$.ajax({
			url: "{{ route('2024.spatial.updateStatus', ':kode') }}".replace(':kode', kode),
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				status: status
			},
			success: function(response) {
				if(response.success) {
					$('#tblSpatial').DataTable().ajax.reload();
					var action = status ? 'Bermitra' : 'Tersedia';
					var message = `Lokasi ${kode} ${action}`;
					Swal.fire({
						icon: 'success',
						title: 'Aktivasi Lokasi',
						text: message,
						timer: 2000,
						showConfirmButton: false
					});
				} else {
					alert('Failed to update status.');
				}
			},
			error: function() {
				alert('Error updating status.');
			}
		});
	});
</script>

{{-- peta --}}
<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};

	document.getElementById('kml_file').addEventListener('change', function() {
		const fileCount = this.files.length;
		const fileCountElement = document.getElementById('fileCount');
		const fileLabel = document.querySelector('label[for="kml_file"]');

		if (fileCount > 0) {
			fileLabel.textContent = `${fileCount} berkas terpilih`;
			fileCountElement.textContent = `${fileCount} file dipilih`;
			fileCountElement.style.display = 'block';
		} else {
			fileLabel.textContent = 'Pilih berkas KML...';
			fileCountElement.style.display = 'none';
		}
	});
</script>

<script>
	document.getElementById('uploadBtn').addEventListener('click', function () {
		let kmlFiles = document.getElementById('kml_file').files;
		if (kmlFiles.length > 0) {
			disableElementsDuringUpload();
			processFiles(kmlFiles);
		} else {
			alert('Pilih setidaknya satu file.');
		}
	});

	// window.addEventListener('beforeunload', function (e) {
	// 	e.preventDefault();
	// 	e.returnValue = ''; // Ini akan menampilkan peringatan di browser
	// });

	function processFiles(files) {
		let index = 0;
		const totalFiles = files.length;

		// Tampilkan progress bar
		document.getElementById('progressContainer').style.display = 'block';
		document.getElementById('progressText').textContent = `Mengunggah 0 of ${totalFiles} berkas...`;

		function processNextFile() {
			if (index < totalFiles) {
				let file = files[index];
				kml_parser(file, () => {
					uploadFile(file, () => {
						index++;
						updateProgress(index, totalFiles);
						processNextFile();
					});
				});
			} else {
				// Semua file berhasil diunggah
				alert('Semua file telah berhasil diunggah.');
				enableElementsAfterUpload();
				// window.removeEventListener('beforeunload', function (e) {
				// 	e.preventDefault();
				// 	e.returnValue = '';
				// });
				location.reload(); // Refresh halaman setelah konfirmasi
			}
		}

		processNextFile();
	}

	function updateProgress(uploadedFiles, totalFiles) {
		let percentComplete = Math.round((uploadedFiles / totalFiles) * 100);
		const progressBar = document.getElementById('progressBar');

		// Update progress bar width and aria-valuenow attribute
		progressBar.style.width = percentComplete + '%';
		progressBar.setAttribute('aria-valuenow', percentComplete);

		document.getElementById('progressText').textContent = `Mengunggah ${uploadedFiles} dari ${totalFiles} berkas (${percentComplete}%)`;
	}

	function kml_parser(kmlFile, callback) {
		const reader = new FileReader();

		reader.onload = (event) => {
			const kmlData = event.target.result;
			const parser = new DOMParser();
			const kmlXml = parser.parseFromString(kmlData, "application/xml");

			const placemark = kmlXml.getElementsByTagName("Placemark")[0];
			if (placemark) {
				const id_lahan = placemark.querySelector("SimpleData[name='ID_LAHAN']").textContent;
				const nikPetani = placemark.querySelector("SimpleData[name='NIK']").textContent;
				const petani = placemark.querySelector("SimpleData[name='PETANI']").textContent;
				const luas = placemark.querySelector("SimpleData[name='LUAS_LAHAN']").textContent;
				const x = parseFloat(placemark.querySelector("SimpleData[name='LATITUDE']").textContent);
				const y = parseFloat(placemark.querySelector("SimpleData[name='LONGITUDE']").textContent);
				const altitude = parseFloat(placemark.querySelector("SimpleData[name='ALTITUDE']").textContent);
				const desa_id = placemark.querySelector("SimpleData[name='ID_DESA']").textContent;
				const poktanName = placemark.querySelector("SimpleData[name='POKTAN']").textContent;

				const kecamatan_id = desa_id.substring(0, 7);
				const kabupaten_id = desa_id.substring(0, 4);
				const provinsi_id = desa_id.substring(0, 2);

				// Extract Polygon coordinates
				const coordinates = placemark.querySelector("Polygon > outerBoundaryIs > LinearRing > coordinates").textContent.trim();
				const polygonArray = coordinates.split(' ').map(coord => {
					const [lng, lat] = coord.split(',').map(Number);
					return { lat, lng };
				});

				// Isi form dengan data yang diekstrak
				document.getElementById("kode_spatial").value = id_lahan;
				document.getElementById("ktp_petani").value = nikPetani;
				document.getElementById("nama_petani").value = petani;
				document.getElementById("luas_lahan").value = luas;
				document.getElementById("poktan_name").value = poktanName;
				document.getElementById("latitude").value = x;
				document.getElementById("longitude").value = y;
				document.getElementById("altitude").value = altitude;
				document.getElementById("kelurahan_id").value = desa_id;
				document.getElementById("kecamatan_id").value = kecamatan_id;
				document.getElementById("kabupaten_id").value = kabupaten_id;
				document.getElementById("provinsi_id").value = provinsi_id;
				document.getElementById("polygon").value = JSON.stringify(polygonArray);

				callback();
			} else {
				alert("Tidak ditemukan data Placemark dalam file KML.");
				callback();
			}
		};

		reader.readAsText(kmlFile);
	}

	function uploadFile(kmlFile, callback) {
		let formData = new FormData();
		formData.append('kml_url', kmlFile);
		formData.append('kode_spatial', document.getElementById('kode_spatial').value);
		formData.append('latitude', document.getElementById('latitude').value);
		formData.append('longitude', document.getElementById('longitude').value);
		formData.append('polygon', document.getElementById('polygon').value);
		formData.append('altitude', document.getElementById('altitude').value);
		formData.append('luas_lahan', document.getElementById('luas_lahan').value);
		formData.append('poktan_name', document.getElementById('poktan_name').value);
		formData.append('ktp_petani', document.getElementById('ktp_petani').value);
		formData.append('nama_petani', document.getElementById('nama_petani').value);
		formData.append('provinsi_id', document.getElementById('provinsi_id').value);
		formData.append('kabupaten_id', document.getElementById('kabupaten_id').value);
		formData.append('kecamatan_id', document.getElementById('kecamatan_id').value);
		formData.append('kelurahan_id', document.getElementById('kelurahan_id').value);

		// Tampilkan progress bar
		document.getElementById('progressContainer').style.display = 'block';

		let xhr = new XMLHttpRequest();
		xhr.open('POST', '{{ route("2024.spatial.storesingle") }}', true); // Ganti dengan rute Anda
		xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}'); // Sertakan token CSRF

		xhr.upload.onprogress = function (event) {
			if (event.lengthComputable) {
				let percentComplete = Math.round((event.loaded / event.total) * 100);
				document.getElementById('progressBar').style.width = percentComplete + '%';
				document.getElementById('progressText').textContent = percentComplete + '%';
			}
		};

		xhr.onload = function () {
			if (xhr.status === 200) {
				callback();
			} else {
				alert('Gagal mengunggah file: ' + kmlFile.name);
				callback();
			}
		};

		xhr.send(formData);
	}

	function disableElementsDuringUpload() {
		document.getElementById('uploadBtn').disabled = true;
		document.getElementById('closeBtn').disabled = true;
		document.getElementById('closeModal').disabled = true;

		document.getElementById('kml_file').disabled = true;
	}

	function enableElementsAfterUpload() {
		document.getElementById('uploadBtn').disabled = false;
		document.getElementById('closeBtn').disabled = false;
		document.getElementById('closeModal').disabled = false;

		document.getElementById('kml_file').disabled = false;
	}
</script>


@endsection

{{-- {{ route('admin.task.commitments.pksmitra', $commitment->id) }} --}}
