@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@can('online_access')
	@include('t2024.partials.sysalert')
		<div class="row" id="contentToPrint">
			<div class="col-lg-3">
				<div class="card mb-g">
					<div class="card-header">
						<div class="d-flex flex-row pt-2  border-top-0 border-left-0 border-right-0">
							<div class="d-inline-block align-middle mr-3">
								<span class="profile-image rounded-circle d-block" style="background-image:url(); background-size: cover;" id="companyLogo"></span>
							</div>
							<h3 class="mb-0 flex-1 text-dark fw-500">
								<span id="companytitle"></span>
								<small class="m-0 l-h-n font-weight-bold"></small>
							</h3>
							<span class="">

							</span>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="form-group col-12">
								<label class="text-muted" for="no_ijin">Nomor RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="no_ijin" name="no_ijin"></span>
								</div>
							</div>

							<div class="form-group col-12">
								<label class="text-muted" for="tgl_ijin">Tanggal Ijin RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="tgl_ijin" name="tgl_ijin"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="tgl_akhir">Tanggal Akhir RIPH</label>
								<div class="input-group">
									<span class="font-weight-bold" id="tgl_akhir" name="tgl_akhir"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="created_at">Tanggal Pengajuan Verifikasi</label>
								<div class="input-group">
									<span class="font-weight-bold" id="created_at" name="created_at"></span>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12"><h5 class="font-weight-bold">Ringkasan</h5></div>
							<div class="form-group col-12">
								<label class="text-muted" for="countPks">Kemitraan</label>
								<div class="input-group">
									<span class="font-weight-bold" id="countPks" name="countPks"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="jml_anggota">Jumlah Anggota</label>
								<div class="input-group">
									<span class="font-weight-bold" id="jml_anggota" name="jml_anggota"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="luas_tanam">Realisasi Tanam</label>
								<div class="input-group">
									<span class="font-weight-bold" id="luas_tanam" name="luas_tanam"></span>
								</div>
							</div>
							<div class="form-group col-12">
								<label class="text-muted" for="jml_titik">Lokasi Tanam</label>
								<div class="input-group">
									<span class="font-weight-bold" id="jml_titik" name="jml_titik"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-9">

				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#panel-1" role="tab" aria-selected="true">Kelengkapan Berkas</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#panel-2" role="tab" aria-selected="true">Perjanjian Kemitraan</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#panel-3" role="tab" aria-selected="true">Monitoring Timeline Realisasi</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#panel-4" role="tab" aria-selected="true">Data Lokasi Tanam</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-danger" data-toggle="tab" href="#panel-5" role="tab" aria-selected="true">Hasil Pemeriksaan</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade" id="panel-1" role="tabpanel" aria-labelledby="panel-1">
						<div id="panel-1" class="panel">
							<form method="post" action="">
								@csrf
								<div class="panel-container show">
									<div class="panel-content">
										<table class="table table-striped table-bordered table-sm w-100" id="attchCheck">
											<thead class="thead-themed text-uppercase text-muted">
												<tr>
													<th>Form</th>
													<th>Nama Berkas</th>
													<th>Tindakan</th>
													<th>Hasil Periksa</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Surat Pertanggungjawaban Mutlak</td>
													<td>{{$userDocs->sptjmtanam}}</td>
													<td>
														@if ($userDocs->sptjmtanam)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->sptjmtanam) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm" name="sptjmtanamcheck" id="sptjmtanamcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->sptjmtanamcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->sptjmtanamcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Surat Pengajuan Verifikasi Tanam</td>
													<td>{{$userDocs->spvt}}</td>
													<td>
														@if ($userDocs->spvt)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->spvt) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm" name="spvtcheck" id="spvtcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->spvtcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->spvtcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Form Realisasi Tanam</td>
													<td>{{$userDocs->rta}}</td>
													<td>
														@if ($userDocs->rta)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->rta) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm" name="rtacheck" id="rtacheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->rtacheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->rtacheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Perbaikan</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Form SPH-SBS</td>
													<td>{{$userDocs->sphtanam}}</td>
													<td>
														@if ($userDocs->sphtanam)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->sphtanam) }}" download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm" name="sphtanamcheck" id="sphtanamcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->sphtanamcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->sphtanamcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Logbook (s.d tanam)</td>
													<td>{{$userDocs->logbooktanam}}</td>
													<td>
														@if ($userDocs->logbooktanam)
															<a href="{{ asset('storage/uploads/'.$npwp.'/'.$periodetahun.'/'.$userDocs->logbooktanam) }}"  download>
																<i class="fas fa-download mr-1"></i>
																Unduh Dokumen
															</a>
														@endif
													</td>
													<td>
														<select class="form-control form-control-sm" name="logbooktanamcheck" id="logbooktanamcheck">
															<option value="">- Pilih status -</option>
															<option value="sesuai" {{ $userDocs->logbooktanamcheck == 'sesuai' ? 'selected' : '' }}>Ada/Sesuai</option>
															<option value="perbaiki" {{ $userDocs->logbooktanamcheck == 'perbaiki' ? 'selected' : '' }}>Tidak Ada/Tidak Sesuai</option>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="card-footer d-flex justify-content-between align-items-center">
									<div class="help-block col-md-7">
									</div>
									<div class="col-md text-right">
										<button type="submit" class="btn btn-primary btn-sm">
											<i class="fal fa-save"></i> Simpan Hasil Pemeriksaan
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="tab-pane fade" id="panel-2" role="tabpanel" aria-labelledby="panel-2">
						<div id="panel-1" class="panel">
							<div class="panel-container show">
								<div class="panel-content">
									<table class="table table-striped table-bordered table-sm w-100" id="pksCheck">
										<thead class="thead-themed text-uppercase text-muted">
											<tr>
												<th style="width: 20%">No Perjanjian</th>
												<th>Kelompok Tani</th>
												<th>Berlaku Sejak</th>
												<th>Berakhir Pada</th>
												<th>Varietas</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>61.250.195.7-436.000</td>
												<td>61.250.195.7-436.000</td>
												<td>61.250.195.7-436.000</td>
												<td>61.250.195.7-436.000</td>
												<td>61.250.195.7-436.000</td>
											</tr>
										</tbody>
									</table>
								</div>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-editor/2.3.1/dataTables.editor.js" integrity="sha512-BKsIfYRuTSaLQncTO/3CUtWr6zko7hbmxWYcBhJ7YqVB1zPIcG0S7hCNf3PLcQds22RlBaVHnKkKLxjSmn9hZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		$(document).ready(function() {
			var noIjin = '{{$ijin}}';
			var formattedNoIjin = noIjin.replace(/[\/.]/g, '');

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
        			// $('#companyLogo').attr('src', logoUrl);
					$('#companyLogo').css('background-image', 'url(' + logoUrl + ')');
				}
			});

			$('#pksCheck').dataTable({
				responsive: true,
				lengthChange: false,
				ordering: true,
				rowCallback: function(row, data) {
					$(row).on('click', function() {
						$('#pksCheck tbody tr').removeClass('selected');
						$(this).addClass('selected');

						if ($(this).next().hasClass('expanded-row')) {
							$(this).next().remove();
						} else {
							var formHTML = '<tr class="expanded-row"><td colspan="5">';
							formHTML += '<a href="#" download>{{$noIjin}}</a>';
							formHTML += '<form id="expandForm">';
							formHTML += '<label for="input1">Input 1:</label>';
							formHTML += '<input class="form-control" type="text" id="input1" name="input1"><br>';
							formHTML += '<label for="input2">Input 2:</label>';
							formHTML += '<textarea name="note" id="note" class="form-control form-control-sm" rows="3"></textarea><br>';
							formHTML += '<button class="btn btn-primary btn-xs" type="submit">Submit</button>';
							formHTML += '</form>';
							formHTML += '</td></tr>';

							$(this).after(formHTML);
						}
					});
				}
			});
		});
	</script>
@endsection
