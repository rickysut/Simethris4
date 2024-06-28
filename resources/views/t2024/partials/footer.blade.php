<!-- BEGIN Page Footer -->
<footer class="page-footer shadow" style="height:10%" role="contentinfo">
	<div class="hidden-md-down d-flex align-items-center flex-1 text-muted">
		<span class="hidden-md-down fw-700">2019-<script>document.write(new Date().getFullYear())</script> © Smartadmin | by&nbsp;<a href='#' class='text-primary fw-500' title='Team Creative' target='_blank'>Team Creative</a></span>
	</div>
	<!--
		bagian ini disembunyikan saat tampil di layar kecil.
	-->
	<div class="hidden-md-down">
		<ul class="list-table m-0">
			<li><a href="" class="text-secondary fw-700">About</a></li>
			<li class="pl-3"><a href="" class="text-secondary fw-700">License</a></li>
			<li class="pl-3"><a href="" class="text-secondary fw-700">Documentation</a></li>
			<li class="pl-3 fs-xl"><a href="#" class="text-secondary" target="_blank"><i class="fal fa-question-circle" aria-hidden="true"></i></a></li>
		</ul>
	</div>
	<!--
		#sedikit perubahan untuk ditampilkan/disembunyikan pada mobile screen
		#perubahan: penambahan icon khusus untuk mobile.
			1. icon show menu
			2. icon go to home
			3. icon go to executive summary.

		#bagian ini disembunyikan saat tampil di layar besar.
	-->
	<div class="col hidden-lg-up">
		<div class="row d-flex justify-content-between align-items-center">
			<a href="javascript:void(0)" class="text-secondary press-scale-down">
				<i class="ni ni-menu fa-3x"></i>
			</a>
			<a href="{{route('2024.verifikator.mobile')}}" class="{{ request()->is('2024.verifikator.mobile') ? 'active' : '' }} fw-700"><i class="{{ request()->is('2024.verifikator.mobile') ? 'fal fa-home-alt' : 'fa fa-home' }} fa-3x"></i></a>
			<a href="javascript:void(0)" class="text-secondary fw-700" >
				<i class="fal fa-chart-line fa-3x"></i>
			</a>
		</div>
	</div>
</footer>
<!-- END Page Footer -->
