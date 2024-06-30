@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
{{-- @can('spatial_data_access') --}}
	<div class="row">
			<div id="myMap" style="height:500px; width: 100%;"></div>
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
								<div class="card-title" data-toggle="collapse" data-target="#data-realisasi" aria-expanded="true">
									<div class="d-flex flex-row align-items-center">
										<div class="info-card-text">
											<div class="text-truncate text-truncate-lg">Data Realisasi: {{$data->kode_spatial}}</div>
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
							<div id="data-realisasi" class="collapse show" data-parent="#data-laporan" style="">
								<div class="card-body">
									<ul class="list-group">
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
										<li class="list-group-item d-flex justify-content-between align-item-start">
											<div class="row">
												<label for="mulai_tanam">Tanggal Awal Tanam<sup class="text-danger"> *</sup></label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fal fa-calendar-day"></i></span>
													</div>
													<input type="date" value="" name="mulai_tanam" id="mulai_tanam" class="font-weight-bold form-control form-control bg-white">
												</div>
												<span class="help-block">Tanggal mulai penanaman.</span>
											</div>
										</li>
									</ul>
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

		createMarker();
		createPolygon();
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
