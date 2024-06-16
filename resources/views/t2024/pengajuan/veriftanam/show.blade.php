@extends('t2024.layouts.admin')
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@include('t2024.partials.sysalert')

	<div class="row">
		<div class="col-12">
			<div class="text-center">
				<i class="fal fa-badge-check fa-3x subheader-icon"></i>
				<h2>Ringkasan Data</h2>
				<div class="row justify-content-center">
					<p class="lead">Ringkasan {{$page_heading}}.</p>
				</div>
			</div>

			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-hover table-sm w-100" style="border: none; border-top:none; border-bottom:none;" id="dataTable">
							<thead class="">
								<th  style="width: 32%"></th>
								<th style="width: 1%"></th>
								<th></th>
								<th></th>
							</thead>
							<tbody>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN UMUM</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Perusahaan</td>
									<td>:</td>
									<td class="fw-500" id="companyName"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Nomor Ijin (RIPH)</td>
									<td>:</td>
									<td class="fw-500" id="noIjin"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Periode RIPH</td>
									<td>:</td>
									<td class="fw-500" id="periode"></td>
									<td></td>
								</tr>
								<tr class="bg-primary-50" style="height: 25px; opacity: 0.2">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN KEWAJIBAN DAN REALISASI</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Luas Wajib Tanam</td>
									<td>:</td>
									<td class="fw-500" id="wajibTanam"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Realisasi Tanam</td>
									<td>:</td>
									<td class="fw-500" id="realisasiTanam">
										<span></span>
										<i></i>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Lokasi Tanam/Spasial</td>
									<td>:</td>
									<td class="fw-500" id="countSpatial"></td>
									<td></td>
								</tr>
								<tr class="" style="height: 25px;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN KEMITRAAN</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Petani Mitra</td>
									<td>:</td>
									<td class="fw-500" id="countAnggota"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Kelompok Tani Mitra</td>
									<td>:</td>
									<td class="fw-500" id="countPoktan"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Perjanjian (PKS) diunggah</td>
									<td>:</td>
									<td class="fw-500" id="countPks">
										<span></span>
										<i></i>
									</td>
									<td></td>
								</tr>
								<tr class="bg-primary-50" style="height: 25px; opacity: 0.2">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>

								<tr>
									<td class="text-uppercase fw-500">KELENGKAPAN BERKAS</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500">A. TAHAP TANAM</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Surat Pengajuan Verifikasi Tanam</td>
									<td>:</td>
									<td class="fw-500" id="spvt">
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Surat Pertanggungjawaban Mutlak (Tanam)</td>
									<td>:</td>
									<td class="fw-500" id="sptjmtanam"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Form Realisasi Tanam</td>
									<td>:</td>
									<td class="fw-500" id="rta"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">SPH-SBS (Tanam)</td>
									<td>:</td>
									<td class="fw-500" id="sphsbstanam"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Logbook (s.d Tanam)</td>
									<td>:</td>
									<td class="fw-500" id="logTanam"></td>
									<td></td>
								</tr>

								{{-- hasil pemeriksaan --}}
								<tr class="bg-primary-50" style="height: 25px; opacity: 0.2">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN HASIL</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500">A. VERIFIKASI TANAM</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Tanggal Pengajuan</td>
									<td>:</td>
									<td class="fw-500" id="avtDate"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Tanggal Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtVerifAt"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Metode Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtMetode"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Catatan Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtNote">
										<p></p>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Hasil Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtStatus">

									</td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="card-footer d-flex justify-content-end">
					<a href="{{ route('2024.user.commitment.index') }}"
						class="btn btn-xs btn-info mr-1" data-toggle="tooltip"
						title data-original-title="Kembali">
						<i class="fal fa-undo mr-1"></i>
						Kembali
					</a>
					{{-- Form pengajuan --}}
					{{-- pengajuan tanam --}}
					<form action="{{route('2024.user.commitment.formavt.submitPengajuan', $ijin)}}" method="post">
						@csrf
						<button type="submit" class="btn btn-xs btn-warning d-none" data-toggle="tooltip" title data-original-title="Ajukan Verifikasi Tanam" id="btnSubmit">
							<i class="fal fa-upload mr-1"></i>
							Ajukan
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div id="panel-1" class="panel">
				<div class="panel-hdr">
					<h5>Riwayat Pengajuan Verifikasi</h5>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<table class="table table-hover table-sm w-100" style="border: none; border-top:none; border-bottom:none;" id="tblVerifHistory">
							<thead class="">
								<th>Tanggal Diajukan</th>
								<th>Verifikator</th>
								<th>Tanggal Verifikasi</th>
								<th>Status</th>
								<th>Catatan</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


@endsection

@section('scripts')
	@parent
	<script>
		$(document).ready(function() {
			var noIjin = '{{$ijin}}';
			var formattedNoIjin = noIjin.replace(/[\/.]/g, '');

			$.ajax({
				url: "{{ route('2024.datafeeder.getDataPengajuan', [':noIjin']) }}".replace(':noIjin', formattedNoIjin),
				type: "GET",
				success: function(data) {
					// Populate the HTML elements with the data
					$('#companyName').text(data.company);
					$('#noIjin').text(data.noIjin);
					$('#periode').text(data.periode);
					$('#wajibTanam').text(data.wajibTanam + ' ha');

					if (data.wajibTanam > data.realisasiTanam) {
						$('#realisasiTanam span').text(data.realisasiTanam + ' ha').addClass('text-warning');
						$('#realisasiTanam i').addClass('fas fa-exclamation-circle text-warning ml-1');
					} else {
						$('#realisasiTanam span').text(data.realisasiTanam + ' ha');
						$('#realisasiTanam i').addClass('fas fa-check ml-1');
					}

					var titikTanam = data.countTanam + ' / ' + data.countSpatial + ' titik';

					if (data.countSpatial > data.countTanam){
						$('#countSpatial').html('<span class="text-danger">' + titikTanam + '</span>');
					}else{
						$('#countSpatial').html('<span class="text-success">' + titikTanam + '</span>');
					}

					$('#countAnggota').text(data.countAnggota + ' orang');
					$('#countPoktan').text(data.countPoktan + ' kelompok');

					if (data.countPoktan > data.countPks) {
						$('#countPks span').text(data.countPks + ' berkas').addClass('text-warning');
						$('#countPks i').addClass('fas fa-exclamation-circle text-warning ml-1');
					} else {
						$('#countPks span').text(data.countPks + ' berkas');
						$('#countPks i').addClass('fas fa-check ml-1');
					}

					$('#spvt').html(data.spvt ? '<span class="text-success">Ada <i class="fal fa-check"></i></span>' : '<span class="text-danger">Tidak Ada <i class="fas fa-exclamation-circle"></i></span>');

					$('#sptjmtanam').html(data.sptjmtanam ? '<span class="text-success">Ada <i class="fal fa-check"></i></span>' : '<span class="text-danger">Tidak Ada <i class="fas fa-exclamation-circle"></i></span>');

					$('#rta').html(data.rta ? '<span class="text-success">Ada <i class="fal fa-check"></i></span>' : '<span class="text-danger">Tidak Ada <i class="fas fa-exclamation-circle"></i></span>');

					$('#sphsbstanam').html(data.sphtanam ? '<span class="text-success">Ada <i class="fal fa-check"></i></span>' : '<span class="text-danger">Tidak Ada <i class="fas fa-exclamation-circle"></i></span>');

					$('#logTanam').html(data.logbooktanam ? '<span class="text-success">Ada <i class="fal fa-check"></i></span>' : '<span class="text-danger">Tidak Ada <i class="fas fa-exclamation-circle"></i></span>');

					var options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };

					var avtDate = data.avtDate ? new Date(data.avtDate) : null;
					var formattedAvtDate = avtDate ? avtDate.toLocaleDateString('id-ID', options) : '-';

					var avtVerifAt = data.avtVerifAt ? new Date(data.avtVerifAt) : null;
					var formattedAvtVerifAt = avtVerifAt ? avtVerifAt.toLocaleDateString('id-ID', options) : '-';

					$('#avtDate').text(formattedAvtDate ?? '-');
					$('#avtVerifAt').text(formattedAvtVerifAt ?? '-');

					$('#avtMetode').text(data.avtMetode ?? '-');

					if (data.avtNote) {
						$('#avtNote p').text(data.avtNote);
					} else {
						$('#avtNote p').text('Tidak ada catatan.');
					}

					if (data.avtStatus == '1') {
                    	$('#avtStatus').html('<span class="text-primary">Verifikasi sudah diajukan. </span> <i class="fas fa-check text-primary ml-1"></i>');
					} else if (data.avtStatus == '2' || data.avtStatus == '3') {
						$('#avtStatus').html('<span class="text-warning">Dalam proses pemeriksaan/verifikasi oleh Petugas. </span> <i class="fas fa-clock text-warning ml-1"></i>');
					} else if (data.avtStatus == '4') {
						$('#avtStatus').html('<span class="text-success">Pemeriksaan/Verifikasi telah Selesai. </span> <i class="fas fa-check ml-1"></i>');
					} else if (data.avtStatus == '5') {
						$('#avtStatus').html('<span class="text-danger">Laporan Realisasi harus diperbaiki (lihat catatan verifikasi). </span> <i class="fas fa-exclamation-circle ml-1"></i>');
					} else {
						$('#avtStatus').text('Belum/Tidak ada pengajuan.');
					}

					//set submit button
					//1. jika tidak ada status realisasi sudah ada, dan pks juga lengkap
					//2. jika status adalah 5,
					//3. jika selain itu

					$('#btnSubmit').text('Ajukan');
					var avtStatus = data.avtStatus;
					var realisasiTanam = data.realisasiTanam;
					var countPoktan = data.countPoktan;
					var countPks = data.countPks;
					if (avtStatus === null && realisasiTanam > 0 && countPks === countPoktan) {
						$('#btnSubmit').removeClass('d-none');
					} else if (avtStatus === '5' ) { //&& realisasiTanam > 0 && countPks === countPoktan
						$('#btnSubmit').removeClass('d-none');
						$('#btnSubmit').text('Ajukan Ulang');
					} else {
						$('#btnSubmit').addClass('d-none');
					}
				},
				error: function(xhr, status, error) {
					console.error('Error fetching data:', error);
				}
			});

			$('#dataTable').dataTable(
			{
				responsive: true,
				lengthChange: false,
				ordering: false,
				pageLength: -1,
				dom:
					"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
					"<'row'<'col-sm-12'tr>>" +
					"<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",
				buttons: [
					{
						extend: 'pdfHtml5',
						text: '<i class="fa fa-file-pdf"></i>',
						titleAttr: 'Generate PDF',
						className: 'btn-outline-danger btn-sm btn-icon mr-1',
						customize: function(doc) {
							doc.pageMargins = [10, 20, 10, 20];
							doc.styles.tableHeader = {
								fontSize: 12,
								bold: true,
								alignment: 'center'
							};
							doc.defaultStyle = {
								fontSize: 10
							};
							doc.content[1].table.widths = [
								'25%',
								'5%',
								'12%',
								'13%',
								'45%',
							];
						},
						exportOptions: {
							columns: [0, 2]
						}
					},
					{
						extend: 'excelHtml5',
						text: '<i class="fa fa-file-excel"></i>',
						titleAttr: 'Generate Excel',
						className: 'btn-outline-success btn-sm btn-icon mr-1',
						exportOptions: {
							columns: [0, 2]
						},
						customize: function(xlsx) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];

							// Mengatur lebar kolom
							$('col', sheet).each(function() {
								$(this).attr('width', 30);
							});

							// Menambahkan gaya khusus
							$('row c[r^="C"]', sheet).each(function() {
								if ($('is t', this).text() == 'Some Text') {
									$(this).attr('s', '42');
								}
							});

							// Menambahkan border ke header
							$('row:first c', sheet).attr('s', '2');
						}
					},
					{
						extend: 'print',
						text: '<i class="fa fa-print"></i>',
						titleAttr: 'Print Table',
						className: 'btn-outline-primary btn-sm btn-icon mr-1',
						exportOptions: {
							columns: [0, 2]
						},
					}
				]
			});

			var table = $('#tblVerifHistory').DataTable({
				responsive: true,
				pageLength: 10,
				lengthChange: true,
				paging: true,
				ordering: true,
				processing: true,
				serverSide: true,
				order: [[0, 'asc']],
				ajax: {
					url: "{{ route('2024.datafeeder.getVerifTanamHistory', [':noIjin']) }}".replace(':noIjin', formattedNoIjin),
					type: 'GET',
					error: function (xhr, error, thrown) {
						// Menangani kesalahan ketika AJAX request gagal atau tidak ada data
						if (xhr.status === 404) {
							console.log("Data tidak ditemukan");
						} else {
							console.error("Terjadi kesalahan: " + xhr.status);
						}
					}
				},
				dom:
					"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'>>" +
					"<'row'<'col-sm-12'tr>>" +
					"<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",
				columns: [
					{ data: 'createdAt' },
					{ data: 'verifAt' },
					{ data: 'checkBy' },
					{
						data: 'status',
						render: function (data, type, row) {
							let currentStatus;
							let htmlClass = ''; // Variabel untuk menentukan kelas HTML jika diperlukan
							if (data == null || data == undefined) {
								currentStatus = '-';
							} else if (data === '1') {
								currentStatus = 'Pengajuan';
								htmlClass = '';
							} else if (data === '2' || data === '3') {
								currentStatus = 'Diperiksa';
								htmlClass = '';
							} else if (data === '4') {
								currentStatus = 'Selesai';
								htmlClass = 'text-success';
							} else if (data === '5') {
								currentStatus = 'Perbaikan';
								htmlClass = 'text-danger';
							} else {
								currentStatus = '-';
							}
							return `<span class='${htmlClass}'>${currentStatus}</span>`;
						}
					},
					{
						data: 'note',
						render: function (data, type, row) {
							return data ? `<p>${data}</p>` : '-';
						}
					},
				],
			});

		});
	</script>
@endsection
