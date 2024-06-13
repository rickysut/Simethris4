@extends('layouts.admin')
@section ('styles')
<style>
td {
	vertical-align: middle !important;
}
</style>
@endsection
@section('content')
{{-- @include('partials.breadcrumb') --}}
@include('partials.subheader')
@can('commitment_access')
@include('partials.sysalert')
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
							@foreach ($commitments as $commitment)
							<tr>
								<td>
									<a href="{{ route('admin.task.commitment.show', $commitment->id) }}" title="Lihat Data Komitmen" target="_blank">
										{{$commitment->no_ijin}}
									</a>
								</td>
								<td class="text-center">{{$commitment->periodetahun}}</td>
								<td>{{$commitment->tgl_ijin}}</td>
								<td class="text-right">{{ number_format($commitment->volume_riph, 0, ',','.') }} ton</td>
								<td>
									<div class="row">
										<div class="col-3">
											Tanam
										</div>
										<div class="col-9 text-right">
											{{ number_format($commitment->luas_wajib_tanam, 2, ',','.') }} ha
										</div>
									</div>
									<div class="row">
										<div class="col-3">
											Produksi
										</div>
										<div class="col-9 text-right">
											{{ number_format($commitment->volume_produksi, 2, ',','.') }} ton
										</div>
									</div>
								</td>
								<td class="text-center">
									<a href="{{ route('admin.task.commitment.realisasi', $commitment->id) }}"
										class="btn btn-icon btn-xs btn-primary" data-toggle="tooltip"
										title data-original-title="Isi Laporan Realisasi Tanam dan Produksi">
										<i class="fal fa-edit"></i>
									</a>
								</td>
								{{-- tanam --}}
								<td class="text-center">
									{{-- @if ($pksFileCount == $pksCount) --}}
										@if (!empty($commitment->userDocs->sptjmtanam))
											{{-- Tanam --}}
											@if (!empty($commitment->userDocs->spvt) && !empty($commitment->userDocs->rta))
												@if(!$commitment->ajuTanam)
													@if (!$commitment->ajuskl || in_array(!$commitment->ajuskl->status, [1, 2, 3, 4]))
														<a href="{{ route('admin.task.commitment.avt', $commitment->id) }}"
															class="btn btn-xs btn-danger btn-icon" data-toggle="tooltip"
															title data-original-title="Ajukan Verifikasi Tanam">
															<i class="fal fa-upload"></i>
														</a>
													@endif
												@elseif($commitment->ajuTanam->status === '1')
													<a href="{{route('admin.task.pengajuan.tanam.show', $commitment->id)}}"
														class="btn btn-xs btn-info btn-icon" data-toggle="tooltip"
														title data-original-title="Verifikasi tanam telah diajukan. Klik untuk Lihat data pengajuan.">
														<i class="fal fa-upload"></i>
													</a>
												@elseif($commitment->ajuTanam->status === '2' || $commitment->ajuTanam->status === '3')
													<a href="{{route('admin.task.pengajuan.tanam.show', $commitment->id)}}"
														class="btn btn-xs btn-warning btn-icon" data-toggle="tooltip"
														title data-original-title="Proses pemeriksaan berkas. Klik untuk Lihat data.">
														<i class="fal fa-clipboard-list-check"></i>
													</a>
												@elseif($commitment->ajuTanam->status === '4')
													<a href="{{route('admin.task.pengajuan.tanam.show', $commitment->id)}}" class="btn btn-xs btn-success btn-icon" data-toggle="tooltip"
													title data-original-title="Verifikasi Tanam selesai. Klik untuk Lihat hasil.">
														<i class="fal fa-check"></i>
													</a>
												@elseif($commitment->ajuTanam->status === '5')
													<div class="dropdown">
														<a href="#" class="btn btn-danger btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Perbaiki data dan laporan">
															<i class="fa fa-exclamation"></i>
														</a>
														<div class="dropdown-menu">
															<a class="dropdown-item" style="text-decoration: none !important;" href="{{route('admin.task.pengajuan.tanam.show', $commitment->id)}}" target="_blank">
																Lihat Hasil Verifikasi
															</a >
															<a class="dropdown-item" style="text-decoration: none !important;" href="{{ route('admin.task.commitment.avt', $commitment->id) }}" target="_blank" data-toggle="tooltip"
																title data-original-title="Perbaiki data dan laporan. Lalu ajukan verifikasi ulang.">
																Ajukan Ulang
															</a>
														</div>
													</div>
												@endif
											@else
												<span id="syaratTanam" data-toggle="modal" data-target="#syaratModal" title="Klik untuk melihat syarat pengajuan Keterangan Lunas.">
													<i class="fas fa-info-circle text-info"></i>
												</span>
											@endif
										@else
											<span id="syaratTanam" data-toggle="modal" data-target="#syaratModal" title="Klik untuk melihat syarat pengajuan verifikasi.">
												<i class="fas fa-info-circle text-info"></i>
											</span>
										@endif
									{{-- @endif --}}
								</td>
								{{-- produksi --}}
								<td class="text-center">
									{{-- @if ($pksFileCount == $pksCount) --}}
										@if (!empty($commitment->userDocs->sptjmproduksi))
											{{-- produksi --}}
											@if (!empty($commitment->userDocs->spvp) && !empty($commitment->userDocs->rpo))
												@if ($commitment->sumVolume >= $commitment->minThresholdProd)
													@if(!$commitment->ajuProduksi)
														@if (!$commitment->ajuskl || in_array(!$commitment->ajuskl->status, [1, 2, 3, 4]))
															<a href="{{ route('admin.task.commitment.avp', $commitment->id) }}"
																class="btn btn-xs btn-warning btn-icon" data-toggle="tooltip"
																title data-original-title="Ajukan Verifikasi Produksi">
																<i class="fal fa-upload"></i>
															</a>
														@endif
													@elseif($commitment->ajuProduksi->status === '1')
														<a href="{{route('admin.task.pengajuan.produksi.show', $commitment->id)}}"
															class="btn btn-xs btn-info btn-icon" data-toggle="tooltip"
															title data-original-title="Verifikasi produksi telah diajukan. Klik untuk Lihat data pengajuan.">
															<i class="fal fa-upload"></i>
														</a>
													@elseif($commitment->ajuProduksi->status === '2' || $commitment->ajuProduksi->status === '3')
														<a href="{{route('admin.task.pengajuan.produksi.show', $commitment->id)}}"
															class="btn btn-xs btn-info btn-icon" data-toggle="tooltip"
															title data-original-title="Proses pemeriksaan berkas. Klik untuk Lihat data pengajuan.">
															<i class="fal fa-upload"></i>
														</a>
													@elseif($commitment->ajuProduksi->status === '4')
														<a href="{{route('admin.task.pengajuan.produksi.show', $commitment->id)}}"
															class="btn btn-xs btn-success btn-icon" data-toggle="tooltip"
															title data-original-title="Verifikasi Produksi selesai. Klik untuk Lihat data pengajuan.">
															<i class="fal fa-check"></i>
														</a>
													@elseif($commitment->ajuProduksi->status === '5')
														<div class="dropdown">
															<a href="#" class="btn btn-danger btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
																<i class="fa fa-exclamation"></i>
															</a>
															<div class="dropdown-menu">
																<a class="dropdown-item" style="text-decoration: none !important;" href="{{route('admin.task.pengajuan.produksi.show', $commitment->id)}}" target="_blank">
																	Lihat Hasil Verifikasi
																</a >
																<a class="dropdown-item" style="text-decoration: none !important;" href="{{ route('admin.task.commitment.avp', $commitment->id) }}" target="_blank" data-toggle="tooltip"
																	title data-original-title="Perbaiki data dan laporan. Lalu ajukan verifikasi ulang.">
																	Ajukan Ulang
																</a>
															</div>
														</div>
													@endif
												@endif
											@else
												<span id="syaratProduksi" data-toggle="modal" data-target="#syaratModal" title="Klik untuk melihat syarat pengajuan Verifikasi Realisasi Komitmen Wajib Produksi.">
													<i class="fas fa-info-circle text-info"></i>
												</span>
											@endif
										@else
											<span id="syaratProduksi" data-toggle="modal" data-target="#syaratModal" title="Klik untuk melihat syarat pengajuan Verifikasi Realisasi Komitmen Wajib Produksi.">
												<i class="fas fa-info-circle text-info"></i>
											</span>
										@endif
									{{-- @endif --}}
								</td>
								<td class="text-center">
									{{-- @if ($pksFileCount == $pksCount) --}}
										@if (!empty($commitment->userDocs->sptjmproduksi))
											{{-- skl --}}
											@if ($commitment->ajuProduksi && $commitment->ajuProduksi->status === '4')
												@if(!$commitment->ajuSkl)
													<a href="{{ route('admin.task.commitment.avskl', $commitment->id) }}"
														class="btn btn-xs btn-warning btn-icon" data-toggle="tooltip"
														title data-original-title="Ajukan Penerbitan SKL">
														<i class="fal fa-upload"></i>
													</a>
												@elseif($commitment->ajuSkl->status === '1')
													<a href="{{route('admin.task.pengajuan.skl.show', $commitment->id)}}" class="btn btn-xs btn-info btn-icon" data-toggle="tooltip" title data-original-title="Penerbitan SKL sudah diajukan">
														<i class="fal fa-upload"></i>
													</a>
												@elseif($commitment->ajuSkl->status === '2')
													<a href="{{route('admin.task.pengajuan.skl.show', $commitment->id)}}" class="btn btn-xs btn-info btn-icon" data-toggle="tooltip" title data-original-title="Rekomendasi Penerbitan SKL">
														<i class="fal fa-search"></i>
													</a>
												@elseif($commitment->ajuSkl->status === '3')
													<a href="{{route('admin.task.pengajuan.skl.show', $commitment->id)}}" class="btn btn-xs btn-info btn-icon" data-toggle="tooltip" title data-original-title="SKL Disetujui untuk Diterbitkan">
														<i class="fal fa-thumbs-up"></i>
													</a>
												@elseif($commitment->ajuSkl->status === '4')
													<a href="{{route('admin.task.pengajuan.skl.show', $commitment->id)}}" class="btn btn-xs btn-info btn-icon" data-toggle="tooltip" title data-original-title="SKL sudah Terbit. Klik untuk melihat Ringkasan Verifikasi.">
														<i class="fal fa-award"></i>
													</a>
												@elseif($commitment->ajuSkl->status === '5')
													<div class="dropdown">
														<a href="#" class="btn btn-danger btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
															<i class="fa fa-exclamation"></i>
														</a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" style="text-decoration: none !important;" href="{{route('admin.task.pengajuan.skl.show', $commitment->id)}}" target="_blank">
																Lihat Hasil Verifikasi
															</a >
															<a class="dropdown-item" style="text-decoration: none !important;" href="{{ route('admin.task.commitment.avskl', $commitment->id) }}" target="_blank" data-toggle="tooltip"
																title data-original-title="Perbaiki data dan laporan. Lalu ajukan verifikasi ulang.">
																Ajukan Ulang
															</a>
														</div>
													</div>
												@endif
											@else
												<span id="syaratSkl" data-toggle="modal" data-target="#syaratModal" title="Klik untuk melihat syarat pengajuan Keterangan Lunas.">
													<i class="fas fa-info-circle text-info"></i>
												</span>
											@endif
										@else
											<span id="syaratSkl" data-toggle="modal" data-target="#syaratModal" title="Klik untuk melihat syarat pengajuan Keterangan Lunas.">
												<i class="fas fa-info-circle text-info"></i>
											</span>
										@endif
									{{-- @endif --}}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
{{-- <div class="modal fade" id="syaratModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-right" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Syarat Pengajuan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="syaratTanamContent" style="display: none;">
					Syarat Pengajuan Verifikasi Realisasi Komitmen Tanam
					<ol class=text-left>
						<li>Dokumen Surat Pertanggungjawaban Mutlak Realisasi Komitmen Tanam telah diunggah</li>
						<li>Dokumen Surat Pengajuan Verifikasi Realisasi Komitmen Tanam telah diunggah</li>
						<li>Form Realisasi Komitmen Tanam telah diunggah (RTA)</li>
						<li>Form SPH-SBS Tanam dari Petugas Data Kecamatan Setempat</li>
					</ol>
				</div>
				<div id="syaratProduksiContent" style="display: none;">
					Syarat Pengajuan Verifikasi Realisasi Komitmen Produksi
					<ol class=text-left>
						<li>Seluruh Syarat Dokumen Realisasi Komitmen Wajib Produksi telah diunggah</li>
						<li>Jumlah Realisasi Komitmen Produksi yang dilaporkan minimum 100% dari Komitmen Produksi</li>
					</ol>
				</div>
				<div id="syaratSklContent" style="display: none;">
					Syarat Pengajuan Surat Keterangan Lunas Realisasi Komitmen Tanam-Produksi
					<ol class=text-left>
						<li>Status Verifikasi Produksi telah dinyatakan SELESAI</li>
						<li>Seluruh Syarat Dokumen Realisasi Komitmen Wajib Produksi telah diunggah</li>
						<li>Seluruh Syarat Dokumen Realisasi Komitmen Wajib Tanam telah diunggah</li>
					</ol>
				</div>
				<div class="">
					<a href="{{ route('admin.task.commitment.realisasi', $commitment->id) }}" data-toggle="tooltip" title data-original-title="Unggah Dokumen" id="tabLink">
						Kunjungi halaman unggah dokumen >>
					</a>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div> --}}
<!-- End Page Content -->

@endcan
@endsection

@section('scripts')
@parent
<script>
</script>


<script>
	$(document).ready(function() {
		// Handler ketika tombol "Show" di klik
        $("#syaratTanam, #syaratProduksi, #syaratSkl").click(function () {
            // Ambil ID tombol yang diklik
            var buttonId = $(this).attr("id");

            // Sembunyikan semua konten modal
            $("#syaratTanamContent, #syaratProduksiContent, #syaratSklContent").hide();

            // Tampilkan konten sesuai dengan tombol yang diklik
            $("#" + buttonId + "Content").show();

            // Tampilkan modal
            $("#syaratModal").modal("show");
        });

		var table = $('#datatable').DataTable({
			responsive: true,
			lengthChange: false,
			dom:
				"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'<'select'>>>" + // Move the select element to the left of the datatable buttons
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

		// Get the unique values of the "Year" column
		var years = table.column(1).data().unique().sort();

		// Create the select element and add the options
		var select = $('<select>')
			.addClass('custom-select custom-select-sm col-3 mr-2')
			.on('change', function() {
				var year = $.fn.dataTable.util.escapeRegex($(this).val());
				table.column(1).search(year ? '^' + year + '$' : '', true, false).draw();
			});

		$('<option>').val('').text('Semua Tahun').appendTo(select);
		$.each(years, function(i, year) {
			$('<option>').val(year).text(year).appendTo(select);
		});

		// Add the select element before the first datatable button
		$('.dt-buttons').before(select);
	});
</script>
@endsection
