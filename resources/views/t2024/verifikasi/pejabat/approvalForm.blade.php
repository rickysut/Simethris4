@extends('layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	{{-- @can('online_access') --}}
		@include('t2024.partials.sysalert')
		<div class="row">
			<div class="col-12">
				<div class="panel">
					<div class="panel-hdr">
						<h2>
							@if ($payload['lastVSkl'])
								No. Pengajuan: {{ $payload['lastVSkl']->tcode }}
							@else
							@endif
						</h2>
						<div class="panel-toolbar">

						</div>
					</div>
					<div class="panel-container">
						<div class="panel-content">
							<div>
								<div class="d-flex">
									<div class="col-2"><span class="text-secondary">Perusahaan: </span></div>
									<div class="col-8"><span class="fw-500">{{$payload['company']}}</span></div>
								</div>
								<div class="d-flex">
									<div class="col-2"><span class="text-secondary">Nomor Ijin (RIPH): </span></div>
									<div class="col-8"><span class="fw-500">{{$payload['noIjin']}}</span></div>
								</div>
								<div class="d-flex">
									<div class="col-2"><span class="text-secondary">Periode: </span></div>
									<div class="col-8"><span class="fw-500">{{$payload['periode']}}</span></div>
								</div>
								<div class="d-flex">
									<div class="col-2"><span class="text-secondary">Tahap: </span></div>
									<div class="col-8"><span class="fw-500">AKHIR</span></div>
								</div>
							</div>
							<div class="panel-content">
								@include('t2024.verifikasi.skl.reportContent')
							</div>
						</div>
					</div>
				</div>
				<div class="panel">
					<div class="panel-hdr">
						<h2>Panel Persetujuan</h2>
					</div>
					<div class="panel-container">
						<div class="panel-content">
							<form action="{{route('2024.pejabat.skl.rekomendasi.approvalStatus', [$ijin, $tcode])}}" method="post" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label for="status">Penetapan Status</label>
									<select class="form-control required" name="status" id="status" aria-describedby="helpStatus" required>
										<option value="" hidden>--pilih status</option>
										<option value="3">Disetujui</option>
										<option value="5">Ditolak</option>
									</select>
									<small id="helpStatus" class="text-muted">Pilih status penerbitan SKL.</small>
								</div>
								<div class="form-group">
									<label for="verified">Verifikasi Nama Pengguna</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control required" placeholder="Nama pengguna anda" name="verified" id="verified" aria-label="Verifikasi nama pengguna" aria-describedby="helpVerified">
										<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="submit" id="btnSubmit">Simpan</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	{{-- @endcan --}}
@endsection

@section('scripts')
	@parent
@endsection
