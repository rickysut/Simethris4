<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\ForeignApi;
use App\Models2024\MasterAnggota;
use App\Models2024\MasterPoktan;
use App\Models2024\MasterSpatial;
use App\Models2024\PullRiph;
use App\Models\MasterKabupaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpatialController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module_name = 'Spatial';
		$page_title = 'Data Spatial';
		$page_heading = 'Peta Lahan Wajib Tanam Produksi Bawang Putih';
		$heading_class = 'bi bi-globe-asia-australia';

		$ijins = PullRiph::select('no_ijin')->get();

		$kabupatens = MasterSpatial::distinct()->pluck('kabupaten_id');

		//output array kabupaten
		$indexKabupaten = MasterKabupaten::whereIn('kabupaten_id', $kabupatens)
			->select('kabupaten_id', 'nama_kab')
			->get()
			->toArray();

		$mapkey = ForeignApi::find(1);

		$summary = MasterSpatial::selectRaw(
			'SUM(luas_lahan) AS total_luas,
			COUNT(*) AS total_lahan,

			SUM(CASE WHEN is_active = 1 THEN luas_lahan ELSE 0 END) AS total_lahan_aktif,
			SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS jml_lahan_aktif,

			SUM(CASE WHEN status = 1 AND is_active = 1 THEN luas_lahan ELSE 0 END) AS total_lahan_mitra,
			SUM(CASE WHEN status = 1 AND is_active = 1 THEN 1 ELSE 0 END) AS jml_lahan_mitra'
		)->first();

		// Menghitung luas dan jumlah lahan tidak aktif
		$totalLuas = $summary->total_luas;
		$jmlLahan = $summary->total_lahan;

		$totalLahanAktif = $summary->total_lahan_aktif;
		$jmlLahanAktif = $summary->jml_lahan_aktif;

		$totalLahanMitra = $summary->total_lahan_mitra;
		$jmlLahanMitra = $summary->jml_lahan_mitra;

		// Menghitung luas dan jumlah lahan tersedia
		$luasTersedia = $totalLahanAktif - $totalLahanMitra;
		$jmlTersedia = $jmlLahanAktif - $jmlLahanMitra;

		$data = [
			'totalLuas' => $totalLuas,
			'jmlLahan' => $jmlLahan,
			'totalLahanAktif' => $totalLahanAktif,
			'jmlLahanAktif' => $jmlLahanAktif,
			'totalLahanMitra' => $totalLahanMitra,
			'jmlLahanMitra' => $jmlLahanMitra,
			'luasTersedia' => $luasTersedia,
			'jmlTersedia' => $jmlTersedia,
		];

		return view('t2024.spatial.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'ijins', 'indexKabupaten', 'data'));
	}


	public function spatialList()
	{
		$module_name = 'Spatial';
		$page_title = 'Data Spatial';
		$page_heading = 'Daftar Lahan Wajib Tanam Produksi Bawang Putih';
		$heading_class = 'bi bi-globe-asia-australia';

		$ijins = PullRiph::select('no_ijin')->get();

		$kabupatens = MasterSpatial::distinct()->pluck('kabupaten_id');

		//output array kabupaten
		$indexKabupaten = MasterKabupaten::whereIn('kabupaten_id', $kabupatens)->select('kabupaten_id', 'nama_kab')->get()->toArray();

		$mapkey = ForeignApi::find(1);

		return view('t2024.spatial.spatialList', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'ijins', 'indexKabupaten'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createsingle()
	{
		$module_name = 'Spatial';
		$page_title = 'Data Spatial';
		$page_heading = 'Buat Peta Lokasi Tanam Baru';
		$heading_class = 'fal fa-map-marked-alt';
		$mapkey = ForeignApi::find(1);

		return view('t2024.spatial.createsingle', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey'));
	}

	public function storesingle(Request $request)
	{
		DB::beginTransaction();

		try {
			$request->validate([
				'kml_url' => 'required|file|mimes:kml,xml,application/vnd.google-earth.kml+xml|max:2048',
			]);

			if ($request->hasFile('kml_url')) {
				$file = $request->file('kml_url');
				$kdLokasi = $request->input('kode_spatial');

				$filename = $kdLokasi . '_' . time() . '.' . $file->getClientOriginalExtension();
				$path = 'uploads/kml';
				$filePath = $file->storeAs($path, $filename, 'public');
			} else {
				throw new \Exception('File not found');
			}

			$kelurahanId = $request->input('kelurahan_id');
			$namaKelompok = $request->input('poktan_name');
			$hashedKodePoktan = md5($kelurahanId . $namaKelompok);
			$kodePoktan = 'poktan_' . $hashedKodePoktan;

			$masterPoktan = MasterPoktan::updateOrCreate(
				[
					'kelurahan_id' => $request->input('kelurahan_id'),
					'nama_kelompok' => $request->input('poktan_name'),
				],
				[
					'kode_poktan' => $kodePoktan,
					'provinsi_id' => $request->input('provinsi_id'),
					'kabupaten_id' => $request->input('kabupaten_id'),
					'kecamatan_id' => $request->input('kecamatan_id'),
					'kelurahan_id' => $request->input('kelurahan_id'),
				],
			);

			MasterAnggota::updateOrCreate(
				[
					'ktp_petani' => $request->input('ktp_petani'),
				],
				[
					'kode_poktan' => $masterPoktan->kode_poktan,
					'nama_petani' => $request->input('nama_petani'),
					'provinsi_id' => $request->input('provinsi_id'),
					'kabupaten_id' => $request->input('kabupaten_id'),
					'kecamatan_id' => $request->input('kecamatan_id'),
					'kelurahan_id' => $request->input('kelurahan_id'),
				],
			);

			MasterSpatial::updateOrCreate(
				['kode_spatial' => $request->input('kode_spatial')],
				[
					'kode_poktan' => $masterPoktan->kode_poktan,
					'ktp_petani' => $request->input('ktp_petani'),
					'nama_petani' => $request->input('nama_petani'),
					'latitude' => $request->input('latitude'),
					'longitude' => $request->input('longitude'),
					'ktp_petani' => $request->input('ktp_petani'),
					'nama_petani' => $request->input('nama_petani'),
					'latitude' => $request->input('latitude'),
					'longitude' => $request->input('longitude'),
					'polygon' => $request->input('polygon'),
					'altitude' => $request->input('altitude'),
					'luas_lahan' => $request->input('luas_lahan'),
					'nama_lahan' => $request->input('nama_lahan'),
					'provinsi_id' => $request->input('provinsi_id'),
					'kabupaten_id' => $request->input('kabupaten_id'),
					'kecamatan_id' => $request->input('kecamatan_id'),
					'kelurahan_id' => $request->input('kelurahan_id'),
					'status' => 0,
					'kml_url' => $filePath,
				]
			);

			DB::commit();
			return redirect()->route('2024.spatial.index')->with('success', 'Data successfully saved.');
		} catch (\Exception $e) {

			DB::rollBack();

			if (isset($filePath)) {
				Storage::disk('public')->delete($filePath);
			}

			return redirect()->back()->with('error', $e->getMessage());
		}
	}

	public function show($id)
	{
		$module_name = 'Spatial';
		$page_title = 'Data Spatial';
		$page_heading = 'Data Lokasi Tanam';
		$heading_class = 'fal fa-map-marked-alt';

		$kode = substr_replace($id, '_', 3, 0);
		$spatial = MasterSpatial::where('kode_spatial', $kode)
			->with(['provinsi', 'kabupaten', 'kecamatan', 'desa'])
			->first();

		$mapkey = ForeignApi::find(1);
		return view('t2024.spatial.edit', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'spatial'));
	}

	public function updatesingle(Request $request)
	{
		DB::beginTransaction();

		try {

			MasterSpatial::updateOrCreate(
				['kode_spatial' => $request->input('kode_spatial')],
				[
					'komoditas' => $request->input('komoditas'),
					'nama_petugas' => $request->input('nama_petugas'),
					'tgl_peta' => $request->input('tgl_peta'),
					'tgl_tanam' => $request->input('tgl_tanam'),
				]
			);

			DB::commit();
			return redirect()->route('2024.spatial.index')->with('success', 'Data successfully saved.');
		} catch (\Exception $e) {

			DB::rollBack();

			return redirect()->back()->with('error', $e->getMessage());
		}
	}

	public function updateStatus(Request $request, $kodeSpatial)
	{
		// Validate the request data
		$validated = $request->validate([
			'status' => 'required|integer',
		]);

		try {
			// Find the spatial record by kode_spatial
			$spatial = MasterSpatial::where('kode_spatial', $kodeSpatial)->first();

			if (!$spatial) {
				return response()->json([
					'success' => false,
					'message' => 'Spatial data not found.'
				], 404);
			}

			// Check if is_active is set to 1
			if ($spatial->is_active !== 1) {
				return response()->json([
					'success' => false,
					'message' => 'Status Lahan harus diaktifkan terlebih dahulu.'
				], 400);
			}

			// Update the status
			$spatial->status = $validated['status'];
			$spatial->save();

			return response()->json(['success' => true]);
		} catch (\Exception $e) {
			// Handle any unexpected errors
			return response()->json([
				'success' => false,
				'message' => 'An error occurred: ' . $e->getMessage()
			], 500);
		}
	}


	public function updateActive(Request $request, $kodeSpatial)
	{
		// Validate the request data
		$validated = $request->validate([
			'activeStatus' => 'required|integer',
		]);

		try {
			// Find the spatial record by kode_spatial
			$spatial = MasterSpatial::where('kode_spatial', $kodeSpatial)->first();

			if (!$spatial) {
				return response()->json([
					'success' => false,
					'message' => 'Spatial data not found.'
				], 404);
			}

			// Check if status is 1
			if ($spatial->status === 1) {
				return response()->json([
					'success' => false,
					'message' => 'Lahan berstatus Bermitra, tidak dapat di blokir.'
				], 400);
			}

			// Update the is_active status
			$spatial->is_active = $validated['activeStatus'];
			$spatial->save();

			return response()->json(['success' => true]);

		} catch (\Exception $e) {
			// Handle any unexpected errors
			return response()->json([
				'success' => false,
				'message' => 'An error occurred: ' . $e->getMessage()
			], 500);
		}
	}

	public function batchUpdateStatus(Request $request)
	{
		// Validasi input
		$validated = $request->validate([
			'status' => 'required|integer',
			'kode_spatial' => 'required|array',
			'kode_spatial.*' => 'required|string', // atau sesuaikan dengan tipe data kode_spatial Anda
		]);

		$status = $validated['status'];
		$kodeSpatialList = $validated['kode_spatial'];

		DB::beginTransaction();

		try {
			foreach ($kodeSpatialList as $kodeSpatial) {
				// Lakukan update status untuk setiap kode_spatial
				$spatial = MasterSpatial::where('kode_spatial', $kodeSpatial)->first();

				if ($spatial) {
					$spatial->status = $status;
					$spatial->save();
				}
			}

			DB::commit();

			return response()->json(['success' => true]);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['success' => false, 'message' => 'Failed to update spatial data.', 'error' => $e->getMessage()], 500);
		}
	}


	public function simulatorJarak(Request $request)
	{
		$module_name = 'Spatial';
		$page_title = 'Simulator Spatial';
		$page_heading = 'Marker Finder Simulator';
		$heading_class = 'fal fa-map-marked-alt';

		$ijins = PullRiph::select('no_ijin')->get();

		$mapkey = ForeignApi::find(1);
		return view('t2024.spatial.simulator', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'ijins'));
	}

	public function getspatial(Request $request)
	{
		//soap call dengan email dan passwoord
		//cek role
		//abort jika fail,, return response dengan json
		$validated = $request->validate([
			'status' => 'required|integer'
		]);



		$status = 1; // status: All = findAll; status = 1 where status = 1; status = 2 where status = 2;

		if ($status == 'All') {
			$spatials = MasterSpatial::select('kode_spatial', 'ktp_petani', 'nama_petani', 'poktan_id', 'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'luas_lahan', 'status')
				->get();
		} else {
			$spatials = MasterSpatial::select('kode_spatial', 'ktp_petani', 'nama_petani', 'poktan_id', 'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'luas_lahan', 'status')
				->where('status', $status)
				->get();
		}

		return response()->json($spatials);
	}
}
