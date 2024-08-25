<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifikasi;
use App\Models2024\AjuVerifProduksi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\AssignmentVerifikasi;
use App\Models2024\Lokasi;
use App\Models2024\MasterSpatial;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models2024\UserFile;
use App\Models\MasterKabupaten;
use App\Models\User;

use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
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

	//halaman daftar pengajuan untuk Administrator
	public function index(Request $request)
	{
		$module_name = 'Proses RIPH';
		$page_title = 'Daftar Pengajuan Verifikasi';
		$page_heading = 'Daftar Pengajuan Verifikasi';
		$heading_class = 'fal fa-ballot-check';

		// Mengambil data dari model AjuVerifikasi dengan status < 6
		$ajuVerif = AjuVerifikasi::where('status', '<', 6)
			->with('commitment')
			->with([
				'verifikator:id,name',
				'datauser:id,npwp_company,company_name',
				'commitment:id,no_ijin,periodetahun',
				'assignments:id,tcode,pengajuan_id,user_id',
				'assignments.user:id,name'
			])
			->get()
			->map(function ($item) {
				// Menambahkan kolom bayangan untuk nama tabel atau model
				$item->table_name = 'Tanam';
				return $item;
			});

		// Mengembalikan data dalam format JSON
		return response()->json($ajuVerif);


		// dd($verifSkls);
		/**
		 * memerlukan:
		 * migrasi dan model file verifSkl dan table avskl
		 * controller untuk verifikasi dan pengajuan SKL
		 * syarat pengajuan:
		 * 1. Volume Produksi yang dilaporkan sudah >100%
		 * 2. hasil verifikasi produksi = 8/telah di verifikasi produksi (cek lagi kode status)
		 */

		// return view('admin.pengajuan.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'verifTanams', 'verifProduksis', 'verifSkls'));
	}

	public function indexpengajuan()
	{
		abort_if(Gate::denies('administrator_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'Verifikasi';
		$page_title = 'Pengajuan Verifikasi';
		$page_heading = 'Daftar Pengajuan Verifikasi';
		$heading_class = 'fal fa-file-search';

		$assignees = User::select('id', 'name', 'username')->whereHas('roles', function ($query) {
			$query->where('id', 3);
		})->get();

		// return response()->json($assignees);

		return view('t2024.commitment.indexAvt', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'assignees'));
	}

	// prosedur pengajuan verifikasi
	public function submitPengajuan(Request $request, $noIjin)
	{
		// Format `noIjin` sesuai kebutuhan
		$formattedNoIjin = $this->formatNoIjin($noIjin);

		// Mendapatkan `npwp` dari user yang sedang login
		$npwp = Auth::user()->data_user->npwp_company;

		// Ambil input `kind` dari request tanpa validasi
		$kind = $request->input('kind');

		// Membuat entri baru di tabel `AjuVerifikasi`
		AjuVerifikasi::create([
			'kind' => $kind,
			'tcode' => time(),
			'npwp' => $npwp,
			'no_ijin' => $formattedNoIjin,
			'status' => 0,
		]);

		// Redirect kembali dengan pesan sukses
		return redirect()->back()->with('success', 'Permohonan Verifikasi ' . $kind . ' berhasil dikirimkan.');
	}

	//untuk verifikasi tanam dan produksi
	public function assignment($noIjin, $tcode)
	{
		abort_if(Gate::denies('administrator_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'Verifikasi';
		$page_title = 'Penugasan Verifikator';
		$page_heading = 'Penugasan Verifikasi';
		$heading_class = 'fal fa-file-check';

		$ijin = $noIjin;
		$formattedNoIjin = $this->formatNoIjin($noIjin);

		$userDocs = UserFile::where('no_ijin', $formattedNoIjin)->first();
		$commitment = PullRiph::select('no_ijin', 'periodetahun', 'tgl_ijin', 'tgl_akhir', 'nama', 'npwp')
			->with([
				'datauser:id,npwp_company,logo'
			])
			->where('no_ijin', $formattedNoIjin)->first();
		$npwp = str_replace(['.', '-'], '', $commitment->npwp);

		$periodetahun = $commitment->periodetahun;
		$avt = AjuVerifikasi::select('id', 'tcode', 'created_at')
			->where('tcode', $tcode)
			->first();

		$pksCount = Pks::select('id', 'no_ijin')
			->where('no_ijin', $formattedNoIjin)->count();

		$lokasis = Lokasi::where('no_ijin', $formattedNoIjin)
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

		//perlu di uji lagi
		$allVerifikators = AssignmentVerifikasi::select('id', 'no_ijin', 'tcode', 'user_id', 'pengajuan_id', 'no_sk', 'tgl_sk', 'file')
			->where('no_ijin', $formattedNoIjin)
			->with(['user:id,name'])
			->get();

		$theseVerifikators = AssignmentVerifikasi::select('id', 'no_ijin', 'tcode', 'user_id', 'pengajuan_id', 'no_sk', 'tgl_sk', 'file')
			->where('no_ijin', $formattedNoIjin)
			->where('kode_pengajuan', $tcode)
			->with(['user:id,name'])
			->get();

		if ($theseVerifikators->isEmpty()) {
			// If $theseVerifikators is empty, use $allVerifikators
			$verifikators = $allVerifikators->map(function ($item) {
				$item->source = 'allVerifikators';
				return $item;
			});
		} else {
			// If $theseVerifikators is not empty, compare user_ids
			$userIdsAll = $allVerifikators->pluck('user_id')->unique()->sort()->values();
			$userIdsThese = $theseVerifikators->pluck('user_id')->unique()->sort()->values();

			if ($userIdsAll->toArray() == $userIdsThese->toArray()) {
				// If all user_ids match, use $theseVerifikators
				$verifikators = $theseVerifikators->map(function ($item) {
					$item->source = 'theseVerifikators';
					return $item;
				});
			} else {
				// If they don't match completely, combine them
				$verifikators = collect();

				// Add entries from $theseVerifikators, marked as such
				foreach ($theseVerifikators as $item) {
					$item->source = 'theseVerifikators';
					$verifikators->push($item);
				}

				// Add remaining entries from $allVerifikators that are not in $theseVerifikators
				foreach ($allVerifikators as $item) {
					if (!$userIdsThese->contains($item->user_id)) {
						$item->source = 'allVerifikators';
						$verifikators->push($item);
					}
				}
			}
		}

		$verifikatorUserIds = $verifikators->pluck('user_id')->toArray();
		// dd($verifikators);
		$assignees = User::select('id', 'name', 'username')
			->whereHas('roles', function ($query) {
				//role verifikator
				$query->where('id', 3);
			})
			->whereNotIn('id', $verifikatorUserIds)
			->get();

		//ini untuk periksa saja:
		// return response()->json($verifikators);

		return view('t2024.commitment.assignment', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'assignees', 'verifikators', 'commitment', 'ijin', 'avt', 'pksCount', 'anggotaCount', 'luasTanam', 'jmlLokasi', 'kabupaten'));
	}

	//tcode milik ajuverifikasi
	public function storeAssignment(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('administrator_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		try {
			$ijin = $noIjin;
			$formattedNoIjin = $this->formatNoIjin($noIjin);

			// Fetch commitment data and extract NPWP number
			$commitment = PullRiph::select('no_ijin', 'npwp', 'periodetahun')
				->where('no_ijin', $formattedNoIjin)
				->firstOrFail();

			$realnpwp = $commitment->npwp;
			$npwp = str_replace(['.', '-'], '', $realnpwp);

			// Fetch the relevant AjuVerifikasi record
			$avt = AjuVerifikasi::select('id', 'tcode', 'status')
				->where('tcode', $tcode)
				->first();

			// Debugging - Check the content of $avt
			// dd($avt);

			// Validate input data
			$request->validate([
				'no_sk' => 'required|string|max:255',
				'tgl_sk' => 'required|date',
				'fileSk' => 'required|mimes:pdf|max:2048',
			]);

			DB::beginTransaction();

			// Check and update status
			if (is_null($avt->status) || $avt->status === '') {
				return redirect()->back()->withErrors(['status' => 'Status pengajuan belum sesuai']);
			} elseif ($avt->status == 0) {
				$avt->update(['status' => 1]);
			}

			// Create a new assignment
			$newTcode = time();
			$assignment = AssignmentVerifikasi::create([
				'tcode' => $newTcode,
				'pengajuan_id' => $avt->id,
				'kode_pengajuan' => $avt->tcode,
				'no_ijin' => $formattedNoIjin,
				'user_id' => $request->input('user_id'),
				'no_sk' => $request->input('no_sk'),
				'tgl_sk' => $request->input('tgl_sk'),
			]);

			// Handle file upload
			$directory = 'uploads/' . $npwp . '/' . $commitment->periodetahun . '/' . $ijin;

			if ($request->hasFile('fileSk')) {
				$file = $request->file('fileSk');
				$fileName = 'SK_' . $ijin . '_' . $newTcode . '_' . time() . '.' . $file->extension();
				$file->storeAs($directory, $fileName, 'public');
				$path = $directory . '/' . $fileName;
				$assignment->update([
					'file' => url($path),
				]);
			}

			DB::commit();
			return redirect()->back()->with('success', 'Penugasan sudah dibuat dan berkas diunggah.');
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->withErrors(['error' => $e->getMessage()]);
		}
	}
	public function reAssignment(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('administrator_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		try {
			$ijin = $noIjin;
			$formattedNoIjin = $this->formatNoIjin($noIjin);

			// Fetch commitment data and extract NPWP number
			$commitment = PullRiph::select('no_ijin', 'npwp', 'periodetahun')
				->where('no_ijin', $formattedNoIjin)
				->firstOrFail();

			$realnpwp = $commitment->npwp;
			$npwp = str_replace(['.', '-'], '', $realnpwp);

			// Fetch the relevant AjuVerifikasi record
			$avt = AjuVerifikasi::select('id', 'tcode', 'status')
				->where('tcode', $tcode)
				->first();

			// Debugging - Check the content of $avt

			// Validate input data
			$request->validate([
				'no_sk' => 'required|string|max:255',
				'tgl_sk' => 'required|date',
			]);

			DB::beginTransaction();

			// Check and update status
			if (is_null($avt->status) || $avt->status === '') {
				return redirect()->back()->withErrors(['status' => 'Status pengajuan belum sesuai']);
			} elseif ($avt->status == 0) {
				$avt->update(['status' => 1]);
			}

			// Create a new assignment
			$newTcode = time();
			$assignment = AssignmentVerifikasi::create([
				'tcode' => $newTcode,
				'pengajuan_id' => $avt->id,
				'kode_pengajuan' => $avt->tcode,
				'no_ijin' => $formattedNoIjin,
				'user_id' => $request->input('user_id'),
				'no_sk' => $request->input('no_sk'),
				'tgl_sk' => $request->input('tgl_sk'),
				'file' => $request->input('fileSk'),
			]);

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
			$assignment = AssignmentVerifikasi::where('tcode', $tcode)->firstOrFail();
			$assignment->delete();

			return response()->json(['success' => 'Assignment successfully deleted.']);
		} catch (\Exception $e) {
			return response()->json(['error' => 'Failed to delete assignment: ' . $e->getMessage()], 500);
		}
	}
}
