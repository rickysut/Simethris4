
<div class="container mt-4">
	<div class="row d-flex align-items-start">
		<div class="col-5">
			<span class="fw-bold h6">A. Ringkasan Kewajiban dan Realisasi</span>
		</div>
		<div class="col-7">
			<ul class="list-group list-group-flush mt-2">
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Realisasi Tanam (m2)</span>
					<span class=fw-semibold>
							<span class="{{ $payload['realisasiTanam'] < $payload['wajibTanam'] ? 'text-danger' : '' }}">{{ number_format((float)$payload['realisasiTanam'], 0, ',', '.') }}</span>
							/ {{ number_format((float)$payload['wajibTanam'] * 10000, 0, ',', '.') }}
					</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Lokasi Tanam Tanam (titik)</span>
					<span class=fw-semibold>
						<span class="{{ $payload['countTanam'] < $payload['countSpatial'] ? 'text-danger' : '' }}">{{ $payload['countTanam'] }}</span>
							/ {{ $payload['countSpatial'] }}
					</span>
				</li>
			</ul>
		</div>
	</div>
	<hr>
	<div class="row d-flex align-items-start">
		<div class="col-md-5">
			<span class="fw-bold h6">B. Ringkasan Kemitraan</span>
		</div>
		<div class="col-md-7">
			<ul class="list-group list-group-flush mt-2">
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Jumlah Petani Mitra</span>
					<span class=fw-semibold>{{ $payload['countAnggota'] }} orang </span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Jumlah Kelompok Tani Mitra</span>
					<span class=fw-semibold>{{ $payload['countPoktan'] }} kelompok </span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Jumlah Perjanjian (PKS) dilampirkan</span>
					<span class=fw-semibold>{{ $payload['countPks'] }} berkas </span>
				</li>
			</ul>
		</div>
	</div>
	<hr>
	<div class="row d-flex align-items-start">
		<div class="col-md-5">
			<span class="fw-bold h6">C. Kelengkapan Berkas Pengajuan</span>
		</div>
		<div class="col-md-7">
			<ul class="list-group list-group-flush mt-2">
				@foreach($payload['userFiles'] as $file)
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>
							@switch($file->kind)
								@case('sptjmtanam')
									Surat Pertanggungjawaban Mutlak (Tanam)
									@break
								@case('sptjmproduksi')
									Surat Pertanggungjawaban Mutlak (Produksi)
									@break
								@case('spvt')
									Surat Permohonan Verifikasi Tanam
									@break
								@case('spvp')
									Surat Permohonan Verifikasi Produksi
									@break
								@case('sphtanam')
									SPH-SBS (Tanam)
									@break
								@case('sphproduksi')
									SPH-SBS (Produksi)
									@break
								@case('rta')
									Form Realisasi Tanam
									@break
								@case('rpo')
									Form Realisasi Produksi
									@break
								@case('logbook')
									Logbook
									@break
								@case('formLa')
									Form Laporan Akhir
									@break
								@default
									{{ $file->kind }}
							@endswitch
						</span>
						<span class=fw-semibold>
							<i class="bi bi-{{ $file->status == '1' ? 'check-square-fill text-success' : 'x-square-fill text-danger' }}"></i>
						</span>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
	<hr>
	<div class="row d-flex align-items-start">
		<div class="col-md-5">
			<span class="fw-bold h6">D. Ringkasan Hasil Verifikasi Tanam</span>
			{{-- <p class="small text-muted">Hasil Verifikasi Produksi</p> --}}
		</div>
		<div class="col-md-7">
			<ul class="list-group list-group-flush mt-2">
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Tanggal Pengajuan</span>
					<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avtDate'])->locale('id')->translatedFormat('F j, Y') }}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Tanggal Verifikasi</span>
					<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avtVerifAt'])->locale('id')->translatedFormat('F j, Y') }}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Metode Verifikasi</span>
					<span class=fw-semibold>{{$payload['avtMetode']}}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Catatan Verifikasi</span>
					<p class=fw-semibold>{{$payload['avtNote']}}</p>
				</li>
			</ul>
		</div>
	</div>
	<hr>
	<div class="row d-flex align-items-start">
		<div class="col-md-5">
			<span class="fw-bold h6">E. Ringkasan Hasil Verifikasi Produksi</span>
			{{-- <p class="small text-muted">Hasil Verifikasi Tanam</p> --}}
		</div>
		<div class="col-md-7">
			<ul class="list-group list-group-flush mt-2">
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Tanggal Pengajuan</span>
					<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avtDate'])->locale('id')->translatedFormat('F j, Y') }}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Tanggal Verifikasi</span>
					<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avtVerifAt'])->locale('id')->translatedFormat('F j, Y') }}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Metode Verifikasi</span>
					<span class=fw-semibold>{{$payload['avtMetode']}}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Catatan Verifikasi</span>
					<p class=fw-semibold>{{$payload['avtNote']}}</p>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="pagebreak"></div>
<div class="container mt-4">
	<div class="row">
		<div class="col-12">
			<span class="fw-bold h6">F. Daftar Perjanjian Kerjasama yang dinyatakan TIDAK SESUAI</span>
			<table class="table table-sm w-100 mt-3">
				<thead>
					<tr>
						<th>Nomor PKS</th>
						<th>Tanggal PKS</th>
						<th>Kelompok Tani</th>
						<th>Tanggal Verifikasi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($payload['failPks'] as $pks)
						<tr>
							<td>{{$pks->no_perjanjian ? $pks->no_perjanjian : 'Tidak Ada'}}</td>
							<td>{{$pks->tgl_perjanjian_start ? $pks->tgl_perjanjian_start : 'Tidak Ada'}}</td>
							<td>{{$pks->nama_poktan}}</td>
							<td>{{ \Carbon\Carbon::parse($pks->verif_at)->locale('id')->translatedFormat('F j, Y') }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="pagebreak"></div>
<div class="container mt-4">
	<div class="row">
		<div class="col-12">
			<span class="fw-bold h6">G. Timeline Realisasi yang dinyatakan TIDAK SESUAI</span>
			<table class="table table-sm w-100 mt-3">
				<thead>
					<tr>
						<th>Kode Lokasi</th>
						<th>Pengelola</th>
						<th>Tanggal Tanam</th>
						<th>Tanggal Panen</th>
						<th>Tanggal Verifikasi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($payload['failTime'] as $timeline)
						<tr>
							<td>{{$timeline->kode_spatial}}</td>
							<td>{{$timeline->ktp_petani}} - {{$timeline->masteranggota->nama_petani}}</td>
							<td class="text-end">{{$timeline->tgl_tanam ? $timeline->tgl_tanam : 'tidak ada'}}</td>
							<td class="text-end">{{$timeline->tgl_panen ? $timeline->tgl_panen : 'tidak ada'}}</td>
							<td class="text-end">{{$timeline->verif_p_at}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="pagebreak"></div>
<div class="container mt-4">
	<div class="row">
		<div class="col-12">
			<span class="fw-bold h6">H. Daftar Lokasi yang dinyatakan TIDAK SESUAI</span>
			<table class="table table-sm mt-3 w-100">
				<thead>
					<tr>
						<th>Kode Lokasi</th>
						<th>Luas Lahan</th>
						<th>Luas Tanam</th>
						<th>Volume Panen</th>
						<th>Pengelola</th>
						<th>Tanggal Verifikasi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($payload['failLokasi'] as $lokasi)
						<tr>
							<td>{{$lokasi->kode_spatial ? $lokasi->kode_spatial : 'Tidak Ada'}}</td>
							<td class="text-end">
								{{ $lokasi->luas_tanam ? number_format((float)$lokasi->luas_tanam, 0, ',', '.') . ' m²' : 'Tidak Ada' }}
							</td>
							<td class="text-end">
								{{ $lokasi->luas_lahan ? number_format((float)$lokasi->luas_lahan, 0, ',', '.') . ' m²' : 'Tidak Ada' }}
							</td>
							<td class="text-end">
								{{ $lokasi->luas_lahan ? number_format((float)$lokasi->volume, 0, ',', '.') . ' kg' : 'Tidak Ada' }}
							</td>
							<td>{{$lokasi->ktp_petani}} - {{$lokasi->masteranggota->nama_petani}}</td>
							<td class="text-end">{{ $lokasi->verif_p_at }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="pagebreak"></div>
