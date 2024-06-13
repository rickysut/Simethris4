@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('spatial_data_access')

	<div class="row">
		<div class="col-12">
			<div class="panel" id="panel-peta">
				<div class="panel-hdr">
					<h2>
						Data Spasial <span class="fw-300">
							<i>Lokasi</i>
						</span>
					</h2>
					<div class="panel-toolbar">
						<span class="fw-500" id="kdLokasiTitle"></span>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form action="{{route('2024.spatial.storesingle')}}" enctype="multipart/form-data" method="POST" id="formSubmit">
							@csrf
							<div class="row d-flex justify-content-between">
								<div class="col-lg-5">
									<div class="form-group">
										<div class="input-group bg-white shadow-inset-2">
											<div class="input-group-prepend">
												<span class="input-group-text bg-transparent border-right-0 py-1 px-3 text-success">
													<i class="fal fa-upload"></i>
												</span>
											</div>
											<div class="custom-file">
												<input type="file" accept=".kml" id="kml_file" name="kml_url" placeholder="ambil berkas KML..."
													class="custom-file-input border-left-0 bg-transparent pl-0" required>
												<label class="custom-file-label text-muted" for="inputGroupFile01">ambil berkas KML... </label>
											</div>
										</div>
										<span class="help-block">Unggah berkas KML <span class="text-danger">*</span></span>
									</div>
									<div id="myMap" style="height: 400px; width: 100%;" hidden></div>
								</div>
								<div class="col-lg-7">
									<ul class="list-group" id="exportedData">
									</ul>
									<div id="myForm" hidden>
										<input class="form-control" type="hidden" id="latitude" name="latitude" value="">
										<input class="form-control" type="hidden" id="longitude" name="longitude" value="">
										<input class="form-control" type="hidden" id="polygon" name="polygon" value="">
										<input class="form-control" type="hidden" id="kode_spatial" name="kode_spatial" value="">
										<input class="form-control" type="hidden" id="ktp_petani" name="ktp_petani" value="">
										<input class="form-control" type="hidden" id="altitude" name="altitude" value="">
										<input class="form-control" type="hidden" id="luas_lahan" name="luas_lahan" value="">
										<input class="form-control" type="hidden" id="nama_lahan" name="nama_lahan" value="">
										<input class="form-control" type="hidden" id="provinsi_id" name="provinsi_id" value="">
										<input class="form-control" type="hidden" id="kabupaten_id" name="kabupaten_id" value="">
										<input class="form-control" type="hidden" id="kecamatan_id" name="kecamatan_id" value="">
										<input class="form-control" type="hidden" id="kelurahan_id" name="kelurahan_id" value="">
										<input class="form-control" type="hidden" id="komoditas" name="komoditas" value="">
										<input class="form-control" type="hidden" id="nama_petugas" name="nama_petugas" value="">
										<input class="form-control" type="hidden" id="tgl_peta"  name="tgl_peta"value="">
										<input class="form-control" type="hidden" id="tgl_tanam" name="tgl_tanam" value="">
										<div class="d-flex justify-content-between mt-3">
											<div></div>
											<div>
												<button class="btn btn-primary" id="btnSubmit">Simpan</button>
											</div>
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

	<div class="row" hidden>
		<div class="col-12">
			<div class="panel" id="panel-data" >
				<div class="panel-hdr">
					<h2>
						Data Lokasi
					</h2>
				</div>
				<div class="panel-container show">
					<table id="kmlTable" class="table table-sm table-bordered table-hover table-striped w-100">
						<thead>
							<tr>
								<th>Id</th>
								<th>Komoditas</th>
								<th>Poktan</th>
								<th>No</th>
								<th>Nama Petani</th>
								<th>Luas</th>
								<th>X</th>
								<th>Y</th>
								<th>Kecamatan</th>
								<th>Desa</th>
							</tr>
						</thead>
						<tbody id="kmlTableBody">
						</tbody>
					</table>
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
	let marker;
	let polygon;
	let myMap;

	function initMap() {
		myMap = new google.maps.Map(document.getElementById("myMap"), {
			center: { lat: -2.5489, lng: 118.0149 },
			zoom: 5,
			mapTypeId: google.maps.MapTypeId.HYBRID,
			fullscreenControl: true,
			mapTypeControl: false,
			streetViewControl: false,
			zoomControl: false,
			scaleControl: false,
			rotateControl: false,
		});
	}

	initMap();

	function kml_parser() {
		document.getElementById("myMap").removeAttribute("hidden");
		document.getElementById("myForm").removeAttribute("hidden");
		if (marker) {
			marker.setMap(null);
		}
		if (polygon) {
			polygon.setMap(null);
		}

		const kmlFile = document.getElementById("kml_file").files[0];

		const reader = new FileReader();

		reader.onload = (event) => {
			const kmlData = event.target.result;

			const parser = new DOMParser();
			const kmlXml = parser.parseFromString(kmlData, "application/xml");

			const coordinates = kmlXml
				.getElementsByTagName("coordinates")[0]
				.textContent.trim()
				.split(/\s+/);

			const latLngs = coordinates.map((coord) => {
				const [lng, lat] = coord.split(",");
				return new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
			});

			const linearRings = Array.from(kmlXml.getElementsByTagName("LinearRing"));
			const polygonPaths = linearRings.map((ring) => {
				const coordinates = ring
					.getElementsByTagName("coordinates")[0]
					.textContent.trim()
					.split(/\s+/);
				return coordinates.map((coord) => {
					const [lng, lat] = coord.split(",");
					return { lat: parseFloat(lat), lng: parseFloat(lng) };
				});
			});

			marker = new google.maps.Marker({
				position: latLngs[0],
				map: myMap,
				draggable: false,
			});

			polygon = new google.maps.Polygon({
				paths: polygonPaths,
				fillColor: "#fd3995",
				strokeColor: "#fd3995",
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillOpacity: 0.5,
				editable: false,
				draggable: false,
				map: myMap,
			});

			const bounds = new google.maps.LatLngBounds();
			latLngs.forEach((polygonPaths) => bounds.extend(polygonPaths));
			myMap.fitBounds(bounds);

			document.getElementById("latitude").value = marker.getPosition().lat();
			document.getElementById("longitude").value = marker.getPosition().lng();
			document.getElementById("polygon").value = JSON.stringify(
				polygon.getPath().getArray()
			);

			var infowindow = new google.maps.InfoWindow({
				content: "Hello World!",
			});

			displayAttributes(kmlXml);
		};

		reader.readAsText(kmlFile);
	}

	function displayAttributes(kmlXml) {
		const exportedDataList = document.getElementById("exportedData");
		exportedDataList.innerHTML = "";

		const placemarks = kmlXml.getElementsByTagName("Placemark");
		for (let i = 0; i < placemarks.length; i++) {
			const placemark = placemarks[i];
			const id_lahan = placemark.querySelector("SimpleData[name='ID_Lahan']").textContent;
			const komoditas = placemark.querySelector("SimpleData[name='Komoditas']").textContent;
			const nikPetani = placemark.querySelector("SimpleData[name='NIK']").textContent;
			const luas = placemark.querySelector("SimpleData[name='Luas_Lahan']").textContent;
			const x = parseFloat(placemark.querySelector("SimpleData[name='Latitude']").textContent);
			const y = parseFloat(placemark.querySelector("SimpleData[name='Longitude']").textContent);
			const altitude = parseFloat(placemark.querySelector("SimpleData[name='Altitude']").textContent);
			const desa_id = placemark.querySelector("SimpleData[name='ID_Desa']").textContent;
			const petugas = placemark.querySelector("SimpleData[name='Petugas']").textContent;
			const tgl_peta = placemark.querySelector("SimpleData[name='Tgl_Pemeta']").textContent;
			const tgl_tanam = placemark.querySelector("SimpleData[name='Tgl_Tanam']").textContent;

			const kecamatan_id = desa_id.substring(0, 7);
			const kabupaten_id = desa_id.substring(0, 4);
			const provinsi_id = desa_id.substring(0, 2);

			console.log('kabupaten id: ' , kabupaten_id);
			console.log('provinsi id: ' , provinsi_id);

			document.getElementById("kode_spatial").value = id_lahan;
			document.getElementById("komoditas").value = komoditas;
			document.getElementById("kdLokasiTitle").textContent = id_lahan;
			document.getElementById("ktp_petani").value = nikPetani;
			document.getElementById("luas_lahan").value = luas;
			document.getElementById("kelurahan_id").value = desa_id;
			document.getElementById("kecamatan_id").value = kecamatan_id;
			document.getElementById("kabupaten_id").value = kabupaten_id;
			document.getElementById("provinsi_id").value = provinsi_id;
			document.getElementById("nama_petugas").value = petugas;
			document.getElementById("tgl_peta").value = tgl_peta;
			document.getElementById("tgl_tanam").value = tgl_tanam;
			document.getElementById("altitude").value = altitude;

			const routeUrl = `{{ route('2024.datafeeder.getCpclByNik', ':nik') }}`.replace(':nik', nikPetani);

			const routeDesa = `{{ route('wilayah.getDesaById', ':id') }}`.replace(':id', desa_id);
			const routeKec = `{{ route('wilayah.getKecById', ':id') }}`.replace(':id', kecamatan_id);
			const routeKab = `{{ route('wilayah.getKabById', ':id') }}`.replace(':id', kabupaten_id);
			const routeProv = `{{ route('wilayah.getProvById', ':id') }}`.replace(':id', provinsi_id);

			Promise.all([
				fetch(routeUrl).then(response => response.json()),
				fetch(routeDesa).then(response => response.json()),
				fetch(routeKec).then(response => response.json()),
				fetch(routeKab).then(response => response.json()),
				fetch(routeProv).then(response => response.json())
			]).then(([petaniData, desaData, kecData, kabData, provData]) => {
				const nama_Petani = petaniData.nama_petani;
				const namaDesa = desaData.nama_desa || 'Desa tidak terdaftar';
				const namaKecamatan = kecData.nama_kecamatan || 'Kecamatan tidak terdaftar';
				const namaKabupaten = kabData.nama_kab || 'Kabupaten tidak terdaftar';
				const namaProvinsi = provData.nama || 'Provinsi tidak terdaftar';

				const namaPetaniClass = nama_Petani === 'KTP tidak terdaftar' ? 'text-danger' : '';
				const namaDesaClass = namaDesa === 'Desa tidak terdaftar' ? 'text-danger' : '';
				const namaKecamatanClass = namaKecamatan === 'Kecamatan tidak terdaftar' ? 'text-danger' : '';
				const namaKabupatenClass = namaKabupaten === 'Kabupaten tidak terdaftar' ? 'text-danger' : '';
				const namaProvinsiClass = namaProvinsi === 'Provinsi tidak terdaftar' ? 'text-danger' : '';

				const listItem = `
					<li class='list-group-item d-flex justify-content-between'>
						<span>Kode Lokasi</span>
						<span id='id_lahan'> ${id_lahan} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Komoditas</span>
						<span id='komoditas'> ${komoditas} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Nama Petani (NIK)</span>
						<span id='nikpetani' class='${namaPetaniClass}'> ${nikPetani} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Nama Petani</span>
						<span id='namaPetani' class='${namaPetaniClass}'> ${nama_Petani} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Luas</span>
						<span id='luas'> ${luas} m<sup>2</sup></span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Altitude (mdpl)</span>
						<span id='altitude'> ${altitude} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Petugas Pemetaan</span>
						<span id='petugas' > ${petugas} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Tanggal Tanam</span>
						<span id='tgl_tanam' > ${tgl_tanam} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Tanggal Pemetaan</span>
						<span id='tglPeta' > ${tgl_peta} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Provinsi</span>
						<span id='provinsi' class='${namaProvinsiClass}'> ${namaProvinsi} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Kabupaten</span>
						<span id='kabupaten' class='${namaKabupatenClass}'> ${namaKabupaten} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Kecamatan</span>
						<span id='kecamatan' class='${namaKecamatanClass}'> ${namaKecamatan} </span>
					</li>
					<li class='list-group-item d-flex justify-content-between'>
						<span>Desa</span>
						<span id='desa' class='${namaDesaClass}'> ${namaDesa} </span>
					</li>
				`;

				exportedDataList.innerHTML += listItem;
			})
			.catch(error => {
				console.error('Error fetching farmer data:', error);
			});
		}
	}

	document.getElementById('kml_file').addEventListener('change', kml_parser);
</script>

@endsection
