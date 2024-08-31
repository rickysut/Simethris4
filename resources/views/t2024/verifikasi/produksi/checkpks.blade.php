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
				@include('t2024.verifikasi.produksi.infocard')
			</div>
			<div class="col-lg-9">
				<div id="panel-2" class="panel">
					<div class="panel-container show">
						@include('t2024.verifikasi.produksi.checkmenu')
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
							<form action="{{ route("2024.verifikator.produksi.markStatus", [$ijin, $tcode, "3"]) }}" method="post" enctype="multipart/form-data">
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
				url: "{{ route('2024.datafeeder.getVerifProduksiByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
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
									// Tutup baris lain yang diperluas
									if ($('.expanded-row').length > 0) {
										$('.expanded-row').prev().removeClass('selected');
										$('.expanded-row').remove(); // Hapus baris yang sebelumnya diperluas
									}

									// Berikan kelas 'selected' pada baris yang diklik
									$('#pksCheck tbody tr').removeClass('selected');
									$(this).addClass('selected');

									// Periksa apakah baris berikutnya sudah diperluas
									if ($(this).next().hasClass('expanded-row')) {
										$(this).next().remove(); // Collapse baris jika sudah diperluas
									} else {
										var formHTML = '<tr class="expanded-row"><td colspan="5" class="bg-primary-50">';

										var berkasUrl = data.file_url;
										console.log(data.file_url);
										formHTML += '<form class="" id="expandForm">';
											formHTML += '<ul class="list-group">';
												//link berkas
												formHTML += '<li class="d-flex list-group-item justify-content-between align-item-start">';
													formHTML += '<div class="col-md-3">';
														formHTML += '<span class="text-left">Berkas:</span>';
													formHTML += '</div>';
													formHTML += '<div class="col-md-9">';
														formHTML += '<span class="text-left"><a href="' + berkasUrl + '" target="blank"> Unduh Berkas </a></div></span>';
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

					var realisasipanen = response.data.sumPanen + ' / ' + response.data.sumWajibVol + ' ton';
					if (response.data.sumPanen / response.data.sumWajibVol < 1){
						$('#volume_panen').html('<span class="text-danger">' + realisasipanen + '</span>');
					}else{
						$('#volume_panen').html('<span class="text-success">' + realisasipanen + '</span>');
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
					url: "{{ route('2024.verifikator.produksi.verifPksStore', [':noIjin', ':tcode']) }}".replace(':noIjin', noIjin).replace(':tcode', tcode),
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
								$('#pksCheck').DataTable().ajax.reload();
							if (result.isConfirmed) {
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

		});
	</script>
@endsection
