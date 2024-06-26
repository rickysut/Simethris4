@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('spatial_data_access')

	<div class="row">
		<div class="col-12">
			<div class="panel" id="panel-peta">
				<div class="panel-container show">
					<div class="panel-content">
						{{-- <form action="{{route('2024.spatial.responseGetLocByRad')}}" enctype="multipart/form-data" method="POST" id="formSubmit">
							@csrf --}}
							<div class="row d-flex justify-content-between">
								<div class="col-6 mb-5">
									<div id="myMap" style="height: 400px; width: 100%;"></div>
								</div>
								<div class="col-lg-6">
									<div class="form-group row">
										<label for="no_ijin" class="col-sm-3 col-form-label">Nomor RIPH</label>
										<div class="col-sm-9">
											<select class="form-control" name="no_ijin" id="no_ijin">
												@foreach ($ijins as $ijin)
													<option value="{{ $ijin->no_ijin }}">{{ $ijin->no_ijin }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="radius" class="col-sm-3 col-form-label">Jarak (km)</label>
										<div class="col-sm-9">
											<input class="form-control" type="number" step="1" id="radius" name="radius" value="1">
										</div>
									</div>
									<div class="form-group row">
										<label for="latitude" class="col-sm-3 col-form-label">Luas Lahan (m2)</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="latitude" name="latitude">
										</div>
									</div>
									<div class="form-group row">
										<label for="longitude" class="col-sm-3 col-form-label">Luas Lahan (m2)</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="longitude" name="longitude">
										</div>
									</div>
									<div class="d-flex justify-content-between mt-3">
										<div></div>
										<div>
											<button class="btn btn-primary" id="btnSubmit">Simulate</button>
										</div>
									</div>
									<ul class="list-group" id="datalokasi">
									</ul>

								</div>
							</div>
						{{-- </form> --}}
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
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(
				function(position) {
					console.log("Latitude: " + position.coords.latitude);
					console.log("Longitude: " + position.coords.longitude);
				},
				function(error) {
					console.error("Error Code = " + error.code + " - " + error.message);
				}
			);
		} else {
			console.log("Geolocation is not supported by this browser.");
		};

		initMap();

		$('#btnSubmit').on('click', function() {
			// Retrieve selected value from dropdown
			var selectedNoIjin = $('#no_ijin').val();
			var radius = $('#radius').val();
			var latitude = $('#latitude').val();
			var longitude = $('#longitude').val();

			// Prepare data to send
			var requestData = {
				noIjin: selectedNoIjin,
				radius: radius,
				latitude: latitude,
				longitude: longitude
			};

			 // Get CSRF token value
			 var csrfToken = $('meta[name="csrf-token"]').attr('content');

			// Add CSRF token to data
			requestData._token = csrfToken;

			// Make AJAX request
			$.ajax({
				url: "{{ route('2024.datafeeder.responseGetLocByRad') }}",
				type: "POST", // Assuming you need to send data via POST method
				data: requestData,
				success: function(response) {
					console.log('Response:', response);
					$('#datalokasi').empty();
					var metadata = '<li class="list-group-item d-flex justify-content-between">' +
                               '<span class="text-muted">Jarak (km)</span>' +
                               '<span>' + response['Jarak (km)'] + '</span>' +
                               '</li>' +
                               '<li class="list-group-item d-flex justify-content-between">' +
                               '<span class="text-muted">Device Location</span>' +
                               '<span>' + response['Device Location'] + '</span>' +
                               '</li>' +
                               '<li class="list-group-item d-flex justify-content-between">' +
                               '<span class="text-muted">Jumlah titik</span>' +
                               '<span>' + response['Jumlah titik'] + '</span>' +
                               '</li>';
					$('#datalokasi').append(metadata);
					clearMarkers();
					$.each(response.data, function(index, location) {
						var spatial = location.spatial;
						var latLng = new google.maps.LatLng(parseFloat(spatial.latitude), parseFloat(spatial.longitude));
                        var newMarker = new google.maps.Marker({
                            position: latLng,
							label: {
								text: location.kode_spatial,
								color: 'white',
							},
                            map: map,
                        });
                        markers.push(newMarker);
					});
					circle.setRadius(radius * 1000);
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
				}
			});
		});
	});

    var map;
    var marker;
    var circle;
	var markers = [];

    function initMap() {
        // Default center coordinate
        var centerLat = parseFloat($('#latitude').val()) || -7.34115;
        var centerLng = parseFloat($('#longitude').val()) || 110.075;

        // Default radius
        var radius = parseFloat($('#radius').val()) || 1;

        // Initialize map
        map = new google.maps.Map(document.getElementById('myMap'), {
			mapTypeId: google.maps.MapTypeId.HYBRID,
            center: { lat: centerLat, lng: centerLng },
            zoom: 15,
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

		var customIcon = {
			url: '{{ asset("img/person.png") }}',
			scaledSize: new google.maps.Size(50, 50) // Atur ukuran ikon di sini (misalnya 32x32 piksel)
		};
        // Initialize marker
        marker = new google.maps.Marker({
            position: { lat: centerLat, lng: centerLng },
            map: map,
            draggable: true,
			icon: customIcon
        });


        // Initialize circle
        circle = new google.maps.Circle({
            strokeColor: '##ffc241',
            strokeOpacity: 0.8,
            strokeWeight: 1,
            fillColor: '#ffc241',
            fillOpacity: 0.2,
            map: map,
            center: { lat: centerLat, lng: centerLng },
            radius: radius * 1000 // Radius in meters
        });

        // Event listener for marker drag
        google.maps.event.addListener(marker, 'dragend', function(event) {
            var newLat = event.latLng.lat();
            var newLng = event.latLng.lng();
            $('#latitude').val(newLat);
            $('#longitude').val(newLng);
            circle.setCenter(new google.maps.LatLng(newLat, newLng));
        });

        // Event listener for radius change
        $('#radius').on('change', function() {
            var newRadius = parseFloat($(this).val());
            circle.setRadius(newRadius * 1000);
        });
    }

	function clearMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    }

</script>

@endsection
