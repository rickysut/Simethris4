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
						<button type="submit" class="btn btn-xs btn-warning d-block" data-toggle="tooltip" title data-original-title="Ajukan Verifikasi Tanam" id="btnSubmit">
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
					// Ringkasan Umum
					$('#companyName').text(data.company);
					$('#noIjin').text(data.noIjin);
					$('#periode').text(data.periode);

					//Ringkasan Realisasi dan Kewajiban
					var wajibTanam = data.wajibTanam * 10000;
					var realisasi = data.realisasiTanam;
					var wajibTanamFormatted = wajibTanam.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
					var realisasiFormatted = realisasi.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

					var luasTanam = realisasiFormatted + ' / ' + wajibTanamFormatted + ' m2';
					if (realisasiFormatted == '0' || realisasiFormatted == null || realisasiFormatted == undefined){
						$('#realisasiTanam').html('<span class="text-danger">' + luasTanam + '</span>');
					}else if (wajibTanamFormatted > realisasiFormatted){
						$('#realisasiTanam').html('<span class="text-warning">' + luasTanam + '</span>');
					}else{
						$('#realisasiTanam').html('<span class="text-success">' + luasTanam + '</span>');
					}

					var titikTanam = data.countTanam + ' / ' + data.countSpatial + ' titik';

					if (data.countSpatial > data.countTanam){
						$('#countSpatial').html('<span class="text-danger">' + titikTanam + '</span>');
					}else{
						$('#countSpatial').html('<span class="text-success">' + titikTanam + '</span>');
					}

					//Ringkasan Kemitraan
					$('#countAnggota').text(data.countAnggota + ' orang');
					$('#countPoktan').text(data.countPoktan + ' kelompok');

					if (data.countPoktan > data.countPks) {
						$('#countPks').html('<span class="text-danger">' + data.countPks +' PKS</span>');
					} else {
						$('#countPks').html('<span class="text-danger">' + data.countPks +' PKS</span>');
					}


					//Kelengkapan Berkas
					//A. Berkas-berkas Tanam
					$('#spvt').html(data.spvt ? '<span class="text-success">Ada</i></span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#sptjmtanam').html(data.sptjmtanam ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#rta').html(data.rta ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#sphsbstanam').html(data.sphtanam ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#logTanam').html(data.logbooktanam ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');


					var options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };

					//Ringkasan Hasil
					//A. Verifikasi Tanam
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

					let statusMessage = '';
					let iconClass = '';
					let iconColorClass = '';

					switch(data.avtStatus) {
						case '0':
							statusMessage = 'Verifikasi sudah diajukan.';
							iconClass = 'fas fa-check';
							iconColorClass = 'text-primary';
							break;
						case '1':
							statusMessage = 'Penetapan Verifikator.';
							iconClass = 'fas fa-check';
							iconColorClass = 'text-primary';
							break;
						case '2':
						case '3':
						case '4':
						case '5':
							statusMessage = 'Dalam proses pemeriksaan/verifikasi oleh Petugas.';
							iconClass = 'fas fa-clock';
							iconColorClass = 'text-warning';
							break;
						case '6':
							statusMessage = 'Pemeriksaan/Verifikasi telah Selesai.';
							iconClass = 'fas fa-check';
							iconColorClass = 'text-success';
							break;
						case '7':
							statusMessage = 'Laporan Realisasi harus diperbaiki (lihat catatan verifikasi).';
							iconClass = 'fas fa-exclamation-circle';
							iconColorClass = 'text-danger';
							break;
						default:
							statusMessage = 'Belum/Tidak ada pengajuan.';
							$('#avtStatus').text(statusMessage);
							return;
					}

					$('#avtStatus').html(`<span class="${iconColorClass}">${statusMessage} </span> <i class="${iconClass} ${iconColorClass} ml-1"></i>`);


					$('#btnSubmit').text('Ajukan');
					var avtStatus = data.avtStatus;
					var realisasiProduksi = data.realisasiProduksi;
					var countPoktan = data.countPoktan;
					var countPks = data.countPks;
					if (avtStatus === null && realisasiProduksi >= data.wajibProduksi) {
						$('#btnSubmit').removeClass('d-none');
					} else if (avtStatus == '7' && realisasiProduksi >= data.wajibProduksi) {
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
					{
						data: 'createdAt',
						render: function (data, type, row) {
							var options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
							var avtDate = data ? new Date(data) : null;
							var formattedAvtDate = avtDate ? avtDate.toLocaleDateString('id-ID', options) : '-';
							return formattedAvtDate;
						}
					},
					{ data: 'checkBy' },
					{
						data: 'verifAt',
						render: function (data, type, row) {
							var options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
							var verifDate = data ? new Date(data) : null;
							var formattedverifDate = verifDate ? verifDate.toLocaleDateString('id-ID', options) : 'belum diverifikasi';
							return formattedverifDate;
						}
					},
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
