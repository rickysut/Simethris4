
<div class="container mt-5">
	<p>Demikian Ringkasan Hasil Pemeriksaan ini dibuat dengan sebenar-benarnya.</p>
	<div class="col-md-4">
		<ul class="list-group list-group-flush mt-2">
			@foreach ( $payload['ajuTanam']->assignments as $assignment )
			<li class="d-flex justify-content-between list-group-item">
				<span class="fw-bold">{{$assignment->user->name}}</span>
				<span class="text-muted">ttd</span>
			</li>
			@endforeach
		</ul>
	</div>
</div>
