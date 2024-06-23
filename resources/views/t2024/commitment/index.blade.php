@extends('t2024.layouts.admin')
@section ('styles')
<style>
td {
	vertical-align: middle !important;
}
</style>
@endsection
@section('content')
{{-- @include('partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@can('commitment_access')
@include('t2024.partials.sysalert')
<div class="row">
	<div class="col-12">
		<div class="panel" id="panel-1">
			<div class="panel-container show">
				<div class="panel-content">
					<table id="datatable" class="table table-bordered table-hover table-striped table-sm w-100">
						<thead class="thead-themed">
							<th>No. RIPH</th>
							<th>Tahun</th>
							<th>Tgl. Terbit</th>
							<th>Vol. RIPH</th>
							<th>Kewajiban</th>
							<th>Data</th>
							<th>Tanam</th>
							<th>Prod</th>
							<th>SKL</th>
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

@section('scripts')
@parent
<script>
</script>


<script>
	$(document).ready(function() {

		var table = $('#datatable').DataTable({
			responsive: true,
			pageLength:10,
			lengthChange: true,
			paging: true,
			ordering: true,
			processing: true,
			serverSide: true,
			order: [[0, 'asc']],
			ajax: {
				url: '{{ route('2024.datafeeder.getAllMyCommitment') }}',
				type: 'GET',
			},

			columnDefs:[
				{
					targets: [5, 6, 7, 8],
					className: "text-center"
				},
				{
					targets: [4,5,6,7,8],
					orderable: false
				}
			],

			columns: [
				{
					data: 'ijin_full',
					render: function (data, type, row) {
						var noIjin = row.noIjin;
						var url = "{{ route('2024.user.commitment.show', ':noIjin') }}".replace(':noIjin', noIjin);
						return `
							<a href="`+ url + `" title="Lihat Data" class="" target="_blank">
								`+ data +`
							</a>
						`;
					}
				},
				{ data: 'periodetahun'},
				{ data: 'tgl_terbit'},
				{
					data: 'volume',
					render: function (data, type, row) {
						var formattedVolume = new Intl.NumberFormat('id-ID').format(data);
						return formattedVolume;
					}
				},
				{
					data: 'wajib_produksi',
					render: function (data, type, row) {
						var wajibLuas = row.wajib_tanam; // bagi /1000 Mengonversi meter persegi ke ribuan hektar
						var formattedLuas = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 3 }).format(wajibLuas);
						var formattedProd = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(data);
						return `
							<div class="row">
								<div class="col-3">
									Tanam
								</div>
								<div class="col-9 text-right">
									`+ formattedLuas +` ha
								</div>
							</div>
							<div class="row">
								<div class="col-3">
									Produksi
								</div>
								<div class="col-9 text-right">
									`+ formattedProd +` ha ton
								</div>
							</div>
						`;
					}
				},
				{
					data: 'noIjin',
					render: function (data, type, row) {
						var noIjin = data;
						var url = "{{ route('2024.user.commitment.realisasi', ':noIjin') }}".replace(':noIjin', noIjin);
						return `
							<a href="`+ url +`"
								class="btn btn-icon btn-xs btn-primary" data-toggle="tooltip"
								title data-original-title="Isi Laporan Realisasi Tanam dan Produksi">
								<i class="fal fa-edit"></i>
							</a>
						`;
					}
				},
				{
					data: 'siapVerifTanam',
					render: function (data, type, row) {
						var noIjin = row.noIjin;
						var status = data;
						var avTanamStatus = row.avTanamStatus;
						var formAvt = "{{ route('2024.user.commitment.formavt', ':noIjin') }}".replace(':noIjin', noIjin);

						console.log("noIjin: " + noIjin);
						console.log("status: " + status);
						console.log("avTanamStatus: " + avTanamStatus);

						if (status === "Belum Siap") {
							return "Belum Siap";
						} else if (status === "Siap") {
							switch (avTanamStatus) {
								case "Tidak ada":
									return `<a href="${formAvt}" class="btn btn-icon btn-xs btn-warning">
												<i class="fal fa-upload"></i>
											</a>`;
								case "1":
									return `<a href="${formAvt}" class="btn btn-icon btn-xs btn-info">
												<i class="fal fa-clock"></i>
											</a>`;
								case "2":
								case "3":
									return `<a href="${formAvt}" class="btn btn-icon btn-xs btn-warning">
												<i class="fal fa-hourglass"></i>
											</a>`;
								case "4":
									return `<a href="${formAvt}" class="btn btn-icon btn-xs btn-success">
												<i class="fal fa-check"></i>
											</a>`;
								case "5":
									return `<div class="dropdown">
												<a href="#" class="btn btn-danger btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fa fa-exclamation"></i>
												</a>
												<div class="dropdown-menu">
													<a class="dropdown-item" style="text-decoration: none !important;" href="${formAvt}" target="_blank">
														Lihat Hasil Verifikasi
													</a>
													<a class="dropdown-item" style="text-decoration: none !important;" href="${formAvt}" target="_blank" data-toggle="tooltip" title="Perbaiki data dan laporan. Lalu ajukan verifikasi ulang.">
														Ajukan Ulang
													</a>
												</div>
											</div>`;
								default:
									return '';
							}
						} else {
							return "error";
						}
					}
				},
				{
					data: 'siapVerifProduksi',
					render: function (data, type, row) {
						var noIjin = row.noIjin;
						var status = data;
						var avProdStatus = row.avProdStatus;
						var formAvp = "{{ route('2024.user.commitment.formavp', ':noIjin') }}".replace(':noIjin', noIjin);

						if (status === "Belum Siap") {
							return "Belum Siap";
						} else if (status === "Siap") {
							switch (avProdStatus) {
								case "Tidak ada":
									return `<a href="${formAvp}" class="btn btn-icon btn-xs btn-warning">
												<i class="fal fa-upload"></i>
											</a>`;
								case "1":
									return `<a href="${formAvp}" class="btn btn-icon btn-xs btn-info">
												<i class="fal fa-clock"></i>
											</a>`;
								case "2":
								case "3":
									return `<a href="${formAvp}" class="btn btn-icon btn-xs btn-warning">
												<i class="fal fa-hourglass"></i>
											</a>`;
								case "4":
									return `<a href="${formAvp}" class="btn btn-icon btn-xs btn-success">
												<i class="fal fa-check"></i>
											</a>`;
								case "5":
									return `<div class="dropdown">
												<a href="#" class="btn btn-danger btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fa fa-exclamation"></i>
												</a>
												<div class="dropdown-menu">
													<a class="dropdown-item" style="text-decoration: none !important;" href="${formAvp}" target="_blank">
														Lihat Hasil Verifikasi
													</a>
													<a class="dropdown-item" style="text-decoration: none !important;" href="${formAvp}" target="_blank" data-toggle="tooltip" title="Perbaiki data dan laporan. Lalu ajukan verifikasi ulang.">
														Ajukan Ulang
													</a>
												</div>
											</div>`;
								default:
									return '';
							}
						} else {
							return "error";
						}
					}
				},
				{
					data: 'siapVerifSkl',
					render: function (data, type, row) {
						var noIjin = row.noIjin;
						var status = data;
						var avSklStatus = row.avSklStatus;
						var formAvSkl = "{{ route('2024.user.commitment.formavskl', ':noIjin') }}".replace(':noIjin', noIjin);

						if (status === "Belum Siap") {
							return "Belum Siap";
						} else if (status === "Siap") {
							switch (avSklStatus) {
								case "Tidak ada":
									return `<a href="${formAvSkl}" class="btn btn-icon btn-xs btn-warning">
												<i class="fal fa-upload"></i>
											</a>`;
								case "1":
									return `<a href="${formAvSkl}" class="btn btn-icon btn-xs btn-info">
												<i class="fal fa-clock"></i>
											</a>`;
								case "2":
								case "3":
									return `<a href="${formAvSkl}" class="btn btn-icon btn-xs btn-warning">
												<i class="fal fa-hourglass"></i>
											</a>`;
								case "4":
									return `<a href="${formAvSkl}" class="btn btn-icon btn-xs btn-success">
												<i class="fal fa-check"></i>
											</a>`;
								case "5":
									return `<div class="dropdown">
												<a href="#" class="btn btn-danger btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fa fa-exclamation"></i>
												</a>
												<div class="dropdown-menu">
													<a class="dropdown-item" style="text-decoration: none !important;" href="${formAvSkl}" target="_blank">
														Lihat Hasil Verifikasi
													</a>
													<a class="dropdown-item" style="text-decoration: none !important;" href="${formAvSkl}" target="_blank" data-toggle="tooltip" title="Perbaiki data dan laporan. Lalu ajukan verifikasi ulang.">
														Ajukan Ulang
													</a>
												</div>
											</div>`;
								default:
									return '';
							}
						} else {
							return "error";
						}
					}
				},
			],
			dom:
				"<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'>>" + // Move the select element to the left of the datatable buttons
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			buttons: [
				/*{
					extend:    'colvis',
					text:      'Column Visibility',
					titleAttr: 'Col visibility',
					className: 'mr-sm-3'
				},*/
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
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
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
						columns: [0, 1, 2, 3, 4]
					},
				}
			]
		});
	});
</script>
@endsection
