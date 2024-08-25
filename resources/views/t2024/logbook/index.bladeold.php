@extends('t2024.layouts.admin')
@section('styles')

<style>
	.pagebreak {
		page-break-before: always
	}
</style>

<link rel="stylesheet" media="screen, print" href="{{ asset('css/miscellaneous/lightgallery/lightgallery.bundle.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

{{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
@endsection

@section('content')

<div class="main small">
	@foreach($payload['lokasis'] as $lokasi)
		<div class="container mt-4">
			<div class="row justify-content-center align-items-start mb-2">
				<div class="col-md-7">
					<div class="logo-text text-center">
						<h6 class="mb-0 fw-bold">PENCATATAN KEGIATAN BUDIDAYA BAWANG PUTIH</h6>
					</div>
				</div>
			</div>
			<div class="row justify-content-center align-items-start mb-5">
				<div class="col-md-7">
					<div class="logo-text text-center">
						<h6 class="mb-0 fw-bold">Kelompok Tani {{$lokasi->pks->masterpoktan->nama_kelompok}}</h6>
						<p class="mb-0 text-muted">Bekerjasama dengan</p>
						<h6 class="mb-0 fw-bold">{{$payload['company']}}</h6>
					</div>
				</div>
			</div>
			<div class="row justify-content-center align-items-start">
				{{-- data --}}
				<div class="col-md-6">
					<ul class="list-group">
						<li class="list-group-item">
							<span class="fw-bold">I. Identitas Lahan</span>
						</li>
						<li class="list-group-item d-flex justify-content-start">
							<span class="col-md-6 text-muted">Kode Lokasi</span>
							<span class="col-md-6 ml-auto ">: {{$lokasi->kode_spatial}}</span>
						</li>
						<li class="list-group-item d-flex justify-content-start">
							<span class="col-md-6 text-muted">Luas lahan</span>
							<span class="col-md-6 ml-auto">: {{$lokasi->spatial->luas_lahan}}</span>
						</li>
						<li class="list-group-item d-flex justify-content-start">
							<span class="col-md-6 text-muted">Nama Petani</span>
							<span class="col-md-6 ml-auto">: {{$lokasi->masteranggota->nama_petani}}</span>
						</li>
					</ul>
				</div>
				{{-- peta --}}
				<div class="col-md-6">
					<input type="" value="{{$lokasi->spatial->latitude}}" name="latitude" id="latitude" readonly>
					<input type="" value="{{$lokasi->spatial->longitude}}" name="longitude" id="longitude" readonly/>
					<input type="" value="{{$lokasi->spatial->polygon}}" name="polygon" id="polygon" readonly>
					<div class="panel">
						<div class="panel-container">
							<div id="myMap" style="height: 300px; width: 100%;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="pagebreak"></div>
	@endforeach
</div>

@endsection

<!-- start script for this page -->
@section('scripts')
@parent


<script>
	$(document).ready(function() {

		var latitudeInput = $('#latitude');
		var longitudeInput = $('#longitude');
		var polygonInput = $('#polygon');
		var luasLahanInput = $('#luas_lahan');
		var namaPetani = $('#nama_petani');
		var ktpPetani = $('#ktp_petani');

		function clearMarkers() {
			markers.forEach(marker => marker.setMap(null));
			markers.length = 0;
		}

		$(document).on('change', 'form input[type="radio"]', function() {
            var $radio = $(this);
            var selectedValue = $radio.val();
            var columnName = $radio.attr('name');
            var $form = $radio.closest('form');
            var formAction = $form.attr('action');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var formData = $form.serializeArray();
            formData.push({ name: 'InputField', value: selectedValue });
            formData.push({ name: 'ColumnName', value: columnName });
            formData.push({ name: '_token', value: csrfToken });

            var dataObject = {};
            $.each(formData, function(index, field) {
                dataObject[field.name] = field.value;
            });

            $.ajax({
                url: formAction,
                method: 'POST',
                data: dataObject,
                success: function(response) {
                    console.log('sukses');
                },
                error: function(xhr) {
                    console.error('An error occurred while updating:', xhr.responseText);
                }
            });
        });
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
</script>
@endsection
