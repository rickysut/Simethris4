@extends('t2024.layouts.admin')
@section('styles')

@endsection
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@can('online_access')
	@include('t2024.partials.sysalert')
		<div class="row" id="contentToPrint">
			<div class="col-lg-3">
				@include('t2024.verifikasi.tanam.infocard')
			</div>
			<div class="col-lg-9">
				<div id="panel-1" class="panel">
					<div class="panel-container show">
					@include('t2024.verifikasi.tanam.checkmenu')
					</div>
					<div class="panel-container show">
						<div class="panel-tag fade show">
							<div class="d-flex align-items-center">
								<i class="fal fa-info-circle mr-1"></i>
								<div class="flex-1">
									<small class="text-danger">AWAL PEMERIKSAAN. Jika selesai pemeriksaan bagian ini, klik tombol di bawah untuk menandai progress pemeriksaan. Set status ke "2".</small>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<table class="table table-striped table-bordered table-sm w-100" id="attchCheck">
								<thead class="thead-themed text-uppercase text-muted">
									<tr>
										<th style="width: 35%">Form</th>
										<th style="width: 30%" class="text-center">Tindakan</th>
										<th style="width: 35%">Hasil Periksa</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($docs as $doc)
										<tr>
											<td>{{$doc->form}}</td>
											<td class="text-center">
												@if ($doc->file_url)
													<a href="{{$doc->file_url}}" target="_blank">
														<i class="fas fa-download mr-1"></i>
														Lihat Dokumen
													</a>
												@else
													@if ($doc->form === 'Logbook')
														<a href="">
															<i class="fas fa-cogs mr-1"></i>
															Generate Logbook
														</a>
													@else
														<span> - </span>
													@endif
												@endif
											</td>
											<td>
												<form action="{{ route("2024.verifikator.tanam.saveCheckBerkas", $ijin) }}" method="post">
													@csrf
													<input type="hidden" value="{{$doc->id}}" name="docId" id="docId">
													<select class="form-control form-control-sm saveCheckBerkas" name="status" id="status">
														<option value="">- Pilih status -</option>
														<option value="1" {{ $doc->status == '1' ? 'selected' : '' }}>Ada/Sesuai</option>
														<option value="0" {{ $doc->status == '0' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
													</select>
												</form>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer d-flex justify-content-between align-items-center">
						<div class="help-block col-md-7">
						</div>
						<div class="col-md text-right">
							<form action="{{ route("2024.verifikator.tanam.markStatus", [$ijin, $tcode, "2"]) }}" method="post" enctype="multipart/form-data">
								@csrf
								<button type="submit" class="btn btn-success btn-sm" id="btnStatus-2" data-status="2">
									<i class="fal fa-save"></i> Tandai progress di sini
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endcan
@endsection

@section('scripts')
	<script src="{{ asset('js/miscellaneous/lightgallery/lightgallery.bundle.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-editor/2.3.1/dataTables.editor.js" integrity="sha512-BKsIfYRuTSaLQncTO/3CUtWr6zko7hbmxWYcBhJ7YqVB1zPIcG0S7hCNf3PLcQds22RlBaVHnKkKLxjSmn9hZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	@parent

	<script>

		$(document).ready(function() {
			$('#attchCheck').dataTable({
				responsive: true,
				lengthChange: false,
				ordering: false,
				searching: false,
			});
			var noIjin = '{{$ijin}}';
			var tcode = '{{$verifikasi->tcode}}';
			var formattedNoIjin = noIjin.replace(/[\/.]/g, '');

			$(document).on('change', '.saveCheckBerkas', function() {
				var form = $(this).closest('form');
				var formData = form.serialize();
				$.ajax({
					url: form.attr('action'),
					type: 'POST',
					data: formData,
					success: function(response) {
						console.log('sukses');
					},
					error: function(xhr, status, error) {
						alert('An error occurred while updating: ' + xhr.responseText);
					}
				});
			});

			function formatDate(dateString) {
				if (!dateString) return '';
				var date = new Date(dateString);
				var options = { year: 'numeric', month: 'long', day: 'numeric' };
				return new Intl.DateTimeFormat('id-ID', options).format(date);
			}
			$.ajax({
				url: "{{ route('2024.datafeeder.getVerifTanamByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
				type: "GET",
				success: function(response) {
					$('#companytitle').text(response.data.perusahaan);
					$('#no_ijin').text(response.data.no_ijin);
					$('#tgl_ijin').text(response.data.tgl_ijin);
					$('#tgl_akhir').text(response.data.tgl_akhir);
					$('#created_at').text(response.data.created_at);
					$('#jml_anggota').text(response.data.countAnggota + ' orang');

					var kemitraan = response.data.countPks + ' berkas / ' + response.data.countPoktan + ' kelompok';
					if (response.data.countPoktan / response.data.countPks == 1){
						$('#countPks').html('<span class="text-success">' + kemitraan + '</span>');
					}else{
						$('#countPks').html('<span class="text-danger">' + kemitraan + '</span>');
					}

					var realisasitanam = response.data.sumLuasTanam + ' / ' + response.data.sumLuas + ' ha';
					if (response.data.sumLuasTanam / response.data.sumLuas < 1){
						$('#luas_tanam').html('<span class="text-danger">' + realisasitanam + '</span>');
					}else{
						$('#luas_tanam').html('<span class="text-success">' + realisasitanam + '</span>');
					}

					var lokasiTanam = response.data.countTanam + ' / ' + response.data.countSpatial + ' titik';
					if (response.data.countTanam / response.data.countSpatial < 1){
						$('#jml_titik').html('<span class="text-danger">' + lokasiTanam + '</span>');
					}else{
						$('#jml_titik').html('<span class="text-success">' + lokasiTanam + '</span>');
					}

					var logoUrl = response.data.logo;
					$('#companyLogo').css('background-image', 'url(' + logoUrl + ')');
				}
			});
		});
	</script>
@endsection
