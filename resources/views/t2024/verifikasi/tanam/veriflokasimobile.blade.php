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
							<div id="data-ringkasan" class="collapse" data-parent="#data-laporan" style="">
								<div class="card-body">
									Data di sini
								</div>
							</div>
						</div>
						<div class="card">
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
						<div class="card">
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
									Data Realisasi
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
									Data Verifikasi
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
    initMap();
});

function initMap() {
    // Set default center to Indonesia if no coordinates are provided
    var defaultLat = -6.2088; // Latitude for Jakarta, Indonesia
    var defaultLng = 106.8456; // Longitude for Jakarta, Indonesia
    var centerLat = parseFloat($('#latitude').val()) || defaultLat;
    var centerLng = parseFloat($('#longitude').val()) || defaultLng;
    var radius = parseFloat($('#radius').val()) || 1;

    map = new google.maps.Map(document.getElementById('myMap'), {
        mapTypeId: google.maps.MapTypeId.HYBRID,
        center: { lat: centerLat, lng: centerLng },
        zoom: 5,
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
}


</script>

@endsection
