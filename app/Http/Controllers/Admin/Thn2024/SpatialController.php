<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\ForeignApi;
use App\Models2024\MasterAnggota;
use App\Models2024\MasterSpatial;
use App\Models2024\PullRiph;
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
		$page_heading = 'Daftar Spatial Wajib Tanam';
		$heading_class = 'bi bi-globe-asia-australia';

		return view('t2024.spatial.index', compact('module_name', 'page_title', 'page_heading', 'heading_class'));
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
				'kml_url' => 'required|file|mimes:kml,xml,application/vnd.google-earth.kml+xml|max:2048', // Maksimum ukuran file 2MB
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

			MasterSpatial::updateOrCreate(
				['kode_spatial' => $request->input('kode_spatial')],
				[
					'komoditas' => $request->input('komoditas'),
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
					'nama_petugas' => $request->input('nama_petugas'),
					'tgl_peta' => $request->input('tgl_peta'),
					'tgl_tanam' => $request->input('tgl_tanam'),
					'kml_url' => $filePath,
				]
			);

			MasterAnggota::updateOrCreate(
				[
					'ktp_petani' => $request->input('ktp_petani'),
				],
				[
					'nama_petani' => $request->input('nama_petani'),
				],
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
		$validated = $request->validate([
			'status' => 'required|integer',
		]);

		$spatial = MasterSpatial::where('kode_spatial', $kodeSpatial)->first();

		if ($spatial) {
			$spatial->status = $validated['status'];
			$spatial->save();

			return response()->json(['success' => true]);
		} else {
			return response()->json(['success' => false, 'message' => 'Spatial data not found.'], 404);
		}
	}

	public function simulatorJarak (Request $request)
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
