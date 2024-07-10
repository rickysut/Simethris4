<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifProduksi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\AjuVerifTanam;
use App\Models2024\DataRealisasi;
use App\Models2024\Lokasi;
use App\Models2024\MasterAnggota;
use App\Models2024\MasterPoktan;
use App\Models2024\MasterSpatial;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models\AjuVerifSkl as ModelsAjuVerifSkl;
use App\Models\MasterDesa;
use App\Models\MasterKecamatan;
use App\Models\UserDocs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Svg\Tag\Rect;

class DataFeederController extends Controller
{
	private function formatNoIjin($noIjin)
	{
		return substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);
	}

	public function getAllMyCommitment(Request $request)
	{
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);
		$npwp = Auth::user()->data_user->npwp_company;

		// Base query
		$query = PullRiph::select('id', 'no_ijin', 'periodetahun', 'tgl_ijin', 'volume_riph', 'luas_wajib_tanam', 'volume_produksi')
			->where('npwp', $npwp)
			->with(['skl', 'lokasi', 'pks', 'ajutanam', 'ajuproduksi', 'ajuskl', 'completed', 'userDocs']);

		// Apply search filter
		if (!empty($searchValue)) {
			$query = $query->where(function ($q) use ($searchValue) {
				$q->where('no_ijin', 'like', "%{$searchValue}%")
					->orWhere('periodetahun', 'like', "%{$searchValue}%")
					->orWhere('tgl_ijin', 'like', "%{$searchValue}%")
					->orWhere('volume_riph', 'like', "%{$searchValue}%")
					->orWhere('luas_wajib_tanam', 'like', "%{$searchValue}%")
					->orWhere('volume_produksi', 'like', "%{$searchValue}%");
			});
		}

		// Calculate total and filtered records
		$totalRecords = $query->count();
		$filteredRecords = $query->count();

		// Apply ordering
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];
			switch ($orderColumn) {
				case 'ijin_full':
					$query = $query->orderBy('no_ijin', $orderDirection);
					break;
				case 'periodetahun':
					$query = $query->orderBy('periodetahun', $orderDirection);
					break;
				default:
					$query = $query->orderBy('id', 'asc');
					break;
			}
		}

		// Apply pagination
		$data = $query->skip($start)->take($length)->get();

		// Process data
		foreach ($data as $commitment) {
			$commitment->sumRealisasiLuas = $commitment->lokasi->sum('luas_tanam');
			$commitment->sumRealisasiPanen = $commitment->lokasi->sum('volume');
			$commitment->pksCount = $commitment->pks->count();
			$commitment->pksFileCount = $commitment->pks->whereNotNull('berkas_pks')->count();

			$commitment->pksComplete = ($commitment->pksCount === $commitment->pksFileCount) ? 'Lengkap' : 'Belum Lengkap';

			$tanamDocsFields = ['spvt', 'sptjmtanam', 'rta', 'sphtanam', 'logbooktanam'];
			$produksiDocsFields = ['spvp', 'sptjmproduksi', 'rpo', 'sphproduksi', 'logbookproduksi', 'formLa'];

			$tanamDocsComplete = !array_filter($tanamDocsFields, function ($field) use ($commitment) {
				return empty($commitment->userDocs->$field);
			});
			$commitment->tanamDocs = $tanamDocsComplete ? "Lengkap" : "Belum Lengkap";

			$produksiDocsComplete = !array_filter($produksiDocsFields, function ($field) use ($commitment) {
				return empty($commitment->userDocs->$field);
			});
			$commitment->produksiDocs = $produksiDocsComplete ? "Lengkap" : "Belum Lengkap";

			$commitment->noIjin = str_replace(['/', '.'], "", $commitment->no_ijin);
			$commitment->avTanamStatus = $commitment->ajutanam()->exists() ? $commitment->ajutanam()->latest()->first()->status : "Tidak ada";
			$commitment->avProdStatus = $commitment->ajuproduksi()->exists() ? $commitment->ajuproduksi()->latest()->first()->status : "Tidak ada";
			$commitment->avSklStatus = $commitment->ajuskl()->exists() ? $commitment->ajuskl()->latest()->first()->status : "Tidak ada";
			$commitment->completeStatus = $commitment->completed()->exists() ? 'Lunas' : "Belum Lunas";

			$commitment->siapVerifTanam = (
				$commitment->sumRealisasiLuas === 0 ||
				$commitment->pksComplete !== 'Lengkap' ||
				$commitment->tanamDocs !== 'Lengkap') ? 'Belum Siap' : 'Siap';

			$commitment->siapVerifProduksi = ($commitment->volume_produksi > $commitment->sumRealisasiPanen || $commitment->pksComplete !== 'Lengkap' || $commitment->tanamDocs !== 'Lengkap' || $commitment->prduksiDocs !== 'Lengkap') ? 'Belum Siap' : 'Siap';

			$commitment->siapVerifSkl = ($commitment->pksComplete !== 'Lengkap' || $commitment->tanamDocs !== 'Lengkap' || $commitment->produksiDocs !== 'Lengkap' || $commitment->avProdStatus !== '4') ? 'Belum Siap' : 'Siap';
			// $commitment->siapVerifSkl = ( $commitment->avProdStatus !== '4') ? 'Belum Siap' : 'Siap';

		}

		// Map data to the required format
		$mappedData = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'ijin_full' => $item->no_ijin,
				'noIjin' => $item->noIjin,
				'periodetahun' => $item->periodetahun,
				'tgl_terbit' => $item->tgl_ijin,
				'volume' => $item->volume_riph,
				'wajib_tanam' => $item->luas_wajib_tanam,
				'wajib_produksi' => $item->volume_produksi,
				'sumRealisasiLuas' => $item->sumRealisasiLuas,
				'sumRealisasiPanen' => $item->sumRealisasiPanen,
				'pksComplete' => $item->pksComplete,
				'tanamDocs' => $item->tanamDocs,
				'siapVerifTanam' => $item->siapVerifTanam,
				'produksiDocs' => $item->produksiDocs,
				'siapVerifProduksi' => $item->siapVerifProduksi,
				'avTanamStatus' => $item->avTanamStatus,
				'avProdStatus' => $item->avProdStatus,
				'siapVerifSkl' => $item->siapVerifSkl,
				'avSklStatus' => $item->avSklStatus,
				'completeStatus' => $item->completeStatus,
			];
		});

		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $mappedData,
		]);
	}

	public function getPksById($tcode)
	{
		$pks = Pks::select('id', 'tcode', 'npwp', 'no_ijin', 'no_perjanjian', 'tgl_perjanjian_start', 'tgl_perjanjian_end', 'varietas_tanam', 'periode_tanam', 'berkas_pks')
			->with(['varietas' => function ($query) {
				$query->select('id', 'nama_varietas');
			}])
			->where('tcode', $tcode)->first();

		$commitment = PullRiph::where('no_ijin', $pks->no_ijin)->first();
		$npwp = str_replace(['.', '-'], '', $commitment->npwp);
		$periodetahun = $commitment->periodetahun;

		$linkBerkas = $pks->berkas_pks ? asset('storage/uploads/' . $npwp . '/' . $periodetahun . '/' . $pks->berkas_pks) : null;

		if ($pks) {
			$data = $pks->toArray();
			$data['linkBerkas'] = $linkBerkas;

			return response()->json($data);
		} else {
			return response()->json([], 404);
		}
	}

	public function timeline(Request $request, $noIjin)
	{
		// Format the noIjin parameter
		$noIjin = $this->formatNoIjin($noIjin);

		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		// Base query
		$query = Lokasi::select('id', 'kode_spatial', 'poktan_id', 'no_ijin', 'tgl_tanam', 'tgl_akhir_tanam', 'tgl_panen', 'tgl_akhir_panen')
			->where('no_ijin', $noIjin)
			->with([
				'pks' => function ($query) {
					$query->select('id', 'no_ijin', 'poktan_id', 'tgl_perjanjian_start', 'tgl_perjanjian_end');
				},
				'pullriph' => function ($query) {
					$query->select('id', 'no_ijin', 'tgl_ijin', 'tgl_akhir');
				}
			]);

		// Calculate total records
		$totalRecords = $query->count();

		// Apply search filter if any
		if (!empty($searchValue)) {
			$query = $query->where(function ($q) use ($searchValue) {
				$q->where('kode_spatial', 'like', "%{$searchValue}%")
					->orWhere('no_ijin', 'like', "%{$searchValue}%");
			});
		}

		// Calculate filtered records
		$filteredRecords = $query->count();

		// Apply order
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];

			switch ($orderColumn) {
				case 'kode_spatial':
				case 'no_ijin':
					$query = $query->orderBy($orderColumn, $orderDirection);
					break;
			}
		}

		// Apply pagination
		$lokasis = $query->skip($start)
			->take($length)
			->get();

		// Map data to the required format
		$data = $lokasis->map(function ($item) {
			return [
				'id' => $item->id,
				'kode_spatial' => $item->kode_spatial,
				'mulai_ijin' => $item->pullriph->tgl_ijin ?? '',
				'akhir_ijin' => $item->pullriph->tgl_akhir ?? '',
				'awal_pks' => $item->pks->tgl_perjanjian_start ?? '',
				'akhir_pks' => $item->pks->tgl_perjanjian_end ?? '',
				'awal_tanam' => $item->tgl_tanam,
				'akhir_tanam' => $item->tgl_akhir_tanam,
				'awal_panen' => $item->tgl_panen,
				'akhir_panen' => $item->tgl_akhir_panen
			];
		});

		// Return response in JSON format
		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $data,
		]);
	}

	public function getPksByIjin(Request $request, $noIjin)
	{
		$noIjin = $this->formatNoIjin($noIjin);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$query = Pks::query()
			->select('id', 'tcode', 'no_ijin', 'poktan_id', 'nama_poktan', 'no_perjanjian', 'tgl_perjanjian_start', 'tgl_perjanjian_end', 'varietas_tanam', 'periode_tanam', 'berkas_pks', 'status', 'note')
			->where('no_ijin', $commitment->no_ijin)
			->withCount('lokasi')
			->with(['lokasi' => function ($query) {
				$query->selectRaw('poktan_id, no_ijin, sum(luas_lahan) as total_luas_lahan')
					->groupBy('poktan_id', 'no_ijin');
			}]);

		// Paginasi sesuai permintaan DataTables
		$draw = $request->input('draw');
		$length = $request->input('length');
		$start = $request->input('start');

		if ($length <= 0) {
			$length = 10; // Atur ke nilai default jika length <= 0
		}

		$data = $query->paginate($length, ['*'], 'page', max(1, $start / $length + 1));
		$items = $data->items();
		foreach ($items as $item) {
			if (is_null($item->tgl_perjanjian_start) || is_null($item->varietas_tanam) || is_null($item->berkas_pks)) {
				$item->statusData = null;
			} else {
				$item->statusData = 'Filled'; // Atur status yang sesuai jika tidak null
			}

			$item->total_luas_lahan = $item->lokasi->sum('total_luas_lahan');
		}

		return response()->json([
			"draw" => intval($draw),
			"recordsTotal" => $data->total(),
			"recordsFiltered" => $data->total(),
			"data" => $data->items(),
		]);
	}

	public function getLokasiByPks(Request $request, $noIjin, $poktanId)
	{
		// Format the noIjin parameter
		$noIjin = $this->formatNoIjin($noIjin);

		$dataRealisasi = Lokasi::select('no_ijin', 'poktan_id', 'luas_tanam', 'volume')
			->where('no_ijin', $noIjin)
			->where('poktan_id', $poktanId)
			->get();

		$realisasiLuasTanam = $dataRealisasi->sum('luas_tanam');
		$realisasiProduksi = $dataRealisasi->sum('volume');

		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		// Base query
		$query = Lokasi::where('no_ijin', $noIjin)
			->where('poktan_id', $poktanId)
			->with('spatial');

		// Calculate total records
		$totalRecords = $query->count();

		// Apply search filter if any
		if (!empty($searchValue)) {
			$query = $query->where(function ($q) use ($searchValue) {
				$q->where('nama_petani', 'like', "%{$searchValue}%")
					->orWhere('ktp_petani', 'like', "%{$searchValue}%")
					->orWhere('kode_spatial', 'like', "%{$searchValue}%");
			});
		}

		// Calculate filtered records
		$filteredRecords = $query->count();

		// Apply order
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];

			switch ($orderColumn) {
				case 'nama_kelompok':
				case 'ktp_petani':
				case 'nama_petani':
				case 'kode_spatial':
				case 'spatial_ktp':
					$query = $query->orderBy($orderColumn, $orderDirection);
					break;
			}
		}

		// Apply pagination
		$pks = $query->skip($start)
			->take($length)
			->get();

		// Map data to the required format
		$data = $pks->map(function ($item) {
			$spatial = $item->spatial;
			return [
				'id' => $item->id,
				'nama_kelompok' => $item->nama_poktan,
				'kode_spatial' => $item->kode_spatial,
				'ktp_petani' => $item->ktp_petani,
				'nama_petani' => $item->nama_petani,
				'spatial_petani' => $spatial ? $spatial->nama_petani : null,
				'spatial_ktp' => $spatial ? $spatial->ktp_petani : null,
				'luas_tanam' => $item->luas_tanam,
				'tgl_tanam' => $item->tgl_tanam ? date('d-m-Y', strtotime($item->tgl_tanam)) : null,
				'volume_panen' => $item->volume,
				'tgl_panen' => $item->tgl_panen ? date('d-m-Y', strtotime($item->tgl_panen)) : null,
			];
		});

		// Return response in JSON format
		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'totalRealisasiLuas' => $realisasiLuasTanam,
			'totalRealisasiProduksi' => $realisasiProduksi,
			'data' => $data,
		]);
	}

	public function getLokasiByIjin(Request $request, $noIjin)
	{
		// Format the noIjin parameter
		$noIjin = $this->formatNoIjin($noIjin);

		$dataRealisasi = Lokasi::select('no_ijin', 'luas_tanam', 'volume')
			->where('no_ijin', $noIjin)
			->get();

		$realisasiLuasTanam = $dataRealisasi->sum('luas_tanam');
		$realisasiProduksi = $dataRealisasi->sum('volume');

		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		// Base query
		$query = Lokasi::where('no_ijin', $noIjin)
			->with(['spatial', 'masteranggota.masterpoktan']);

		// Calculate total records
		$totalRecords = $query->count();

		// Apply search filter if any
		if (!empty($searchValue)) {
			$query = $query->where(function ($q) use ($searchValue) {
				$q->where('nama_petani', 'like', "%{$searchValue}%")
					->orWhere('ktp_petani', 'like', "%{$searchValue}%")
					->orWhere('kode_spatial', 'like', "%{$searchValue}%");
			});
		}

		// Calculate filtered records
		$filteredRecords = $query->count();

		// Apply order
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];

			switch ($orderColumn) {
				case 'nama_kelompok':
				case 'ktp_petani':
				case 'nama_petani':
				case 'kode_spatial':
				case 'spatial_ktp':
					$query = $query->orderBy($orderColumn, $orderDirection);
					break;
			}
		}

		// Apply pagination
		$lokasis = $query->skip($start)
			->take($length)
			->get();

		// Map data to the required format
		$data = $lokasis->map(function ($item) {
			$spatial = $item->spatial;
			$masterAnggota = $item->masteranggota ? $item->masteranggota->first() : null;
			$nama_kelompok = $masterAnggota ? ($masterAnggota->masterpoktan ? $masterAnggota->masterpoktan->nama_kelompok : null) : null;
			return [
				'id' => $item->id,
				'nama_kelompok' => $item->nama_poktan,
				'kode_spatial' => $item->kode_spatial,
				'ktp_petani' => $item->ktp_petani,
				'nama_petani' => $item->nama_petani,
				'nama_kelompok' => $nama_kelompok,
				'spatial_petani' => $spatial ? $spatial->nama_petani : null,
				'spatial_ktp' => $spatial ? $spatial->ktp_petani : null,
				'luas_lahan' => $item->luas_lahan,
				'luas_tanam' => $item->luas_tanam,
				'tgl_tanam' => $item->tgl_tanam ? date('d-m-Y', strtotime($item->tgl_tanam)) : null,
				'volume_panen' => $item->volume,
				'tgl_panen' => $item->tgl_panen ? date('d-m-Y', strtotime($item->tgl_panen)) : null,
				'status' => $item->status,
			];
		});

		// Return response in JSON format
		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'totalRealisasiLuas' => $realisasiLuasTanam,
			'totalRealisasiProduksi' => $realisasiProduksi,
			'data' => $data,
		]);
	}

	public function getLokasiByIjinNik($noIjin, $nik)
	{
		$noIjin = $this->formatNoIjin($noIjin);

		// Base query
		$query = Lokasi::where('no_ijin', $noIjin)
			->where('ktp_petani', $nik)
			->with(['spatial', 'masteranggota.masterpoktan', 'fototanam', 'fotoproduksi'])
			->get();

		// Map data to the required format
		$data = $query->map(function ($item) {
			$spatial = $item->spatial;
			$masterAnggota = $item->masteranggota->first();
			$nama_kelompok = optional(optional($masterAnggota)->masterpoktan)->nama_kelompok;

			$fotoTanam = $item->fototanam->map(function ($foto) {
				return [
					'id' => $foto->id,
					'url' => $foto->filename,
				];
			});

			$fotoProduksi = $item->fotoproduksi->map(function ($foto) {
				return [
					'id' => $foto->id,
					'url' => $foto->filename,
				];
			});

			return [
				'id' => $item->id,
				'nama_kelompok' => $item->nama_poktan,
				'kode_spatial' => optional($spatial)->kode_spatial,
				'ktp_petani' => $item->ktp_petani,
				'nama_petani' => optional($item->masteranggota)->nama_petani,
				'nama_kelompok_poktan' => $nama_kelompok,
				'spatial_petani' => optional($spatial)->nama_petani,
				'spatial_ktp' => optional($spatial)->ktp_petani,
				'latitude' => optional($spatial)->latitude,
				'longitude' => optional($spatial)->longitude,
				'polygon' => optional($spatial)->polygon,
				'luas_lahan' => $item->luas_lahan,
				'luas_tanam' => $item->luas_tanam,
				'awal_tanam' => $item->tgl_tanam ? date('d-m-Y', strtotime($item->tgl_tanam)) : null,
				'akhir_tanam' => $item->tgl_akhir_tanam ? date('d-m-Y', strtotime($item->tgl_akhir_tanam)) : null,
				'volume_panen' => $item->volume,
				'tgl_panen' => $item->tgl_panen ? date('d-m-Y', strtotime($item->tgl_panen)) : null,
				'status' => $item->status,
				'fototanam' => $fotoTanam,
				'fotoProduksi' => $fotoProduksi,
			];
		});

		// Return response in JSON format
		return response()->json($data);
	}

	public function getSpatialByKecamatan(Request $request, $kecId)
	{
		$spatials = MasterSpatial::select('id', 'kode_spatial', 'kecamatan_id')->where('kecamatan_id', $kecId)->get();
		return response()->json($spatials);
	}

	public function getSpatialByKode(Request $request, $spatial)
	{
		$spatial = substr($spatial, 0, 3) . '-' .
			substr($spatial, 3, 3) . '-' .
			substr($spatial, 6, 4);

		$lokasi = MasterSpatial::select('id', 'kode_spatial', 'latitude', 'longitude', 'polygon', 'luas_lahan', 'ktp_petani')->where('kode_spatial', $spatial)->first();
		$anggota = MasterAnggota::select('id', 'ktp_petani', 'nama_petani')->where('ktp_petani', $lokasi->ktp_petani)->first();

		$data = [
			'lokasi_id' => $lokasi->id,
			'kode_spatial' => $lokasi->kode_spatial,
			'latitude' => $lokasi->latitude,
			'longitude' => $lokasi->longitude,
			'polygon' => $lokasi->polygon,
			'luas_lahan' => $lokasi->luas_lahan,
			'ktp_petani' => $lokasi->ktp_petani,
			'nama_petani' => $anggota->nama_petani,
		];

		return response()->json($data);
	}

	public function getAllSpatials(Request $request)
	{
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 100);
		$searchValue = $request->input('search.value', '');

		// $spatials = MasterSpatial::where('ktp_petani', '3324016204770001')->with('anggota')->get();
		// dd($spatials);

		$data = MasterSpatial::with([
				// 'anggota:id,poktan_id,nama_petani,ktp_petani',
				'provinsi:provinsi_id,nama',
				'kabupaten:kabupaten_id,nama_kab',
				'jadwal:kode_spatial,awal_masa,akhir_masa',
				'anggota:ktp_petani,nama_petani',
			])
			->get();

		$query = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'kode_spatial' => $item->kode_spatial,
				'luas_lahan' => $item->luas_lahan,
				'ktp_petani' => $item->ktp_petani,
				'nama_petani' => $item->nama_petani,
				'nama_anggota' => $item->anggota ? $item->anggota->nama_petani : null,
				'kml_url' => $item->kml_url,
				'provinsi_id' => $item->provinsi_id,
				'nama_provinsi' => $item->provinsi ? $item->provinsi->nama : null,
				'kabupaten_id' => $item->kabupaten_id,
				'nama_kabupaten' => $item->kabupaten ? $item->kabupaten->nama_kab : null,
				'kecamatan_id' => $item->kecamatan_id,
				'nama_kecamatan' => $item->kecamatan ? $item->kecamatan->nama_kecamatan : null,
				'kelurahan_id' => $item->kelurahan_id,
				'nama_desa' => $item->desa ? $item->desa->nama_desa : null,
				'status' => $item->status ? $item->status : null,
			];
		});

		if ($searchValue) {
			$query = $query->filter(function ($item) use ($searchValue) {
				return strpos(strtolower($item['kode_spatial']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['ktp_petani']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_anggota']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_provinsi']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_kabupaten']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_kecamatan']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_desa']), strtolower($searchValue)) !== false;
			});
		}

		if ($request->has('order')) {
			$orderColumn = $request->input('order')[0]['column'];
			$orderDirection = $request->input('order')[0]['dir'];
			$columnName = $request->input('columns')[$orderColumn]['data'];


			// Gunakan switch case atau if else untuk menentukan kolom pengurutan
			switch ($columnName) {
				case 'kode_spatial':
					$query = $query->sortBy('kode_spatial');
					break;
				case 'ktp_petani':
					$query = $query->sortByDesc('ktp_petani');
					break;
				case 'nama_anggota':
					$query = $query->sortByDesc('nama_anggota');
					break;
				case 'nama_provinsi':
					$query = $query->sortByDesc('nama_provinsi');
					break;
				case 'nama_kabupaten':
					$query = $query->sortByDesc('nama_kabupaten');
					break;
				case 'nama_kecamatan':
					$query = $query->sortByDesc('nama_kecamatan');
					break;
				case 'nama_desa':
					$query = $query->sortByDesc('nama_desa');
					break;
			}
		}

		$totalRecords = $data->count();
		$filteredRecords = $query->count();

		$spatials = $query->slice($start)->take($length)->values();

		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $spatials,
		]);
	}

	public function getAllPoktan(Request $request)
	{
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');

		$data = MasterPoktan::with([
			'provinsi',
			'kabupaten',
			'kecamatan',
			'desa',
		])->get();

		$query = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'kode_poktan' => $item->kode_poktan,
				'nama_kelompok' => $item->nama_kelompok,
				'nama_pimpinan' => $item->nama_pimpinan,
				'hp_pimpinan' => $item->hp_pimpinan,
				'provinsi_id' => $item->provinsi_id,
				'nama_provinsi' => $item->provinsi ? $item->provinsi->nama : null,
				'kabupaten_id' => $item->kabupaten_id,
				'nama_kabupaten' => $item->kabupaten ? $item->kabupaten->nama_kab : null,
				'kecamatan_id' => $item->kecamatan_id,
				'nama_kecamatan' => $item->kecamatan ? $item->kecamatan->nama_kecamatan : null,
				'kelurahan_id' => $item->kelurahan_id,
				'nama_desa' => $item->desa ? $item->desa->nama_desa : null,
			];
		});

		if ($searchValue) {
			$query = $query->filter(function ($item) use ($searchValue) {
				return strpos(strtolower($item['nama_kelompok']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_pimpinan']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['hp_pimpinan']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_provinsi']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_kabupaten']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_kecamatan']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_desa']), strtolower($searchValue)) !== false;
			});
		}

		if ($request->has('order')) {
			$orderColumn = $request->input('order')[0]['column'];
			$orderDirection = $request->input('order')[0]['dir'];
			$columnName = $request->input('columns')[$orderColumn]['data'];

			// Gunakan switch case atau if else untuk menentukan kolom pengurutan
			switch ($columnName) {
				case 'nama_kelompok':
					$query = $query->sortBy('nama_kelompok');
					break;
				case 'nama_pimpinan':
					$query = $query->sortByDesc('nama_pimpinan');
					break;
				case 'kontak':
					$query = $query->sortByDesc('kontak');
					break;
				case 'nama_provinsi':
					$query = $query->sortByDesc('nama_provinsi');
					break;
				case 'nama_kabupaten':
					$query = $query->sortByDesc('nama_kabupaten');
					break;
				case 'nama_kecamatan':
					$query = $query->sortByDesc('nama_kecamatan');
					break;
				case 'nama_desa':
					$query = $query->sortByDesc('nama_desa');
					break;
			}
		}

		$totalRecords = $data->count();
		$filteredRecords = $query->count();

		$poktans = $query->slice($start)->take($length)->values();

		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $poktans,
		]);
	}

	public function getAllCpcl(Request $request)
	{
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');

		$data = MasterAnggota::with([
			'provinsi' => function ($query) {
				$query->select('provinsi_id', 'nama');
			},
			'kabupaten' => function ($query) {
				$query->select('kabupaten_id', 'nama_kab');
			},
			'kecamatan' => function ($query) {
				$query->select('kecamatan_id', 'nama_kecamatan');
			},
			'desa' => function ($query) {
				$query->select('kelurahan_id', 'nama_desa');
			},
			'spatial'
		])->get();

		$query = $data->map(function ($item) {
			$spatialCount = $item->spatial->count();
			$spatialSum = $item->spatial->sum('luas_lahan');
			return [
				'id' => $item->id,
				'nama_petani' => $item->nama_petani,
				'ktp_petani' => $item->ktp_petani,
				'kontak' => $item->hp_petani,
				'provinsi_id' => $item->provinsi_id,
				'nama_provinsi' => $item->provinsi ? $item->provinsi->nama : null,
				'kabupaten_id' => $item->kabupaten_id,
				'nama_kabupaten' => $item->kabupaten ? $item->kabupaten->nama_kab : null,
				'kecamatan_id' => $item->kecamatan_id,
				'nama_kecamatan' => $item->kecamatan ? $item->kecamatan->nama_kecamatan : null,
				'kelurahan_id' => $item->kelurahan_id,
				'nama_desa' => $item->desa ? $item->desa->nama_desa : null,
				'jumlah_spatial' => $spatialCount,
				'total_luas' => $spatialSum,
			];
		});

		if ($searchValue) {
			$query = $query->filter(function ($item) use ($searchValue) {
				return strpos(strtolower($item['nama_petani']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['ktp_petani']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['kontak']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_provinsi']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_kabupaten']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_kecamatan']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_desa']), strtolower($searchValue)) !== false;
				strtolower($item['jumlah_spatial']) == strtolower($searchValue);
			});
		}

		if ($request->has('order')) {
			$orderColumn = $request->input('order')[0]['column'];
			$orderDirection = $request->input('order')[0]['dir'];
			$columnName = $request->input('columns')[$orderColumn]['data'];

			// Gunakan switch case atau if else untuk menentukan kolom pengurutan
			switch ($columnName) {
				case 'nama_petani':
					$query = $query->sortBy('nama_petani');
					break;
				case 'ktp_petani':
					$query = $query->sortByDesc('ktp_petani');
					break;
				case 'kontak':
					$query = $query->sortByDesc('kontak');
					break;
				case 'nama_provinsi':
					$query = $query->sortByDesc('nama_provinsi');
					break;
				case 'nama_kabupaten':
					$query = $query->sortByDesc('nama_kabupaten');
					break;
				case 'nama_kecamatan':
					$query = $query->sortByDesc('nama_kecamatan');
					break;
				case 'nama_desa':
					$query = $query->sortByDesc('nama_desa');
					break;
				case 'jumlah_spatial':
					$query = $query->sortByDesc('nama_desa');
					break;
			}
		}

		$totalRecords = $data->count();
		$filteredRecords = $query->count();

		$poktans = $query->slice($start)->take($length)->values();

		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $poktans,
		]);
	}

	public function getAllCpclByKec(Request $request, $kecId)
	{
		$cpcls = MasterAnggota::select('ktp_petani', 'nama_petani', 'kecamatan_id')->where('kecamatan_id', $kecId)->get();
		$data = [];
		foreach ($cpcls as $cpcl) {
			$data[] = [
				'ktp_petani' => $cpcl->ktp_petani,
				'nama_petani' => $cpcl->nama_petani,
				'kecamatan_id' => $cpcl->kecamatan_id
			];
		}

		return response()->json($data);
	}

	public function updateOrCreateDesa()
	{
		// Path ke file JSON
		$jsonFilePath = storage_path('app/public/output_file.json');

		// Baca file JSON
		if (!file_exists($jsonFilePath)) {
			return response()->json(['error' => 'File JSON tidak ditemukan.'], 404);
		}

		$jsonData = file_get_contents($jsonFilePath);
		$desas = json_decode($jsonData, true);

		foreach ($desas as $desa) {
			MasterDesa::updateOrCreate(
				['kelurahan_id' => $desa['kelurahan_id']],
				[
					'kecamatan_id' => $desa['kecamatan_id'],
					'nama_desa' => strtoupper($desa['nama_desa']), // Mengubah nama desa menjadi huruf kapital
				]
			);
		}


		return response()->json(['success' => 'Data desa berhasil diupdate atau dibuat.'], 200);
	}

	public function getCpclByNik(Request $request, $nik)
	{
		$nikData = MasterAnggota::select('ktp_petani', 'nama_petani')->where('ktp_petani', $nik)->first();

		$data = $nikData ? [
			'ktp_petani' => $nikData->ktp_petani,
			'nama_petani' => $nikData->nama_petani,
		] : [
			'ktp_petani' => 'KTP tidak terdaftar',
			'nama_petani' => 'KTP tidak terdaftar',
		];
		return response()->json($data);
	}

	public function getDataPengajuan($noIjin)
	{
		$noIjin = $this->formatNoIjin($noIjin);


		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$verifTanam = AjuVerifTanam::where('no_ijin', $noIjin)->latest()->first() ?? new AjuVerifTanam();
		$verifProduksi = AjuVerifProduksi::where('no_ijin', $noIjin)->latest()->first() ?? new AjuVerifProduksi();
		$verifSkl = AjuVerifSkl::where('no_ijin', $noIjin)->latest()->first() ?? new AjuVerifSkl();
		$userDocs = UserDocs::where('no_ijin', $noIjin)->first() ?? new UserDocs();
		$pks = Pks::where('no_ijin', $noIjin)->get() ?? new Pks();
		$lokasis = Lokasi::where('no_ijin', $noIjin)->get() ?? new Lokasi();

		$data = [
			'company' => $commitment->datauser->company_name,
			'noIjin' => $commitment->no_ijin,
			'periode' => $commitment->periodetahun,
			'avtDate' => $verifTanam->created_at,
			'avtVerifAt' => $verifTanam->verif_at,
			'avtStatus' => $verifTanam->status,
			'avtMetode' => $verifTanam->metode,
			'avtNote' => $verifTanam->note,

			'avpDate' => $verifProduksi->created_at,
			'avpVerifAt' => $verifProduksi->verif_at,
			'avpMetode' => $verifProduksi->metode,
			'avpNote' => $verifProduksi->note,
			'avpStatus' => $verifProduksi->status,
			'avsklDate' => $verifSkl->created_at,
			'avsklVerifAt' => $verifSkl->verif_at,
			'avsklStatus' => $verifSkl->status,
			'avsklMetode' => $verifSkl->metode,
			'avsklNote' => $verifSkl->note,
			'avsklRecomendBy' => $verifSkl->recomend_by,
			'avsklRecomendAt' => $verifSkl->recomend_at,
			'avsklRecomendNote' => $verifSkl->recomend_note,
			'avsklApprovedBy' => $verifSkl->approved_by,
			'avsklApprovedAt' => $verifSkl->approved_at,
			'avsklPublishedAt' => $verifSkl->published_at,

			'wajibTanam' => $commitment->luas_wajib_tanam,
			'wajibProduksi' => $commitment->volume_produksi,
			'realisasiTanam' => $commitment->lokasi->sum('luas_tanam'),
			'realisasiProduksi' => $commitment->lokasi->sum('volume'),
			'countAnggota' => $commitment->lokasi->groupBy('ktp_petani')->count(),
			'countPoktan' => $commitment->lokasi->groupBy('poktan_id')->count(),
			'countPks' => $pks->where('berkas_pks', '!=', null)->count(),
			'countSpatial' => $lokasis->count(),
			'countTanam' => $lokasis->where('luas_tanam', '!=', null)->count(),
			'userDocs' => $userDocs,
		];

		return response()->json($data);
	}

	public function getVerifTanamHistory(Request $request, $noIjin)
	{
		// Format the $noIjin
		$noIjin = $this->formatNoIjin($noIjin);

		// Get the request inputs
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		// Build the query
		$query = AjuVerifTanam::select('id', 'no_ijin', 'check_by', 'verif_at', 'status', 'note', 'created_at')
			->where('no_ijin', $noIjin)
			->with('verifikator');

		// Get total records count
		$totalRecords = $query->count();

		// Apply search filter
		if (!empty($searchValue)) {
			$query = $query->where(function ($q) use ($searchValue) {
				$q->where('check_by', 'like', "%{$searchValue}%")
					->orWhere('verif_at', 'like', "%{$searchValue}%")
					->orWhere('status', 'like', "%{$searchValue}%")
					->orWhere('note', 'like', "%{$searchValue}%");
			});
		}

		// Get filtered records count
		$filteredRecords = $query->count();

		// Apply ordering
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];

			switch ($orderColumn) {
				case 'status':
					$query = $query->orderBy('status', $orderDirection);
					break;
				case 'check_by':
					$query = $query->orderBy('check_by', $orderDirection);
					break;
				case 'verif_at':
					$query = $query->orderBy('verif_at', $orderDirection);
					break;
				case 'note':
					$query = $query->orderBy('note', $orderDirection);
					break;
				default:
					$query = $query->orderBy('id', 'desc');
					break;
			}
		}

		// Apply pagination
		$data = $query->skip($start)
			->take($length)
			->get();

		// Process the data
		if ($data) {
			foreach ($data as $history) {
				$history->checkBy = ($history->check_by && $history->verifikator) ? $history->verifikator->name : null;
			}
		}

		// Format the response
		$response = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'createdAt' => $item->created_at,
				'checkBy' => $item->checkBy,
				'status' => $item->status,
				'verifAt' => $item->verif_at,
				'note' => $item->note,
			];
		});

		// Return JSON response
		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $response,
		]);
	}

	public function getVerifProdHistory(Request $request, $noIjin)
	{
		// Format the $noIjin
		$noIjin = $this->formatNoIjin($noIjin);

		// Get the request inputs
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		// Build the query
		$query = AjuVerifProduksi::select('id', 'no_ijin', 'check_by', 'verif_at', 'status', 'note', 'created_at')
			->where('no_ijin', $noIjin)
			->with('verifikator');

		// Get total records count
		$totalRecords = $query->count();

		// Apply search filter
		if (!empty($searchValue)) {
			$query = $query->where(function ($q) use ($searchValue) {
				$q->where('check_by', 'like', "%{$searchValue}%")
					->orWhere('verif_at', 'like', "%{$searchValue}%")
					->orWhere('status', 'like', "%{$searchValue}%")
					->orWhere('note', 'like', "%{$searchValue}%");
			});
		}

		// Get filtered records count
		$filteredRecords = $query->count();

		// Apply ordering
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];

			switch ($orderColumn) {
				case 'status':
					$query = $query->orderBy('status', $orderDirection);
					break;
				case 'check_by':
					$query = $query->orderBy('check_by', $orderDirection);
					break;
				case 'verif_at':
					$query = $query->orderBy('verif_at', $orderDirection);
					break;
				case 'note':
					$query = $query->orderBy('note', $orderDirection);
					break;
				default:
					$query = $query->orderBy('id', 'desc');
					break;
			}
		}

		// Apply pagination
		$data = $query->skip($start)
			->take($length)
			->get();

		// Process the data
		if ($data) {
			foreach ($data as $history) {
				$history->checkBy = ($history->check_by && $history->verifikator) ? $history->verifikator->name : null;
			}
		}

		// Format the response
		$response = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'createdAt' => $item->created_at,
				'checkBy' => $item->checkBy,
				'status' => $item->status,
				'verifAt' => $item->verif_at,
				'note' => $item->note,
			];
		});

		// Return JSON response
		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $response,
		]);
	}
	public function getVerifSklHistory(Request $request, $noIjin)
	{
		// Format the $noIjin
		$noIjin = $this->formatNoIjin($noIjin);

		// Get the request inputs
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		// Build the query
		$query = AjuVerifSkl::select('id', 'no_ijin', 'check_by', 'verif_at', 'status', 'verif_note', 'created_at')
			->where('no_ijin', $noIjin)
			->with('verifikator');

		// Get total records count
		$totalRecords = $query->count();

		// Apply search filter
		if (!empty($searchValue)) {
			$query = $query->where(function ($q) use ($searchValue) {
				$q->where('check_by', 'like', "%{$searchValue}%")
					->orWhere('verif_at', 'like', "%{$searchValue}%")
					->orWhere('status', 'like', "%{$searchValue}%")
					->orWhere('verif_note', 'like', "%{$searchValue}%");
			});
		}

		// Get filtered records count
		$filteredRecords = $query->count();

		// Apply ordering
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];

			switch ($orderColumn) {
				case 'status':
					$query = $query->orderBy('status', $orderDirection);
					break;
				case 'check_by':
					$query = $query->orderBy('check_by', $orderDirection);
					break;
				case 'verif_at':
					$query = $query->orderBy('verif_at', $orderDirection);
					break;
				case 'verif_note':
					$query = $query->orderBy('note', $orderDirection);
					break;
				default:
					$query = $query->orderBy('id', 'desc');
					break;
			}
		}

		// Apply pagination
		$data = $query->skip($start)
			->take($length)
			->get();

		// Process the data
		if ($data) {
			foreach ($data as $history) {
				$history->checkBy = ($history->check_by && $history->verifikator) ? $history->verifikator->name : null;
			}
		}

		// Format the response
		$response = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'createdAt' => $item->created_at,
				'checkBy' => $item->checkBy,
				'status' => $item->status,
				'verifAt' => $item->verif_at,
				'note' => $item->verif_note,
			];
		});

		// Return JSON response
		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $response,
		]);
	}

	public function getRequestVerifTanam(Request $request)
	{
		$user = Auth::user();
		// Get the request inputs
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);
		$periodeFilter = $request->input('periode', null);
		$statusFilter = $request->input('status', null);

		// Build the query
		$query = AjuVerifTanam::select('id', 'tcode', 'npwp', 'no_ijin', 'check_by', 'verif_at', 'status', 'note', 'created_at')
			->when($user->roles[0]->title !== 'Administrator', function ($query) {
				$query->where('status', 0);
			})
			->where(function ($query) use ($user) {
				$query->where('check_by', null)
					->orWhere('check_by', $user->id);
			})
			->with([
				'verifikator:id,name',
				'datauser:id,npwp_company,company_name',
				'commitment:id,no_ijin,periodetahun',
				'assignments:id,tcode,pengajuan_id,user_id',
				'assignments.user:id,name'
			]);

		if ($periodeFilter) {
			$query->whereHas('commitment', function ($query) use ($periodeFilter) {
				$query->where('periodetahun', $periodeFilter);
			});
		}

		if ($statusFilter) {
			$query->where('status', $statusFilter);
		}

		$data = $query->get();

		$data = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'tcode' => $item->tcode,
				'check_by' => $item->check_by,
				'verifikator' => $item->verifikator ? $item->verifikator->name : null,
				'periode' => $item->commitment ? $item->commitment->periodetahun : null,
				'perusahaan' => $item->datauser ? $item->datauser->company_name : null,
				'no_ijin' => $item->no_ijin,
				'created_at' => $item->created_at,
				'verif_at' => $item->verif_at,
				'status' => $item->status,
				'ijin' => str_replace(['/', '.', '-'], '', $item->no_ijin),
				'assignments' => $item->assignments->map(function ($assignment) {
					return [
						'id' => $assignment->id,
						'user_id' => $assignment->user_id,
						'user_name' => $assignment->user ? $assignment->user->name : null,
						'tcode' => $assignment ? $assignment->tcode : null,
						'no_sk' => $assignment->no_sk,
						'tgl_sk' => $assignment->tgl_sk,
						'file' => $assignment->file,
					];
				}),
			];
		});

		if ($searchValue) {
			$data = $data->filter(function ($item) use ($searchValue) {
				return strpos(strtolower($item['periode']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['perusahaan']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['no_ijin']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['ijin']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['status']), strtolower($searchValue)) !== false;
			})->values();
		}

		if (!empty($order)) {
			$orderColumn = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$columnName = $columns[$orderColumn]['data'];

			$data = $data->sortBy(function ($item) use ($columnName) {
				return $item[$columnName];
			}, SORT_REGULAR, $orderDirection === 'desc')->values();
		}

		$totalRecords = $data->count();
		$filteredRecords = $data->count();

		$verifList = $data->slice($start, $length)->values();

		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $verifList,
		]);
	}

	public function getVerifTanamByIjin(Request $request, $noIjin)
	{
		$userId = Auth::user()->id;
		$noIjin = $this->formatNoIjin($noIjin);

		$query = AjuVerifTanam::select('id', 'npwp', 'no_ijin', 'check_by', 'verif_at', 'status', 'note', 'created_at')
			->where('no_ijin', $noIjin)
			->with([
				'commitment:no_ijin,tgl_ijin,tgl_akhir,periodetahun',
				'datauser:id,npwp_company,company_name,logo',
				'verifikator:id,name',
			])->first();

		if ($query) {
			$data = [
				'id' => $query->id,
				'check_by' => $query->check_by,
				'verifikator' => $query->verifikator ? $query->verifikator->name : null,
				'periode' => $query->commitment ? $query->commitment->periodetahun : null,
				'perusahaan' => $query->datauser ? $query->datauser->company_name : null,
				'sumLuas' => $query->commitment->lokasi->sum('luas_lahan'),
				'sumLuasTanam' => $query->commitment->lokasi->sum('luas_tanam'),
				'countPks' => $query->commitment->pks->where('berkas_pks', '!=', null)->count(),
				'countPoktan' => $query->commitment->lokasi->groupBy('poktan_id')->count(),
				'countSpatial' => $query->commitment->lokasi->count(),
				'countTanam' => $query->commitment->lokasi->where('luas_tanam', '!=', null)->count(),
				'countAnggota' => $query->commitment->lokasi->groupBy('ktp_petani')->count(),
				'logo' => $query->datauser && $query->datauser->logo ? asset('storage/' . $query->datauser->logo) : asset('/img/avatars/farmer.png'),
				'no_ijin' => $query->no_ijin,
				'tgl_ijin' => $query->commitment->tgl_ijin,
				'tgl_akhir' => $query->commitment->tgl_akhir,
				'created_at' => $query->created_at,
				'verif_at' => $query->verif_at,
				'status' => $query->status,
				'ijin' => str_replace(['/', '.', '-'], '', $query->no_ijin),
			];
		} else {
			$data = null; // Handle the case where no data is found
		}

		return response()->json([
			'userId' => $userId,
			'data' => $data,
		]);
	}

	private function haversineDistance($lat1, $lon1, $lat2, $lon2)
	{
		$deltaLat = deg2rad($lat2 - $lat1);
		$deltaLon = deg2rad($lon2 - $lon1);
		$a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) * sin($deltaLon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$distance = 6371 * $c;

		return $distance;
	}

	//response saat android mengirim data current location dan nomor riph (no_ijin)
	public function responseGetLocByRad(Request $request)
	{
		$request->validate([
			'noIjin' => 'required|string',
			'latitude' => 'required|string',
			'longitude' => 'required|string',
			'radius' => 'required|integer', //remark jika tidak digunakan atau menggunakan nilai statis
		]);

		$formattedNoIjin = $request->input('noIjin');
		$centerLat = $request->input('latitude');
		$centerLng = $request->input('longitude');
		$radius = $request->input('radius'); //beri nilai default jika statis

		$locations = Lokasi::select('id', 'no_ijin', 'kode_spatial')
			->with(['spatial' => function ($query) {
				$query->select('kode_spatial', 'latitude', 'longitude');
			}])
			->where('no_ijin', $formattedNoIjin)
			->get();

		$filteredLocations = $locations->filter(function ($location) use ($centerLat, $centerLng, $radius) {
			$latitude = $location->spatial->latitude;
			$longitude = $location->spatial->longitude;
			$distance = $this->haversineDistance($centerLat, $centerLng, $latitude, $longitude);
			return $distance <= $radius;
		});

		return response()->json([
			'Jarak (km)' => $radius,
			'No Ijin' => $formattedNoIjin,
			'Device Location' => 'Lat: ' . $centerLat . ' || Long: ' . $centerLng,
			'Jumlah titik' => $filteredLocations->count(),
			'data' => $filteredLocations,
		]);
	}

	//response saat marker diklik dalam responseGetLocByDRad data dari header
	public function getLocDataByIjinBySpatial($noIjin, $spatial)
	{
		$noIjin = $this->formatNoIjin($noIjin);

		$query = Lokasi::where('no_ijin', $noIjin)
			->where('kode_spatial', $spatial)
			->with(['spatial', 'masteranggota.masterpoktan', 'fototanam', 'fotoproduksi'])
			->get();

		$data = $query->map(function ($item) {
			$spatial = $item->spatial;
			$masterAnggota = $item->masteranggota->first();
			$nama_kelompok = optional(optional($masterAnggota)->masterpoktan)->nama_kelompok;

			$fotoTanam = $item->fototanam->map(function ($foto) {
				return [
					'id' => $foto->id,
					'url' => $foto->filename,
				];
			});

			$fotoProduksi = $item->fotoproduksi->map(function ($foto) {
				return [
					'id' => $foto->id,
					'url' => $foto->filename,
				];
			});

			return [
				'id' => $item->id,
				'nama_kelompok' => $item->nama_poktan,
				'kode_spatial' => optional($spatial)->kode_spatial,
				'ktp_petani' => $item->ktp_petani,
				'nama_petani' => optional($item->masteranggota)->nama_petani,
				'nama_kelompok_poktan' => $nama_kelompok,
				'spatial_petani' => optional($spatial)->nama_petani,
				'spatial_ktp' => optional($spatial)->ktp_petani,
				'latitude' => optional($spatial)->latitude,
				'longitude' => optional($spatial)->longitude,
				'polygon' => optional($spatial)->polygon,
				'luas_lahan' => $item->luas_lahan,
				'luas_tanam' => $item->luas_tanam,
				'awal_tanam' => $item->tgl_tanam ? date('d-m-Y', strtotime($item->tgl_tanam)) : null,
				'akhir_tanam' => $item->tgl_akhir_tanam ? date('d-m-Y', strtotime($item->tgl_akhir_tanam)) : null,
				'volume_panen' => $item->volume,
				'tgl_panen' => $item->tgl_panen ? date('d-m-Y', strtotime($item->tgl_panen)) : null,
				'status' => $item->status,
				'fototanam' => $fotoTanam,
				'fotoProduksi' => $fotoProduksi,
			];
		});

		// Return response in JSON format
		return response()->json($data);
	}

	//response saat marker diklik dalam responseGetLocByDRad data dari body
	public function postLocDataByIjinBySpatial(Request $request)
	{
		$request->validate([
			'noIjin' => 'required|string',
			'kode_spatial' => 'required|string',
		]);

		$noIjin = $request->input('noIjin');
		$kdspatial = $request->input('kode_spatial');

		$query = Lokasi::where('no_ijin', $noIjin)
			->where('kode_spatial', $kdspatial)
			->with(['spatial', 'masteranggota.masterpoktan', 'fototanam', 'fotoproduksi'])
			->get();

		$data = $query->map(function ($item) {
			$spatial = $item->spatial;
			$masterAnggota = $item->masteranggota->first();
			$nama_kelompok = optional(optional($masterAnggota)->masterpoktan)->nama_kelompok;

			$fotoTanam = $item->fototanam->map(function ($foto) {
				return [
					'id' => $foto->id,
					'url' => $foto->filename,
				];
			});

			$fotoProduksi = $item->fotoproduksi->map(function ($foto) {
				return [
					'id' => $foto->id,
					'url' => $foto->filename,
				];
			});

			return [
				'id' => $item->id,
				'nama_kelompok' => $item->nama_poktan,
				'kode_spatial' => optional($spatial)->kode_spatial,
				'ktp_petani' => $item->ktp_petani,
				'nama_petani' => optional($item->masteranggota)->nama_petani,
				'nama_kelompok_poktan' => $nama_kelompok,
				'spatial_petani' => optional($spatial)->nama_petani,
				'spatial_ktp' => optional($spatial)->ktp_petani,
				'latitude' => optional($spatial)->latitude,
				'longitude' => optional($spatial)->longitude,
				'polygon' => optional($spatial)->polygon,
				'luas_lahan' => $item->luas_lahan,
				'luas_tanam' => $item->luas_tanam,
				'awal_tanam' => $item->tgl_tanam ? date('d-m-Y', strtotime($item->tgl_tanam)) : null,
				'akhir_tanam' => $item->tgl_akhir_tanam ? date('d-m-Y', strtotime($item->tgl_akhir_tanam)) : null,
				'volume_panen' => $item->volume,
				'tgl_panen' => $item->tgl_panen ? date('d-m-Y', strtotime($item->tgl_panen)) : null,
				'status' => $item->status,
				'fototanam' => $fotoTanam,
				'fotoProduksi' => $fotoProduksi,
			];
		});

		// Return response in JSON format
		return response()->json($data);
	}


	//get spatial by status
	public function getspatial(Request $request)
	{
		$status = 1;
		//kita uji di sini untuk provinsi_id = "33";
		$provinsi = $request->input('provinsi_id', "All");

		// Inisialisasi query dasar
		$query = MasterSpatial::select(
			'kode_spatial',
			'ktp_petani',
			'nama_petani',
			'poktan_id',
			'provinsi_id',
			'kabupaten_id',
			'kecamatan_id',
			'kelurahan_id',
			'luas_lahan',
			'status'
		);

		// Filter berdasarkan status jika status bukan 'All'
		if ($status != 'All') {
			$query->where('status', $status);
		}

		// Filter berdasarkan wilayah jika disediakan
		if ($provinsi != 'All') {
			$query->where('provinsi_id', $request->input('provinsi_id'));
		}
		if ($request->has('kabupaten_id')) {
			$query->where('kabupaten_id', $request->input('kabupaten_id'));
		}
		if ($request->has('kecamatan_id')) {
			$query->where('kecamatan_id', $request->input('kecamatan_id'));
		}
		if ($request->has('kelurahan_id')) {
			$query->where('kelurahan_id', $request->input('kelurahan_id'));
		}

		// Eksekusi query untuk mendapatkan hasil
		$spatials = $query->get();

		// Modifikasi struktur data
		$spatials = $spatials->map(function ($spatial) {
			return [
				'kode_spatial' => $spatial->kode_spatial,
				'luas_lahan' => $spatial->luas_lahan,
				'ktp_petani' => $spatial->ktp_petani,
				'nama_petani' => $spatial->nama_petani,
				'poktan_id' => $spatial->poktan_id,
				'status' => $spatial->status,
				'wilayah' => [
					'provinsi_id' => $spatial->provinsi_id,
					'kabupaten_id' => $spatial->kabupaten_id,
					'kecamatan_id' => $spatial->kecamatan_id,
					'kelurahan_id' => $spatial->kelurahan_id,
				]
			];
		});

		// Kembalikan hasil dalam bentuk JSON
		return response()->json([
			'spatials' => $spatials,
		]);
	}
}
