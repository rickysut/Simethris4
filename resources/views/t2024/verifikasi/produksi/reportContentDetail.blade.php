
<div class="container mt-4">
	<div class="row justify-content-center align-items-start text-center">
		<div class="col-12 d-flex justify-content-center align-items-center">
			{{-- <img src="https://simethris4.test/img/favicon.png" class="profile-image rounded-circle" alt="Rijaludin Akbar"> --}}
			<i class="bi bi-geo-alt-fill text-secondary display-4 me-3"></i>
			<div class="logo-text">
				<h2 class="mb-0 fw-bold">DAFTAR LOKASI</h2>
				<p class="mb-0 text-muted">Realisasi Wajib Tanam Bawang Putih</p>
			</div>
		</div>
	</div>
</div>

<div class="container mt-5">
	<div class="row justify-content-center align-items-start">
		<ul class="list-group">
			<li class="list-group-item d-flex align-items-start">
				<span class="col-6 text-muted">Pelaku Usaha:</span>
				<span class="col-6 fw-bold">{{$payload['company']}}</span>
			</li>
			<li class="list-group-item d-flex align-items-start">
				<span class="col-6 text-muted">Nomor Ijin (RIPH):</span>
				<span class="col-6 fw-bold">{{$payload['noIjin']}}</span>
			</li>
			<li class="list-group-item d-flex align-items-start">
				<span class="col-6 text-muted">Periode:</span>
				<span class="col-6 fw-bold">{{$payload['periode']}}</span>
			</li>
			<li class="list-group-item d-flex align-items-start">
				<span class="col-6 text-muted">Hasil Verifikasi:</span>
				<span class="col-6 fw-bold">
					@if($payload['avtStatus'] == 6)
						<span class="text-success">Selesai - Sesuai</span>
					@elseif($payload['avtStatus'] == 7)
						<span class="text-danger">Selesai - Perbaikan</span>
					@else
						<span class="text-info">Tidak ada status</span>
					@endif
				</span>
			</li>
			<li class="list-group-item d-flex align-items-start">
				<span class="col-6 text-muted">Verifikator:</span>
				<span class="col-6 fw-bold">
					<ul class="fw-bold">
						@foreach ( $payload['ajuProduksi']->assignments as $assignment )
						<li>{{$assignment->user->name}}</li>
						@endforeach
					</ul>
				</span>
			</li>
		</ul>
	</div>
	<hr class="mt-5">
</div>
@foreach($payload['daftarLokasi'] as $poktan)
<div class="container mt-5">
	<div class="row text-center">
		<span class="col-12">Kelompok Tani</span>
		<span class="col-12 h2">{{$poktan->masterpoktan->nama_kelompok}}</span>
	</div>
	<div class="row">
		<div class="table col-12">
			<table class="table w-100">
				<thead>
					<tr>
						<th width="30%">Lokasi</th>
						<th width="20%">Pengelola</th>
						<th>Desa-Kecamatan</th>
						<th>Tgl Tanam</th>
						<th>Luas Tanam</th>
						<th class="text-center">Status</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($poktan->lokasi as $lokasi)
						<tr>
							<td>
								{{$lokasi->kode_spatial}} <br>
								<span>Lat: {{$lokasi->spatial->latitude}}</span><br>
								<span>Lng: {{$lokasi->spatial->longitude}}</span>
							</td>
							<td>{{$lokasi->masteranggota->nama_petani}}</td>
							<td>{{$lokasi->spatial->desa->nama_desa}} - {{$lokasi->spatial->kecamatan->nama_kecamatan}}</td>
							<td>{{$lokasi->tgl_tanam}}</td>
							<td class="text-end">{{$lokasi->luas_tanam}} m2</td>
							<td class="text-center">
								<i class="bi bi-{{$lokasi->status !== 1 ? 'x-square-fill text-danger' : 'check-square-fill text-success'}}"></i>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="pagebreak"></div>
@endforeach
