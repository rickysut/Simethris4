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
				<div class="card mb-g">
					<div class="card-header">
						<div class="d-flex flex-row pt-2  border-top-0 border-left-0 border-right-0">
							<div class="d-inline-block align-middle mr-3">
								<span class="profile-image rounded-circle d-block" style="background-image:url(); background-size: cover;" id="companyLogo"></span>
							</div>
							<h3 class="mb-0 flex-1 text-dark fw-500">
								<span id="companytitle"></span>
								<small class="m-0 l-h-n font-weight-bold"></small>
							</h3>
							<span class="">
							</span>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="form-group col-12">
								<label class="text-muted" for="no_ijin">Nomor RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="no_ijin" name="no_ijin"></span>
								</div>
							</div>

							<div class="form-group col-12">
								<label class="text-muted" for="tgl_ijin">Tanggal Ijin RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="tgl_ijin" name="tgl_ijin"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="tgl_akhir">Tanggal Akhir RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="tgl_akhir" name="tgl_akhir"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="created_at">Tanggal Pengajuan Verifikasi</label>
								<div class="input-group">
									<span class="font-weight-bold" id="created_at" name="created_at"></span>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12"><h5 class="font-weight-bold">Ringkasan</h5></div>
							<div class="form-group col-12">
								<label class="text-muted" for="countPks">Kemitraan</label>
								<div class="input-group">
									<span class="font-weight-bold" id="countPks" name="countPks"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="jml_anggota">Jumlah Anggota</label>
								<div class="input-group">
									<span class="font-weight-bold" id="jml_anggota" name="jml_anggota"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="luas_tanam">Realisasi Tanam</label>
								<div class="input-group">
									<span class="font-weight-bold" id="luas_tanam" name="luas_tanam"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="jml_titik">Lokasi Tanam</label>
								<div class="input-group">
									<span class="font-weight-bold" id="jml_titik" name="jml_titik"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-9">
				<div id="panel-2" class="panel">
					<div class="panel-container show">
						@include('t2024.verifikasi.tanam.checkmenu')
					</div>
					<div class="panel-container show">
						<div class="panel-tag fade show">
							<div class="d-flex align-items-center">
								<i class="fal fa-info-circle mr-1"></i>
								<div class="flex-1">
									<small class="text-danger">klik tombol di bawah untuk menandai proress pemeriksaan di sini. Set status ke "3".</small>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<table class="table table-striped table-bordered table-sm w-100" id="pksCheck">
								<thead class="thead-themed text-uppercase text-muted">
									<tr>
										<th style="width: 20%">No Perjanjian</th>
										<th>Kelompok Tani</th>
										<th>Berlaku Sejak</th>
										<th>Berakhir Pada</th>
										<th>Status</th>
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
							<form action="{{ route("2024.verifikator.tanam.markStatus", [$ijin, $tcode, "3"]) }}" method="post" enctype="multipart/form-data">
								@csrf
								<button type="submit" class="btn btn-success btn-sm" id="btnStatus-3" data-status="3">
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
				url: "{{ route('2024.datafeeder.getVerifTanamByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
				type: "GET",
				success: function(response) {
					$('#companytitle').text(response.data.perusahaan);
					$('#no_ijin').text(response.data.no_ijin);
					$('#tgl_ijin').text(response.data.tgl_ijin);
					$('#tgl_akhir').text(response.data.tgl_akhir);
					$('#created_at').text(response.data.created_at);
					$('#jml_anggota').text(response.data.countAnggota + ' orang');
					if (response.data.status > 1) {
						$('#pksCheck').dataTable({
							responsive: true,
							lengthChange: false,
							ordering: true,
							processing: true,
							serverSide: true,
							ajax: {
								url: "{{ route('2024.datafeeder.getPksByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
								type: "GET",
							},
							columns: [
								{data: 'no_perjanjian'},
								{data: 'nama_poktan'},
								{
									data: 'tgl_perjanjian_start',
									render: function(data, type, row) {
										return formatDate(data);
									}
								},
								{
									data: 'tgl_perjanjian_end',
									render: function(data, type, row) {
										return formatDate(data);
									}
								},
								{
									data: 'status',
									render: function(data, type, row) {
										if (data === null) {
											return '<span class="btn btn-icon btn-xs btn-warning "><i class="fal fa-exclamation-circle"></i></span>';
										} else if (data === 'Sesuai') {
											return '<span class="btn btn-icon btn-xs btn-success"><i class="fal fa-check"></i></span>';
										} else if (data === 'Tidak Sesuai') {
											return '<span class="btn btn-icon btn-xs btn-danger"><i class="fas fa-ban"></i></span>';
										} else {
											return ''; // Jika nilai status tidak sesuai dengan kondisi yang diberikan
										}
									}
								},
							],
							rowCallback: function(row, data) {
								$('td:eq(2)', row).addClass('text-right');
								$('td:eq(3)', row).addClass('text-right');
								$('td:eq(4)', row).addClass('text-center');
								$(row).css('cursor', 'pointer');
								$(row).on('click', function() {
									$('#pksCheck tbody tr').removeClass('selected');
									$(this).addClass('selected');

									if ($(this).next().hasClass('expanded-row')) {
										$(this).next().remove();
									} else {
										var formHTML = '<tr class="expanded-row"><td colspan="5">';
										var berkasUrl = '{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun) }}/' + data.berkas_pks;
											formHTML += '<form class="" id="expandForm">';
												formHTML += '<ul class="list-group">';
													//link berkas
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Berkas:</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<span class="text-left"><a href="' + berkasUrl + '" download>' + data.berkas_pks + '</a></div></span>';
														formHTML += '</div>';
													formHTML += '</li>';

													//status periksa
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Hasil Pemeriksaan:</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<div class="form-group">';
																formHTML += '<label for="status" class="sr-only">Status Pemeriksaan</label>';
																formHTML += '<select id="status" name="status" class="form-control form-control-sm" required>';
																	formHTML += '<option hidden value="">- pilih status periksa -</option>';
																	formHTML += '<option value="Sesuai"' + (data.status === 'Sesuai' ? ' selected' : '') + '>Sesuai</option>';
																	formHTML += '<option value="Tidak Sesuai"' + (data.status === 'Tidak Sesuai' ? ' selected' : '') + '>Tidak Sesuai</option>';
																formHTML += '</select>';
																formHTML += '<small id="helpId" class="text-muted">Status hasil pemeriksaan.</small>';
															formHTML += '</div>';
														formHTML += '</div>';
													formHTML += '</li>';

													//Catatan periksa
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Catatan Verifikasi:</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<div class="form-group">';
																formHTML += '<label for="note" class="sr-only">Catatan Verifikasi:</label>';
																formHTML += '<textarea id="note" name="note" class="form-control form-control-sm" rows="3">' + (data.note || '') + '</textarea>';
																formHTML += '<small id="helpId" class="text-muted">Catatan pemeriksaan.</small>';
															formHTML += '</div>';
														formHTML += '</div>';
													formHTML += '</li>';

													//Submit hasil
													formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
														formHTML += '<div class="col-md-3">';
															formHTML += '<span class="text-left">Tindakan</span>';
														formHTML += '</div>';
														formHTML += '<div class="col-md-9">';
															formHTML += '<button class="btn btn-warning btn-block btn-xs" type="button" id="submitCheckPks" data-code="'+ data.kode_poktan +'">Simpan</button>';
														formHTML += '</div>';
													formHTML += '</li>';
												formHTML += '</ul>';
											formHTML += '</form>';
										formHTML += '</td></tr>';

										$(this).after(formHTML);
									}
								});
							}
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

			//simpan hasil pemeriksaan berkas PKS
			$(document).on('click', '#submitCheckPks', function() {
				var tcode = $(this).data('code');
				var status = $('#status').val(); // Mengambil nilai dari input status
				var note = $('#note').val(); // Mengambil nilai dari input note

				$.ajax({
					url: "{{ route('2024.verifikator.tanam.verifPksStore', [':noIjin', ':tcode']) }}".replace(':noIjin', noIjin).replace(':tcode', tcode),
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						status: status,
						note: note
					},
					success: function(response) {
						Swal.fire({
							icon: 'success',
							title: 'Progress Pemeriksaan',
							text: 'Status pemeriksaan ditandai sebagai ' + status,
						}).then((result) => {
							if (result.isConfirmed) {
								$('#pksCheck').DataTable().ajax.reload();
							}
						});
					},
					error: function(xhr, status, error) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: error,
						});
					}
				});
			});

			//menandai fase/tahap pemeriksaan
			$(document).on('click', '.btnStatus', function() {
				var status = $(this).data('status');
				var url = '{{ route("2024.verifikator.tanam.markStatus", [":noIjin", ":tcode", ":status"]) }}'
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
