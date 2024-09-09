@extends('t2024.layouts.admin')
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@include('t2024.partials.sysalert')

	<div class="row">
		<div class="col-12">
			<div class="text-center">
				<i class="fal fa-badge-check fa-3x subheader-icon"></i>
				<h2>Ringkasan Data</h2>
				<div class="row justify-content-center">
					<p class="lead">Ringkasan {{$page_heading}}</p>
				</div>
			</div>

			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-hover table-sm w-100" style="border: none; border-top:none; border-bottom:none;" id="dataTable">
							<thead class="">
								<th  style="width: 32%"></th>
								<th style="width: 1%"></th>
								<th></th>
								<th></th>
							</thead>
							<tbody>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN UMUM</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Perusahaan</td>
									<td>:</td>
									<td class="fw-500" id="companyName"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Nomor Ijin (RIPH)</td>
									<td>:</td>
									<td class="fw-500" id="noIjin"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Periode RIPH</td>
									<td>:</td>
									<td class="fw-500" id="periode"></td>
									<td></td>
								</tr>
								<tr class="bg-primary-50" style="height: 25px; opacity: 0.2">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN KEWAJIBAN DAN REALISASI</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Luas Wajib Tanam</td>
									<td>:</td>
									<td class="fw-500" id="wajibTanam"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Volume Wajib Produksi</td>
									<td>:</td>
									<td class="fw-500" id="wajibProduksi"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Realisasi Tanam</td>
									<td>:</td>
									<td class="fw-500" id="realisasiTanam">
										<span></span>
										<i></i>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Lokasi Tanam/Spasial</td>
									<td>:</td>
									<td class="fw-500" id="countSpatial"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Realisasi Produksi</td>
									<td>:</td>
									<td class="fw-500" id="sumPanen"></td>
									<td></td>
								</tr>
								<tr class="" style="height: 25px;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN KEMITRAAN</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Petani Mitra</td>
									<td>:</td>
									<td class="fw-500" id="countAnggota"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Kelompok Tani Mitra</td>
									<td>:</td>
									<td class="fw-500" id="countPoktan"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted">Jumlah Perjanjian (PKS) diunggah</td>
									<td>:</td>
									<td class="fw-500" id="countPks">
									</td>
									<td></td>
								</tr>
								<tr class="bg-primary-50" style="height: 25px; opacity: 0.2">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>

								<tr>
									<td class="text-uppercase fw-500">KELENGKAPAN BERKAS</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								{{-- tanam --}}
								<tr>
									<td class="text-uppercase fw-500">A. TAHAP TANAM</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Surat Pengajuan Verifikasi Tanam</td>
									<td>:</td>
									<td class="fw-500" id="spvt">
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Surat Pertanggungjawaban Mutlak (Tanam)</td>
									<td>:</td>
									<td class="fw-500" id="sptjmtanam"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Form Realisasi Tanam</td>
									<td>:</td>
									<td class="fw-500" id="rta"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">SPH-SBS (Tanam)</td>
									<td>:</td>
									<td class="fw-500" id="sphsbstanam"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Logbook (s.d Tanam)</td>
									<td>:</td>
									<td class="fw-500" id="logTanam"></td>
									<td></td>
								</tr>

								<tr class="" style="height: 25px;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								{{-- produksi --}}
								<tr>
									<td class="text-uppercase fw-500">B. TAHAP PRODUKSI</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Surat Pengajuan Verifikasi Produksi</td>
									<td>:</td>
									<td class="fw-500" id="spvp"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Surat Pertanggungjawaban Mutlak (Produksi)</td>
									<td>:</td>
									<td class="fw-500" id="sptjmProduksi"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Form Realisasi Produksi</td>
									<td>:</td>
									<td class="fw-500" id="rpo"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">SPH-SBS (Produksi)</td>
									<td>:</td>
									<td class="fw-500" id="sphProduksi"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Logbook (s.d Produksi)</td>
									<td>:</td>
									<td class="fw-500" id="logProduksi"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Laporan Akhir</td>
									<td>:</td>
									<td class="fw-500" id="formLa"></td>
									<td></td>
								</tr>

								{{-- hasil pemeriksaan --}}
								<tr class="bg-primary-50" style="height: 25px; opacity: 0.2">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500 h6">RINGKASAN HASIL</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500">A. VERIFIKASI TANAM</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Tanggal Pengajuan</td>
									<td>:</td>
									<td class="fw-500" id="avtDate"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Tanggal Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtVerifAt"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Metode Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtMetode"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Catatan Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtNote">
										<p></p>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Hasil Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avtStatus"></td>
									<td></td>
								</tr>
								<tr class="" style="height: 25px;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-uppercase fw-500">B. VERIFIKASI PRODUKSI</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Tanggal Pengajuan</td>
									<td>:</td>
									<td class="fw-500" id="avpDate"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Tanggal Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avpVerifAt"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Metode Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avpMetode"></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Catatan Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avpNote">
										<p></p>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="text-muted pl-4">Hasil Verifikasi</td>
									<td>:</td>
									<td class="fw-500" id="avpStatus"></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="card-footer d-flex justify-content-end">
					<a href="{{ route('2024.user.commitment.index') }}"
						class="btn btn-xs btn-info mr-1" data-toggle="tooltip"
						title data-original-title="Kembali">
						<i class="fal fa-undo mr-1"></i>
						Kembali
					</a>
					{{-- Form pengajuan --}}
					{{-- pengajuan tanam --}}
					<form action="" method="post">
						@csrf
						<input type="hidden" value="SKL" id="kind" name="kind">
						<button type="submit" class="btn btn-xs btn-warning" data-toggle="tooltip" title data-original-title="Ajukan Verifikasi Tanam" id="btnSubmit">
							<i class="fal fa-upload mr-1"></i>
							Ajukan
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div id="panel-1" class="panel">
				<div class="panel-hdr">
					<h5>Riwayat Pengajuan Verifikasi</h5>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<table class="table table-hover table-sm w-100" style="border: none; border-top:none; border-bottom:none;" id="tblVerifHistory">
							<thead class="">
								<th>Tanggal Diajukan</th>
								<th>Verifikator</th>
								<th>Tanggal Verifikasi</th>
								<th>Status</th>
								<th>Catatan</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


@endsection

@section('scripts')
	@parent
	<script>
		$(document).ready(function() {

		});
	</script>
@endsection
