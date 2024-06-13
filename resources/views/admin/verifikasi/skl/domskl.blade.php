
<link rel="stylesheet" media="screen, print" href="{{asset('/css/smartadmin/page-invoice.css')}}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">


<div class="container" style="background-color: white !important;">
	<div data-size="A4">
		<div class="row">
			<div class="col-sm-12 mb-5">
				<img src="{{asset('/img/kopsto.png')}}" width="100%">
			</div>
		</div>
		<div class="row mb-5">
			<div class="col-sm-6 d-flex">
				<div class="table-responsive">
					<table class="table table-clean table-sm align-self-end">
						<tbody>
							<tr>
								<td>
									<strong>Nomor</strong>
								</td>
								<td>
									<span class="mr-1">: {{$skl->no_skl}}</span>
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
			</div>
			<div class="col-sm-6 ml-sm-auto">
				<div class="table-responsive">
					<table class="table table-sm table-clean text-right">
						<tbody>
							<tr>
								<td>
									<strong>{{ date('d F Y', strtotime($skl->published_date)) }}</strong>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row fs-xl">
			<div class="col-sm-12">
				Kepada Yth.<br>
				Pimpinan<br>
				<strong>
					<span class="keep-print-font">{{$commitment->datauser->company_name}}</span>
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
					<dt class="col-sm-9">: {{$commitment->user->data_user->company_name}}</dt>
					<dd class="col-sm-3">Nomor RIPH</dd>
					<dt class="col-sm-9">: {{$commitment->no_ijin}}</dt>
					<dd class="col-sm-3">Komitmen Wajib Tanam</dd>
					<dt class="col-sm-9">
						<dl class="row">
							<dd class="col-sm-3">Komitmen</dd>
							<dt class="col-sm-9">: {{ number_format($commitment->volume_riph * 0.05 / 6, 2, '.', ',') }} ha;</dt>
							<dd class="col-sm-3">Realisasi</dd>
							<dt class="col-sm-9">: {{ number_format($total_luas, 2, '.', ',') }} ha.</dt>
							<dd class="col-sm-3">Verifikasi</dd>
							<dt class="col-sm-9">: {{number_format($pengajuan->luas_verif,2,'.',',')}} ha.</dt>
						</dl>
					</dt>
					<dd class="col-sm-3">Wajib Produksi</dd>
					<dt class="col-sm-9">
						<dl class="row">
							<dd class="col-sm-3">Komitmen</dd>
							<dt class="col-sm-9">: {{ number_format($commitment->volume_riph * 0.05, 2, '.', ',') }} ton;</dt>
							<dd class="col-sm-3">Realisasi</dd>
							<dt class="col-sm-9">: {{ number_format($total_volume, 2, '.', ',') }} ton.</dt>
							<dd class="col-sm-3">Verifikasi</dd>
							<dt class="col-sm-9">: {{number_format($pengajuan->volume_verif,2,'.',',')}} ton.</dt>
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
				<dl class="row mt-5 align-items-center">
					<dd class="col-sm-7">
						{{$QrCode}}
					</dd>
					<dd class="col-sm-5">
						{{-- <div class="text-dark" style="background-image: url('{{ asset('storage/uploads/dataadmin/'.$pejabat->dataadmin->sign_img) }}'); background-size: 50%;background-repeat: no-repeat;"> --}}
							Direktur,
							<br>
								<img style="max-width: 7em" src="{{ asset('storage/uploads/dataadmin/'.$pejabat->dataadmin->sign_img) }}" alt="ttd">
								<br>
							<u><strong>{{$pejabat->dataadmin->nama ?? ''}}</strong></u><br>
							<span class="mr-1">NIP.</span>{{$pejabat->dataadmin->nip ??''}}
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
