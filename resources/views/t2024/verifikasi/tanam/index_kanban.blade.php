@extends('t2024.layouts.admin')
@section('styles')
<style>
	.hoverpanel:hover {
		background: #f2f2f242;
	}
</style>
@endsection
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
										<th>No. RIPH</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="row d-flex justify-content-between align-items-center">
							<div class="col-md-3 col-sm-6">
								<input type="text" class="form-control bg-subtlelight pl-6" placeholder="Search">
							</div>
							<div class="col-md-3 col-sm-6">
								<select class="custom-select form-control" id="selectStatus">
									<option selected>status...</option>
									<option value="1">Semua</option>
									<option value="2">Dalam Verifikasi</option>
									<option value="3">Selesai</option>
								</select>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row justify-content-start align-items-start">
							<div class="col-lg-4 col-md-6 col-sm-12">
							</div>
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
					"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'<'toolbar'>B>>" +
					"<'row'<'col-sm-12'tr>>" +
					"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
				ajax: {
					url: "{{ route('2024.datafeeder.getRequestVerifTanam') }}",
					type: "GET",
					dataSrc: "data",
					data: function (d) {
						d.status = $('#selectStatus').val(); // Send status filter value
					}
				},
				columns: [
					{
						data: 'ijin',
						render: function(data, type, row){
							return getPanelHtml(row);
						}
					}
				],
				columnDefs: [
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
						className: 'ml-2 btn-outline-danger btn-sm btn-icon mr-1'
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
				],
				initComplete: function() {
					$("div.toolbar").html(`
						<select class="custom-select form-control" id="selectStatus">
							<option selected>status...</option>
							<option value="1">Semua</option>
							<option value="2">Dalam Verifikasi</option>
							<option value="3">Selesai</option>
						</select>
					`);
				}
			});
			function getProgressBarHtml(status) {
	switch (status) {
		case '1': return createProgressBar("Tahap 1", 5, "text-success", "bg-success-50");
		case '2': return createProgressBar("Tahap 2", 10, "text-success", "bg-success-100");
		case '3': return createProgressBar("Tahap 3", 15, "text-success", "bg-success-200");
		case '4': return createProgressBar("Tahap 4", 25, "text-success", "bg-success-300");
		case '5': return createProgressBar("Tahap 5", 75, "text-success", "bg-success-400");
		case '6': return createProgressBar("Tahap Akhir", 100, "text-success", "bg-success-500");
		case '7': return createProgressBar("Tahap Akhir", 100, "text-danger", "bg-danger-500");
		default: return "Status tidak diketahui";
	}
}

// Fungsi untuk menghasilkan HTML panel berdasarkan data baris
function getPanelHtml(row) {
	var company = row.perusahaan;
	var no_ijin = row.no_ijin;
	var status = row.status;
	var tcode = row.tcode;

	var viewStat = '{{ route("2024.verifikator.tanam.result", [":noIjin", ":tcode"]) }}'.replace(':noIjin', row.ijin).replace(':tcode', tcode);
	var checkStat = '{{ route("2024.verifikator.tanam.check", [":noIjin", ":tcode"]) }}'.replace(':noIjin', row.ijin).replace(':tcode', tcode);

	var buttonHtml = (status > 5) ?
		`<a href='${viewStat}' data-toggle="tooltip" title="Lihat hasil" class="mr-1 btn btn-xs btn-icon btn-info">
			<i class="fal fa-file-search"></i>
		</a>` :
		`<a href='${checkStat}' data-toggle="tooltip" title="Verifikasi" class="mr-1 btn btn-xs btn-icon btn-primary">
			<i class="fal fa-file-search"></i>
		</a>`;

	var progressBarHtml = getProgressBarHtml(status);

	return `
		<div class="panel" id="panel-${no_ijin}">
			<div class="panel-hdr">
				<h2>${company}</h2>
				${progressBarHtml}
			</div>
			<div class="panel-container show">
				<div class="panel-content">
					<div class="d-flex flex-row px-3 pt-3 pb-2">
						<span class="status status-danger">
							@if (!empty(Auth::user()->data_user->avatar))
								<img class="profile-image rounded-circle d-inline-block" src="{{ asset('storage/' . Auth::user()->data_user->avatar) }}" alt="img">
							@else
								<img class="profile-image rounded-circle d-inline-block" src="{{ asset('/img/avatars/farmer.png') }}" alt="img">
							@endif
						</span>
						<div class="ml-3">
							<a href="javascript:void(0);" title="${no_ijin}" class="d-block fw-700 text-dark">${no_ijin}</a>
							<div class="d-flex mt-3 flex-wrap">
								${buttonHtml}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	`;
}

// Fungsi untuk membuat progress bar
function createProgressBar(label, widthPercentage, textColorClass, backgroundClass, showLink = false, link = '') {
	let progressContent = `
		<div class="progress progress-sm mb-3">
			<div class="progress-bar ${backgroundClass}" role="progressbar" style="width: ${widthPercentage}%;"
				aria-valuenow="${widthPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
		</div>`;

	if (showLink) {
		return `
			<div class="d-flex ${textColorClass}">
				${label}
				<span class="d-inline-block ml-auto mb-1">
					<a href="${link}" class="btn btn-icon btn-xs ${textColorClass === 'text-success' ? 'btn-success' : 'btn-danger'}">
						<i class="fal ${textColorClass === 'text-success' ? 'fa-check' : 'fa-ban'}"></i>
					</a>
				</span>
			</div>
			${progressContent}`;
	} else {
		return `
			<div class="d-flex ${textColorClass}">
				${label}
				<span class="d-inline-block ml-auto mb-1">
					<i class='fas fa-hourglass ${textColorClass}'></i>
				</span>
			</div>
			${progressContent}`;
	}
}

			// Tambahkan event listener untuk select option
			$(document).on('change', '#selectStatus', function() {
				$('#avtanamTable').DataTable().ajax.reload();
			});


		});
	</script>
@endsection
