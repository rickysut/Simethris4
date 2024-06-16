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

		// Calculate total records
		$totalRecords = $query->count();

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

		// Calculate filtered records
		$filteredRecords = $query->count();

		// Apply ordering
		if (!empty($order)) {
			$orderColumnIndex = $order[0]['column'];
			$orderDirection = $order[0]['dir'];
			$orderColumn = $columns[$orderColumnIndex]['data'];

			// Pastikan kolom yang diurutkan adalah kolom yang valid di basis data
			switch ($orderColumn) {
				case 'ijin_full':
					// Jika menggunakan 'ijin_full', mungkin Anda ingin mengganti ke kolom 'no_ijin'
					$query = $query->orderBy('no_ijin', $orderDirection);
					break;
				case 'periodetahun':
					$query = $query->orderBy('periodetahun', $orderDirection);
					break;
					// Tambahkan case lain jika ada kolom lain yang perlu diurutkan
				default:
					// Default pengurutan jika tidak ada yang cocok
					$query = $query->orderBy('id', 'asc');
					break;
			}
		}


		// Apply pagination
		$data = $query->skip($start)
			->take($length)
			->get();

		// Process data
		if ($data) {
			foreach ($data as $commitment) {
				$commitment->sumRealisasiLuas = $commitment->lokasi->sum('luas_tanam');
				$commitment->sumRealisasiPanen = $commitment->lokasi->sum('volume');
				$commitment->pksCount = $commitment->pks->count();
				$commitment->pksFileCount = $commitment->pks->whereNotNull('berkas_pks')->count();

				if ($commitment->pksCount > 0 || $commitment->pksFileCount > 0) {
					$commitment->pksComplete = ($commitment->pksCount === $commitment->pksFileCount) ? 'Lengkap' : 'Belum Lengkap';
				} else {
					$commitment->pksComplete = 'Belum Lengkap';
				}

				$tanamDocsFields = ['spvt', 'sptjmtanam', 'rta', 'sphtanam', 'logbooktanam'];
				$produksiDocsFields = ['spvp', 'sptjmproduksi', 'rpo', 'sphproduksi', 'logbookproduksi', 'formLa'];

				$tanamDocsComplete = true;
				foreach ($tanamDocsFields as $field) {
					if (empty($commitment->userDocs->$field)) {
						$tanamDocsComplete = false;
						break;
					}
				}
				$commitment->tanamDocs = $tanamDocsComplete ? "Lengkap" : "Belum Lengkap";

				$produksiDocsComplete = true;
				foreach ($produksiDocsFields as $field) {
					if (empty($commitment->userDocs->$field)) {
						$produksiDocsComplete = false;
						break;
					}
				}
				$commitment->produksiDocs = $produksiDocsComplete ? "Lengkap" : "Belum Lengkap";

				$ijin = $commitment->no_ijin;
				$commitment->noIjin = str_replace(['/', '.'], "", $ijin);

				// Statuses
				$commitment->avTanamStatus = $commitment->ajutanam()->exists() ? $commitment->ajutanam()->latest()->first()->status : "Tidak ada";
				$commitment->avProdStatus = $commitment->ajuproduksi()->exists() ? $commitment->ajuproduksi()->latest()->first()->status : "Tidak ada";
				$commitment->avSklStatus = $commitment->ajuskl()->exists() ? $commitment->ajuskl()->latest()->first()->status : "Tidak ada";
				$commitment->completeStatus = $commitment->completed()->exists() ? 'Lunas' : "Belum Lunas";
				if ($commitment->sumRealisasiLuasEmpty || $commitment->pksComplete !== 'Lengkap' || $commitment->tanamDocs !== 'Lengkap') {
					$commitment->siapVerifTanam = 'Belum Siap';
				} elseif ($commitment->avProdStatus === 'Siap') {
					$commitment->siapVerifTanam = 'Tidak Perlu';
				} else {
					$commitment->siapVerifTanam = 'Siap';
				}

				// Determine siapVerifProduksi status
				if ($commitment->sumRealisasiPanen === 0 || $commitment->sumRealisasiPanen === null) {
					$commitment->siapVerifProduksi = 'Belum Siap';
				} else {
					$commitment->siapVerifProduksi = 'Siap';
				}
			}
		}

		// Map data to the required format
		$query = $data->map(function ($item) {
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
				'avSklStatus' => $item->avSklStatus,
				'completeStatus' => $item->completeStatus,
			];
		});

		// Return response in JSON format
		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $query,
		]);
	}

	public function getPksById($id)
	{
		$pks = Pks::select('id', 'npwp', 'no_ijin', 'no_perjanjian', 'tgl_perjanjian_start', 'tgl_perjanjian_end', 'varietas_tanam', 'periode_tanam', 'berkas_pks')
			->with(['varietas' => function ($query) {
				$query->select('id', 'nama_varietas');
			}])
			->find($id);

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

	public function getPksByIjin(Request $request, $noIjin)
	{
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$query = Pks::query()
			->select('id', 'no_ijin', 'poktan_id', 'nama_poktan', 'no_perjanjian', 'tgl_perjanjian_start', 'tgl_perjanjian_end', 'varietas_tanam', 'periode_tanam', 'berkas_pks')
			->where('no_ijin', $commitment->no_ijin)
			->withCount('lokasi')
			->withSum('lokasi', 'luas_lahan');

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
				$item->status = null;
			} else {
				$item->status = 'Filled'; // Atur status yang sesuai jika tidak null
			}
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
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

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
			'data' => $data,
		]);
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
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');

		$data = MasterSpatial::select('id', 'kode_spatial', 'luas_lahan', 'ktp_petani', 'nama_petani', 'provinsi_id', 'kabupaten_id', 'kml_url')
			->with([
				// 'anggota:id,poktan_id,nama_petani,ktp_petani',
				'provinsi:provinsi_id,nama',
				'kabupaten:kabupaten_id,nama_kab',
				'jadwal:kode_spatial,awal_masa,akhir_masa',
			])->get();

		$query = $data->map(function ($item) {
			return [
				'id' => $item->id,
				'kode_spatial' => $item->kode_spatial,
				'luas_lahan' => $item->luas_lahan,
				'ktp_petani' => $item->ktp_petani,
				'nama_petani' => $item->nama_petani,
				'kml_url' => $item->kml_url,
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
				return strpos(strtolower($item['kode_spatial']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['ktp_petani']), strtolower($searchValue)) !== false ||
					strpos(strtolower($item['nama_petani']), strtolower($searchValue)) !== false ||
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
				case 'nama_petani':
					$query = $query->sortByDesc('nama_petani');
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
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);


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
			'avpStatus' => $verifProduksi->status,
			'avpMetode' => $verifProduksi->metode,
			'avpNote' => $verifProduksi->note,

			'avsklDate' => $verifSkl->created_at,
			'avsklVerifAt' => $verifSkl->verif_at,
			'avsklStatus' => $verifSkl->status,
			'avsklMetode' => $verifSkl->metode,
			'avsklNote' => $verifSkl->note,

			'publishedAt' => $verifSkl->published_date,
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
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		// Get the request inputs
		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		// Build the query
		$query = AjuVerifTanam::select('id', 'no_ijin', 'check_by', 'verif_at', 'status', 'note')
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
}
