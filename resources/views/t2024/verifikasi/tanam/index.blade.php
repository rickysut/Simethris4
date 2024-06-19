@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@can('online_access')
	@include('t2024.partials.sysalert')
		<div class="row">
			<div class="col-12">
				<div class="panel" id="panel-1">
					<div class="panel-container show">
						<div class="panel-content">
							<table id="avtanamTable" class="table table-sm table-bordered table-striped w-100">
								<thead>
									<tr>
										<th>Periode</th>
										<th>Pelaku Usaha</th>
										<th>No. RIPH</th>
										<th>Diajukan pada</th>
										<th>Verifikator</th>
										<th>Status</th>
										<th>Tindakan</th>
									</tr>
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
		$(document).ready(function() {
		//initialize datatable dataPengajuan
			$('#avtanamTable').dataTable({
				responsive: true,
				lengthChange: false,
				ordering: true,
				processing: true,
				serverSide: true,
				dom:
					"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
					"<'row'<'col-sm-12'tr>>" +
					"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
				ajax: {
					url: "{{ route('2024.datafeeder.getRequestVerifTanam') }}",
					type: "GET",
					dataSrc: "data"
				},
				columns: [
					{data: 'periode'},
					{data: 'perusahaan'},
					{data: 'no_ijin'},
					{data: 'created_at'},
					{data: 'verifikator'},
					{
						data: 'status',
						render: function (data, type, row) {

							var status1 = data == 1 ? 'btn-warning' : 'btn-default';
							var status1Icon = data == 1 ? 'fa-exclamation-circle' : 'fa-check';

							var status2 = (data >= 2 && data <= 5) ? 'btn-success' : 'btn-default';
							var status2Icon = (data >= 2 && data <= 5) ? 'fa-check' : 'fa-hourglass';

							var status3 = (data >= 3 && data <= 5) ? 'btn-success' : 'btn-default';
							var status3Icon = (data >= 3 && data <= 5) ? 'fa-check' : 'fa-hourglass';

							var status4 = (data == 4) ? 'btn-success' : (data == 5) ? 'btn-danger' : 'btn-default';
							var status4Icon = (data == 4) ? 'fa-check' : (data == 5) ? 'fa-ban' : 'fa-hourglass';

							return `
								<div class="btn-group btn-group-toggle" role="group">
									<label class="btn ${status1} btn-xs" data-toggle="tooltip" data-original-title="Verifikasi diajukan">
										1 <i class="fa ${status1Icon}"></i>
									</label>
									<label class="btn ${status2} btn-xs" data-toggle="tooltip" data-original-title="Verifikasi dalam proses">
										2 <i class="fa ${status2Icon}"></i>
									</label>
									<label class="btn ${status3} btn-xs" data-toggle="tooltip" data-original-title="Verifikasi tahap akhir">
										3 <i class="fa ${status3Icon}"></i>
									</label>
									<label class="btn ${status4} btn-xs" data-toggle="tooltip" data-original-title="Verifikasi selesai">
										4 <i class="fa ${status4Icon}"></i>
									</label>
								</div>
							`;
						}
					},
					{
						data: 'ijin',
						render: function (data, type, row) {
							var noIjin = data;
							var status = row.status;
							var viewStat = '{{ route("2024.verifikator.tanam.result", ":noIjin") }}'.replace(':noIjin', noIjin);
							var checkStat = '{{ route("2024.verifikator.tanam.check", ":noIjin") }}'.replace(':noIjin', noIjin);
							if(status === '4'){
								return `
									<a href='`+ viewStat +`' data-toggle="tooltip" title="Lihat hasil" class="mr-1 btn btn-xs btn-icon btn-info">
										<i class="fal fa-file-search"></i>
									</a>
								`;
							}else{
								return `
									<a href='`+ checkStat +`' data-toggle="tooltip" title="Verifikasi" class="mr-1 btn btn-xs btn-icon btn-primary">
										<i class="fal fa-file-search"></i>
									</a>
								`;
							}
						}
					},
				],
				columnDefs: [
					{
						targets: [3, 4], // Indeks untuk kolom tanggal
						render: function (data, type, row) {
							if (type === 'display' || type === 'filter') {
								return data ? moment(data).format('DD-MM-YYYY') : '-';
							}
							return data;
						}
					},
					{
						targets: '_all',
						className: 'text-center'
					}
				],
				buttons: [
					{
						extend: 'pdfHtml5',
						text: '<i class="fa fa-file-pdf"></i>',
						titleAttr: 'Generate PDF',
						className: 'btn-outline-danger btn-sm btn-icon mr-1'
					},
					{
						extend: 'excelHtml5',
						text: '<i class="fa fa-file-excel"></i>',
						titleAttr: 'Generate Excel',
						className: 'btn-outline-success btn-sm btn-icon mr-1'
					},
					{
						extend: 'print',
						text: '<i class="fa fa-print"></i>',
						titleAttr: 'Print Table',
						className: 'btn-outline-primary btn-sm btn-icon mr-1'
					}
				]
			});
		});
	</script>
@endsection
