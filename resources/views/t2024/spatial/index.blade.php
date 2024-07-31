@extends('t2024.layouts.admin')
@section('styles')
<style>
	.display-5{
		font-size: 1.8rem;
		font-weight: 300;
		line-height: 1.25;
	}
</style>
@endsection
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('spatial_data_access')
	<section class="mb-3">
		<div class="row">
			<div class="col-12">
				<div class="card-deck">
					<div class="card bg-info-300 overflow-hidden position-relative mb-g">
						<div class="card-body text-right">
							<div>
								<label class="fs-sm mb-0">Total Lahan Wajib Tanam</label>
								<h4 class="display-5 font-weight-bold mb-0" ><span id="totalLuas"></span> ha</h4>
							</div>
							<div>
								<h4 class="display-5 font-weight-bold mb-0"><span id="totalLahan"></span> titik</h4>
							</div>
							<p class="m-0 l-h-n"></p>
						</div>
						<i class="fal fa-globe-asia position-absolute pos-left pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
					</div>
					<div class="card bg-success-300 overflow-hidden position-relative mb-g">
						<div class="card-body text-right">
							<div>
								<label class="fs-sm mb-0">Lahan Tersedia</label>
								<h4 class="display-5 font-weight-bold mb-0"><span id="totalLuasAktif"></span> ha</h4>
							</div>
							<div>
								<h4 class="display-5 font-weight-bold mb-0"><span id="totalLahanAktif"></span> titik</h4>
							</div>
							<p class="m-0 l-h-n"></p>
						</div>
						<i class="fal fa-lock-open-alt position-absolute pos-left pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
					</div>
					<div class="card bg-warning-300 overflow-hidden position-relative mb-g">
						<div class="card-body text-right">
							<div>
								<label class="fs-sm mb-0" aria-label="Luas Lahan" aria-describedby="#deskripsilabel">Lahan Bermitra</label>
								<h4 class="display-5 font-weight-bold mb-0"><span id="totalLuasNonAktif"></span> ha</h4>
							</div>
							<div>
								<h4 class="display-5 font-weight-bold mb-0"><span id="totalLahanNonAktif"></span> titik</h4>
							</div>
							<p class="m-0 l-h-n"></p>
						</div>
						<i class="fal fa-map-marker-check position-absolute pos-left pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="row">
		<div class="col">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>
						Daftar <span class="fw-300"><i>Spatial CPCL</i></span>
					</h2>
					<div class="panel-toolbar">
						<a href="{{route('2024.spatial.createsingle')}}" class="btn btn-xs btn-primary waves-effect waves-themed" data-toggle="tooltip" data-offset="0,10" data-original-title="Buat Peta Tunggal baru (Manual)">
							<i class="fal fa-map-marked-alt"></i>
							Buat/Import Peta Baru
						</a>
						<div class="btn-group" hidden>
							<button type="button" class="btn btn-xs btn-primary waves-effect waves-themed">
								<i class="fal fa-plus mr-1"></i>
								Peta Baru
							</button>
							<button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-themed" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="sr-only">Toggle Dropdown</span>
								<i class="fal fa-chevron-down"></i>
							</button>
							<div class="dropdown-menu" style="">
								<a href="{{route('2024.spatial.createsingle')}}" onclick="navigateBack();" class="dropdown-item" data-toggle="tooltip" data-offset="0,10" data-original-title="Buat Peta Tunggal baru (Manual)">
									<i class="fal fa-plus"></i>
									Buat Peta Baru
								</a>
								<a href="javascript:void(0);" onclick="navigateBack();" class="dropdown-item" data-toggle="tooltip" data-offset="0,10" data-original-title="Import Peta Lokasi Tunggal (Per Lokasi)">
									<i class="fal fa-map-marked-alt"></i>
									Impor Peta Tunggal
								</a>
								<a href="javascript:void(0);" onclick="navigateBack();" class="dropdown-item" data-toggle="tooltip" data-offset="0,10" data-original-title="Import Peta Lokasi Jamak (Banyak Lokasi)">
									<i class="fal fa-layer-plus"></i>
									Impor Peta Jamak
								</a>
								{{-- <div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a> --}}
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
@parent

<script>
	$(document).ready(function(){

		$('#tblSpatial').dataTable(
		{
			responsive: true,
			lengthChange: false,
			ordering: true,
			processing: true,
			serverSide: true,
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			ajax: {
				url: "{{ route('2024.datafeeder.getAllSpatials')}}",
				type: "GET",
				dataSrc: function(json) {
					// Update the totalLuas element with the total land area
					var totalLuas = parseFloat(json.totalLuas) / 10000;
					var totalLuasAktif = parseFloat(json.totalLuasAktif) / 10000;
					var totalLuasNonAktif = parseFloat(json.totalLuasNonAktif) / 10000;
					$('#totalLuas').text(totalLuas.toLocaleString('id-ID', {
						minimumFractionDigits: 4,
						maximumFractionDigits: 4
					}));
					$('#totalLahan').text(json.totalLahan.toLocaleString('id-ID', {
						minimumFractionDigits: 0,
						maximumFractionDigits: 0
					}));
					$('#totalLuasAktif').text(totalLuasAktif.toLocaleString('id-ID', {
						minimumFractionDigits: 4,
						maximumFractionDigits: 4
					}));
					$('#totalLahanAktif').text(json.totalLahanAktif.toLocaleString('id-ID', {
						minimumFractionDigits: 0,
						maximumFractionDigits: 0
					}));
					$('#totalLuasNonAktif').text(totalLuasNonAktif.toLocaleString('id-ID', {
						minimumFractionDigits: 4,
						maximumFractionDigits: 4
					}));
					$('#totalLahanNonAktif').text(json.totalLahanNonAktif.toLocaleString('id-ID', {
						minimumFractionDigits: 0,
						maximumFractionDigits: 0
					}));
					return json.data;
				}
			},
			"columnDefs": [
				{ "targets": [2,3], "className": "text-right" },
				{ "targets": [4], "className": "text-center" },
			],
			columns:[
				{data: 'kode_spatial'},
				{data: 'ktp_petani',
					render: function (data, type, row) {
						return row.nama_anggota;
					}
				},
				{
					data: 'luas_lahan',
					render: function(data, type, row) {
						// if (type === 'display') {
						// 	return parseFloat(data).toLocaleString('id-ID', {
						// 		minimumFractionDigits: 2,
						// 		maximumFractionDigits: 2
						// 	});
						// }
						var luasHektare = parseFloat(data) / 10000;
						return luasHektare.toLocaleString('id-ID', {
							minimumFractionDigits: 4,
							maximumFractionDigits: 4
						});
						return `
							<div class="justify-content-end">`
								+ data + `
							</div>
							`;
					}
				},
				{data: 'provinsi_id',
					render: function (data, type, row) {
						return row.nama_provinsi;
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

						// Determine the checked state based on the status
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
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Generate PDF',
					className: 'btn-outline-danger btn-sm btn-icon mr-1'
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				}
			]
		});
	});

	$(document).on('change', '.status-switch', function() {
		var kode = $(this).data('kode');
		var status = $(this).is(':checked') ? 1 : 0;
		console.log('kode: ', kode);
		console.log('status: ', status);
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
@endsection

{{-- {{ route('admin.task.commitments.pksmitra', $commitment->id) }} --}}
