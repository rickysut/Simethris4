<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifikasi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\Lokasi;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models2024\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PejabatController extends Controller
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

    public function index()
	{
		if (Auth::user()->roles[0]->title !== 'Pejabat') {
			abort(403, 'Unauthorized');
		}

		$module_name = 'SKL';
		$page_title = 'Daftar Permohonan';
		$page_heading = 'Daftar Permohonan Penerbitan SKL';
		$heading_class = 'fa fa-file-signature';

		if (Auth::user()->roles[0]->title == 'Pejabat') {
			$payload = AjuVerifSkl::where('status', 2)->get();

			// Manipulasi no_ijin dan tambahkan sebagai atribut 'ijin'
			$payload = $payload->map(function ($item) {
				// Contoh: Hapus semua karakter non-alfanumerik dari no_ijin
				$item->ijin = preg_replace('/[^a-zA-Z0-9]/', '', $item->no_ijin);

				return $item;
			});
		}
		// return response()->json([
		// 	'data' => $payload,
		// ]);
		return view('t2024.verifikasi.pejabat.recomendations', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'payload'));
	}

	public function approvalForm(Request $request, $noIjin, $tcode)
	{
		$module_name = 'SKL';
		$page_title = 'Form Persetujuan SKL';
		$page_heading = 'Form Persetujuan Penerbitan SKL';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		//if current status = 2
		$currentApproval = AjuVerifSkl::where('tcode', $tcode)->first();
		$currentStatus = $currentApproval->status;

		if($currentStatus != 2) {
			return redirect()->back()->with('error', 'Form Persetujuan tidak dapat diakses. Status pengajuan tidak valid.');
		}

		$tcode = $tcode;
		$ijin = $noIjin;

		$noIjin = $this->formatNoIjin($noIjin);

		$payload = $this->payload($ijin, $tcode);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$npwp_company = $commitment->npwp;

		return view('t2024.verifikasi.pejabat.approvalForm', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'ijin', 'payload', 'tcode'));

	}

	public function payload($noIjin, $tcode)
	{
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();

		//pengajuan verifikasi ini dapat terjadi berulang, maka ambil data terakhir saja
		$lastVT = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'TANAM')->latest()->first() ?? new AjuVerifikasi();
		$lastVP = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'PRODUKSI')->latest()->first() ?? new AjuVerifikasi();

		//hanya terdapat 1 record
		$lastVSkl = AjuVerifSkl::where('tcode', $tcode)->first();

		$historyVT = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'TANAM')->get() ?? new AjuVerifikasi();
		$historyVP = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'PRODUKSI')->get() ?? new AjuVerifikasi();

		$pks = Pks::where('no_ijin', $noIjin)->get() ?? new Pks();
		$lokasis = Lokasi::where('no_ijin', $noIjin)->get() ?? new Lokasi();
		$userFiles = UserFile::where('no_ijin', $noIjin)->whereIn('kind', ['sptjmtanam', 'sptjmproduksi', 'spvt', 'spvp', 'rta', 'rpo', 'sphtanam', 'sphproduksi', 'logbook', 'formLa'])->get() ?? new UserFile();

		$payload = [
			'company' => $commitment->datauser->company_name,
			'npwp' => $commitment->datauser->npwp_company,
			'ijin' => $ijin,
			'noIjin' => $commitment->no_ijin,
			'periode' => $commitment->periodetahun,

			'avtDate' => $lastVT->created_at,
			'avtVerifAt' => $lastVT->verif_at,
			'avtStatus' => $lastVT->status,
			'avtMetode' => $lastVT->metode,
			'avtNote' => $lastVT->note,

			'avpDate' => $lastVP->created_at,
			'avpVerifAt' => $lastVP->verif_at,
			'avpMetode' => $lastVP->metode,
			'avpNote' => $lastVP->note,
			'avpStatus' => $lastVP->status,

			'avsklDate' => $lastVSkl ? $lastVSkl->created_at : null,
			'avsklVerifAt' => $lastVSkl ? $lastVSkl->verif_at : null,
			'avsklStatus' => $lastVSkl ? $lastVSkl->status : null,
			'avsklMetode' => $lastVSkl ? $lastVSkl->metode : null,
			'avsklNote' => $lastVSkl ? $lastVSkl->note : null,

			'avsklRecomendBy' =>$lastVSkl ?  $lastVSkl->recomend_by : null,
			'avsklRecomendAt' => $lastVSkl ? $lastVSkl->recomend_at : null,
			'avsklRecomendNote' => $lastVSkl ? $lastVSkl->recomend_note : null,

			'avsklApprovedBy' => $lastVSkl ? $lastVSkl->approved_by : null,
			'avsklApprovedAt' => $lastVSkl ? $lastVSkl->approved_at : null,
			'avsklPublishedAt' => $lastVSkl ? $lastVSkl->published_at : null,

			'wajibTanam' => $commitment->luas_wajib_tanam * 10000,
			'wajibProduksi' => $commitment->volume_produksi,
			'realisasiTanam' => $commitment->lokasi->sum('luas_tanam'),
			'realisasiProduksi' => $commitment->lokasi->sum('volume'),
			'countAnggota' => $commitment->lokasi->groupBy('ktp_petani')->count(),
			'countPoktan' => $commitment->lokasi->groupBy('kode_poktan')->count(),
			'countPks' => $pks->where('berkas_pks', '!=', null)->count(),
			'countSpatial' => $lokasis->count(),
			'countTanam' => $lokasis->where('luas_tanam', '!=', null)->count(),
			'pksFiles' => $pks,
			'userFiles' => $userFiles,

			//current data
			'lastVSkl' => $lastVSkl,

			//riwayat verifikasi
			'ajuTanam' => $historyVT,
			'ajuProduksi' => $historyVP,
		];

		return $payload;
	}

	public function approvalStatus(Request $request, $noIjin, $tcode)
	{
		if (Auth::user()->roles[0]->title !== 'Pejabat') {
			abort(403, 'Unauthorized');
		}

		$user = Auth::user();
		$currentApproval = AjuVerifSkl::where('tcode', $tcode)->first();
		$currentStatus = $currentApproval->status;
		if($currentStatus != 2) {
			return redirect()->route('2024.pejabat.skl.rekomendasi.index')->with('error', 'Persetujuan tidak dapat diberikan. Status saat ini tidak valid.');
		}

		$validUser = Auth::user();
		$guess = $request->input('verified');
		if ($guess != $validUser->username) {
			return redirect()->route('2024.pejabat.skl.rekomendasi.index')->with('error', 'Validasi nama pengguna GAGAL!, Isi nama pengguna Anda yang benar.');
		}

		$givenStatus = $request->input('status');
		$currentApproval->update([
			'approved_by' => $user->id,
			'approved_at' => now(),
			'status' => $givenStatus,
		]);
		return redirect()->route('2024.pejabat.skl.rekomendasi.index')->with('success', 'Persetujuan Penerbitan SKL berhasil diberikan.');
	}
}
