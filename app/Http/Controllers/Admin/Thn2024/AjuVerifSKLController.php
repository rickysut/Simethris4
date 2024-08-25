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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class AjuVerifSKLController extends Controller
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
	//halaman show Ringkasan Pengajuan Verifikasi
	public function index(Request $request, $noIjin)
	{
		$module_name = 'Komitmen';
		$page_title = 'Pengajuan Penerbitan SKL';
		$page_heading = 'Pengajuan Penerbitan SKL';
		$heading_class = 'fal fa-file-invoice';

		$ijin = $noIjin;

		$noIjin = $this->formatNoIjin($noIjin);

		$payload = $this->payload($ijin);

		$npwp_company = Auth::user()->data_user->npwp_company;
		$commitment = PullRiph::where('no_ijin', $noIjin)->first();

		// return response()->json([
		// 	'data' => $payload,
		// ]);

		return view('t2024.pengajuan.verifskl.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'ijin', 'payload'));
	}

	public function submitPengajuanSkl(Request $request, $noIjin)
	{
		// Format `noIjin` sesuai kebutuhan
		$formattedNoIjin = $this->formatNoIjin($noIjin);

		// Mendapatkan `npwp` dari user yang sedang login
		$npwp = Auth::user()->data_user->npwp_company;

		// Ambil input `kind` dari request tanpa validasi
		$kind = $request->input('kind');

		// Membuat entri baru di tabel `AjuVerifikasi`
		AjuVerifSkl::create([
			'npwp' => $npwp,
			'no_ijin' => $formattedNoIjin,
			'tcode' => 'avskls_' . time(),
			'status' => 0,
		]);

		// Redirect kembali dengan pesan sukses
		return redirect()->back()->with('success', 'Permohonan Verifikasi ' . $kind . ' berhasil dikirimkan.');
	}

	public function reSubmitPengajuanSkl(Request $request, $noIjin)
	{
		// Format `noIjin` sesuai kebutuhan
		$formattedNoIjin = $this->formatNoIjin($noIjin);

		// Mendapatkan `npwp` dari user yang sedang login
		$npwp = Auth::user()->data_user->npwp_company;

		// Membuat entri baru di tabel `AjuVerifikasi`
		AjuVerifSkl::updateOrCreate(
			[
				'npwp' => $npwp,
				'no_ijin' => $formattedNoIjin,
			],
			[
				'status' => 0,
			]
		);

		$this->generateRepReqSkl($noIjin);

		// Redirect kembali dengan pesan sukses
		return redirect()->back()->with('success', 'Permohonan Penerbitan SKL berhasil dikirimkan.');
	}

	public function generateRepReqSkl($noIjin)
	{
		$payload = $this->payload($noIjin);
		$template = view('t2024.pengajuan.verifskl.result', ['payload' => $payload])->render();

		$npwp = str_replace(['.', '-'], '', $payload['npwp']);
		$periode = $payload['periode'];
		$tcode = $payload['lastVSkl']->tcode;
		$fileName = 'PVS_' .  $noIjin . '_' . '_' . time() . '.pdf';
		$directory = 'uploads/' . $npwp . '/' . $periode . '/' . $noIjin;
		$path = $directory . '/' . $fileName;

		AjuVerifSkl::updateOrCreate(
			[
				'no_ijin' => $payload['noIjin'],
				'npwp' => $payload['npwp'],
			],
			[
				'report_url' => url($path),
			]
		);

		UserFile::updateOrCreate(
			[
				'no_ijin' => $payload['noIjin'],
				'kind' => 'report_skl',
			],
			[
				'file_code' => 'PVS_'.$tcode,
				'file_url' => url($path),
			]
		);

		if (!Storage::disk('public')->exists($directory)) {
			Storage::disk('public')->makeDirectory($directory);
		}

		// Generate the PDF and save it
		Browsershot::html($template)
			->showBackground()
			->margins(4, 0, 4, 0,)
			->format('A4')

			->save(Storage::disk('public')->path($path))
		;

		return redirect()->back()->with('success', 'Berkas berhasil dibuat.');
	}

	public function payload($noIjin)
	{
		$ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();

		$lastVT = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'TANAM')->latest()->first() ?? new AjuVerifikasi();
		$lastVP = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'PRODUKSI')->latest()->first() ?? new AjuVerifikasi();
		$lastVSkl = AjuVerifSkl::where('no_ijin', $noIjin)->latest()->first();

		$historyVT = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'TANAM')->get() ?? new AjuVerifikasi();
		$historyVP = AjuVerifikasi::where('no_ijin', $noIjin)->where('kind', 'PRODUKSI')->get() ?? new AjuVerifikasi();
		$historyVSkl = AjuVerifSkl::where('no_ijin', $noIjin)->latest()->get() ?? new AjuVerifSkl();
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
			'ajuSkl' => $historyVSkl,
		];

		return $payload;
	}
}
