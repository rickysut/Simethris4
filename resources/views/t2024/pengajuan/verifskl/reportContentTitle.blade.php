<div class="container page-cover">
	<div class="row justify-content-between align-items-start">
		<!-- Left Grid: Logo & Text -->
		<div class="col-12 d-flex align-items-start justify-content-center">
			<img src="https://simethris4.test/img/favicon.png" class="mx-2 mt-1 profile-image rounded-circle" style="width: 50px; height:50px" alt="Rijaludin Akbar">
			{{-- <i class="bi bi-ui-checks-grid text-secondary display-4 me-3"></i> --}}
			<div class="logo-text">
				<h1 class="mb-0 fw-bold">RINGKASAN LAPORAN</h1>
				<p class="mb-3 h5 text-muted">Pelaksanaan Realisasi Wajib Tanam-Produksi Bawang Putih.</p>
				<div>
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
						<div class="col-4"><span class="text-secondary">Tahap: </span></div>
						<div class="col-8"><span class="fw-bold">Permohonan Penerbitan SKL</span></div>
					</div>
					<div class="d-flex">
						<div class="col-4"><span class="text-secondary">Kode Pengajuan: </span></div>
						<div class="col-8"><span class="fw-bold">{{ $payload['lastVSkl']->tcode ? $payload['lastVSkl']->tcode : ' - ' }}</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr>
</div>
