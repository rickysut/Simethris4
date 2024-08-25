<script>
	$(document).ready(function() {
		$('#attchCheck').dataTable({
			responsive: true,
			lengthChange: false,
			ordering: false,
			searching: false,
		});
		var noIjin = '{{$ijin}}';
		var tcode = '{{$verifikasi->tcode}}';
		var formattedNoIjin = noIjin.replace(/[\/.]/g, '');

		$(document).on('change', '.saveCheckBerkas', function() {
			var form = $(this).closest('form');
			var formData = form.serialize();
			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				data: formData,
				success: function(response) {
					console.log('sukses');
				},
				error: function(xhr, status, error) {
					alert('An error occurred while updating: ' + xhr.responseText);
				}
			});
		});

		function formatDate(dateString) {
			if (!dateString) return '';
			var date = new Date(dateString);
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			return new Intl.DateTimeFormat('id-ID', options).format(date);
		}
		$.ajax({
			url: "{{ route('2024.datafeeder.getVerifProduksiByIjin', [':noIjin']) }}".replace(':noIjin', noIjin),
			type: "GET",
			success: function(response) {
				$('#companytitle').text(response.data.perusahaan);
				$('#no_ijin').text(response.data.no_ijin);
				$('#tgl_ijin').text(response.data.tgl_ijin);
				$('#tgl_akhir').text(response.data.tgl_akhir);
				$('#created_at').text(response.data.created_at);
				$('#jml_anggota').text(response.data.countAnggota + ' orang');

				var kemitraan = response.data.countPks + ' berkas / ' + response.data.countPoktan + ' kelompok';
				if (response.data.countPoktan / response.data.countPks == 1){
					$('#countPks').html('<span class="text-success">' + kemitraan + '</span>');
				}else{
					$('#countPks').html('<span class="text-danger">' + kemitraan + '</span>');
				}

				var realisasitanam = response.data.sumLuasTanam + ' / ' + response.data.sumLuas + ' ha';
				if (response.data.sumLuasTanam / response.data.sumLuas < 1){
					$('#luas_tanam').html('<span class="text-danger">' + realisasitanam + '</span>');
				}else{
					$('#luas_tanam').html('<span class="text-success">' + realisasitanam + '</span>');
				}

				var realisasipanen = response.data.sumPanen + ' / ' + response.data.sumWajibVol + ' ton';
				if (response.data.sumPanen / response.data.sumWajibVol < 1){
					$('#volume_panen').html('<span class="text-danger">' + realisasipanen + '</span>');
				}else{
					$('#volume_panen').html('<span class="text-success">' + realisasipanen + '</span>');
				}

				var lokasiTanam = response.data.countTanam + ' / ' + response.data.countSpatial + ' titik';
				if (response.data.countTanam / response.data.countSpatial < 1){
					$('#jml_titik').html('<span class="text-danger">' + lokasiTanam + '</span>');
				}else{
					$('#jml_titik').html('<span class="text-success">' + lokasiTanam + '</span>');
				}

				var logoUrl = response.data.logo;
				$('#companyLogo').css('background-image', 'url(' + logoUrl + ')');
			}
		});

		//menandai fase/tahap pemeriksaan
		$(document).on('click', '.btnStatus', function() {
			var status = $(this).data('status');
			var url = '{{ route("2024.verifikator.produksi.markStatus", [":noIjin", ":tcode", ":status"]) }}'
				.replace(':noIjin', noIjin)
				.replace(':tcode', tcode)
				.replace(':status', status);

			$.ajax({
				url: url,
				type: 'POST',
				data: {
					_token: '{{ csrf_token() }}'
				},
				success: function(response) {
					console.log('Response Status:', response.status);
					var statusText;
					var statusInt = parseInt(response.status);
					switch (statusInt) {
						case 2:
							statusText = 'BERKAS-BERKAS';
							break;
						case 3:
							statusText = 'PKS';
							break;
						case 4:
							statusText = 'TIMELINE REALISASI';
							break;
						case 5:
							statusText = 'LOKASI TANAM';
							break;
						default:
							statusText = 'Unknown Status';
							break;
					}

					Swal.fire({
						icon: 'success',
						title: 'Progress Pemeriksaan',
						text: 'Status pemeriksaan ' + statusText + ' ditandai SELESAI',
					}).then((result) => {
						if (result.isConfirmed) {

							$('#lokasiCheck').DataTable().ajax.reload();
						}
					});
				},
				error: function(xhr, status, error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'There was an error updating the status.',
					});
				}
			});
		});
	});
</script>
