@extends('layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('spatial_data_access')
	<div class="row">
		<div class="col">
			<div class="panel" id="panel-1">
				<div class="card-header">
					<div class="panel-content">
						<h4 class="text-muted">Pencarian Kelompok Tani</h4>
						<div class="row">
							<div class="form-group col-md-3">
								<label for="idProv">Provinsi</label>
								<select name="idProv" id="idProv" class="custom-select form-control" aria-describedby="helpProv">
								</select>
								<small id="helpProv" class="text-muted">saring berdasarkan provinsi</small>
							</div>
							<div class="form-group col-md-3">
								<label for="idKab">Kabupaten</label>
								<select name="idKab" id="idKab" class="custom-select form-control" aria-describedby="helpKab">
								</select>
								<small id="helpKab" class="text-muted">saring berdasarkan kabupaten/kota</small>
							</div>
							<div class="form-group col-md-3">
								<label for="idKec">Kecamatan</label>
								<select name="idKec" id="idKec" class="custom-select form-control" aria-describedby="helpKec">
								</select>
								<small id="helpKec" class="text-muted">saring data berdasarkan kecamatan</small>
							</div>
							<div class="form-group col-md-3">
								<label for="poktan">Kelompok Tani</label>
								<select name="poktan" id="poktan" class="custom-select form-control" aria-describedby="helpPoktan">
								</select>
								<small id="helpPoktan" class="text-muted">saring data berdasarkan nama kelompok</small>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-12">
								<label class="form-label">Cari data</label>
								<div class="input-group bg-white shadow-inset-2">
									<div class="input-group-prepend">
										<span class="input-group-text bg-transparent border-right-0 py-1 px-3 text-success">
											<i class="fal fa-search"></i>
										</span>
									</div>
									<input type="text" name="searchValue" id="searchValue" aria-describedby="searchValue" class="form-control border-left-0 bg-transparent pl-0" placeholder="kata kunci...">
									<div class="input-group-append">
										<button class="btn btn-default waves-effect waves-themed" type="button">Temukan</button>
									</div>
								</div>
								<small for="searchValue" class="text-muted">Temukan data berdasarkan kata kunci</small>
							</div>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<div></div>
							<div class="ml-auto">
								<button id="printPoktan" class="btn btn-primary">
									<span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    								<span id="buttonText">Cetak Daftar</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<!-- datatable start -->
						<table id="tblAnggota" class="table table-bordered table-hover table-sm table-striped w-100">
							<thead class="thead-themed">
								<th>Nama Petani</th>
								<th>NIK</th>
								<th>Kontak</th>
								<th>Lahan</th>
								<th>Provinsi</th>
								<th>Kabupaten</th>
								<th>Kecamatan</th>
								<th>Desa</th>
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

		var provinsiSelect = $('#idProv');
		var kabupatenSelect = $('#idKab');
		var kecamatanSelect = $('#idKec');
		var desaSelect = $('#idDesa');

		$('#tblAnggota').dataTable(
		{
			responsive: true,
			lengthChange: true,
			lengthMenu: [10, 25, 50, 100],
			paging: true,
			ordering: true,
			processing: true,
			serverSide: true,
			language: {
				"processing": "Sedang memproses...",
				"lengthMenu": "Tampilkan _MENU_ entri",
				"zeroRecords": "Tidak ditemukan data yang sesuai",
				"info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
				"infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
				"infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
				"search": "Cari:",
				"paginate": {
					"first": "Pertama",
					"last": "Terakhir",
					"next": "Berikutnya",
					"previous": "Sebelumnya"
				},
				"emptyTable": "Tidak ada data di dalam tabel",
				"loadingRecords": "Sedang memuat...",
				"thousands": ".",
				"decimal": ",",
				"aria": {
					"sortAscending": ": aktifkan untuk mengurutkan kolom naik",
					"sortDescending": ": aktifkan untuk mengurutkan kolom turun"
				}
			},
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			ajax: {
				url: "{{ route('2024.datafeeder.getAllCpcl')}}",
				type: "GET",
				dataSrc: "data"
			},
			columns:[
				{data: 'nama_petani'},
				{data: 'ktp_petani'},
				{data: 'kontak'},
				{
					data: 'jumlah_spatial',
					render: function (data, type, row) {
						return `<ul class='list-group'>
							<li class='list-group-items d-flex justify-content-between align-item-center'>
								<span>Lahan</span>
								<span>`+ data +` titik</span>
							</li>
							<li class='list-group-items d-flex justify-content-between align-item-center'>
								<span>Luas</span>
								<span>`+ row.total_luas / 10000 +` ha</span>
							</li>
							</ul>
							`;
					}
				},
				{data: 'nama_provinsi'},
				{data: 'nama_kabupaten'},
				{data: 'nama_kecamatan'},
				{data: 'nama_desa'},
				{
					data: 'ktp_petani',
					render: function (data, type, row) {
						return `<a type="button" href="{{ route('2024.cpcl.anggota.show', ':nik') }}" class="btn btn-icon btn-default btn-xs"><i class="fal fa-pencil"></i></a>`
							.replace(':nik', data);
					}
				}
			],
			buttons: [
				{
					extend: 'pdfHtml5',
					text: '<i class="fa fa-file-pdf"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Generate PDF',
					className: 'btn-outline-danger btn-sm btn-icon mr-1',
					exportOptions:
					{
						columns: [0,1,2,3,4,5],
					}
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1',
					exportOptions:
					{
						columns: [0,1,2,3,4,5]
					}
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1',
					exportOptions:
					{
						columns: [0,1,2,3,4,5]
					}
				}
			],
		});
	});
</script>
@endsection
