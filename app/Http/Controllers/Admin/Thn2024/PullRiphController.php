<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models2024\PullRiph;
use App\Models2024\AjuVerifProduksi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\AjuVerifTanam;
use App\Models2024\Completed;
use App\Models2024\DataRealisasi;
use App\Models2024\Lokasi;
use App\Models2024\Pks;
use Exception;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PullRiphController extends Controller
{

	public function index()
	{
		abort_if(Gate::denies('pull_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'Proses RIPH';
		$page_title = 'Tarik Data RIPH';
		$page_heading = 'Tarik Data RIPH';
		$heading_class = 'fa fa-sync-alt';
		$npwp_company = (Auth::user()::find(Auth::user()->id)->data_user->npwp_company ?? null);
		$noIjins = PullRiph::where('npwp', $npwp_company)->select('no_ijin')->get();
		// Cari ajutanam yang memiliki nomor ijin dari $noIjins
		$ajutanam = AjuVerifTanam::whereIn('no_ijin', $noIjins)->get();

		// Cari ajuproduksi dengan nomor ijin dari $noIjins
		$ajuproduksi = AjuVerifProduksi::whereIn('no_ijin', $noIjins)->get();

		// Cari skl dengan nomor ijin dari $noIjins
		$ajuskl = AjuVerifSkl::whereIn('no_ijin', $noIjins)->get();

		// Cari completed dengan nomor ijin dari $noIjins
		$completed = Completed::whereIn('no_ijin', $noIjins)->get();
		return view('t2024.pullriph.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'npwp_company', 'noIjins', 'ajutanam', 'ajuproduksi', 'ajuskl', 'completed'));
	}

	public function pull(Request $request)
	{
		try {
			$options = array(
				'soap_version' => SOAP_1_1,
				'exceptions' => true,
				'trace' => 1,
				'cache_wsdl' => WSDL_CACHE_MEMORY,
				'connection_timeout' => 25,
				'style' => SOAP_RPC,
				'use' => SOAP_ENCODED,
			);

			$client = new \SoapClient('https://riph.pertanian.go.id/api.php/simethris?wsdl', $options);
			$parameter = array(
				'user' => 'simethris',
				'pass' => 'wsriphsimethris',
				'npwp' => $request->string('npwp'),
				'nomor' =>  $request->string('nomor')
			);

			$response = $client->__soapCall('get_riph', $parameter);
		} catch (\Exception $e) {
			$errorMessage = $e->getMessage();
			Log::error("Error: $errorMessage. Code: " . $e->getCode() . ". Trace: " . $e->getTraceAsString());
			return redirect()->back()->with('error', 'Pull Method. Error while trying to retrieve data. Please Contact Administrator for this error: (' . $errorMessage . ')');
		}
		$res = json_decode(json_encode((array)simplexml_load_string($response)), true);

		return $res;
	}



	public function store(Request $request)
	{
		$filepath = '';
		try {
			$options = array(
				'soap_version' => SOAP_1_1,
				'exceptions' => true,
				'trace' => 1,
				'cache_wsdl' => WSDL_CACHE_MEMORY,
				'connection_timeout' => 25,
				'style' => SOAP_RPC,
				'use' => SOAP_ENCODED,
			);

			$client = new \SoapClient('https://riph.pertanian.go.id/api.php/simethris?wsdl', $options);
			$stnpwp = $request->get('npwp');
			$npwp = str_replace('.', '', $stnpwp);
			$npwp = str_replace('-', '', $npwp);
			$noijin =  $request->get('no_ijin');
			$fijin = str_replace('.', '', $noijin);
			$fijin = str_replace('/', '', $fijin);
			$parameter = array(
				'user' => 'simethris',
				'pass' => 'wsriphsimethris',
				'npwp' => $npwp,
				'nomor' =>  $request->get('no_ijin')
			);
			$response = $client->__soapCall('get_riph', $parameter);
			$datariph = json_encode((array)simplexml_load_string($response));
			$filepath = 'uploads/' . $npwp . '/' . $fijin . '.json';
			Storage::disk('public')->put($filepath, $datariph);
		} catch (\Exception $e) {
			$errorMessage = $e->getMessage();
			// Log pesan kesalahan ke dalam file log laravel
			Log::error("Error: $errorMessage. Code: " . $e->getCode() . ". Trace: " . $e->getTraceAsString());
			return redirect()->back()->with('error', 'Soap Error while trying to connect to Client. Please Contact Administrator for this error: (' . $errorMessage . ')');
		}

		$user = Auth::user();
		DB::beginTransaction();
		try {
			$riph = PullRiph::updateOrCreate(
				[
					'npwp' => $stnpwp,
					'no_ijin' => $noijin,
					'user_id' => $user->id
				],
				[
					'keterangan'        => $request->get('keterangan'),
					'nama'                => $request->get('nama'),
					'periodetahun'        => $request->get('periodetahun'),
					'tgl_ijin'            => $request->get('tgl_ijin'),
					'tgl_akhir'            => $request->get('tgl_akhir'),
					'no_hs'                => $request->get('no_hs'),
					'volume_riph'        => $request->get('volume_riph'),
					'volume_produksi'    => $request->get('volume_produksi'),
					'luas_wajib_tanam'    => $request->get('luas_wajib_tanam'),
					'stok_mandiri'        => $request->get('stok_mandiri'),
					'pupuk_organik'        => $request->get('pupuk_organik'),
					'npk'                => $request->get('npk'),
					'dolomit'            => $request->get('dolomit'),
					'za'                => $request->get('za'),
					'mulsa'                => $request->get('mulsa'),
					'datariph' => $filepath
				]
			);

			$dtjson = json_decode($datariph);
			if ($riph) {
				$lastPoktan = '';
				if ($dtjson->riph->wajib_tanam->kelompoktani->loop === null) {
					return redirect()->back()->with('error', 'Gagal menyimpan. Data Kelompok tani tidak lengkap.');
				} else {
					DataRealisasi::where([
						'npwp_company' => $stnpwp,
						'no_ijin' => $noijin,
					])->forceDelete();

					Lokasi::where([
						'npwp' => $stnpwp,
						'no_ijin' => $noijin,
					])->forceDelete();
					PKS::where([
						'npwp' => $stnpwp,
						'no_ijin' => $noijin,
					])->forceDelete();
					if (is_array($dtjson->riph->wajib_tanam->kelompoktani->loop)) {
						$spatialCounter = 97;
						foreach ($dtjson->riph->wajib_tanam->kelompoktani->loop as $poktan) {
							$namaPoktan = trim($poktan->nama_kelompok, ' ');
							$namaPetani = trim($poktan->nama_petani, ' ');
							if (!empty($poktan->kode_spatial)) {
								$kdSpatial = trim($poktan->kode_spatial, ' ');
							} else {
								// Jika tidak ada kode_spatial, gunakan nilai increment
								$spatialCounter++;
								$kdSpatial = 'TMG_' . str_pad($spatialCounter, 5, '0', STR_PAD_LEFT);
							}
							$luas = trim($poktan->luas_lahan, ' ');
							$ktp = isset($poktan->ktp_petani) ? $poktan->ktp_petani : '';
							if (is_string($ktp)) {
								// Menghapus karakter yang tidak diperlukan
								$ktp = preg_replace('/[^0-9\p{Latin}\pP\p{Sc}@\s]+/u', '', $ktp);
								$ktp = trim($ktp, "\u{00a0}");
								$ktp = trim($ktp, "\u{00c2}");
							} else {
								// Kesalahan terdeteksi jika $ktp bukan string
								$ktp = "";
							}

							$periodeTanam = isset($poktan->periode_tanam) ? $poktan->periode_tanam : '';
							if (is_string($periodeTanam)) {
								$periodeTanam = $poktan->periode_tanam;
							} else {
								// Kesalahan terdeteksi jika $ktp bukan string
								$periodeTanam = "";
							}

							$idpoktan = isset($poktan->id_poktan) ? trim($poktan->id_poktan, ' ') : '';
							$idpetani = isset($poktan->id_petani) ? trim($poktan->id_petani, ' ') : '';
							$idkabupaten = isset($poktan->id_kabupaten) ? trim($poktan->id_kabupaten, ' ') : '';
							$idkecamatan = isset($poktan->id_kecamatan) ? trim($poktan->id_kecamatan, ' ') : '';
							$idkelurahan = isset($poktan->id_kelurahan) && is_string($poktan->id_kelurahan) ? trim($poktan->id_kelurahan, ' ') : '';
							$lastPoktan = $idpoktan;
							Pks::updateOrCreate(
								[
									'npwp' => $stnpwp,
									'no_ijin' => $noijin,
									'poktan_id' => $idpoktan,
									'nama_poktan' => $namaPoktan
								],
								[
									'kabupaten_id' => $idkabupaten,
									'kecamatan_id' => $idkecamatan,
									'kelurahan_id' => $idkelurahan
								]
							);

							Lokasi::create(
								[
									'npwp' => $stnpwp,
									'no_ijin' => $noijin,
									'kode_spatial' => $kdSpatial,
									'poktan_id' => $idpoktan,
									'ktp_petani' => $ktp,
									'nama_petani' => $namaPetani,
									'anggota_id' => $idpetani,
									'luas_lahan' => $luas,
									'periode_tanam' => $periodeTanam,
								],
							);
						}
					} elseif (is_object($dtjson->riph->wajib_tanam->kelompoktani->loop)) {
						$spatialCounter = 97;
						$poktan = $dtjson->riph->wajib_tanam->kelompoktani->loop;
						if (!empty($poktan->kode_spatial)) {
							$kdSpatial = trim($poktan->kode_spatial, ' ');
						} else {
							// Jika tidak ada kode_spatial, gunakan nilai increment
							$spatialCounter++;
							$kdSpatial = 'TMG_' . str_pad($spatialCounter, 5, '0', STR_PAD_LEFT);
						}
						$namaPoktan = trim($poktan->nama_kelompok, ' ');
						$namaPetani = trim($poktan->nama_petani, ' ');
						$luas = trim($poktan->luas_lahan, ' ');
						$ktp = isset($poktan->ktp_petani) ? $poktan->ktp_petani : '';
						if (is_string($ktp)) {
							// Menghapus karakter yang tidak diperlukan
							$ktp = preg_replace('/[^0-9\p{Latin}\pP\p{Sc}@\s]+/u', '', $ktp);
							$ktp = trim($ktp, "\u{00a0}");
							$ktp = trim($ktp, "\u{00c2}");
						} else {
							// Kesalahan terdeteksi jika $ktp bukan string
							$ktp = "";
						}

						$periodeTanam = isset($poktan->periode_tanam) ? $poktan->periode_tanam : '';
						if (is_string($periodeTanam)) {
							$periodeTanam = $poktan->periode_tanam;
						} else {
							// Kesalahan terdeteksi jika $ktp bukan string
							$periodeTanam = "";
						}
						$idpoktan = isset($poktan->id_poktan) ? trim($poktan->id_poktan, ' ') : '';
						$idpetani = isset($poktan->id_petani) ? trim($poktan->id_petani, ' ') : '';
						$idkabupaten = isset($poktan->id_kabupaten) ? trim($poktan->id_kabupaten, ' ') : '';
						$idkecamatan = isset($poktan->id_kecamatan) ? trim($poktan->id_kecamatan, ' ') : '';
						$idkelurahan = isset($poktan->id_kelurahan) && is_string($poktan->id_kelurahan) ? trim($poktan->id_kelurahan, ' ') : '';

						DataRealisasi::where([
							'npwp_company' => $stnpwp,
							'no_ijin' => $noijin,
						])->forceDelete();

						Lokasi::where([
							'npwp' => $stnpwp,
							'no_ijin' => $noijin,
							'poktan_id' => $idpoktan,
							'anggota_id' => $idpetani,
						])->forceDelete();
						PKS::where([
							'npwp' => $stnpwp,
							'no_ijin' => $noijin,
							'poktan_id' => $idpoktan,
						])->forceDelete();
						$lastPoktan = $idpoktan;
						Pks::updateOrCreate(
							[
								'npwp' => $stnpwp,
								'no_ijin' => $noijin,
								'poktan_id' => $idpoktan,
								'nama_poktan' => $namaPoktan
							],
							[
								'kabupaten_id' => $idkabupaten,
								'kecamatan_id' => $idkecamatan,
								'kelurahan_id' => $idkelurahan
							]
						);

						Lokasi::create(
							[
								'npwp' => $stnpwp,
								'no_ijin' => $noijin,
								'kode_spatial' => $kdSpatial,
								'poktan_id' => $idpoktan,
								'ktp_petani' => $ktp,
								'nama_petani' => $namaPetani,
								'anggota_id' => $idpetani,
								'luas_lahan' => $luas,
								'periode_tanam' => $periodeTanam,
							],
						);
					}
				}
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			$errorMessage = $e->getMessage();
			// Log pesan kesalahan ke dalam file log laravel
			Log::error("Error: $errorMessage. Code: " . $e->getCode() . ". Trace: " . $e->getTraceAsString());
			return redirect()->back()->with('error', 'Pull Store Method. Please Contact Administrator for this error: (' . $errorMessage . ')');
		}

		return redirect()->route('2024.user.commitment.index')->with('success', 'Sukses menyimpan data dan dapat Anda lihat pada daftar di bawah ini.');
	}
}
