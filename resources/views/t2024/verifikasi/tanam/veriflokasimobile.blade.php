@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
{{-- @can('spatial_data_access') --}}
	<div class="row">
			<div id="myMap" style="height:300px; width: 100%;"></div>
		<div class="panel">
			<div class="panel-container">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="panel" id="panel-peta">
				<div class="panel-container show">
					<form action="">
					<div class="accordion" id="data-laporan">
						<div class="card">
							<div class="card-header">
								<div class="card-title collapsed" data-toggle="collapse" data-target="#data-ringkasan" aria-expanded="false">
									<div class="d-flex flex-row align-items-center">
										<div class="info-card-text">
											<div class="text-truncate text-truncate-lg">Ringkasan</div>
										</div>
									</div>
									<span class="ml-auto align-self-start">
										<span class="collapsed-reveal">
											<i class="fal fa-chevron-up fs-xl"></i>
										</span>
										<span class="collapsed-hidden">
											<i class="fal fa-chevron-down fs-xl"></i>
										</span>
									</span>
								</div>
							</div>
							<div id="data-ringkasan" class="collapse show" data-parent="#data-laporan" style="">
								<div class="card-body">
									<ul class="list-group mb-3">
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">Nama Petani</span>
											<span class="fw-bold" id="">{{$data->spatial->nama_petani}}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">NIK Petani</span>
											<span class="fw-bold" id="">{{$data->ktp_petani}}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">Luas Lahan (m2)</span>
											<span class="fw-bold" id="">{{ number_format($data->luas_lahan, 0, ',', '.') }}</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="card" hidden>
							<div class="card-header">
								<div class="card-title collapsed" data-toggle="collapse" data-target="#data-berkas" aria-expanded="false">
									<div class="d-flex flex-row align-items-center">
										<div class="info-card-text">
											<div class="text-truncate text-truncate-lg">Berkas-berkas</div>
										</div>
									</div>
									<span class="ml-auto align-self-start">
										<span class="collapsed-reveal">
											<i class="fal fa-chevron-up fs-xl"></i>
										</span>
										<span class="collapsed-hidden">
											<i class="fal fa-chevron-down fs-xl"></i>
										</span>
									</span>
								</div>
							</div>
							<div id="data-berkas" class="collapse" data-parent="#data-laporan" style="">
								<div class="card-body">
									Daftar Berkas
								</div>
							</div>
						</div>
						<div class="card" hidden>
							<div class="card-header">
								<div class="card-title collapsed" data-toggle="collapse" data-target="#data-pks" aria-expanded="false">
									<div class="d-flex flex-row align-items-center">
										<div class="info-card-text">
											<div class="text-truncate text-truncate-lg">Perjanjian Kerjasama</div>
										</div>
									</div>
									<span class="ml-auto align-self-start">
										<span class="collapsed-reveal">
											<i class="fal fa-chevron-up fs-xl"></i>
										</span>
										<span class="collapsed-hidden">
											<i class="fal fa-chevron-down fs-xl"></i>
										</span>
									</span>
								</div>
							</div>
							<div id="data-pks" class="collapse" data-parent="#data-laporan" style="">
								<div class="card-body">
									Daftar
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<div class="card-title collapsed" data-toggle="collapse" data-target="#data-realisasi" aria-expanded="false">
									<div class="d-flex flex-row align-items-center">
										<div class="info-card-text">
											<div class="text-truncate text-truncate-lg">Data Realisasi</div>
										</div>
									</div>
									<span class="ml-auto align-self-start">
										<span class="collapsed-reveal">
											<i class="fal fa-chevron-up fs-xl"></i>
										</span>
										<span class="collapsed-hidden">
											<i class="fal fa-chevron-down fs-xl"></i>
										</span>
									</span>
								</div>
							</div>
							<div id="data-realisasi" class="collapse" data-parent="#data-laporan" style="">
								<div class="card-body">
									<ul class="list-group mb-3">
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">Luas Tanam</span>
											<span class="fw-bold" id="">{{ number_format($data->luas_tanam, 0, ',', '.') }}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">Tanggal Tanam</span>
											<span class="fw-bold" id="">{{$data->tgl_tanam}}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">Volume Panen (ton)</span>
											<span class="fw-bold" id="">{{ number_format($data->volume, 0, ',', '.') }}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">Disimpan untuk Benih</span>
											<span class="fw-bold" id="">{{ number_format($data->vol_benih, 0, ',', '.') }}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<span class="text-muted">Dijual</span>
											<span class="fw-bold" id="">{{ number_format($data->vol_jual, 0, ',', '.') }}</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<div class="card-title collapsed" data-toggle="collapse" data-target="#data-verifikasi" aria-expanded="false">
									<div class="d-flex flex-row align-items-center">
										<div class="info-card-text">
											<div class="text-truncate text-truncate-lg">Data Verifikasi</div>
										</div>
									</div>
									<span class="ml-auto align-self-start">
										<span class="collapsed-reveal">
											<i class="fal fa-chevron-up fs-xl"></i>
										</span>
										<span class="collapsed-hidden">
											<i class="fal fa-chevron-down fs-xl"></i>
										</span>
									</span>
								</div>
							</div>
							<div id="data-verifikasi" class="collapse" data-parent="#data-laporan" style="">
								<div class="card-body">
									<div class="row flex">
										<input type="file" accept="image/*" capture="camera" id="cameraInput" style="display:none;">
										<div class="col-12 mb-3">
											<label for="mulai_tanam">Hasil Verifikasi</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fal fa-calendar-day"></i></span>
												</div>
												<select class="form-control" name="locus" id="locus">
													<option value="">--pilih lokasi</option>

												</select>
											</div>
											<span class="help-block">Tanggal mulai penanaman.</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-content">
						<button class="btn btn-primary btn-block">Simpan</button>
					</div>
					</form>
				</div>
			</div>

		</div>
	</div>

{{-- @endcans --}}

@endsection

<!-- start script for this page -->
@section('scripts')
@parent
<script>
	$(document).ready(function() {
		var ijin = '{{$ijin}}';
		var noIjin = '{{$noIjin}}';
		var spatial = '{{$spatial}}';
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(
				function(position) {
					console.log("Latitude: " + position.coords.latitude);
					console.log("Longitude: " + position.coords.longitude);

					var thisLat = position.coords.latitude;
					var thisLong = position.coords.longitude;
					$('#latitude').val(thisLat);
					$('#longitude').val(thisLong);
					$('#gpstatus').html('GPS status <span class="text-success font-weight-bold">Aktif</span>');

					initMap(thisLat, thisLong);
				},
				function(error) {
					console.error("Error Code = " + error.code + " - " + error.message);
					$('#gpstatus').html('GPS status <span class="text-danger font-weight-bold">Tidak Aktif/Tidak Diijinkan</span>');
				}
			);
		} else {
			console.log("Geolocation is not supported by this browser.");
			$('#gpstatus').html('Perangkat <span class="text-danger font-weight-bold">Tidak mendukung</span> Fitur ini.');
		}
		var lat = parseFloat('{{$data->spatial->latitude}}');
		var lng = parseFloat('{{$data->spatial->longitude}}');
		var poly = JSON.parse('{{$data->spatial->polygon}}'.replace(/&quot;/g,'"'));
		var kodeId = '{{$data->kode_spatial}}';
		console.log(lat, lng, poly, kodeId);
	});


	let myMap;
	const markers = [];
	let polygon;

	function initMap() {
		myMap = new google.maps.Map(document.getElementById("myMap"), {
			center: { lat: -2.5489, lng: 118.0149 },
			zoom: 5,
			mapTypeId: google.maps.MapTypeId.SATELLITE,
			mapTypeControl: false,
			streetViewControl: false,
			scaleControl: true,
			rotateControl: false,
			styles: [
				{
					featureType: 'all',
					elementType: 'labels',
					stylers: [{ visibility: 'off' }]
				}
			]
		});

		cameraBtn();
		createMarker();
		createPolygon();
	}

	function cameraBtn() {
		var controlDiv = document.createElement('div');

		var button = document.createElement('button');
		button.style.backgroundColor = '#fff';
		button.style.border = 'none';
		button.style.outline = 'none';
		button.style.width = '40px';
		button.style.height = '40px';
		button.style.borderRadius = '2px';
		button.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
		button.style.cursor = 'pointer';
		button.style.marginRight = '10px';
		button.style.padding = '0';
		button.title = 'Take a Photo';
		controlDiv.appendChild(button);

		var icon = document.createElement('i');
		icon.className = 'fas fa-camera';
		icon.style.fontSize = '18px';
		icon.style.margin = '10px';
		button.appendChild(icon);

		button.addEventListener('click', function() {
			document.getElementById('cameraInput').click();
		});

		controlDiv.index = 1;
		myMap.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
	}

	function createMarker() {
		const latitude = parseFloat('{{$data->spatial->latitude}}');
		const longitude = parseFloat('{{$data->spatial->longitude}}');
		const kodeId = '{{$data->kode_spatial}}';

		if (!isNaN(latitude) && !isNaN(longitude)) {
			const position = new google.maps.LatLng(latitude, longitude);
			const marker = new google.maps.Marker({
				position: position,
				map: myMap,
				draggable: false,
				label: {
					text: kodeId,
					color: "white", // Set the label text color to white
					fontSize: "14px", // Optional: Adjust the font size
					fontWeight: "bold" // Optional: Make the label bold
				}
			});
			markers.push(marker);
			myMap.setCenter(position);
			myMap.setZoom(18);
		}
	}

	function createPolygon() {
		let polygonCoords = '{{$data->spatial->polygon}}'.replace(/&quot;/g,'"');
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
