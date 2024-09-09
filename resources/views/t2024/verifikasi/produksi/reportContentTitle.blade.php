<div class="container mt-4">
	<div class="row justify-content-between align-items-start">
		<!-- Left Grid: Logo & Text -->
		<div class="col-md-6">
			{{-- <img src="https://simethris4.test/img/favicon.png" class="profile-image rounded-circle" alt="Rijaludin Akbar"> --}}
			<i class="bi bi-ui-checks-grid text-secondary display-4 me-3"></i>
			<div class="logo-text">
				<h2 class="mb-0 fw-bold">RINGKASAN HASIL</h2>
				<p class="mb-0 text-muted">Pelaksanaan Verifikasi Realisasi Wajib {{$payload['ajuProduksi']->kind}} Bawang Putih.</p>
			</div>
		</div>

		<!-- Right Grid: List or Other Content -->
		<div class="col-md-5">
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
				<div class="col-4"><span class="text-secondary">Verifikator: </span></div>
				<div class="col-8">
					<ul class="fw-bold">
						@foreach ( $payload['ajuProduksi']->assignments as $assignment )
						<li>{{$assignment->user->name}}</li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="d-flex">
				<div class="col-4"><span class="text-secondary">Hasil Verifikasi: </span></div>
				<div class="col-8"><span class="fw-bold">
					@if($payload['avpStatus'] == 6)
						<span class="text-success">Selesai - Sesuai</span>
					@elseif($payload['avpStatus'] == 7)
						<span class="text-danger">Selesai - Perbaikan</span>
					@else
						<span class="text-info">Tidak ada status</span>
					@endif
				</span></div>
			</div>
		</div>
	</div>
	<hr>
</div>
