@extends('t2024.layouts.admin')
@section('styles')
<style>
	#tableOverlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(255, 255, 255, 0.8);
		z-index: 9999;
		display: none;
		align-items: center;
		justify-content: center;
		font-size: 1.5em;
		color: #333;
	}

</style>
@endsection
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@can('administrator_access')
	@include('t2024.partials.sysalert')
		<div class="row">
			<div class="col-12">

				<div class="panel" id="panel-1">
					<div class="panel-container show">
						<div class="panel-content">
							<table id="reqSklTable" class="table table-sm table-bordered table-striped w-100">
								<thead>
									<tr>
										<th hidden></th>
										<th>Periode</th>
										<th>Pelaku Usaha</th>
										<th>No. RIPH</th>
										<th>Diajukan pada</th>
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
			$('#reqSklTable').dataTable({
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
					url: "{{ route('2024.datafeeder.getRequestSkl') }}",
					type: "GET",
					dataSrc: "data"
				},
				columns: [
					{data: 'id', visible: false, searchable: false},
					{data: 'periode'},
					{data: 'perusahaan'},
					{data: 'no_ijin'},
					{
						data: 'created_at',
						render: function(data, type, row) {
							if (data) {
								var date = new Date(data);
								var options = { year: 'numeric', month: 'long', day: 'numeric' };
								return new Intl.DateTimeFormat('id-ID', options).format(date);
							}
							return ''; // Jika data kosong, kembalikan string kosong
						}
					},
					{
						data: 'ijin',
						render: function(data, type, row) {
							var status = row.status;
							var tcode = row.tcode;
							var actionBtn = '';
							var checkStat = '{{ route("2024.admin.permohonan.skl.check", [":noIjin", ":tcode"]) }}'.replace(':noIjin', data).replace(':tcode', tcode);
							var postStat = '{{ route("2024.admin.permohonan.skl.draftSkl", [":noIjin", ":tcode"]) }}'.replace(':noIjin', data).replace(':tcode', tcode);

							var uploadSkl = '{{ route("2024.admin.skl.uploadSkl", [":noIjin", ":tcode"]) }}'.replace(':noIjin', data).replace(':tcode', tcode);
							var publishSkl = '{{ route("2024.admin.skl.publishSkl", [":noIjin", ":tcode"]) }}'.replace(':noIjin', data).replace(':tcode', tcode);


							var draftUrl = row.draft_url;
							var sklUrl = row.skl_url;
							if (status === "0") {
								actionBtn = `
									<a href="${checkStat}" class="btn btn-icon btn-xs btn-warning waves-effect waves-themed">
										<i class="fal fa-exclamation-circle"></i>
									</a>
								`;
							} else if (status === "2") {
								actionBtn = `
									<a class="btn btn-icon btn-xs btn-default waves-effect waves-themed" title="Dalam proses persetujuan Pimpinan">
										<i class="fal fa-hourglass-start text-danger"></i>
									</a>
								`;
							} else if (status === "3") {
								//jika belum ada no_skl
								if (row.no_skl === null || row.no_skl === undefined) {
									actionBtn = `
										<button type="button" class="btn btn-warning btn-icon btn-xs waves-effect waves-themed" data-toggle="modal" data-target="#inputSklNum${row.id}">
											<i class="fal fa-file-invoice"></i>
										</button>
										<div class="modal fade" id="inputSklNum${row.id}" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title fw-500">Nomor SKL</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true"><i class="fal fa-times"></i></span>
														</button>
													</div>
													<form id="noSkl${row.id}" action="${postStat}" method="post" enctype="multipart/form-data">
														@csrf
														<div class="modal-body">
															<div class="form-group">
																<label for="no_skl">Nomor SKL</label>
																<input type="text" name="no_skl" id="no_skl${row.id}" class="form-control required" placeholder="Nomor SKL" aria-describedby="helpId" required>
																<small id="helpId" class="text-muted">Nomor SKL</small>
															</div>

														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary btn-sm waves-effect waves-themed" data-dismiss="modal">Batal</button>
															<button class="btn btn-primary btn-sm waves-effect waves-themed" type="submit">
																<span id="loadingIndicator" class="spinner-border spinner-border-sm" style="display:none; role="status" aria-hidden="true"></span>
																<span id="submitBtnText">Buat Draft</span>
															</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									`;
								//jika ada no_skl
								} else {
									// jika file skl tidak ada
									if(row.skl_url === null || row.skl_url === undefined) {
										actionBtn = `
											<a href="${row.draft_url}" target="blank" class="btn btn-info btn-icon btn-xs waves-effect waves-themed" title="Lihat Draft SKL">
												<i class="fal fa-print"></i>
											</a>
											<button type="button" class="btn btn-primary btn-icon btn-xs waves-effect waves-themed" data-toggle="modal" data-target="#inputFile${row.id}">
												<i class="fal fa-upload"></i>
											</button>
											<div class="modal fade" id="inputFile${row.id}" tabindex="-1" role="dialog" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title fw-500">Unggah SKL</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true"><i class="fal fa-times"></i></span>
															</button>
														</div>
														<form id="sklUpload${row.id}" action="${uploadSkl}" method="post" enctype="multipart/form-data">
															@csrf
															<div class="modal-body">
																<div class="form-group">
																	<label for="skl_url">Unggah</label>
																	<input type="file" name="skl_url" id="skl_url${row.id}" class="form-control required" placeholder="Unggah SKL" aria-describedby="helpId" required>
																	<small id="helpId" class="text-muted">Unggah salinan berkas SKL yang telah di tandatangani oleh Pimpinan.</small>
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Batal</button>
																<button type="submit" class="btn btn-primary btn-sm waves-effect waves-themed">Unggah</button>
															</div>
														</form>
													</div>
												</div>
											</div>
										`;
									// jika file skl sudah ada
									} else {
										if (row.published_at === null || row.published_at === undefined) {
											actionBtn = `
												<form id="publishSkl${row.id}" action="${publishSkl}" method="post" enctype="multipart/form-data">
													@csrf
													<button type="submit" class="btn btn-icon btn-xs btn-default waves-effect waves-themed" title="Terbitkan SKL">
														<i class="fal fa-badge text-danger"></i>
													</button>
												</form>
											`;
										} else {
											actionBtn = '';
										}
									}
								}

								$(document).on('submit', `#noSkl${row.id}`, function(e) {
									e.preventDefault();
									$('#loadingIndicator').show();
									$('#submitBtnText').text('di Proses');
									console.log('Form submitted for row ID:', row.id);
									var form = $(this);
									$.ajax({
										url: form.attr('action'),
										method: form.attr('method'),
										data: form.serialize(),
										success: function(response) {
											$(`#inputSklNum${row.id}`).modal('hide');
											$('#reqSklTable').DataTable().ajax.reload(null, false);
											Swal.fire({
												toast: true,
												position: 'center',
												icon: 'success',
												title: 'Data berhasil di-update dan tabel di-reload.',
												showConfirmButton: true,
												timer: 3000
											});
										},
										error: function(xhr) {
											console.log('Error:', xhr.responseText);
											Swal.fire({
												toast: true,
												position: 'top-end',
												icon: 'error',
												title: 'Terjadi kesalahan, data gagal di-update.',
												showConfirmButton: false,
												timer: 3000
											});
										},
										complete: function() {
											$('#loadingIndicator').hide();
											$('#submitBtnText').text('Buat Draft');
										}
									});
								});
							} else if (status === "5"){
								//jalankan route post returnVerif
								actionBtn = `
									<a class="btn btn-icon btn-xs btn-default waves-effect waves-themed" title="Ditolak">
										<i class="fa fa-ban text-danger"></i>
									</a>
								`;
							} else {
								actionBtn = '';
							}
							return actionBtn;
						}
					}

				],
				order: [[0, 'asc']],
				columnDefs: [
					{
						targets: [4], // Indeks untuk kolom tanggal
						render: function (data, type, row) {
							if (type === 'display' || type === 'filter') {
								return data ? moment(data).format('DD-MM-YYYY') : '-';
							}
							return data;
						}
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
				],

			});
		});
	</script>
@endsection
