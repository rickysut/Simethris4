<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifTanam;
use App\Models2024\AssignmentTanam;
use App\Models2024\ForeignApi;
use App\Models2024\Lokasi;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
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

class VerifTanamController extends Controller
{
	public function indexpengajuan()
	{
		abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi';
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

		$module_name = 'Verifikasi';
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

		$anggotaCount = Lokasi::where('no_ijin', $noIjin)->distinct('ktp_petani')->count();
		$luasTanam = Lokasi::where('no_ijin', $noIjin)->sum('luas_tanam');
		$jmlLokasi = Lokasi::where('no_ijin', $noIjin)->count();

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

		// return response()->json($commitment);

		return view('t2024.commitment.assignment', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'assignees', 'verifikators', 'commitment', 'ijin', 'avt', 'pksCount', 'anggotaCount', 'luasTanam', 'jmlLokasi'));
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

			$avt = AjuVerifTanam::select('id', 'tcode')->where('tcode', $tcode)->first();

			$request->validate([
				'user_id' => 'required|exists:users,id',
				'no_sk' => 'required|string|max:255',
				'tgl_sk' => 'required|date',
				'fileSk' => 'required|mimes:pdf|max:2048',
			]);

			DB::beginTransaction();
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
			return redirect()->back()->with('success', 'Assignment successfully created and file uploaded.');
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



	public function index()
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi';
		$page_title = 'Pengajuan Verifikasi';
		$page_heading = 'Daftar Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-search';

		return view('t2024.verifikasi.tanam.index', compact('module_name', 'page_title', 'page_heading', 'heading_class'));
	}

	public function check(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi';
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

		return view('t2024.verifikasi.tanam.check', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'npwp', 'mapkey', 'verifikasi'));
	}

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


	public function verifPksStore(Request $request, $tcode)
	{
		$user = Auth::user();

		$verifikasi = Pks::where('tcode', $tcode)->first();
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

	public function storelokasicheck(Request $request, $noIjin, $spatial)
	{
		$formattedNoIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		DB::beginTransaction();

		try {
			$lokasi = Lokasi::where('no_ijin', $formattedNoIjin)
				->where('kode_spatial', $spatial)
				->first();

			if ($lokasi) {
				$lokasi->update([
					'status' => $request->input('statuslokasi'),
				]);

				DB::commit();

				return response()->json([
					'message' => 'Lokasi updated successfully.',
					'lokasi' => $lokasi
				], 200);
			} else {
				DB::rollBack();

				return response()->json([
					'message' => 'Lokasi not found.'
				], 404);
			}
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'message' => 'An error occurred while updating Lokasi.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function markStatus(Request $request, $noIjin, $tcode, $status)
	{
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$verifikasi = AjuVerifTanam::where('tcode', $tcode)->first();
		$verifikasi->update([
			'status' => $status,
		]);

		return response()->json([
			'message' => 'success',
			'status' => $status
		], 200);
	}

	public function store(Request $request, $noIjin)
	{
		dd($noIjin);
	}

	public function result(Request $request, $noIjin)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi';
		$page_title = 'Ringkasan Verifikasi Tanam';
		$page_heading = 'Ringkasan Hasil Verifikasi Tanam';
		$heading_class = 'fal fa-file-check';

		$user = Auth::user();
		$ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		return view('t2024.verifikasi.tanam.result', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin'));
	}


	//mobile
	public function findmarker(Request $request)
	{
		$module_name = 'Verifikasi';
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
				'latitude' => -7.34105,
				'longitude' => 110.0749047922466,
				'name' => 'Kebun Temanggung',
			],
		];

		$mapkey = ForeignApi::find(1);
		return view('t2024.verifikasi.tanam.simulator', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'ijins', 'myLocus'));
	}

	public function veriflokasimobile(Request $request, $noIjin, $spatial)
	{
		$ijin = $noIjin;
		$spatial = $spatial;
		$module_name = 'Verifikasi';
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
