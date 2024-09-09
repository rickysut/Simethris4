@extends('t2024.layouts.admin')
@section('styles')

<style>
	.middle-align tbody td {
		vertical-align: middle;
	}

	.timeline {
		border-left: 1px solid hsl(0, 0%, 90%);
		position: relative;
		list-style: none;
	}

	.timeline .timeline-item {
		position: relative;
	}

	.timeline .timeline-item:after {
		position: absolute;
		display: block;
		top: 0;
	}

	.timeline .timeline-item:after {
		background-color: hsl(0, 0%, 90%);
		left: -46px;
		border-radius: 50%;
		height: 11px;
		width: 11px;
		content: "";
	}
</style>

<link rel="stylesheet" media="screen, print" href="{{ asset('css/miscellaneous/lightgallery/lightgallery.bundle.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

{{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
@endsection

@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('online_access')
	<div class="row d-flex align-items-start">
		<div class="col-md-4">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>
						<span class="text-muted fw-300">Data Lokasi </span>
						<span class="fw-600">
							{{$data['spatial']->kode_spatial}}</i>
						</span>
					</h2>
					<div class="panel-toolbar">
						@include('t2024.partials.globaltoolbar')
					</div>
				</div>


				<div class="panel-container">
					<div id="myMap" cl style="height: 370px; width: 100%;"></div>
					<div class="panel-content">
						<ul class="list-group">
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">Pemilik/Pengelola</span>
								<span class="fw-500">{{$data['spatial']->nama_petani}}</span>
							</li>
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">NIK Pemilik/Pengelola</span>
								<span class="fw-500">{{$data['spatial']->ktp_petani}}</span>
							</li>
							<li class="list-group-item d-flex align-items-start justify-content-between">
								<span class="text-muted">Luas Lahan (m2)</span>
								<span class="fw-500">{{$data['spatial']->luas_lahan}}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel" id="panel-2">
				<div class="panel-hdr">
					<h2>Log Kegiatan</h2>
					<div class="panel-toolbar">
						@include('t2024.partials.globaltoolbar')
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<input type="hidden" value="{{$data['pks']->no_ijin}}" name="no_ijin">
						<input type="hidden" value="{{$data['spatial']->kode_spatial}}" name="anggota_id">
						<input type="hidden" value="{{$data['spatial']->latitude}}" name="latitude" id="latitude" readonly>
						<input type="hidden" value="{{$data['spatial']->longitude}}" name="longitude" id="longitude" readonly/>
						<input type="hidden" value="{{$data['spatial']->polygon}}" name="polygon" id="polygon" readonly>
						<!-- Section: Timeline -->
						<section class="px-3">
							<ul class="timeline accordion accordion-clean" id="js_demo_accordion-1">
								@foreach ($data['timelineItems'] as $item)
									<li class="timeline-item mb-5">
										<h5 class="mb-3 data-title" id="title_{{ $item['id'] }}" data-toggle="collapse" data-target="#content_{{ $item['id'] }}">
											<span class="mr-1 fw-500">{{ $item['date'] ?? '?' }} : </span>
											<span class="fw-700 text-{{ $item['status'] === 1 ? 'success' : ($item['status'] === 0 ? 'danger' : 'warning') }}">
												<i class="fa fa-{{ $item['status'] === 1 ? 'check' : ($item['status'] === 0 ? 'ban' : 'exclamation-circle') }}"></i>
											</span>
											<span class="fw-700 text-primary">{{ $item['title'] }}</span>
										</h5>
										<div class="collapse" id="content_{{ $item['id'] }}" data-parent="#js_demo_accordion-1">
											<div class="row d-flex">
												<div class="col-md-3 mb-3">
													<div class="card" style="width: 100%; padding-top: 100%; position: relative; overflow: hidden;">
														<div class="card-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
															background-image: url('{{ $item['foto'] ?? asset('img/posts_img/default-post-image-light.svg') }}');
															background-size: cover; background-repeat: no-repeat; background-position: center;">
														</div>
													</div>
												</div>
												<div class="col-md-9 d-flex flex-column">
													<p class="mb-auto">{{ $item['comment'] ?? 'Tidak ada data' }}</p>
													<div class="d-flex flex-column">
														@if(isset($item['value']))
															<span>{{ $item['value'] }}</span>
														@endif

														@if(isset($item['value1']))
															<span>{{ $item['value1'] }}</span>
														@endif

														@if(isset($item['value2']))
															<span>{{ $item['value2'] }}</span>
														@endif

														@if(isset($item['value3']))
															<span>{{ $item['value3'] }}</span>
														@endif

														@if(isset($item['value4']))
															<span>{{ $item['value4'] }}</span>
														@endif
													</div>
													<p class="mt-auto">
														<form action="{{ route('2024.verifikator.produksi.storePhaseCheck', [$ijin, $lokasi->tcode]) }}" id="formLahan">
															@csrf
															<label>Hasil Periksa</label>
															<div class="custom-control custom-radio">
																<input type="radio" class="custom-control-input" id="{{ $item['columnName'] }}_1" name="{{ $item['columnName'] }}" value="1" @if($item['status'] === 1) checked @endif>
																<label class="custom-control-label" for="{{ $item['columnName'] }}_1">Sesuai</label>
															</div>
															<div class="custom-control custom-radio">
																<input type="radio" class="custom-control-input" id="{{ $item['columnName'] }}_0" name="{{ $item['columnName'] }}" value="0" @if($item['status'] === 0) checked @endif>
																<label class="custom-control-label" for="{{ $item['columnName'] }}_0">Tidak sesuai</label>
															</div>
														</form>
													</p>
												</div>
											</div>
										</div>
									</li>
								@endforeach
							</ul>
						</section>
						<!-- Section: Timeline -->
					</div>
				</div>
				<form action="{{route('2024.verifikator.produksi.storelokasicheck', [$ijin, $lokasi->tcode])}}" id="storeLokasiCheck" method="post">
					@csrf
					<div class="card-footer d-flex align-items-start">
						<div class="ml-auto">
							<a href="{{route('2024.verifikator.produksi.checkdaftarlokasi', [$ijin, $verifikasi])}}" class="btn btn-info mr-1">Kembali</a>
							<button type="submit" class="btn btn-warning">Verifikasi Lahan Selesai</button>
						</div>
					</div>
				</form>
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
