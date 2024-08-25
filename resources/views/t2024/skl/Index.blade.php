@extends('t2024.layouts.admin')
@section('styles')
<style>
	.display-5{
		font-size: 1.8rem;
		font-weight: 300;
		line-height: 1.25;
	}
</style>
@endsection
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
{{-- @can('spatial_data_access') --}}
	<section class="mb-3">
		<div class="row">
			<div class="col">
				<div class="panel" id="panel-1">
					<div class="panel-container show">
						<div class="panel-content">
							<!-- datatable start -->
							<table id="tblSkl" class="table table-bordered table-hover table-sm table-striped w-100">
								<thead class="thead-themed">
									<th>Perusahaan</th>
									<th>No SKL</th>
									<th>No. RIPH</th>
									<th>Periode</th>
									<th>Terbit</th>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
{{-- @endcan --}}

@endsection

<!-- start script for this page -->
@section('scripts')
@parent

<script>
	$(document).ready(function(){
		$('#tblSkl').dataTable(
		{
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
				url: "{{ route('2024.datafeeder.getAllSkls')}}",
				type: "GET",
				dataSrc: "data",
			},
			columns:[
				{data: 'datauser.company_name'},
				{data: 'no_skl'},
				{data: 'no_ijin'},
				{data: 'periodetahun'},
				{data: 'published_date'},
			],
			rowCallback: function(row, data) {
				$(row).css('cursor', 'pointer');
				$(row).on('click', function() {
					window.open(data.url, '_blank');
				});
			},
			buttons: [
				{
					extend: 'pdfHtml5',
					text: '<i class="fa fa-file-pdf"></i>',
					title: 'Daftar SKL Terbit',
					titleAttr: 'Generate PDF',
					className: 'btn-outline-danger btn-sm btn-icon mr-1'
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					title: 'Daftar SKL Terbit',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					title: 'Daftar SKL Terbit',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				}
			]
		});
	});
</script>
@endsection

{{-- {{ route('admin.task.commitments.pksmitra', $commitment->id) }} --}}
