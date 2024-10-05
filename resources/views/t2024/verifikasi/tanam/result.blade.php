@extends('layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('t2024.partials.subheader')
	@can('online_access')
	@include('t2024.partials.sysalert')
		<div class="row">
			<div class="col-12">

			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="panel">
					<div class="panel-hdr">
						<h2></h2>
						<div class="panel-toolbar">
							@if ($payload['ajuTanam']->report_url)
								<a href="{{ route('2024.verifikator.tanam.generateReport', ['noIjin' => $ijin, 'tcode' => $tcode]) }}"
									class="btn btn-sm btn-warning mr-1">
									<i class="fal fa-download"></i> Re-Generate Report
								</a>
								<a href="{{ $payload['ajuTanam']->report_url }}"
									class="btn btn-sm btn-success" target="_blank">
									<i class="fal fa-download"></i> Lihat
								</a>
							@else
								<a href="{{ route('2024.verifikator.tanam.generateReport', ['noIjin' => $ijin, 'tcode' => $tcode]) }}"
									class="btn btn-sm btn-primary">
									<i class="fal fa-download"></i> Generate Report
								</a>
							@endif
						</div>
					</div>
					<div class="panel-container">
						<div class="panel-content">
							<div class="container">
								<div class="row">
									<div class="col-6">
										<div class="d-flex">
											<div class="col-4"><span class="text-secondary">Perusahaan: </span></div>
											<div class="col-8"><span class="fw-bold">{{$payload['company']}}</span></div>
										</div>
										<div class="d-flex">
											<div class="col-4"><span class="text-secondary">Nomor Ijin (RIPH): </span></div>
											<div class="col-8"><span class="fw-bold">{{$payload['noIjin']}}</span></div>
										</div>
										<div class="d-flex">
											<div class="col-4"><span class="text-secondary">Periode: </span></div>
											<div class="col-8"><span class="fw-bold">{{$payload['periode']}}</span></div>
										</div>
										<div class="d-flex">
											<div class="col-4"><span class="text-secondary">Hasil Verifikasi: </span></div>
											<div class="col-8">
												<span class="fw-bold">
													@if($payload['avtStatus'] == 6)
														<span class="text-success">Selesai - Sesuai</span>
													@elseif($payload['avtStatus'] == 7)
														<span class="text-danger">Selesai - Perbaikan</span>
													@else
														<span class="text-info">Tidak ada status</span>
													@endif
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-container">
						<div class="panel-content">
							@include('t2024.verifikasi.tanam.reportContent')
						</div>
					</div>
				</div>
			</div>
		</div>
	@endcan
@endsection

@section('scripts')
	@parent
@endsection
