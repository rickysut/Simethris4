<div class="pagebreak"></div>
<div class="container">
	<ul class="list-group">
		<li class="list-group-item">
			<div class="d-flex w-100 justify-content-between">
				<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Ringkasan Kewajiban dan Realisasi</h6>
			</div>
			<ul class="list-group list-group-flush ">
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
		</li>
		<li class="list-group-item">
			<div class="d-flex w-100 justify-content-between">
				<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Ringkasan Kemitraan</h6>
			</div>
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
		</li>
		<li class="list-group-item">
			<div class="d-flex w-100 justify-content-between">
				<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Kelengkapan Berkas Pengajuan</h6>
			</div>
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
		</li>
		<li class="list-group-item">
			<div class="d-flex w-100 justify-content-between">
				<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Verifikasi Tanam</h6>
				<span>
					@if($payload['avtStatus'] == 6)
						<span class="badge badge-pill badge-success bg-success">Sesuai</span>
					@elseif($payload['avtStatus'] == 7)
						<span class="badge badge-pill badge-danger bg-danger">Dikembalikan</span>
					@else
					@endif
				</span>
			</div>
			<p class="small">Data ringkasan hasil verifikasi terakhir dari fase tanam</p>
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
		</li>
		<li class="list-group-item">
			<div class="d-flex w-100 justify-content-between">
				<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Verifikasi Produksi</h6>
				<span>
					@if($payload['avpStatus'] == 6)
						<span class="badge badge-pill badge-success bg-success">Sesuai</span>
					@elseif($payload['avpStatus'] == 7)
						<span class="badge badge-pill badge-danger bg-danger">Dikembalikan</span>
					@else
					@endif
				</span>
			</div>
			<p class="small">Data ringkasan hasil verifikasi terakhir dari fase produksi</p>
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
		</li>
		@if($payload['avsklStatus'] == 0)
		@else
			<li class="list-group-item">
				<div class="d-flex w-100 justify-content-between">
					<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Pemeriksaan Akhir</h6>
					<span>
						@if($payload['avsklStatus'] == 0)
						@elseif($payload['avsklStatus'] == 6)
							<span class="badge badge-pill badge-danger bg-danger">Dikembalikan</span>
						@else
							<span class="badge badge-pill badge-success bg-success">Sesuai</span>
						@endif
					</span>
				</div>
				<p class="small">Pemeriksaan akhir atas permohonan penerbitan surat keterangan lunas</p>
				<ul class="list-group list-group-flush mt-2">
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>Tanggal Pengajuan</span>
						<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avsklDate'])->locale('id')->translatedFormat('F j, Y') }}</span>
					</li>
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>Tanggal Verifikasi</span>
						<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avsklVerifAt'])->locale('id')->translatedFormat('F j, Y') }}</span>
					</li>
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>Catatan Verifikasi</span>
						<p class=fw-semibold>{{$payload['avsklNote']}}</p>
					</li>
				</ul>
			</li>
		@endif
		@if($payload['avsklRecomendAt'])
			<li class="list-group-item">
				<div class="d-flex w-100 justify-content-between">
					<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Data Rekomendasi</h6>
					<span>
						@if($payload['avsklStatus'] == 0)
						@elseif($payload['avsklStatus'] == 2)
							<span class="badge badge-pill badge-success bg-success">Direkomendasikan</span>
						@elseif($payload['avsklStatus'] == 3)
							<span class="badge badge-pill badge-success bg-success">Disetujui</span>
						@elseif($payload['avsklStatus'] == 4)
							<span class="badge badge-pill badge-success bg-success">Diterbitkan</span>
						@elseif($payload['avsklStatus'] >= 5)
							<span class="badge badge-pill badge-danger bg-danger">Tidak Disetujui</span>
						@endif
					</span>
				</div>
				<p class="small">Rekomendasi Penerbitan SKL kepada Pimpinan</p>
				<ul class="list-group list-group-flush mt-2">
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>Tanggal direkomendasikan</span>
						<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avsklRecomendAt'])->locale('id')->translatedFormat('F j, Y') }}</span>
					</li>
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>Catatan Rekomendasi</span>
						<p class=fw-semibold>{{$payload['avsklRecomendNote']}}</p>
					</li>
					@if($payload['avsklApprovedAt'])
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>Tanggal Disetujui</span>
						<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avsklApprovedAt'])->locale('id')->translatedFormat('F j, Y') }}</span>
					</li>
					@endif
					@if($payload['avsklPublishedAt'])
					<li class="d-flex justify-content-between list-group-item">
						<span class=text-muted>Tanggal Diterbitkan</span>
						<span class="fw-semibold">{{ \Carbon\Carbon::parse($payload['avsklPublishedAt'])->locale('id')->translatedFormat('F j, Y') }}</span>
					</li>
					@endif
				</ul>
			</li>
		@endif
		<div class="pagebreak"></div>
		<li class="list-group-item">
			<div class="d-flex w-100 justify-content-between">
				<h6 class="mb-1 fw-500 fw-semibold" style="text-transform:uppercase">Riwayat Pengajuan Verifikasi</h6>
			</div>
			<p class="small">Pemeriksaan akhir atas permohonan penerbitan surat keterangan lunas</p>
			<table class="table table-bordered table-striped table-sm w-100" style="border: none; border-top:none; border-bottom:none;" id="tblVerifHistory">
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
		</li>
	</ul>
</div>
