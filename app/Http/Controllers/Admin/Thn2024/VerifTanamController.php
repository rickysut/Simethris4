<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifProduksi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\AjuVerifTanam;
use App\Models2024\AssignmentTanam;
use App\Models2024\ForeignApi;
use App\Models2024\Lokasi;
use App\Models2024\MasterSpatial;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models\MasterKabupaten;
use App\Models\User;
use App\Models\UserDocs;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;

class VerifTanamController extends Controller
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
	public function indexpengajuan()
	{
		abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Pengajuan Verifikasi';
		$page_heading = 'Daftar Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		$assignees = User::select('id', 'name', 'username')->whereHas('roles', function ($query) {
			$query->where('id', 3);
		})->get();

		// return response()->json($assignees);

		return view('t2024.commitment.indexAvt', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'assignees'));
	}

	public function assignment($noIjin, $tcode)
	{
		abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'Verifikasi Tanam';
		$page_title = 'Penugasan Verifikator';
		$page_heading = 'Penugasan Verifikasi Tanam';
		$heading_class = 'fal fa-file-check';

		$ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$userDocs = UserDocs::where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $userDocs->npwp);
		$commitment = PullRiph::select('no_ijin', 'periodetahun', 'tgl_ijin', 'tgl_akhir', 'nama', 'npwp')
			->with([
				'datauser:id,npwp_company,logo'
			])
			->where('no_ijin', $noIjin)->first();
		$periodetahun = $commitment->periodetahun;
		$avt = AjuVerifTanam::select('id', 'tcode', 'created_at')
			->where('tcode', $tcode)
			->first();

		$pksCount = Pks::select('id', 'no_ijin')
			->where('no_ijin', $noIjin)->count();

		$lokasis = Lokasi::where('no_ijin', $noIjin)
			->select('ktp_petani', 'luas_tanam', 'kode_spatial')
			->get();

		$kodeSpatials = $lokasis->pluck('kode_spatial')->unique();

		$kabupatenIds = MasterSpatial::whereIn('kode_spatial', $kodeSpatials)
			->pluck('kabupaten_id')
			->unique();

		$kabupaten = MasterKabupaten::whereIn('kabupaten_id', $kabupatenIds)
			->select('kabupaten_id', 'nama_kab')
			->get();

		$anggotaCount = $lokasis->pluck('ktp_ptani')->unique()->count();
		$luasTanam = $lokasis->sum('luas_tanam');
		$jmlLokasi = $lokasis->count();

		$verifikators = AssignmentTanam::select('id', 'tcode', 'user_id', 'pengajuan_id', 'no_sk', 'tgl_sk', 'file')
			->where('pengajuan_id', $avt->id)
			->with(['user:id,name'])
			->get();

		$verifikatorUserIds = $verifikators->pluck('user_id')->toArray();

		$assignees = User::select('id', 'name', 'username')
			->whereHas('roles', function ($query) {
				$query->where('id', 3);
			})
			->whereNotIn('id', $verifikatorUserIds)
			->get();

		// return response()->json($kabupaten);

		return view('t2024.commitment.assignment', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'assignees', 'verifikators', 'commitment', 'ijin', 'avt', 'pksCount', 'anggotaCount', 'luasTanam', 'jmlLokasi', 'kabupaten'));
	}

	//tcode milik ajuveriftanam
	public function storeAssignment(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		try {
			$noIjin = substr($noIjin, 0, 4) . '/' .
				substr($noIjin, 4, 2) . '.' .
				substr($noIjin, 6, 3) . '/' .
				substr($noIjin, 9, 1) . '/' .
				substr($noIjin, 10, 2) . '/' .
				substr($noIjin, 12, 4);

			$commitment = PullRiph::select('no_ijin', 'npwp', 'periodetahun')
				->where('no_ijin', $noIjin)
				->firstOrFail();

			$realnpwp = $commitment->npwp;
			$npwp = str_replace(['.', '-'], '', $realnpwp);

			$avt = AjuVerifTanam::select('id', 'tcode', 'status')->where('tcode', $tcode)->first();

			$request->validate([
				'user_id' => 'required|exists:users,id',
				'no_sk' => 'required|string|max:255',
				'tgl_sk' => 'required|date',
				'fileSk' => 'required|mimes:pdf|max:2048',
			]);

			DB::beginTransaction();

			if ($avt->status === null || $avt->status === '') {
				return redirect()->back()->withErrors(['status' => 'Status pengajuan belum sesuai']);
			} elseif ($avt->status == 0) {
				AjuVerifTanam::where('tcode', $tcode)->update(['status' => 1]);
			}

			$newTcode = time();
			$assignment = AssignmentTanam::create([
				'tcode' => $newTcode,
				'pengajuan_id' => $avt->id,
				'user_id' => $request->input('user_id'),
				'no_sk' => $request->input('no_sk'),
				'tgl_sk' => $request->input('tgl_sk'),
			]);

			if ($request->hasFile('fileSk')) {
				$time = time();
				$fileName = 'SK_' . $newTcode . '_' . $time . '.pdf';
				$filePath = $request->file('fileSk')->storeAs('uploads/' . $npwp . '/' . $commitment->periodetahun, $fileName, 'public');
				$assignment->file = $fileName;
				$assignment->save();
			}

			DB::commit();
			return redirect()->back()->with('success', 'Penugasan sudah dibuat dan berkas diunggah.');
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->withErrors(['error' => $e->getMessage()]);
		}
	}

	public function deleteAssignment($tcode)
	{
		try {
			$assignment = AssignmentTanam::where('tcode', $tcode)->firstOrFail();
			$assignment->delete();

			return response()->json(['success' => 'Assignment successfully deleted.']);
		} catch (\Exception $e) {
			return response()->json(['error' => 'Failed to delete assignment: ' . $e->getMessage()], 500);
		}
	}


	/**
	 * ini ketika sudah assignment, muncul di verifikator
	 */
	public function index()
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Tugas Verifikasi';
		$page_heading = 'Daftar Tugas Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		return view('t2024.verifikasi.tanam.index', compact('module_name', 'page_title', 'page_heading', 'heading_class'));
	}

	/**
	 * menandai fase pemeriksaan (status pada tabel avtanams)
	 */
	public function markStatus(Request $request, $noIjin, $tcode, $status)
	{
		// Format nomor ijin
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		// Array untuk status text
		$statusTextArray = [
			1 => 'Berkas-berkas',
			2 => 'PKS',
			3 => 'Timeline',
			4 => 'Lokasi Tanam',
			5 => 'Akhir'
		];

		// Ambil verifikasi berdasarkan tcode
		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->first();

		// Set current phase dan next phase
		$currentPhase = $statusTextArray[$status];
		$nextPhase = array_key_exists($status + 1, $statusTextArray) ? $statusTextArray[$status + 1] : 'Tidak ada tahap berikutnya';

		// Validasi status yang diberikan
		if (!array_key_exists($status, $statusTextArray)) {
			return redirect()->back()->with('success', 'Tahap Pemeriksaan ' . $currentPhase . ' sudah selesai, lanjutkan ke tahap pemeriksaan ' . $nextPhase . '.');
		}

		// Pastikan status yang baru lebih besar atau sama dengan status saat ini
		if ($status < $verifikasi->status) {
			return redirect()->back()->with('success', 'Tahap Pemeriksaan ' . $currentPhase . ' sudah selesai, lanjutkan ke tahap pemeriksaan ' . $nextPhase . '.');
		}

		// Update status di database
		$verifikasi->update(['status' => $status]);

		// Redirect dengan pesan sukses
		return redirect()->back()->with('success', 'Tahap Pemeriksaan ' . $currentPhase . ' sudah selesai, lanjutkan ke tahap pemeriksaan ' . $nextPhase . '.');
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
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$userDocs = UserDocs::where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $userDocs->npwp);
		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.tanam.check', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	/**
	 * menandai pemeriksaan berkas di user_docs
	 */
	public function saveCheckBerkas(Request $request, $noIjin)
	{
		$user = Auth::user();

		// Format ulang nomor ijin sesuai kebutuhan
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		// Daftar nama field yang diperbolehkan untuk diperbarui
		$allowedFields = [
			'sptjmtanamcheck',
			'spvtcheck',
			'rtacheck',
			'sphtanamcheck',
			'spdstcheck',
			'logbooktanamcheck',
		];

		// Inisialisasi array untuk menampung data yang akan diupdate
		$updateData = [];

		// Periksa satu per satu field yang diperbolehkan
		foreach ($allowedFields as $field) {
			if ($request->has($field)) {
				$updateData[$field] = $request->input($field);
			}
		}

		// Pastikan ada setidaknya satu field yang diperbarui
		if (!empty($updateData)) {
			$userdocs = UserDocs::where('no_ijin', $noIjin)->first();

			// Update hanya field yang diterima dari permintaan
			$userdocs->update(array_merge($updateData, [
				'tanamcheck_by' => $user->id,
				'tanamverif_at' => now(),
			]));

			return response()->json([
				'message' => 'success'
			], 200);
		}

		// Mengembalikan respon jika tidak ada field yang ditemukan atau diizinkan untuk diperbarui
		return response()->json([
			'message' => 'failed',
			'error' => 'No valid fields found in request'
		], 400);
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
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$userDocs = UserDocs::where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $userDocs->npwp);
		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.tanam.checkpks', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	/**
	 * menandai pemeriksaan berkas PKS (dan status pemeriksaan di avtanams)
	 */
	public function verifPksStore(Request $request, $noIjin, $kodePoktan)
	{
		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

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
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$userDocs = UserDocs::where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $userDocs->npwp);
		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.tanam.checktimeline', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	public function checkdaftarlokasi(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Verifikasi Tanam';
		$page_heading = 'Data Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$userDocs = UserDocs::where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $userDocs->npwp);
		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.tanam.checkdaftarlokasi', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	public function verifLokasiByIjinBySpatial($noIjin, $verifikasi,$tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$module_name = 'Verifikasi Tanam';
		$page_title = 'Verifikasi Realisasi Tanam';
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

		$data = [
			'ijin' => $ijin,
			'noIjin' => $noIjin,
			'lokasi' => $lokasi,
			'pks' => $pks,
			'spatial' => $spatial,
			// 'anggota' => $spatial->anggota,
		];
		// return response()->json($data);
		return view('t2024.verifikasi.tanam.checkLokasi', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'data', 'mapkey', 'kabupatens', 'ijin', 'lokasi', 'verifikasi'));
	}

	public function storePhaseCheck(Request $request, $noIjin, $tcode)
	{
		// Format the 'no_ijin' as needed
		$formattedNoIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

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

					// return response()->json([
					// 	'message' => 'Lokasi updated successfully.',
					// 	'lokasi' => $lokasi
					// ], 200);
					return redirect()->back()->with('success', 'Lokasi updated successfully.');
				} else {
					DB::rollBack();
					return redirect()->back()->with('error', 'Column name or value missing.');
					// return response()->json([
					// 	'message' => 'Column name or value missing.'
					// ], 400);
				}
			} else {
				DB::rollBack();
				return redirect()->back()->with('error', 'Lokasi not found.');
				// return response()->json([
				// 	'message' => 'Lokasi not found.'
				// ], 404);
			}
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('error', 'An error occurred while updating Lokasi: ' . $e->getMessage());
			// return response()->json([
			// 	'message' => 'An error occurred while updating Lokasi.',
			// 	'error' => $e->getMessage()
			// ], 500);
		}
	}

	/**
	 * akhir pemeriksaan di satu lokasi
	 */
	public function storelokasicheck(Request $request, $noIjin, $tcode)
	{
		// Format the 'no_ijin' as needed
		$formattedNoIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		DB::beginTransaction();

		try {
			$lokasi = Lokasi::where('tcode', $tcode)
				->first();

			if (!$lokasi) {
				DB::rollBack();
				return redirect()->back()->with('error', 'Lokasi not found.');
			}

			$columns = [
				'tanamStatus', 'lahanStatus', 'benihStatus', 'mulsaStatus',
				'pupuk1Status', 'pupuk2Status', 'pupuk3Status', 'optStatus'
			];

			$allFieldsPresent = collect($columns)->every(fn ($col) => !is_null($lokasi->$col));
			if (!$allFieldsPresent) {
				DB::rollBack();
				return redirect()->back()->with('error', 'Anda belum menyelesaikan seluruh bagian pemeriksaan.');
			}

			$hasZeroValue = collect($columns)->contains(fn ($col) => $lokasi->$col == 0);
			if ($hasZeroValue) {
				$lokasi->update(['status' => 0]);
				DB::commit();
				return redirect()->back()->with('success', 'Verifikasi di Lahan ini telah selesai. Dan dinyatakan TIDAK SESUAI');
			}

			$allFieldsOne = collect($columns)->every(fn ($col) => $lokasi->$col == 1);
			if ($allFieldsOne) {
				$lokasi->update(['status' => 1]);
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
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$userDocs = UserDocs::where('no_ijin', $noIjin)->first();
		$npwp = str_replace(['.', '-'], '', $userDocs->npwp);
		$commitment = PullRiph::select('no_ijin', 'periodetahun')->where('no_ijin', $noIjin)->first();
		$periodetahun = $commitment->periodetahun;
		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->first();
		$mapkey = ForeignApi::find(1);

		// return response()->json($verifikasi);

		return view('t2024.verifikasi.tanam.checkfinal', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'npwp', 'mapkey', 'verifikasi', 'tcode'));
	}

	/**
	 * simpan pemeriksaan final
	 */
	public function storeFinalCheck(Request $request, $noIjin, $tcode)
	{
		$user = Auth::user();

		// Find the specific verification record
		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->firstOrFail();
		$periodetahun = substr($noIjin, -4);
		$npwp = $verifikasi->npwp;

		$fileNpwp = str_replace(['.', '-'], '', $npwp);
		$fileNoIjin = str_replace(['/', '.'], '', $noIjin);

		try {
			DB::beginTransaction();

			$filenameBatanam = $verifikasi->batanam;
			$filenameNdhprt = $verifikasi->ndhprt;

			if ($request->hasFile('batanam')) {
				$file = $request->file('batanam');

				$request->validate([
					'batanam' => 'file|mimes:pdf|max:2048',
				]);

				$filenameBatanam = 'batanam_' . $fileNoIjin . '_' . time() . '.' . $file->extension();
				$file->storeAs('uploads/' . $fileNpwp . '/' . $periodetahun, $filenameBatanam, 'public');
			}

			if ($request->hasFile('ndhprt')) {
				$file = $request->file('ndhprt');

				$request->validate([
					'ndhprt' => 'file|mimes:pdf|max:2048',
				]);

				$filenameNdhprt = 'notdintanam_' . $fileNoIjin . '_' . time() . '.' . $file->extension();
				$file->storeAs('uploads/' . $fileNpwp . '/' . $periodetahun, $filenameNdhprt, 'public');
			}

			$verifikasi->update([
				'note' => $request->input('note'),
				'metode' => $request->input('metode'),
				'status' => $request->input('status'),
				'check_by' => $user->id,
				'verif_at' => Carbon::now(),
				'batanam' => $filenameBatanam, // the filename
				'ndhprt' => $filenameNdhprt, // the filename
			]);

			DB::commit();

			// return redirect()->back()->with('success', 'Sukses');
			return redirect()->route('2024.verifikator.tanam.result', [$noIjin, $tcode])->with('success', 'Pemeriksaan Realisasi Komitmen Wajib Tanam dinyatakan Selesai dan Data berhasil disimpan.');
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
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Ringkasan Verifikasi Tanam';
		$page_heading = 'Ringkasan Hasil Verifikasi Tanam';
		$heading_class = 'fal fa-file-check';
		$user = Auth::user();

		//menggunakan function datareport
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);
		$payload = $this->datareport($ijin, $tcode);

		return view('t2024.verifikasi.tanam.result', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'tcode', 'payload'));
	}

	public function generateReport($noIjin, $tcode)
	{
		//menggunakan function datareport
		$payload = $this->datareport($noIjin, $tcode);

		$template = view('t2024.verifikasi.tanam.report', ['payload' => $payload])->render();

        // app/public/updloads/ . $payload['npwp'] . '/' . $payload['periode'] . '/' . $noIjin . 'report_avt_' . $tcode . fileextention
		$pdfPath = storage_path('app/public/uploads/example.pdf');

		Browsershot::html($template)
			->showBackground()
			->margins(4, 0, 4, 0)
			->format('A4')
			->save($pdfPath);

		return response()->download($pdfPath);
	}

	public function datareport ($noIjin, $tcode)
	{
		$ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$verifTanam = AjuVerifTanam::where('tcode', $tcode)->first() ?? new AjuVerifTanam();
		$verifProduksi = AjuVerifProduksi::where('no_ijin', $noIjin)->latest()->first() ?? new AjuVerifProduksi();
		$verifSkl = AjuVerifSkl::where('no_ijin', $noIjin)->latest()->first() ?? new AjuVerifSkl();
		$userDocs = UserDocs::where('no_ijin', $noIjin)->first() ?? new UserDocs();
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

		$tglIjin = $commitment->tgl_ijin;
		$tglAkhir = $commitment->tgl_akhir;

		// Now use these dates in your query
		$failTime = Lokasi::select('id', 'kode_spatial', 'kode_poktan', 'no_ijin', 'tgl_tanam', 'tgl_panen', 'ktp_petani', 'verifAt')
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
			'userDocs' => $userDocs,
			'failPks' => $failPks,
			'failTime' => $failTime,
			'failLokasi' => $failLokasi,
			'ajuTanam' => $verifTanam,
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
		return view('t2024.verifikasi.tanam.simulator', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'ijins', 'myLocus'));
	}

	public function veriflokasimobile(Request $request, $noIjin, $spatial)
	{
		$ijin = $noIjin;
		$spatial = $spatial;
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Simulator Spatial';
		$page_heading = 'Verifikasi Lahan ' . $spatial;
		$heading_class = 'fal fa-map-marker';

		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$data = Lokasi::where('kode_spatial', $spatial)
			->where('no_ijin', $noIjin)
			->with(['masteranggota', 'pks'])
			->first();

		$mapkey = ForeignApi::find(1);
		return view('t2024.verifikasi.tanam.veriflokasimobile', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'data', 'ijin', 'noIjin', 'spatial'));
	}
}
