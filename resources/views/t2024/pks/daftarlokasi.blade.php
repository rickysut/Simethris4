@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('pks_create')
	@php
		$npwp = str_replace(['.', '-'], '', $npwpCompany);
	@endphp
	<div class="row">
		<div class="col">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>
						Data <span class="fw-300"><i>Informasi</i></span>
					</h2>
					<div class="panel-toolbar">
						<a href="{{route('2024.user.commitment.realisasi', $ijin)}}" class="btn btn-info btn-xs">
							<i class="fal fa-undo mr-1"></i>Kembali
						</a>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content row d-flex">
						<div class="form-group col-md-4">
							<label for="">No. RIPH</label>
							<input disabled class="form-control form-control-sm fw-500 text-primary"
							placeholder="" aria-describedby="helpId"
							value="{{$commitment->no_ijin}}">
						</div>
						<div class="form-group col-md-4">
							<label for="">No. Perjanjian</label>
							<input disabled class="form-control form-control-sm fw-500 text-primary"
							placeholder="" aria-describedby="helpId"
							value="{{$pks->no_perjanjian}}">
						</div>
						<div class="form-group col-md-4">
							<label for="">Kelompoktani</label>
							<input disabled class="form-control form-control-sm fw-500 text-primary"
							placeholder="" aria-describedby="helpId"
							value="{{$pks->nama_poktan}}">
						</div>
					</div>
				</div>
			</div>
			<div class="panel" id="panel-2">
				<div class="panel-hdr">
					<h2>
						Daftar <span class="fw-300"><i>Realisasi Lokasi dan Pelaksana</i></span>
					</h2>
					<div class="panel-toolbar">
						@include('t2024.partials.globaltoolbar')
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<!-- datatable start -->
						<table id="tblLokasi" class="table table-bordered table-hover table-sm table-striped w-100">
							<thead class="thead-themed">
								<th>Kode Lokasi</th>
								<th>Pelaksana</th>
								<th>Realisasi Tanam (m2)</th>
								<th>Tanggal</th>
								<th>Realisasi Panen</th>
								<th>Tanggal</th>
								<th>Tindakan</th>
							</thead>
							<tbody>

							</tbody>
							<tfoot class="thead-themed">
								<tr>
									<th class="text-right" colspan="7">TOTAL REALISASI</th>
								</tr>
								<tr>
									<th class="text-right" colspan="5">Realisasi Luas Tanam: </th>
									<th class="text-right" id="totalRealisasiLuas" colspan="2"> ha</th>
								</tr>
								<tr>
									<th class="text-right" colspan="5">Realisasi Volume Panen</th>
									<th class="text-right" id="totalRealisasiProduksi" colspan="2"> ton</th>
								</tr>
							</tfoot>
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
		$(document).ready(function() {
			var noIjin = '{{$commitment->no_ijin}}';
			var formattedNoIjin = noIjin.replace(/[\/.]/g, '');
			var poktanId = '{{$pks->kode_poktan}}';
			var ijin = '{{$ijin}}';

			$('#tblLokasi').dataTable(
			{
				responsive: true,
				pageLength:10,
				lengthChange: true,
				paging: true,
				ordering: true,
				processing: true,
				serverSide: true,
				order: [[0, 'asc']],
				dom:
					"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
					"<'row'<'col-sm-12'tr>>" +
					"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

				ajax: {
					url: "{{ route('2024.datafeeder.getLokasiByPks', [':noIjin', ':poktanId']) }}".replace(':noIjin', formattedNoIjin).replace(':poktanId', poktanId),
					type: "GET",
					dataFilter: function(data){
						var json = JSON.parse(data);
						// Update the span with id=totalRealisasiProduksi
						$('#totalRealisasiProduksi').text(json.totalRealisasiProduksi + ' ton');
						var luasInHectares = json.totalRealisasiLuas; // Convert square meters to hectares
						var formattedLuas = luasInHectares.toLocaleString('id-ID', { maximumFractionDigits: 4 });

						// Update totalRealisasiLuas span
						$('#totalRealisasiLuas').text(formattedLuas + ' m2');
						return data;
					}
				},

				columns: [
					{
						data: 'kode_spatial',
						render: function (data, type, row) {
							var origin = row.origin;
							if (row.origin === 'local'){
								return data + `<sup class="badge badge-xs badge-danger ml-2">Tambahan</sup> `
							}else{
								return data;
							}
						}
					},
					{
						data: 'ktp_petani',
						name: 'nama_petani',
						render: function (data, type, row) {
							return row.spatial_petani + ' / ' + data;
						}
					},
					{ data: 'luas_tanam'},
					{ data: 'tgl_tanam' },

					{ data: 'volume_panen'},
					{ data: 'tgl_panen' },
					{
						data: 'tcode',
						render: function(data, type, row) {
							// Base edit button
							let buttons = `
								<a href="{{ route('2024.user.commitment.addrealisasi', ['noIjin' => ':ijin', 'spatial' => ':spatial']) }}" title="Isi/ubah data realisasi" class="btn btn-outline-primary btn-icon btn-xs">
									<i class="fa fa-edit"></i>
								</a>
							`.replace(':ijin', ijin).replace(':spatial', data);

							// Add delete button if origin is 'local'
							if (row.origin === 'local') {
								buttons += `
									<form action="{{ route('2024.user.commitment.deleteOriginLocalRealisasi', ['spatial' => ':spatial']) }}" method="POST" style="display:inline;">
										@csrf
										@method('DELETE')
										<button title="hapus data" class="btn btn-danger btn-icon btn-xs" type="button" onclick="confirmDelete(this)">
											<i class="fal fa-trash"></i>
										</button>
									</form>
								`.replace(':spatial', data);
							}
							return buttons;
						}
					}

				],
				columnDefs: [
					{
						targets: [6],
						className: 'text-center'
					},
					{
						targets: [2, 4],
						className: 'text-right'
					},
					{
						orderable: false,
						targets: [2, 3, 4, 5, 6]
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
		function confirmDelete(button) {
			// Show confirmation dialog
			if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
				// Find the form related to this button
				let form = button.closest('form');
				// Submit the form
				form.submit();
			}
		}
	</script>
@endsection

{{-- {{ route('admin.task.commitments.pksmitra', $commitment->id) }} --}}
