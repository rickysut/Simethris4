@extends('t2024.layouts.admin')
@section('styles')
<link rel="stylesheet" media="screen, print" href="{{ asset('css/miscellaneous/lightgallery/lightgallery.bundle.css') }}">
@endsection
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@can('online_access')
	@include('t2024.partials.sysalert')
		<div class="row" id="contentToPrint">
			<div class="col-lg-3">
				@include('t2024.verifikasi.tanam.infocard')
			</div>
			<div class="col-lg-9">
				<div id="panel-3" class="panel">
					<div class="panel-container show">
						@include('t2024.verifikasi.tanam.checkmenu')
					</div>
					<div class="panel-container show">
						<div class="panel-tag fade show">
							<div class="d-flex align-items-start">
								<i class="fal fa-info-circle mr-1"></i>
								<div class="flex-1">
									<p class="text-danger small">
										klik tombol di bawah untuk menandai progres pemeriksaan di sini. Set status ke "4".
									</p>
									<p class="text-danger small">
										<ul class="small">
											<li>Tanggal berwarna merah memiliki arti : Tanggal berada di luar rentang semestinya</li>
											<li>Tanggal berwarna hijau memiliki arti : Tanggal berada di dalam rentang semestinya</li>
											<li>Tanggal kosong/tidak ada data : data tidak diisi oleh pelaku usaha</li>
											<li>invalid date : Data petani atau poktan tidak tersedia di database</li>
										</ul>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<table class="table table-striped table-bordered table-sm w-100" id="timeLine">
								<thead class="thead-themed text-muted">
									<tr>
										<th style="width: 15%">Lokasi</th>
										<th>Awal PKS</th>
										<th>Akhir PKS</th>
										<th>Tgl Tanam</th>
										<th>Tgl Panen</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer d-flex justify-content-between align-items-center">
						<div class="help-block col-md-7">
						</div>
						<div class="col-md text-right">
							<form action="{{ route("2024.verifikator.tanam.markStatus", [$ijin, $tcode, "4"]) }}" method="post" enctype="multipart/form-data">
								@csrf
								<button type="submit" class="btn btn-success btn-sm" id="btnStatus-4" data-status="4">
									<i class="fal fa-save"></i> Tandai progress di sini
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endcan
@endsection

@section('scripts')
	<script src="{{ asset('js/miscellaneous/lightgallery/lightgallery.bundle.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-editor/2.3.1/dataTables.editor.js" integrity="sha512-BKsIfYRuTSaLQncTO/3CUtWr6zko7hbmxWYcBhJ7YqVB1zPIcG0S7hCNf3PLcQds22RlBaVHnKkKLxjSmn9hZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	@parent

	<script>
		$(document).ready(function() {
			var noIjin = '{{$ijin}}';
			var tcode = '{{$verifikasi->tcode}}';
			var formattedNoIjin = noIjin.replace(/[\/.]/g, '');
			function formatDate(dateString) {
				if (!dateString) return '';
				var date = new Date(dateString);
				var options = { year: 'numeric', month: 'long', day: 'numeric' };
				return new Intl.DateTimeFormat('id-ID', options).format(date);
			}
			$.ajax({
				url: "{{ route('2024.datafeeder.getVerifTanamByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
				type: "GET",
				success: function(response) {
					$('#companytitle').text(response.data.perusahaan);
					$('#no_ijin').text(response.data.no_ijin);
					$('#tgl_ijin').text(formatDate(response.data.tgl_ijin));
					$('#tgl_akhir').text(formatDate(response.data.tgl_akhir));
					$('#created_at').text(formatDate(response.data.created_at));
					$('#jml_anggota').text(response.data.countAnggota + ' orang');
					if (response.data.status > 2) {
						$('#timeLine').dataTable({
							responsive: true,
							lengthChange: false,
							ordering: true,
							processing: true,
							serverSide: true,
							ajax: {
								url: "{{ route('2024.datafeeder.timeline', [':noIjin']) }}".replace(':noIjin', noIjin),
								type: "GET",
							},
							columns: [

								{data: 'kode_spatial'},
								{
									data: 'awal_pks',
									render: function(data, type, row) {
										var ijinStart = new Date(row.mulai_ijin);
										var ijinEnd = new Date(row.akhir_ijin);
										var awalPks = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAwalPks = awalPks.toLocaleDateString('id-ID', options);
										var textClass = (awalPks >= ijinStart && awalPks <= ijinEnd) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAwalPks}</span>`;
									}
								},
								{
									data: 'akhir_pks',
									render: function(data, type, row) {
										var ijinStart = new Date(row.mulai_ijin);
										var ijinEnd = new Date(row.akhir_ijin);
										var akhirPks = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAkhirPks = akhirPks.toLocaleDateString('id-ID', options);
										var textClass = (akhirPks >= ijinStart && akhirPks <= ijinEnd) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAkhirPks}</span>`;
									}
								},
								{
									data: 'awal_tanam',
									render: function(data, type, row) {
										var ijinStart = new Date(row.mulai_ijin);
										var ijinEnd = new Date(row.akhir_ijin);
										var pksStart = new Date(row.awal_pks);
										var pksEnd = new Date(row.akhir_pks);

										if (!data) {
											return '<span class="text-danger"></span>';
										}

										var awalTanam = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAwalTanam = awalTanam.toLocaleDateString('id-ID', options);
										var textClass = (awalTanam >= ijinStart && awalTanam <= ijinEnd || awalTanam >= pksStart && awalTanam <= pksEnd) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAwalTanam}</span>`;
									}
								},
								{
									data: 'awal_panen',
									render: function(data, type, row) {
										var akhirTanam = new Date(row.akhir_tanam);

										if (!data) {
											return '<span class="text-danger"></span>';
										}

										var awalPanen = new Date(data);
										var options = { year: 'numeric', month: 'long', day: 'numeric' };
										var formattedAwalPanen = awalPanen.toLocaleDateString('id-ID', options);
										var textClass = (akhirTanam && awalPanen > akhirTanam) ? 'text-success' : 'text-danger';

										return `<span class="${textClass}">${formattedAwalPanen}</span>`;
									}
								}
							],
						});
					}

					var kemitraan = response.data.countPks + ' berkas / ' + response.data.countPoktan + ' kelompok';
					if (response.data.countPoktan / response.data.countPks == 1){
						$('#countPks').html('<span class="text-success">' + kemitraan + '</span>');
					}else{
						$('#countPks').html('<span class="text-danger">' + kemitraan + '</span>');
					}

					var realisasitanam = response.data.sumLuasTanam + ' / ' + response.data.sumLuas + ' m2';
					if (response.data.sumLuasTanam / response.data.sumLuas < 1){
						$('#luas_tanam').html('<span class="text-danger">' + realisasitanam + '</span>');
					}else{
						$('#luas_tanam').html('<span class="text-success">' + realisasitanam + '</span>');
					}

					var lokasiTanam = response.data.countTanam + ' / ' + response.data.countSpatial + ' titik';
					if (response.data.countTanam / response.data.countSpatial < 1){
						$('#jml_titik').html('<span class="text-danger">' + lokasiTanam + '</span>');
					}else{
						$('#jml_titik').html('<span class="text-success">' + lokasiTanam + '</span>');
					}

					var logoUrl = response.data.logo;
					$('#companyLogo').css('background-image', 'url(' + logoUrl + ')');
				}
			});

		});
	</script>
@endsection
