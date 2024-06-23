<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifTanam;
use App\Models2024\ForeignApi;
use App\Models2024\Lokasi;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
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
}
