<div class="pagebreak"></div>
<style>
	.border-line {
		border-top: 3px double #335382;
		margin-top: 10px;
	}
	.table-clean {
		background: transparent;
		border: none;
	}

	.table-clean tr,
	.table-clean td,
	.table-clean th {
		border: none;
		background: none;
	}
</style>
<div class="container">
	<div class="d-flex justify-content-center align-items-center" style="margin-top: 1cm">
		<div class="">
			<img src="{{asset('/img/koplogo.svg')}}" width="100px">
		</div>
		<div class="ms-2 text-center" style="color: #335382">
			<span class="fw-bold fw-500 text-center" style="font-size: 17pt; line-height: 17px">KEMENTERIAN PERTANIAN</span> <br>
			<span class="fw-bold fw-500 text-center" style="font-size: 18pt; line-height: 18px">DIREKTORAT JENDERAL HORTIKULTURA</span> <br>
			<span class="fw-bold fw-500 text-center" style="font-size: 11pt; line-height: 12px">JALAN AUP NOMOR 3 PASAR MINGGU JAKARTA SELATAN 12520</span> <br>
			<span class="fw-semibold text-center" style="font-size: 11pt; line-height: 12px">TELP/FAXIMILI : (021) 780665 - 7817611</span> <br>
			<span class="fw-semibold text-center" style="font-size: 11pt; line-height: 12px">WEBSITE : ditsayur.hortikultura.pertanian.go.id</span> <br>
			<span class="fw-semibold text-center" style="font-size: 11pt; line-height: 12px">EMAIL : ditsayurobat@pertanian.go.id</span> <br>

		</div>
	</div>
	{{-- garis-garis --}}
	<div class="border-line mt-2 mb-3"></div>
	<div class="row d-flex justify-content-between align-items-start">
		<div class="col-6" style="font-size: 12pt">
			<table class="table table-clean table-sm align-self-end">
				<tbody>
					<tr>
						<td>
							<strong>Nomor</strong>
						</td>
						<td>
							<span class="mr-1">: {{$payload['avsklNum']}}</span>
						</td>
					</tr>
					<tr>
						<td>
							<strong>Lampiran</strong>
						</td>
						<td>
							: -
						</td>
					</tr>
					<tr>
						<td>
							<strong>Hal</strong>
						</td>
						<td>
							: Keterangan Telah Melaksanakan Wajib Tanam dan Wajib Produksi
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-6 text-end">
			<div class="table-responsive">
				<table class="table table-sm table-clean text-right">
					<tbody>
						<tr>
							<td>
								<span class="js-get-date fw-500">{{ \Carbon\Carbon::parse($payload['avsklApprovedAt'])->translatedFormat('d F Y') }}</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row fs-xl">
			<div class="col-sm-12">
				Kepada Yth.<br>
				Pimpinan<br>
				<strong>
					<span class="keep-print-font">{{$payload['company']}}</span>
				</strong><br>
				di<br>
				Tempat<br>
				<p class="justify-align-stretch mt-5">
					Berdasarkan hasil evaluasi dan validasi laporan realisasi tanam dan produksi, dengan ini kami menyatakan:
				</p>
			</div>
			<div class="col-12">
				<dl class="row">
					<dd class="col-sm-3">Nama Perusahaan</dd>
					<dt class="col-sm-9">: {{$payload['company']}}</dt>
					<dd class="col-sm-3">Nomor RIPH</dd>
					<dt class="col-sm-9">: {{$payload['noIjin']}}</dt>
					<dd class="col-sm-3">Komitmen Wajib Tanam</dd>
					<dt class="col-sm-9">
						<dl class="row">
							<dd class="col-sm-3">Komitmen</dd>
							<dt class="col-sm-9">: {{ number_format((float)$payload['wajibTanam'], 2, ',', '.') }} ha;</dt>
							<dd class="col-sm-3">Realisasi</dd>
							<dt class="col-sm-9">:  {{ number_format((float)$payload['realisasiTanam'], 2, ',', '.') }} ha;</dt>
							<dd class="col-sm-3">Verifikasi</dd>
							<dt class="col-sm-9">: SESUAI.</dt>
						</dl>
					</dt>
					<dd class="col-sm-3">Wajib Produksi</dd>
					<dt class="col-sm-9">
						<dl class="row">
							<dd class="col-sm-3">Komitmen</dd>
							<dt class="col-sm-9">:  {{ number_format((float)$payload['wajibProduksi'], 0, ',', '.') }} ton;</dt>
							<dd class="col-sm-3">Realisasi</dd>
							<dt class="col-sm-9">:  {{ number_format((float)$payload['realisasiProduksi'], 0, ',', '.') }} ton;</dt>
							<dd class="col-sm-3">Verifikasi</dd>
							<dt class="col-sm-9">: SESUAI.</dt>
						</dl>
					</dt>
				</dl>
			</div>
			<div class="col-12">
				<p class="justify-align-stretch">
					Telah melaksanakan kewajiban pengembangan bawang putih di dalam negeri sebagaimana ketentuan dalam Permentan 39 tahun 2019 dan perubahannya.
				</p>
				<p class="justify-align-stretch mt-3">
					Atas perhatian dan kerjasama Saudara disampaikan terima kasih.
				</p>
			</div>
			<div class="col-12">
				<dl class="row mt-3 align-items-center">
					<dd class="col-sm-7">
						{{$payload['QrCode']}}
					</dd>
					<dd class="col-sm-5">
							<span class="mb-5" style="height: 7em">Direktur,</span>
							<br><br><br>
								ttd
							<br>
							<u><strong>{{$payload['lastVSkl']->direktur->name}}</strong></u><br>
							<span class="mr-1">NIP. {{$payload['lastVSkl']->direktur->dataadmin->nip}}</span>
						</div>
					</dd>
				</dl>
				<div class="row">
					<div class="col-sm-12">
						<ul><u>Tembusan</u>
							<li>Direktur Jenderal Hortikultura</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row position-relative">
				<i class="position-absolute pos-right pos-bottom opacity-50 mb-n1 ml-n1" >dicetak pada: {{ now() }}</i>
			</div>
		</div>
	</div>
</div>
