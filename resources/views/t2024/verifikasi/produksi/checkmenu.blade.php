<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="collapse navbar-collapse" id="navbarText">
		<ul class="navbar-nav mr-auto">
			@if($verifikasi->status > 0)
				<li class="nav-item {{ request()->is('2024/verifikator/produksi/check/berkas*') ? 'active bg-warning rounded' : '' }}">
					<a class="nav-link"
						@if(request()->is('2024/verifikator/produksi/check/berkas*'))
						@else
							href="{{ route('2024.verifikator.produksi.check', [$ijin, $tcode]) }}"
						@endif>
						Berkas-Berkas
						<span class="sr-only">(current)</span>
					</a>
				</li>
			@endif
			@if($verifikasi->status > 1)
				<li class="nav-item {{ request()->is('2024/verifikator/produksi/check/pks*') ? 'active bg-warning rounded' : '' }}">
					<a class="nav-link"
						@if(request()->is('2024/verifikator/produksi/check/pks*'))
						@else
							href="{{ route('2024.verifikator.produksi.checkpks', [$ijin, $tcode]) }}"
						@endif>
						Perjanjian Kerjasama
					</a>
				</li>
			@endif
			@if($verifikasi->status > 2)
				<li class="nav-item {{ request()->is('2024/verifikator/produksi/check/timeline*') ? 'active bg-warning rounded' : '' }}">
					<a class="nav-link"
						@if(request()->is('2024/verifikator/produksi/check/timeline*'))
						@else
							href="{{ route('2024.verifikator.produksi.checktimeline', [$ijin, $tcode]) }}"
						@endif>
						Timeline Realisasi
					</a>
				</li>
			@endif
			@if($verifikasi->status > 3)
				<li class="nav-item {{ request()->is('2024/verifikator/produksi/check/lokasi*') ? 'active bg-warning rounded' : '' }}">
					<a class="nav-link"
						@if(request()->is('2024/verifikator/produksi/check/lokasi*'))
						@else
							href="{{ route('2024.verifikator.produksi.checkdaftarlokasi', [$ijin, $tcode]) }}"
						@endif>
						Lokasi Tanam
					</a>
				</li>
			@endif
			@if($verifikasi->status > 4)
				<li class="nav-item {{ request()->is('2024/verifikator/produksi/check/final*') ? 'active bg-warning rounded' : '' }}">
					<a class="nav-link"
						@if(request()->is('2024/verifikator/produksi/check/final*'))
						@else
							href="{{ route('2024.verifikator.produksi.checkfinal', [$ijin, $tcode]) }}"
						@endif>
						Hasil Pemeriksaan
					</a>
				</li>
			@endif
		</ul>
		<span class="navbar-text">
			{{-- Navbar text with an inline element --}}
		</span>
	</div>
</nav>