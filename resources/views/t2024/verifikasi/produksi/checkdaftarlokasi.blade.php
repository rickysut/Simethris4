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
		{{-- <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-right modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Verifikasi Detail Lokasi</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
						<button type="button" class="btn btn-primary" id="btnLokasiCheck">Simpan</button>
					</div>
				</div>
			</div>
		</div> --}}
		<div class="row" id="contentToPrint">
			<div class="col-lg-3">
				@include('t2024.verifikasi.produksi.infocard')
			</div>
			<div class="col-lg-9">
				<div id="panel-4" class="panel">
					<div class="panel-container show">
						@include('t2024.verifikasi.produksi.checkmenu')
					</div>
					<div class="panel-container show">
						<div class="panel-tag fade show">
							<div class="d-flex align-items-center">
								<i class="fal fa-info-circle mr-1"></i>
								<div class="flex-1">
									<small class="text-danger">klik tombol di bawah untuk menandai progress pemeriksaan di sini. Set status ke "5". Verifikasi pertama wajib di lapangan, tabel di sini tidak dapat dilihat apabila belum melakukan verifikasi di lapangan minimal 1x.</small>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-container show">
						{{-- <div class="row d-flex justify-content-between">
							<div class="col-lg-5 mb-5">
								<div id="myMap" style="height: 400px; width: 100%;"></div>
							</div>
						</div> --}}
						<div class="panel-content">
							<table class="table table-striped table-bordered table-sm w-100" id="lokasiCheck">
								<thead>
									<tr>
										<th>Kode Lokasi</th>
										<th>Luas Lahan</th>
										<th>Luas Tanam</th>
										<th>Poktan</th>
										<th>Pengelola</th>
										<th>Status</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div class="card-footer d-flex justify-content-between align-items-center">
						<div class="help-block col-md-7">
						</div>
						<div class="col-md text-right">
							<form action="{{ route("2024.verifikator.produksi.markStatus", [$ijin, $tcode, "5"]) }}" method="post" enctype="multipart/form-data">
								@csrf
								<button type="submit" class="btn btn-success btn-sm" id="btnStatus-5" data-status="5">
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

			function formatListItem(label, value) {
				return '<li class="list-group-item d-flex justify-content-between">' +
					'<span class="text-muted">' + label + '</span>' +
					'<span>' + value + '</span>' +
				'</li>';
			}

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
				url: "{{ route('2024.datafeeder.getVerifProduksiByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
				type: "GET",
				success: function(response) {
					$('#companytitle').text(response.data.perusahaan);
					$('#no_ijin').text(response.data.no_ijin);
					$('#tgl_ijin').text(response.data.tgl_ijin);
					$('#tgl_akhir').text(response.data.tgl_akhir);
					$('#created_at').text(response.data.created_at);
					$('#jml_anggota').text(response.data.countAnggota + ' orang');

					if (response.data.status > 3) {
						$('#lokasiCheck').dataTable({
							responsive: true,
							lengthChange: false,
							ordering: true,
							processing: true,
							serverSide: true,
							ajax: {
								url: "{{ route('2024.datafeeder.getLokasiByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
								type: "GET",
							},
							columns: [
								{data: 'kode_spatial'},
								{
									data: 'luas_lahan',
									render: function(data, type, row) {
										var luasHa = data ? data / 10000 : 0;
										return luasHa + ' ha';
									}
								},
								{
									data: 'luas_tanam',
									render: function(data, type, row) {
										var luasHa = data ? data / 10000 : 0;
										return luasHa + ' ha';
									}
								},
								{data: 'nama_kelompok'},
								{data: 'nama_petani'},
								{
									data: 'status',
									render: function(data, type, row) {
										var status;
										if (data === 1) {
											status = '<span class="badge badge-success btn-xs btn-icon text"><i class="fal fa-check"></i></span>';
										} else if (data === 0) {
											status = '<span class="badge badge-danger btn-xs btn-icon text"><i class="fal fa-ban"></i></span>';
										} else {
											status = '<span class="badge badge-warning btn-xs btn-icon text"><i class="fal fa-exclamation-circle"></i></span>';
										}
										return status;
									}
								},
							],
							rowCallback: function(row, data) {
								$(row).css('cursor', 'pointer');
								$('td:eq(1)', row).addClass('text-right');
								$('td:eq(2)', row).addClass('text-right');
								$('td:eq(5)', row).addClass('text-center');
							}
						});

						$('#lokasiCheck tbody').on('click', 'tr', function () {
							// Get the data from the clicked row
							var data = $('#lokasiCheck').DataTable().row(this).data();
							var noIjin = '{{$ijin}}';  // Pass server-side value to JavaScript
							var ajutanam = '{{$verifikasi->tcode}}';  // Pass server-side value to JavaScript
							var tcode = data.tcode;

							// Construct the URL dynamically
							var url = "{{ route('2024.verifikator.produksi.verifLokasiByIjinBySpatial', ['noIjin' => ':noIjin', 'verifikasi' => ':ajutanam','tcode' => ':tcode']) }}"
								.replace(':noIjin', noIjin)
								.replace(':ajutanam', ajutanam)
								.replace(':tcode', tcode);

							// Redirect to the constructed URL
							window.location.href = url;
						});
					}

					var kemitraan = response.data.countPks + ' berkas / ' + response.data.countPoktan + ' kelompok';
					if (response.data.countPoktan / response.data.countPks == 1){
						$('#countPks').html('<span class="text-success">' + kemitraan + '</span>');
					}else{
						$('#countPks').html('<span class="text-danger">' + kemitraan + '</span>');
					}

					var realisasitanam = response.data.sumLuasTanam + ' / ' + response.data.sumLuas + ' ha';
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

			//menandai fase/tahap pemeriksaan
			$(document).on('click', '.btnStatus', function() {
				var status = $(this).data('status');
				var url = '{{ route("2024.verifikator.produksi.markStatus", [":noIjin", ":tcode", ":status"]) }}'
					.replace(':noIjin', noIjin)
					.replace(':tcode', tcode)
					.replace(':status', status);

				$.ajax({
					url: url,
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}'
					},
					success: function(response) {
						console.log('Response Status:', response.status);
						var statusText;
						var statusInt = parseInt(response.status);
						switch (statusInt) {
							case 2:
								statusText = 'BERKAS-BERKAS';
								break;
							case 3:
								statusText = 'PKS';
								break;
							case 4:
								statusText = 'TIMELINE REALISASI';
								break;
							case 5:
								statusText = 'LOKASI TANAM';
								break;
							default:
								statusText = 'Unknown Status';
								break;
						}

						Swal.fire({
							icon: 'success',
							title: 'Progress Pemeriksaan',
							text: 'Status pemeriksaan ' + statusText + ' ditandai SELESAI',
						}).then((result) => {
							if (result.isConfirmed) {

								$('#lokasiCheck').DataTable().ajax.reload();
							}
						});
					},
					error: function(xhr, status, error) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'There was an error updating the status.',
						});
					}
				});
			});
		});
	</script>
@endsection
