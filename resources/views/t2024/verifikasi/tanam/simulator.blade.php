@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
{{-- @can('spatial_data_access') --}}
	<div class="modal fade" id="default-example-modal-sm-center" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fal fa-times"></i></span>
					</button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<span id="gpstatus"></span>
	</div>
	<div class="row">
			<div id="myMap" style="height:600px; width: 100%;"></div>
		<div class="panel">
			<div class="panel-container">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="panel" id="panel-peta">
				<div class="panel-container show">
					<div class="panel-content">
						{{-- <form action="{{route('2024.spatial.responseGetLocByRad')}}" enctype="multipart/form-data" method="POST" id="formSubmit">
							@csrf --}}
							<div class="row d-flex justify-content-between">
								<div class="col-lg-6 mb-5">

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
										<label for="latitude" class="col-sm-3 col-form-label">Latitude</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="latitude" name="latitude">
										</div>
									</div>
									<div class="form-group row">
										<label for="longitude" class="col-sm-3 col-form-label">Longitude</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="longitude" name="longitude">
										</div>
									</div>
									<div class="d-flex justify-content-between mt-3">
										<div></div>
										<div>
											<button class="btn btn-primary" id="btnSubmit">Simulasikan</button>
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

{{-- @endcans --}}

@endsection

<!-- start script for this page -->
@section('scripts')
@parent
<script>
	$(document).ready(function() {
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
		});

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

	function addYourLocationButton(map, marker) {
		var controlDiv = document.createElement('div');

		var firstChild = document.createElement('button');
		firstChild.style.backgroundColor = '#fff';
		firstChild.style.border = 'none';
		firstChild.style.outline = 'none';
		firstChild.style.width = '40px';
		firstChild.style.height = '40px';
		firstChild.style.borderRadius = '2px';
		firstChild.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
		firstChild.style.cursor = 'pointer';
		firstChild.style.marginRight = '10px';
		firstChild.style.padding = '0';
		firstChild.title = 'Your Location';
		controlDiv.appendChild(firstChild);

		var secondChild = document.createElement('div');
		secondChild.style.margin = '10px';
		secondChild.style.width = '18px';
		secondChild.style.height = '18px';
		secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
		secondChild.style.backgroundSize = '180px 18px';
		secondChild.style.backgroundPosition = '0px 0px';
		secondChild.style.backgroundRepeat = 'no-repeat';
		secondChild.id = 'you_location_img';
		firstChild.appendChild(secondChild);

		google.maps.event.addListener(map, 'dragend', function() {
			$('#you_location_img').css('background-position', '0px 0px');
		});

		firstChild.addEventListener('click', function() {
			var imgX = '0';
			var animationInterval = setInterval(function() {
				if (imgX == '-18') imgX = '0';
				else imgX = '-18';
				$('#you_location_img').css('background-position', imgX + 'px 0px');
			}, 500);
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					marker.setPosition(latlng);
					map.setCenter(latlng);
					map.setZoom(14);
					document.getElementById('latitude').value = position.coords.latitude;
					document.getElementById('longitude').value = position.coords.longitude;
					clearInterval(animationInterval);
					$('#you_location_img').css('background-position', '-144px 0px');
				}, function() {
					clearInterval(animationInterval);
					$('#you_location_img').css('background-position', '0px 0px');
				});
			} else {
				clearInterval(animationInterval);
				$('#you_location_img').css('background-position', '0px 0px');
			}
		});

		controlDiv.index = 1;
		map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
	}

	function showYourModal() {
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
		button.title = 'Your Location';
		controlDiv.appendChild(button);

		var img = document.createElement('div');
		img.style.margin = '10px';
		img.style.width = '18px';
		img.style.height = '18px';
		img.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
		img.style.backgroundSize = '180px 18px';
		img.style.backgroundPosition = '0px 0px';
		img.style.backgroundRepeat = 'no-repeat';
		img.id = 'you_location_img';
		button.appendChild(img);

		google.maps.event.addListener(map, 'dragend', function() {
			$('#you_location_img').css('background-position', '0px 0px');
		});

		button.addEventListener('click', function() {
			// Tambahkan kode untuk membuka modal di sini
			$('#default-example-modal-sm-center').modal('show'); // Contoh: menggunakan jQuery untuk membuka modal dengan ID 'myModal'
		});

		controlDiv.index = 2;
		map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
	}

    var map;
    var marker;
    var circle;
	var markers = [];

    function initMap(lat, lng) {
    var centerLat = parseFloat($('#latitude').val()) || lat;
    var centerLng = parseFloat($('#longitude').val()) || lng;
    var radius = parseFloat($('#radius').val()) || 1;

    map = new google.maps.Map(document.getElementById('myMap'), {
        mapTypeId: google.maps.MapTypeId.HYBRID,
        center: { lat: centerLat, lng: centerLng },
        zoom: 14,
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
        scaledSize: new google.maps.Size(50, 50)
    };

    marker = new google.maps.Marker({
        position: { lat: centerLat, lng: centerLng },
        map: map,
        draggable: true,
        icon: customIcon
    });

    circle = new google.maps.Circle({
        strokeColor: '#ffc241',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#ffc241',
        fillOpacity: 0.2,
        map: map,
        center: { lat: centerLat, lng: centerLng },
        radius: radius * 1000
    });

    google.maps.event.addListener(marker, 'dragend', function(event) {
        var newLat = event.latLng.lat();
        var newLng = event.latLng.lng();
        $('#latitude').val(newLat);
        $('#longitude').val(newLng);
        circle.setCenter(new google.maps.LatLng(newLat, newLng));
    });

    $('#radius').on('change', function() {
        var newRadius = parseFloat($(this).val());
        circle.setRadius(newRadius * 1000);
    });

    addYourLocationButton(map, marker);
    showYourModal();
}

	function clearMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    }

</script>

@endsection
