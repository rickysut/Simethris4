<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifikasi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\ForeignApi;
use App\Models2024\Lokasi;
use App\Models2024\MasterSpatial;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models2024\UserFile;
use App\Models\MasterKabupaten;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;

class VerifProduksiController extends Controller
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
	/**
	 * PENUGASAN VERIFIKATOR
	 */

	public function saveSelectedLocations(Request $request)
	{
		// Validasi input
		$request->validate([
			// 'selected_location_id' => 'required|string|exists:lokasis,tcode', // Validasi ID lokasi
			'is_selected' => 'required|boolean' // Validasi status checkbox
		]);

		// Ambil data dari request
		$selectedLocationId = $request->input('selected_location_id');
		$isSelected = $request->input('is_selected');

		// Update status is_selected untuk lokasi dengan tcode yang dipilih
		Lokasi::where('tcode', $selectedLocationId)
			->update(['is_selected' => $isSelected]);

		// Kembalikan respons JSON
		return response()->json(['message' => 'Location updated successfully']);
	}

	public function index()
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Produksi';
		$page_title = 'Tugas Verifikasi';
		$page_heading = 'Daftar Tugas Verifikasi Produksi';
		$heading_class = 'fal fa-file-search';

		return view('t2024.verifikasi.produksi.index', compact('module_name', 'page_title', 'page_heading', 'heading_class'));
	}

	/**
	 * menandai fase pemeriksaan (status pada tabel avtanams)
	 */
	public function markStatus(Request $request, $noIjin, $tcode, $status)
	{
		$ijinUrl = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$statusUrlArray = [
			1 => '2024.verifikator.produksi.check',
			2 => '2024.verifikator.produksi.checkpks',
			3 => '2024.verifikator.produksi.checktimeline',
			4 => '2024.verifikator.produksi.checkdaftarlokasi',
			5 => '2024.verifikator.produksi.checkfinal'
		];

		$verifikasi = AjuVerifikasi::where('tcode', $tcode)->first();

		$nextPhaseUrl = array_key_exists($status + 1, $statusUrlArray) ? $statusUrlArray[$status + 1] : null;

		if (!array_key_exists($status, $statusUrlArray)) {
			return redirect()->route($statusUrlArray[$verifikasi->status], ['noIjin' => $ijinUrl, 'tcode' => $tcode])
				->with('success', 'Tahap Pemeriksaan sudah selesai, lanjutkan ke tahap pemeriksaan berikutnya.');
		}

		if ($status < $verifikasi->status) {
			return redirect()->route($statusUrlArray[$verifikasi->status], ['noIjin' => $ijinUrl, 'tcode' => $tcode])
				->with('success', 'Tahap Pemeriksaan sudah selesai, lanjutkan ke tahap pemeriksaan berikutnya.');
		}

		$verifikasi->update(['status' => $status]);

		return redirect()->route($statusUrlArray[$status], ['noIjin' => $ijinUrl, 'tcode' => $tcode])
			->with('success', 'Tahap Pemeriksaan sudah selesai, lanjutkan ke tahap pemeriksaan berikutnya.');
	}

	public function check(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Verifikasi Tanam';
		$page_heading = 'Data Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$docs = UserFile::where('no_ijin', $noIjin)->whereIn(
			'kind',
			[
				'sptjmtanam',
				'sptjmproduksi',
				'rta',
				'rpo',
				'sphtanam',
				'sphproduksi',
				'spvp',
				'logbook',
				'formLa',
			]
		)->orderBy('kind', 'DESC')
			->get();

		$docs->each(function ($doc) {
			switch ($doc->kind) {
				case 'sptjmtanam':
					$doc->form = 'Surat Pertanggungjawaban Mutlak Tanam';
					break;
				case 'sptjmproduksi':
					$doc->form = 'Surat Pertanggungjawaban Mutlak Produksi';
					break;
				case 'rta':
					$doc->form = 'Form Realisasi Tanam';
					break;
				case 'sphtanam':
					$doc->form = 'Form SPH-SBS Tanam';
					break;
				case 'spvp':
					$doc->form = 'Surat Permohonan Verifikasi Produksi';
					break;
				case 'rpo':
					$doc->form = 'Form Realisasi Produksi';
					break;
				case 'sphproduksi':
					$doc->form = 'Form SPH-SBS Produksi';
					break;
				case 'logbook':
					$doc->form = 'Logbook';
					break;
				case 'formLa':
					$doc->form = 'Laporan Akhir';
					break;
				default:
					$doc->form = null;
					break;
			}
		});

		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $commitment->npwp);
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifikasi::where('tcode', $tcode)->first();

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.produksi.check', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'periodetahun', 'npwp', 'verifikasi', 'tcode', 'docs'));
	}

	/**
	 * menandai pemeriksaan berkas di user_docs
	 */
	public function saveCheckBerkas(Request $request, $noIjin)
	{
		$user = Auth::user();
		DB::beginTransaction();
		// Format ulang nomor ijin sesuai kebutuhan
		$noIjin = $this->formatNoIjin($noIjin);
		try {

			UserFile::where('id', $request->input('docId'))->update([
				'verif_by' => $user->id,
				'verif_at' => now(),
				'status' => $request->input('status'),
			]);
			DB::commit();
			return response()->json(['message' => 'Successfully updated']);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['message' => 'An error occurred while updating ' . $e->getMessage()]);
		}
	}

	public function checkpks(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Verifikasi Tanam';
		$page_heading = 'Data Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$userDocs = UserFile::where('no_ijin', $noIjin)->where('kind', 'pks')->get();
		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		// $npwp = str_replace(['.', '-'], '', $commitment->npwp);
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifikasi::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.produksi.checkpks', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'mapkey', 'verifikasi', 'tcode'));
	}

	/**
	 * menandai pemeriksaan berkas PKS (dan status pemeriksaan di avtanams)
	 */
	public function verifPksStore(Request $request, $noIjin, $kodePoktan)
	{
		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$verifikasi = Pks::where('no_ijin', $noIjin)
			->where('kode_poktan', $kodePoktan)
			->first();
		if (!$verifikasi) {
			return response()->json([
				'message' => 'error',
				'error' => 'Verification record not found'
			], 404);
		}

		$verifikasi->update([
			'status' => $request->input('status'),
			'note' => $request->input('note'),
			'verif_by' => $user->id,
			'verif_at' => now(),
		]);

		return response()->json([
			'message' => 'success',
			'status' => $request->input('status')
		], 200);
	}

	public function checktimeline(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Verifikasi Tanam';
		$page_heading = 'Data Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $commitment->npwp);
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifikasi::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.produksi.checktimeline', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	public function checkdaftarlokasi(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Produksi';
		$page_title = 'Verifikasi Produksi';
		$page_heading = 'Data Pengajuan Verifikasi Produksi';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $commitment->npwp);
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifikasi::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.produksi.checkdaftarlokasi', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	public function verifLokasiByIjinBySpatial($noIjin, $verifikasi, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$module_name = 'Verifikasi Produksi';
		$page_title = 'Verifikasi Realisasi Tanam-Produksi';
		$page_heading = $page_title;
		$heading_class = 'fal fa-farm';

		$user = Auth::user();

		$mapkey = ForeignApi::find(1);
		$lokasi = Lokasi::where('tcode', $tcode)->first();
		$pks = Pks::where('kode_poktan', $lokasi->kode_poktan)->where('no_ijin', $noIjin)->first();
		$spatial = MasterSpatial::select('id', 'kode_spatial', 'latitude', 'longitude', 'polygon', 'altitude', 'luas_lahan', 'kabupaten_id', 'nama_petani', 'ktp_petani')->where('kode_spatial', $lokasi->kode_spatial)
			->first();

		$kabupatens = MasterKabupaten::select('kabupaten_id', 'nama_kab')->get();
		if (!$spatial) {
			return redirect()->back()->with('Perhatian', 'Data Spatial tidak ditemukan.');
		}

		$userFiles = UserFile::where('no_ijin', $noIjin)->where('file_code', $tcode)->get();
		$lahanFoto = $userFiles->where('kind', 'lahanfoto')->first()->file_url ?? null;
		$mulsaFoto = $userFiles->where('kind', 'mulsaFoto')->first()->file_url ?? null;
		$benihFoto = $userFiles->where('kind', 'benihFoto')->first()->file_url ?? null;
		$tanamFoto = $userFiles->where('kind', 'tanamFoto')->first()->file_url ?? null;
		$pupuk1Foto = $userFiles->where('kind', 'pupuk1Foto')->first()->file_url ?? null;
		$pupuk2Foto = $userFiles->where('kind', 'pupuk2Foto')->first()->file_url ?? null;
		$pupuk3Foto = $userFiles->where('kind', 'pupuk3Foto')->first()->file_url ?? null;
		$optFoto = $userFiles->where('kind', 'optFoto')->first()->file_url ?? null;
		$prodFoto = $userFiles->where('kind', 'prodFoto')->first()->file_url ?? null;

		$timelineItems = collect([
			['id' => 1, 'date' => $lokasi->lahandate, 'columnName' => 'lahanStatus', 'title' => 'Pengolahan Lahan', 'comment' => $lokasi->lahancomment, 'status' => $lokasi->lahanStatus, 'foto' => $lahanFoto],

			['id' => 2, 'date' => $lokasi->benihDate, 'columnName' => 'benihStatus', 'title' => 'Persiapan Benih', 'comment' => $lokasi->benihComment, 'value' => 'Jumlah Benih: ' . $lokasi->benihsize . ' kg', 'status' => $lokasi->benihStatus, 'foto' => $benihFoto],

			['id' => 3, 'date' => $lokasi->mulsaDate, 'columnName' => 'mulsaStatus', 'title' => 'Pemasangan Mulsa', 'comment' => $lokasi->mulsaComment, 'value' => 'Jumlah Mulsa: ' . $lokasi->mulsaSize . ' roll', 'status' => $lokasi->mulsaStatus, 'foto' => $mulsaFoto],

			['id' => 4, 'date' => $lokasi->tgl_tanam, 'columnName' => 'tanamStatus', 'title' => 'Penanaman', 'comment' => $lokasi->tanamComment, 'value' => 'Luas Tanam: ' . $lokasi->luas_tanam . ' m2', 'status' => $lokasi->tanamStatus, 'foto' => $tanamFoto],

			['id' => 5, 'date' => $lokasi->pupuk1Date, 'columnName' => 'pupuk1Status', 'title' => 'Pemupukan Pertama', 'comment' => $lokasi->pupuk1Comment, 'value' => 'Pupuk Organik: ' . $lokasi->organik1 . ' kg', 'value2' => 'NPK: ' . $lokasi->npk1 . ' kg', 'value3' => 'Dolomit: ' . $lokasi->dolomit1 . ' kg', 'value4' => 'ZA: ' . $lokasi->za1 . ' kg', 'status' => $lokasi->pupuk1Status, 'foto' => $pupuk1Foto],

			['id' => 6, 'date' => $lokasi->pupuk2Date, 'columnName' => 'pupuk2Status', 'title' => 'Pemupukan Kedua', 'comment' => $lokasi->pupuk2Comment, 'value' => 'Pupuk Organik: ' . $lokasi->organik2 . ' kg', 'value2' => 'NPK: ' . $lokasi->npk2 . ' kg', 'value3' => 'Dolomit: ' . $lokasi->dolomit2 . ' kg', 'value4' => 'ZA: ' . $lokasi->za2 . ' kg', 'status' => $lokasi->pupuk2Status, 'foto' => $pupuk2Foto],

			['id' => 7, 'date' => $lokasi->pupuk3Date, 'columnName' => 'pupuk3Status', 'title' => 'Pemupukan Ketiga', 'comment' => $lokasi->pupuk3Comment, 'value' => 'Pupuk Organik: ' . $lokasi->organik3 . ' kg', 'value2' => 'NPK: ' . $lokasi->npk3 . ' kg', 'value3' => 'Dolomit: ' . $lokasi->dolomit3 . ' kg', 'value4' => 'ZA: ' . $lokasi->za3 . ' kg', 'status' => $lokasi->pupuk3Status, 'foto' => $pupuk3Foto],

			['id' => 8, 'date' => $lokasi->optDate, 'columnName' => 'optStatus', 'title' => 'Pengendalian OPT', 'comment' => $lokasi->optComment, 'status' => $lokasi->optStatus, 'foto' => $optFoto],

			['id' => 9, 'date' => $lokasi->tgl_panen, 'columnName' => 'prodStatus', 'title' => 'Produksi/Panen', 'comment' => $lokasi->prodComment, 'value' => 'Jumlah Panen: ' . $lokasi->volume . ' kg', 'status' => $lokasi->prodStatus, 'foto' => $prodFoto],

			['id' => 10, 'date' => $lokasi->tgl_panen, 'columnName' => 'distStatus', 'title' => 'Distribusi hasil', 'comment' => $lokasi->distComment, 'value' => 'Disimpan: ' . $lokasi->vol_benih . ' kg', 'value2' => 'Dijual: ' . $lokasi->vol_jual . ' kg', 'status' => $lokasi->distStatus],
		]);

		$timelineItems = $timelineItems->map(function ($item) {
			// Format tanggal
			$item['date'] = $item['date'] ? Carbon::createFromFormat('Y-m-d', $item['date'])->format('d M Y') : null;
			if (!isset($item['status'])) {
				$item['status'] = null;
			}
			return $item;
		});

		$sortedTimelineItems = $timelineItems->sort(function ($a, $b) {
			$dateA = $a['date'] ? Carbon::createFromFormat('d M Y', $a['date']) : null;
			$dateB = $b['date'] ? Carbon::createFromFormat('d M Y', $b['date']) : null;

			// Jika tanggal sama, urutkan berdasarkan id
			if ($dateA == $dateB) {
				return $a['id'] <=> $b['id'];
			}

			// Jika salah satu tanggal null, tempatkan di akhir
			if ($dateA === null) {
				return 1;
			}

			if ($dateB === null) {
				return -1;
			}

			// Urutkan berdasarkan tanggal
			return $dateA <=> $dateB;
		});


		$data = [
			'ijin' => $ijin,
			'noIjin' => $noIjin,
			'lokasi' => $lokasi,
			'pks' => $pks,
			'spatial' => $spatial,
			'timelineItems' => $timelineItems,
		];
		// return response()->json($data);
		return view('t2024.verifikasi.produksi.checkLokasi', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'data', 'mapkey', 'kabupatens', 'ijin', 'lokasi', 'verifikasi'));
	}

	public function storePhaseCheck(Request $request, $noIjin, $tcode)
	{
		// Format the 'no_ijin' as needed
		$formattedNoIjin = $this->formatNoIjin($noIjin);

		DB::beginTransaction();

		try {
			$lokasi = Lokasi::where('tcode', $tcode)
				->first();
			if ($lokasi) {
				$columnName = $request->input('ColumnName');
				$columnValue = $request->input('InputField');

				if ($columnName && $columnValue !== null) {
					$lokasi->update([
						$columnName => $columnValue,
					]);

					DB::commit();
					return response()->json(['message' => 'Successfully updated']);
				} else {
					DB::rollBack();
					return response()->json(['message' => 'Column name or value missing.']);
				}
			} else {
				DB::rollBack();
				return response()->json(['message' => 'Lokasi not found.']);
			}
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['An error occurred while updating Lokasi: ' . $e->getMessage()]);
		}
	}

	/**
	 * akhir pemeriksaan di satu lokasi
	 */
	public function storelokasicheck(Request $request, $noIjin, $tcode)
	{
		// Format the 'no_ijin' as needed
		$formattedNoIjin = $this->formatNoIjin($noIjin);

		DB::beginTransaction();

		try {
			$lokasi = Lokasi::where('tcode', $tcode)
				->first();

			if (!$lokasi) {
				DB::rollBack();
				return redirect()->back()->with('error', 'Lokasi not found.');
			}

			$columns = [
				'tanamStatus',
				'lahanStatus',
				'benihStatus',
				'mulsaStatus',
				'pupuk1Status',
				'pupuk2Status',
				'pupuk3Status',
				'optStatus',
				'prodStatus',
				'distStatus'
			];

			$allFieldsPresent = collect($columns)->every(fn($col) => !is_null($lokasi->$col));
			if (!$allFieldsPresent) {
				DB::rollBack();
				return redirect()->back()->with('error', 'Anda belum menyelesaikan seluruh bagian pemeriksaan.');
			}

			$hasZeroValue = collect($columns)->contains(fn($col) => $lokasi->$col == 0);
			if ($hasZeroValue) {
				$lokasi->update([
					'status' => 0,
					'verif_p_at' => Carbon::now(),
				]);
				DB::commit();
				return redirect()->back()->with('success', 'Verifikasi di Lahan ini telah selesai. Dan dinyatakan TIDAK SESUAI');
			}

			$allFieldsOne = collect($columns)->every(fn($col) => $lokasi->$col == 1);
			if ($allFieldsOne) {
				$lokasi->update([
					'status' => 1,
					'verif_p_at' => Carbon::now(),
				]);
				DB::commit();
				return redirect()->back()->with('success', 'Verifikasi di Lahan ini telah selesai. Dan dinyatakan SESUAI');
			}

			DB::rollBack();
			return redirect()->back()->with('error', 'Unexpected error occurred.');
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('error', 'An error occurred while updating Lokasi: ' . $e->getMessage());
		}
	}

	public function checkfinal(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Verifikasi Tanam';
		$page_heading = 'Data Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $commitment->npwp);
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifikasi::where('tcode', $tcode)->first();
		// dd($verifikasi);
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.produksi.checkfinal', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	/**
	 * simpan pemeriksaan final
	 */
	public function storeFinalCheck(Request $request, $noIjin, $tcode)
	{
		$user = Auth::user();

		// Find the specific verification record
		$verifikasi = AjuVerifikasi::where('tcode', $tcode)->firstOrFail();
		$periodetahun = substr($noIjin, -4);
		$npwp = $verifikasi->npwp;

		$fileNpwp = str_replace(['.', '-'], '', $npwp);
		$fileNoIjin = str_replace(['/', '.'], '', $noIjin);

		try {
			DB::beginTransaction();

			$filenameBatanam = $verifikasi->fileBa;
			$filenameNdhprt = $verifikasi->fileNdhp;
			$directory = 'uploads/' . $fileNpwp . '/' . $periodetahun . '/' . $fileNoIjin;

			$pathBaTanam = null;
			$pathNdhprt = null;

			if ($request->hasFile('batanam')) {
				$file = $request->file('batanam');
				$request->validate([
					'batanam' => 'file|mimes:pdf|max:2048',
				]);

				$filenameBatanam = 'baVerifTanam_' . $fileNoIjin . '_' . time() . '.' . $file->extension();
				$file->storeAs($directory, $filenameBatanam, 'public');
				$pathBaTanam = $directory . '/' . $filenameBatanam;
			}

			if ($request->hasFile('ndhprt')) {
				$file = $request->file('ndhprt');
				$request->validate([
					'ndhprt' => 'file|mimes:pdf|max:2048',
				]);

				$filenameNdhprt = 'notdintanam_' . $fileNoIjin . '_' . time() . '.' . $file->extension();
				$file->storeAs($directory, $filenameNdhprt, 'public');
				$pathNdhprt = $directory . '/' . $filenameNdhprt;
			}

			$updateData = [
				'note' => $request->input('note'),
				'metode' => $request->input('metode'),
				'status' => $request->input('status'),
				'check_by' => $user->id,
				'verif_at' => Carbon::now(),
			];

			if ($pathBaTanam) {
				$updateData['fileBa'] = url($pathBaTanam);
			}

			if ($pathNdhprt) {
				$updateData['fileNdhp'] = url($pathNdhprt);
			}

			$verifikasi->update($updateData);


			DB::commit();

			// return redirect()->back()->with('success', 'Sukses');
			return redirect()->route('2024.verifikator.produksi.result', [$noIjin, $tcode])->with('success', 'Pemeriksaan Realisasi Komitmen Wajib Tanam dinyatakan Selesai dan Data berhasil disimpan.');
		} catch (\Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
		}
	}


	// public function storelokasicheck(Request $request, $noIjin, $spatial) this is OLD
	// {
	// 	$formattedNoIjin = substr($noIjin, 0, 4) . '/' .
	// 		substr($noIjin, 4, 2) . '.' .
	// 		substr($noIjin, 6, 3) . '/' .
	// 		substr($noIjin, 9, 1) . '/' .
	// 		substr($noIjin, 10, 2) . '/' .
	// 		substr($noIjin, 12, 4);

	// 	DB::beginTransaction();

	// 	try {
	// 		$lokasi = Lokasi::where('no_ijin', $formattedNoIjin)
	// 			->where('kode_spatial', $spatial)
	// 			->first();

	// 		if ($lokasi) {
	// 			$lokasi->update([
	// 				'status' => $request->input('statuslokasi'),
	// 			]);

	// 			DB::commit();

	// 			return response()->json([
	// 				'message' => 'Lokasi updated successfully.',
	// 				'lokasi' => $lokasi
	// 			], 200);
	// 		} else {
	// 			DB::rollBack();

	// 			return response()->json([
	// 				'message' => 'Lokasi not found.'
	// 			], 404);
	// 		}
	// 	} catch (\Exception $e) {
	// 		DB::rollBack();

	// 		return response()->json([
	// 			'message' => 'An error occurred while updating Lokasi.',
	// 			'error' => $e->getMessage()
	// 		], 500);
	// 	}
	// }


	public function result(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Produksi';
		$page_title = 'Ringkasan Verifikasi Produksi';
		$page_heading = 'Ringkasan Hasil Verifikasi Produksi';
		$heading_class = 'fal fa-file-check';
		$user = Auth::user();

		//menggunakan function datareport
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);
		$payload = $this->datareport($ijin, $tcode);
		return view('t2024.verifikasi.produksi.result', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'tcode', 'payload'));
	}

	public function generateReport($noIjin, $tcode)
	{
		// Using the datareport function to get the payload
		$payload = $this->datareport($noIjin, $tcode);

		// Render the HTML template with payload data
		$template = view('t2024.verifikasi.produksi.report', ['payload' => $payload])->render();

		// Prepare the directory and file name
		$npwp = str_replace(['.', '-'], '', $payload['npwp']);
		$periode = $payload['periode'];
		$fileName = 'report_vp_' .  $noIjin . '_' . $tcode . '_' . time() . '.pdf';
		$directory = 'uploads/' . $npwp . '/' . $periode . '/' . $noIjin;
		$path = $directory . '/' . $fileName;

		$ajuVerifProduksi = AjuVerifikasi::where('tcode', $tcode)
			->first();

		if ($ajuVerifProduksi) {
			$ajuVerifProduksi->update(['report_url' => url($path)]);
		}

		if (!Storage::disk('public')->exists($directory)) {
			Storage::disk('public')->makeDirectory($directory);
		}

		// Generate the PDF and save it
		Browsershot::html($template)
			->showBackground()
			->margins(4, 0, 4, 0,)
			->format('A4')
			->save(Storage::disk('public')->path($path));
		return redirect()->back()->with('success', 'Ringkasan Laporan Hasil Pemeriksaan berhasil dibuat.');

		// return response()->download(Storage::disk('public')->path($path));
	}

	public function datareport($noIjin, $tcode)
	{
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$verifProduksi = AjuVerifikasi::where('tcode', $tcode)->first() ?? new AjuVerifikasi();
		$verifTanam = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'TANAM')->latest()->first() ?? new AjuVerifikasi();
		$verifSkl = AjuVerifSkl::where('no_ijin', $noIjin)->latest()->first() ?? new AjuVerifSkl();
		$userFiles = UserFile::where('no_ijin', $noIjin)->whereIn('kind', ['sptjmtanam', 'sptjmproduksi', 'spvp', 'rta', 'rpo', 'sphtanam', 'sphproduksi', 'logbook', 'formLa'])->get() ?? new UserFile();
		$pks = Pks::where('no_ijin', $noIjin)->get() ?? new Pks();
		$lokasis = Lokasi::where('no_ijin', $noIjin)->get() ?? new Lokasi();
		$failPks = Pks::where('no_ijin', $noIjin)->where('status', 'Tidak Sesuai')->get() ?? new Pks();
		$failLokasi = Lokasi::where('no_ijin', $noIjin)
			->where(function ($query) {
				$query->where('status', 0)
					->orWhereNull('status');
			})
			->get();

		if ($failLokasi->isEmpty()) {
			$failLokasi = new Lokasi();
		}

		// Controller: Fetch data including related lokasi
		$daftarLokasi = Pks::where('no_ijin', $noIjin)->with('lokasi')->get() ?? new Pks();

		$tglIjin = $commitment->tgl_ijin;
		$tglAkhir = $commitment->tgl_akhir;

		// Now use these dates in your query
		$failTime = Lokasi::select('id', 'kode_spatial', 'kode_poktan', 'no_ijin', 'tgl_tanam', 'tgl_panen', 'ktp_petani', 'verif_p_at')
			->where('no_ijin', $noIjin)
			->where(function ($query) use ($tglIjin, $tglAkhir) {
				$query->where(function ($query) use ($tglIjin, $tglAkhir) {
					$query->whereNull('tgl_tanam')
						->orWhere('tgl_tanam', '<', $tglIjin)
						->orWhere('tgl_tanam', '>', $tglAkhir);
				})
					->orWhere(function ($query) use ($tglIjin, $tglAkhir) {
						$query->whereNull('tgl_panen')
							->orWhere('tgl_panen', '<', $tglIjin)
							->orWhere('tgl_panen', '>', $tglAkhir);
					});
			})
			->get();

		// dd($failTime);

		$payload = [
			'company' => $commitment->datauser->company_name,
			'npwp' => $commitment->datauser->npwp_company,
			'ijin' => $ijin,
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
			'countPoktan' => $commitment->lokasi->groupBy('kode_poktan')->count(),
			'countPks' => $pks->where('berkas_pks', '!=', null)->count(),
			'countSpatial' => $lokasis->count(),
			'countTanam' => $lokasis->where('luas_tanam', '!=', null)->count(),
			'userFiles' => $userFiles,
			'daftarLokasi' => $daftarLokasi,
			'failPks' => $failPks,
			'failTime' => $failTime,
			'failLokasi' => $failLokasi,
			'ajuTanam' => $verifTanam,
			'ajuProduksi' => $verifProduksi,
		];

		return $payload;
	}

	//mobile
	public function findmarker(Request $request)
	{
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Simulator Spatial';
		$page_heading = 'Marker Finder Simulator';
		$heading_class = 'fal fa-map-marked-alt';

		$ijins = PullRiph::select('no_ijin')->get();

		$myLocus = [
			[
				'id' => 1,
				'latitude' => -6.286147,
				'longitude' => 106.838966,
				'name' => 'Kantor Ditjen Hortikultura',
			],
			[
				'id' => 2,
				'latitude' => -6.66440,
				'longitude' => 106.863234,
				'name' => 'Lokasi Pengujian 1',
			],
			[
				'id' => 3,
				'latitude' => -7.150,
				'longitude' => 109.952,
				'name' => 'Kebun Kendal',
			],
		];

		$mapkey = ForeignApi::find(1);
		return view('t2024.verifikasi.produksi.simulator', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'ijins', 'myLocus'));
	}

	public function veriflokasimobile(Request $request, $noIjin, $spatial)
	{
		$ijin = $noIjin;
		$spatial = $spatial;
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Simulator Spatial';
		$page_heading = 'Verifikasi Lahan ' . $spatial;
		$heading_class = 'fal fa-map-marker';

		$noIjin = $this->formatNoIjin($noIjin);

		$data = Lokasi::where('kode_spatial', $spatial)
			->where('no_ijin', $noIjin)
			->with(['masteranggota', 'pks'])
			->first();

		$mapkey = ForeignApi::find(1);
		return view('t2024.verifikasi.produksi.veriflokasimobile', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'data', 'ijin', 'noIjin', 'spatial'));
	}
}
