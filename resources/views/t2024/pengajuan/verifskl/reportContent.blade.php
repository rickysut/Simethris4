<div class="pagebreak"></div>
<div class="container" style="margin-top: 15mm; margin-bottom: 15mm;">
	<div class="row d-flex align-items-start">
		<div class="col-5">
			<span class="fw-bold h6">A. Ringkasan Kewajiban dan Realisasi</span>
		</div>
		<div class="col-7">
			<ul class="list-group list-group-flush mt-2">
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Lokasi Lahan Tanam (titik)</span>
					<span class=fw-semibold>
						<span class="{{ $payload['countTanam'] < $payload['countSpatial'] ? 'text-danger' : 'text-success' }}">{{ $payload['countTanam'] }}</span>
							/ {{ $payload['countSpatial'] }}
					</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Realisasi Tanam (m2)</span>
					<span class=fw-semibold>
							<span class="{{ $payload['wajibTanam'] > $payload['realisasiTanam'] ? 'text-danger' : 'text-success' }}">{{ number_format((float)$payload['realisasiTanam'], 0, ',', '.') }}</span>
							/ {{ number_format((float)$payload['wajibTanam'], 0, ',', '.') }}
					</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Realisasi Produksi (ton)</span>
					<span class=fw-semibold>
							<span class="{{ $payload['realisasiProduksi'] < $payload['wajibProduksi'] ? 'text-danger' : 'text-success' }}">{{ number_format((float)$payload['realisasiProduksi'], 0, ',', '.') }}</span>
							/ {{ number_format((float)$payload['wajibProduksi'], 0, ',', '.') }}
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
			<p class="small text-muted">Data verifikasi tanam terakhir</p>
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
			<p class="small text-muted">Data verifikasi produksi terakhir</p>
		</div>
		<div class="col-md-7">
			<ul class="list-group list-group-flush mt-2">
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Tanggal Pengajuan</span>
					<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avpDate'])->locale('id')->translatedFormat('F j, Y') }}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Tanggal Verifikasi</span>
					<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avpVerifAt'])->locale('id')->translatedFormat('F j, Y') }}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Metode Verifikasi</span>
					<span class=fw-semibold>{{$payload['avpMetode']}}</span>
				</li>
				<li class="d-flex justify-content-between list-group-item">
					<span class=text-muted>Catatan Verifikasi</span>
					<p class=fw-semibold>{{$payload['avpNote']}}</p>
				</li>
			</ul>
		</div>
	</div>
	<hr>
</div>
<div class="pagebreak"></div>
<div class="container" style="margin-top: 15mm; margin-bottom: 15mm;">
	<div class="row">
		<div class="col-12">
			<span class="fw-bold h6">F. Riwayat Pengajuan Verifikasi</span>
		</div>
		<div class="col-12">
			<table class="table table-hover table-sm w-100" style="border: none; border-top:none; border-bottom:none;" id="tblVerifHistory">
				<thead class="">
					<th>Tahap</th>
					<th>Tanggal Diajukan</th>
					<th>Tanggal Verifikasi</th>
					<th>Status</th>
				</thead>
				<tbody>
					@foreach ($payload['ajuTanam'] as $history)
					<tr>
						<td>{{$history->kind}}</td>
						<td>{{$history->created_at}}</td>
						<td>{{$history->verif_at}}</td>
						<td>
							<i class="bi bi-{{ $history->status == 6 ? 'check-square-fill text-success' : 'x-square-fill text-danger' }}"></i>
						</td>
					</tr>
					@endforeach
					@foreach ($payload['ajuProduksi'] as $history)
					<tr>
						<td>{{$history->kind}}</td>
						<td>{{$history->created_at}}</td>
						<td>{{$history->verif_at}}</td>
						<td>
							<i class="bi bi-{{ $history->status == 6 ? 'check-square-fill text-success' : 'x-square-fill text-danger' }}"></i>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
