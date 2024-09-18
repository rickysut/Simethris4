@extends('t2024.layouts.admin')
@section('content')
{{-- @include('t2024.partials.breadcrumb') --}}
@include('t2024.partials.subheader')
@include('t2024.partials.sysalert')
@can('spatial_data_access')
	<div class="row">
		<div class="col">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>
						Daftar <span class="fw-300"><i>Kelompok Tani</i></span>
					</h2>
					<div class="panel-toolbar">
						{{-- <div class="btn-group">
							<a href="{{route('2024.cpcl.poktan.create')}}" type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-offset="0,10" data-original-title="Tambah kelompok tani">
								<i class="fal fa-users"></i>
								Kelompok Tani Baru
							</a>
						</div> --}}
					</div>
				</div>
				<div class="card-header">
					<div class="panel-content">
						<h4 class="text-muted">Pencarian Kelompok Tani</h4>
						<div class="row">
							<div class="form-group col-md-3">
								<label for="idProv">Provinsi</label>
								<select name="idProv" id="idProv" class="custom-select form-control" aria-describedby="helpProv">
								</select>
								<small id="helpProv" class="text-muted">saring berdasarkan provinsi</small>
							</div>
							<div class="form-group col-md-3">
								<label for="idKab">Kabupaten</label>
								<select name="idKab" id="idKab" class="custom-select form-control" aria-describedby="helpKab">
								</select>
								<small id="helpKab" class="text-muted">saring berdasarkan kabupaten/kota</small>
							</div>
							<div class="form-group col-md-3">
								<label for="idKec">Kecamatan</label>
								<select name="idKec" id="idKec" class="custom-select form-control" aria-describedby="helpKec">
								</select>
								<small id="helpKec" class="text-muted">saring data berdasarkan kecamatan</small>
							</div>
							<div class="form-group col-md-3">
								<label for="status">Status</label>
								<select name="status" id="status" class="custom-select form-control" aria-describedby="helpStatus">
									<option value="" hidden>pilih status</option>
									@foreach ($indexStatus as $status)
										<option value="{{$status}}">{{$status}}</option>
									@endforeach
								</select>
								<small id="helpStatus" class="text-muted">saring data sesuai status</small>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-12">
								<label class="form-label">Cari data</label>
								<div class="input-group bg-white shadow-inset-2">
									<div class="input-group-prepend">
										<span class="input-group-text bg-transparent border-right-0 py-1 px-3 text-success">
											<i class="fal fa-search"></i>
										</span>
									</div>
									<input type="text" name="searchValue" id="searchValue" aria-describedby="searchValue" class="form-control border-left-0 bg-transparent pl-0" placeholder="kata kunci...">
									<div class="input-group-append">
										<button class="btn btn-default waves-effect waves-themed" type="button">Temukan</button>
									</div>
								</div>
								<small for="searchValue" class="text-muted">Temukan data berdasarkan kata kunci</small>
							</div>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<div></div>
							<div class="ml-auto">
								<button id="printPoktan" class="btn btn-primary">
									<span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    								<span id="buttonText">Cetak Daftar</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<!-- datatable start -->
						<table id="tblPoktan" class="table table-bordered table-hover table-sm table-striped w-100">
							<thead class="thead-themed">
								<th>Nama Kelompok</th>
								<th>Pimpinan</th>
								<th>Kontak</th>
								<th>Provinsi</th>
								<th>Kabupaten</th>
								<th>Kecamatan</th>
								<th>Desa</th>
								<th>Status</th>
								<th>Tindakan</th>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endcan

@endsection

<!-- start script for this page -->
@section('scripts')
@parent

<script>
	$(document).ready(function(){
		$('#printPoktan').on('click', function() {
    // Get the values from the form inputs or elements
    const provinsiId = $('#idProv').val();
    const kabupatenId = $('#idKab').val();
    const kecamatanId = $('#idKec').val();
    const status = $('#status').val();

    console.log('id :', provinsiId, kabupatenId, kecamatanId, status);

    // Create the URL with query parameters
    const url = `{{ route('2024.cpcl.poktan.renderPrintAllPoktan') }}`;

    // Show spinner and change button text
    $('#spinner').show();
    $('#buttonText').text('Mempersiapkan Unduhan');

    // Use AJAX to request the PDF
    $.ajax({
        url: url,
        method: 'GET',
        data: {
            provinsi_id: provinsiId,
            kabupaten_id: kabupatenId,
            kecamatan_id: kecamatanId,
            status: status
        },
        xhrFields: {
            responseType: 'blob'  // Important for binary file handling
        },
        success: function(data, status, xhr) {
            // Create a URL for the blob and initiate download
            const blob = new Blob([data], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'daftar_poktan.pdf';  // Specify the filename
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Show success message with Swal.fire
            Swal.fire({
                icon: 'success',
                title: 'Unduhan Selesai',
                text: 'Berkas PDF berhasil dibuat dan diunduh. Periksa direktori unduhan di perangkat Anda.'
            });
        },
        error: function(xhr, status, error) {
            // Show error message with Swal.fire
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengunduh',
                text: 'Terjadi kesalahan saat mengunduh file: ' + error
            });
        },
        complete: function() {
            // Hide spinner and revert button text
            $('#spinner').hide();
            $('#buttonText').text('Cetak Daftar');
        }
    });
});


		var provinsiSelect = $('#idProv');
		var kabupatenSelect = $('#idKab');
		var kecamatanSelect = $('#idKec');

		provinsiSelect.empty().append($('<option>', { value: '', text: '-- Pilih Provinsi --' }));

		$("#idProv").select2({
			placeholder: "-- pilih provinsi"
		});
		$("#idKab").select2({
			placeholder: "-- pilih kabupaten"
		});
		$("#idKec").select2({
			placeholder: "-- pilih kecamatan"
		});

		$.get('/2024/datafeeder/getAllProvinsi', function(response) {
			var data = response.data;

			if (data && data.length) {
				$.each(data, function(index, value) {
					var option = $('<option>', {
						value: value.provinsi_id,
						text: value.nama
					});

					provinsiSelect.append(option);
				});

				// Memuat kabupaten jika provinsi sudah dipilih
				var initialProvinsiId = provinsiSelect.val();
				if (initialProvinsiId) {
					loadKabupaten(initialProvinsiId);
				}
			} else {
				provinsiSelect.append($('<option>', { value: '', text: 'Data provinsi tidak tersedia' }));
			}
		});

		provinsiSelect.change(function() {
			var provinsiId = $(this).val();
			loadKabupaten(provinsiId);
		});

		// Load kecamatan when a kabupaten is selected
		kabupatenSelect.change(function() {
			var kabupatenId = $(this).val();
			loadKecamatan(kabupatenId);
		});

		function loadKabupaten(provinsiId) {
			kabupatenSelect.empty().append($('<option>', { value: '', text: '-- Pilih Kabupaten --' }));
			kecamatanSelect.empty().append($('<option>', { value: '', text: '-- Pilih Kecamatan --' }));

			if (provinsiId) {
				$.get('/2024/datafeeder/getKabByProv/' + provinsiId, function(response) {
					var data = response.data;

					if (data && data.length) {
						$.each(data, function(index, value) {
							var option = $('<option>', {
								value: value.kabupaten_id,
								text: value.nama_kab
							});

							kabupatenSelect.append(option);
						});

						// Memuat kecamatan jika kabupaten sudah dipilih
						var initialKabupatenId = kabupatenSelect.val();
						if (initialKabupatenId) {
							loadKecamatan(initialKabupatenId);
						}
					} else {
						kabupatenSelect.append($('<option>', { value: '', text: 'Data kabupaten tidak tersedia' }));
					}
				});
			}
		}

		// Fungsi untuk memuat kecamatan berdasarkan kabupaten
		function loadKecamatan(kabupatenId) {
			kecamatanSelect.empty().append($('<option>', { value: '', text: '-- Pilih Kecamatan --' }));

			if (kabupatenId) {
				$.get('/2024/datafeeder/getKecByKab/' + kabupatenId, function(response) {
					var data = response.data;

					if (data && data.length) {
						$.each(data, function(index, value) {
							var option = $('<option>', {
								value: value.kecamatan_id,
								text: value.nama_kecamatan
							});

							kecamatanSelect.append(option);
						});

						// Memuat desa jika kecamatan sudah dipilih
						var initialKecamatanId = kecamatanSelect.val();
					} else {
						kecamatanSelect.append($('<option>', { value: '', text: 'Data kecamatan tidak tersedia' }));
					}
				});
			}
		}

		var table = $('#tblPoktan').DataTable({
			responsive: true,
			lengthChange: true,
			lengthMenu: [10, 25, 50, 100],
			paging: true,
			ordering: true,
			processing: true,
			serverSide: true,
			language: {
				"processing": "Sedang memproses...",
				"lengthMenu": "Tampilkan _MENU_ entri",
				"zeroRecords": "Tidak ditemukan data yang sesuai",
				"info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
				"infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
				"infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
				"search": "Cari:",
				"paginate": {
					"first": "Pertama",
					"last": "Terakhir",
					"next": "Berikutnya",
					"previous": "Sebelumnya"
				},
				"emptyTable": "Tidak ada data di dalam tabel",
				"loadingRecords": "Sedang memuat...",
				"thousands": ".",
				"decimal": ",",
				"aria": {
					"sortAscending": ": aktifkan untuk mengurutkan kolom naik",
					"sortDescending": ": aktifkan untuk mengurutkan kolom turun"
				}
			},
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			ajax: {
				url: "{{ route('2024.datafeeder.getAllPoktan')}}",
				type: "GET",
				data: function(d) {
					d.provinsi_id = $('#idProv').val();
					d.kabupaten_id = $('#idKab').val();
					d.kecamatan_id = $('#idKec').val();
					d.status = $('#status').val();
					d.searchValue = $('#searchValue').val();
					console.log('intip: ', d);
				},
			},
			columns:[
				{data: 'nama_kelompok'},
				{data: 'nama_pimpinan'},
				{data: 'hp_pimpinan'},
				{data: 'nama_provinsi'},
				{data: 'nama_kabupaten'},
				{data: 'nama_kecamatan'},
				{data: 'nama_desa'},
				{data: 'status'},
				{
					data: 'id',
					render: function (data, type, row) {
						return `<a type="button" href="{{ route('2024.cpcl.poktan.edit', ':id') }}" class="btn btn-icon btn-default btn-xs"><i class="fal fa-pencil"></i></a>`
							.replace(':id', data);
					}
				}
			],
			buttons: [
				{
					extend: 'pdfHtml5',
					text: '<i class="fa fa-file-pdf"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Generate PDF',
					className: 'btn-outline-danger btn-sm btn-icon mr-1',
					exportOptions:
					{
						columns: [0,1,2,3,4,5],
					}
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1',
					exportOptions:
					{
						columns: [0,1,2,3,4,5]
					}
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					title: 'Daftar Realisasi Lokasi dan Pelaksana',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1',
					exportOptions:
					{
						columns: [0,1,2,3,4,5]
					}
				}
			],
		});
		$('#searchValue, #idProv, #idKab, #idKec, #status').change(function() {
			console.log('Prov: ', $('#idProv').val());
			console.log('Kab: ', $('#idKab').val());
			console.log('Kec: ', $('#idKec').val());
			table.draw();
		});
	});
</script>
@endsection
