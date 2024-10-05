@extends('layouts.admin')
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
					<div class="panel-content p-5">

						<div class="row d-flex align-items-start">
							<div class="col-md-4">
								<span class="fw-bold h6 text-uppercase">RINGKASAN UMum</span>
							</div>
							<div class="col-md-8">
								<ul class="list-group list-group-flush">
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Perusahaan</span>
										<span class="fw-500" id="companyName"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Nomor Ijin</span>
										<span class="fw-500" id="noIjin"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Periode RIPH</span>
										<span class="fw-500" id="periode"></span>
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="row d-flex align-items-start">
							<div class="col-md-4">
								<span class="fw-bold h6 text-uppercase">RINGKASAN KEWAJIBAN DAN REALISASI</span>
							</div>
							<div class="col-md-8">
								<ul class="list-group list-group-flush">
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Realisasi Tanam (m2)</span>
										<span class="fw-500" id="realisasiTanam"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Realisasi Produksi</span>
										<span class="fw-500" id="sumPanen"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Jumlah Lokasi Tanam</span>
										<span class="fw-500" id="countSpatial"></span>
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="row d-flex align-items-start">
							<div class="col-md-4">
								<span class="fw-bold h6 text-uppercase">RINGKASAN KEMITRAAN</span>
							</div>
							<div class="col-md-8">
								<ul class="list-group list-group-flush">
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Jumlah Petani Mitra</span>
										<span class="fw-500" id="countAnggota"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Jumlah Kelompok Tani Mitra</span>
										<span class="fw-500" id="countPoktan"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Jumlah Perjanjian (PKS) diunggah</span>
										<span class="fw-500" id="countPks"></span>
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="row d-flex align-items-start">
							<div class="col-md-4">
								<span class="fw-bold h6 text-uppercase">KELENGKAPAN BERKAS</span>
							</div>
							<div class="col-md-8">
								<ul class="list-group list-group-flush">
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Surat Pengajuan Verifikasi Produksi</span>
										<span class="fw-500" id="spvp"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Surat Pertanggungjawaban Mutlak (Tanam)</span>
										<span class="fw-500" id="sptjmtanam"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Surat Pertanggungjawaban Mutlak (Produksi)</span>
										<span class="fw-500" id="sptjmProduksi"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Form Realisasi Tanam</span>
										<span class="fw-500" id="rta"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Form Realisasi Produksi</span>
										<span class="fw-500" id="rpo"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Logbook</span>
										<span class="fw-500" id="logProduksi"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">SPH-SBS (Tanam)</span>
										<span class="fw-500" id="sphsbstanam"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">SPH-SBS (Produksi)</span>
										<span class="fw-500" id="sphProduksi"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Laporan Akhir</span>
										<span class="fw-500" id="formLa"></span>
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="row d-flex align-items-start">
							<div class="col-md-4">
								<span class="fw-bold h6 text-uppercase">RINGKASAN HASIL VERIFIKASI</span>
							</div>
							<div class="col-md-8">
								<ul class="list-group list-group-flush">
									<li class="list-group-item">
										<span class="col-6 fw-500">A. TAHAP TANAM</span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Tanggal Pengajuan</span>
										<span class="fw-500" id="avtDate"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Tanggal Verifikasi</span>
										<span class="fw-500" id="avtVerifAt"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Metode Verifikasi</span>
										<span class="fw-500" id="avtMetode"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Catatan Verifikasi</span>
										<span class="fw-500" id="avtNote">
											<p></p>
										</span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Hasil Verifikasi</span>
										<span class="fw-500" id="avtStatus"></span>
									</li>
									<li class="list-group-item">
										<span class="col-6 fw-500">B. TAHAP PRODUKSI</span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Tanggal Pengajuan</span>
										<span class="fw-500" id="avpDate"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Tanggal Verifikasi</span>
										<span class="fw-500" id="avpVerifAt"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Metode Verifikasi</span>
										<span class="fw-500" id="avpMetode"></span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Catatan Verifikasi</span>
										<span class="fw-500" id="avpNote">
											<p></p>
										</span>
									</li>
									<li class="list-group-item d-flex justify-content-start align-items-start">
										<span class="text-muted col-6">Hasil Verifikasi</span>
										<span class="fw-500" id="avpStatus"></span>
									</li>
								</ul>
							</div>
						</div>
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
					<form action="{{route('2024.user.commitment.submitPengajuan', $ijin)}}" method="post">
						@csrf
						<input type="hidden" value="PRODUKSI" id="kind" name="kind">
						<button type="submit" class="btn btn-xs btn-warning" data-toggle="tooltip" title data-original-title="Ajukan Verifikasi Tanam" id="btnSubmit">
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
								<th>Laporan</th>
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

			function checkTanamFileKind(tanamFiles, kind) {
				return tanamFiles.some(file => file.kind === kind && file.berkas === 'Ada');
			}
			function checkProdFileKind(prodFiles, kind) {
				return prodFiles.some(file => file.kind === kind && file.berkas === 'Ada');
			}

			$.ajax({
				url: "{{ route('2024.datafeeder.getDataPengajuan', [':noIjin']) }}".replace(':noIjin', formattedNoIjin),
				type: "GET",
				success: function(data) {
					// Ringkasan Umum
					$('#companyName').text(data.company);
					$('#noIjin').text(data.noIjin);
					$('#periode').text(data.periode);

					//Ringkasan Realisasi dan Kewajiban

					var avtStatus = data.avtStatus;
					var avpStatus = data.avpStatus;

					var wajibTanam = data.wajibTanam * 10000;

					var realisasi = data.realisasiTanam;
					var wajibTanamFormatted = wajibTanam.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
					var realisasiFormatted = realisasi.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

					var luasTanam = realisasiFormatted + ' / ' + wajibTanamFormatted + ' m2';
					if (realisasi == '0' || realisasi == null || realisasi == undefined){
						$('#realisasiTanam').html('<span class="text-danger">' + luasTanam + '</span>');
					}else if (wajibTanam > realisasi){
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

					var realP = data.realisasiProduksi;
					var realisasiProduksi = data.realisasiProduksi/1000;
					var realPFormatted = realisasiProduksi.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });

					var volProduksi = realPFormatted + ' / ' + data.wajibProduksi + ' ton';
					if (realP == '0' || realP == null || realP == undefined){
						$('#sumPanen').html('<span class="text-danger">' + volProduksi + '</span>');
					}else if (realisasiProduksi > realP){
						$('#sumPanen').html('<span class="text-warning">' + volProduksi + '</span>');
					}else{
						$('#sumPanen').html('<span class="text-success">' + volProduksi + '</span>');
					}

					//Ringkasan Kemitraan
					$('#countAnggota').text(data.countAnggota + ' orang');
					$('#countPoktan').text(data.countPoktan + ' kelompok');
					$('#countPks').text(data.countPks + ' Berkas');


					//Kelengkapan Berkas
					//A. Berkas-berkas Tanam
					const tanamFiles = data.tanamFiles;
					$('#spvt').html(checkTanamFileKind(tanamFiles, 'spvt') ? '<span class="text-success">Ada</i></span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#sptjmtanam').html(checkTanamFileKind(tanamFiles, 'sptjmtanam') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#rta').html(checkTanamFileKind(tanamFiles, 'rta') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#sphsbstanam').html(checkTanamFileKind(tanamFiles, 'sphtanam') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#logTanam').html(checkTanamFileKind(tanamFiles, 'logbook') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');



					//B. Berkas-berkas Produksi checkProdFileKind
					const prodFiles = data.prodFiles;

					$('#spvp').html(checkProdFileKind(prodFiles, 'spvp') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#sptjmProduksi').html(checkProdFileKind(prodFiles, 'sptjmproduksi') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#rpo').html(checkProdFileKind(prodFiles, 'rpo') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#sphProduksi').html(checkProdFileKind(prodFiles, 'sphproduksi') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#logProduksi').html(checkProdFileKind(tanamFiles, 'logbook') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');

					$('#formLa').html(checkProdFileKind(prodFiles, 'formLa') ? '<span class="text-success">Ada</span>' : '<span class="text-danger">Tidak Ada</span>');


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

					switch(avtStatus) {
						case '0':
							statusMessage = 'Verifikasi sudah diajukan.';
							iconClass = 'fas fa-download';
							iconColorClass = 'text-primary';
							break;
						case '1':
							statusMessage = 'Penetapan Verifikator.';
							iconClass = 'fas fa-clock';
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
							// return;
					};

					$('#avtStatus').html(`<span class="${iconColorClass}">${statusMessage} </span> <i class="${iconClass} ${iconColorClass} ml-1"></i>`);

					//B. Verifikasi Produksi
					var avpDate = data.avpDate ? new Date(data.avpDate) : null;
					var formattedAvpDate = avpDate ? avpDate.toLocaleDateString('id-ID', options) : '-';

					var avpVerifAt = data.avpVerifAt ? new Date(data.avpVerifAt) : null;
					var formattedAvpVerifAt = avpVerifAt ? avpVerifAt.toLocaleDateString('id-ID', options) : '-';

					$('#avpDate').text(formattedAvpDate ?? '-');
					$('#avpVerifAt').text(formattedAvpVerifAt ?? '-');

					$('#avpMetode').text(data.avpMetode ?? '-');

					if (data.avpNote) {
						$('#avpNote p').text(data.avpNote);
					} else {
						$('#avpNote p').text('Tidak ada catatan.');
					}

					switch(avpStatus) {
						case '0':
							statusMessage = 'Verifikasi sudah diajukan.';
							iconClass = 'fas fa-download';
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
							iconClass = '';
							iconColorClass = '';
							$('#avpStatus').text(statusMessage);
							// return;
					};


					$('#avpStatus').html(`<span class="${iconColorClass}">${statusMessage} </span> <i class="${iconClass} ${iconColorClass} ml-1"></i>`);

					$('#btnSubmit').text('Ajukan');
					var avpStatus = data.avpStatus;
					var realisasiProduksi = data.realisasiProduksi;
					var countPoktan = data.countPoktan;
					var countPks = data.countPks;
					if (avpStatus === null && realisasiProduksi >= data.wajibProduksi) {
						$('#btnSubmit').removeClass('d-none');
					} else if (avpStatus === '5' && realisasiProduksi >= data.wajibProduksi) {
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
					url: "{{ route('2024.datafeeder.getVerifProdHistory', [':noIjin']) }}".replace(':noIjin', formattedNoIjin),
					type: 'GET',
					error: function (xhr, error, thrown) {
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
							var createdDate = data ? new Date(data) : null;
							var formattedCreate = createdDate ? createdDate.toLocaleDateString('id-ID', options) : '-';
							return formattedCreate;
						}
					},
					{
						data: 'verifAt',
						render: function (data, type, row) {
							var options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
							var verifDate = data ? new Date(data) : null;
							var formattedVerif = verifDate ? verifDate.toLocaleDateString('id-ID', options) : '-';
							return formattedVerif;
						}
					},
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
							} else if (data === '2' || data === '3' || data === '4' || data === '5' ) {
								currentStatus = 'Diperiksa';
								htmlClass = 'text-info';
							} else if (data === '6') {
								currentStatus = 'Selesai dan Sesuai';
								htmlClass = 'text-success';
							} else if (data === '7') {
								currentStatus = 'Selesai dan Perbaikan';
								htmlClass = 'text-danger';
							} else {
								currentStatus = '-';
							}
							return `<span class='${htmlClass}'>${currentStatus}</span>`;
						}
					},
					{
						data: 'reportUrl',
						render: function (data, type, row) {
							return data ? `<a href="${data}" target="_blank">Lihat</a>` : '-';
						}
					},
				],
			});

		});
	</script>
@endsection
