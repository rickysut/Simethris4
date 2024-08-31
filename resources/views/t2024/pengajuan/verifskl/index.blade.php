@extends('t2024.layouts.admin')
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
							{{-- jika tidak ada data --}}
							@if (!$payload['lastVSkl'])
								<form action="{{route('2024.user.commitment.submitPengajuanSkl', $ijin)}}" method="post">
									@csrf
									<button class="btn btn-sm btn-primary" type="submit">
										<i class="fal fa-upload"></i> Ajukan SKL
									</button>
								</form>
							{{-- jika ada --}}
							@else
								{{-- jika status bernilai 6 --}}
								@if ($payload['lastVSkl']->status == 6)
									<form action="{{route('2024.user.commitment.reSubmitPengajuanSkl', $ijin)}}" method="post">
										@csrf
										<button class="btn btn-sm btn-primary" type="submit">
											<i class="fal fa-upload"></i> Ajukan Ulang SKL
										</button>
									</form>
								@endif

								{{-- jika report_url ada --}}
								@if (!$payload['lastVSkl']->report_url)
									<a href="{{route('2024.user.commitment.generateRepReqSkl', $ijin)}}"
										class="btn btn-sm btn-warning mr-1">
										<i class="fal fa-download"></i> Generate Report
									</a>
								{{-- jika report_url tidak ada --}}
								@else
									<a href="{{ $payload['lastVSkl']->report_url }}"
										class="btn btn-sm btn-success ml-2" target="_blank">
										<i class="fal fa-download"></i> Lihat
									</a>
								@endif
							@endif
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
						</div>
					</div>
					@include('t2024.pengajuan.verifSkl.reportContent')
				</div>
			</div>
		</div>
	{{-- @endcan --}}
@endsection

@section('scripts')
	@parent
@endsection
