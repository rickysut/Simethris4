<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifikasi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\Completed;
use App\Models2024\Lokasi;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models2024\UserFile;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;

class VerifSklController extends Controller
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
		abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		//page level
		$module_name = 'SKL';
		$page_title = 'Daftar Permohonan SKL';
		$page_heading = 'Daftar Permohonan SKL';
		$heading_class = 'fal fa-file-search';

		$data = AjuVerifSkl::orderBy('id', 'ASC')
			->with([
				'datauser:id,npwp_company,company_name',
				'verifikator:id,name',
				'recomendBy:id,name',
				'direktur:id,name',
				'commitment:id,no_ijin,periodetahun',
			])->get();

		return view('t2024.verifikasi.skl.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'data'));
	}

	public function check(Request $request, $noIjin, $tcode)
	{
		$module_name = 'SKL';
		$page_title = 'Permohonan SKL';
		$page_heading = 'Data Permohonan SKL';
		$heading_class = 'fal fa-file-search';

		$tcode = $tcode;
		$ijin = $noIjin;

		$noIjin = $this->formatNoIjin($noIjin);

		$payload = $this->payload($ijin, $tcode);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$npwp_company = $commitment->npwp;

		// return response()->json([
		// 	'data' => $payload,
		// ]);

		return view('t2024.verifikasi.skl.check', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'ijin', 'payload', 'tcode'));
	}

	public function storeVerifSkl(Request $request, $noIjin, $tcode)
	{
		abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$validUser = Auth::user();
		$guess = $request->input('verified');
		$verif_note = $request->input('recomend_note');
		$status = $request->input('status');
		if ($guess != $validUser->username) {
			return redirect()->back()->with('error', 'Validasi nama pengguna GAGAL!, Isi nama pengguna Anda yang benar.');
		}

		if ($status = 2) {
			$payload = $this->payload($noIjin, $tcode);

			$now = now();

			AjuVerifSkl::where('tcode', $tcode)->update([
				'check_by' => $validUser->id,
				'verif_at' => $now,
				'metode' => 'on system',
				'verif_note' => $verif_note,
				'recomend_by' => $validUser->id,
				'recomend_at' => $now,
				'recomend_note' => $now,
				'status' => 2,
			]);

			return redirect()->route('2024.admin.permohonan.skl.index')->with('success', 'Rekomendasi Penerbitan SKL berhasil diajukan kepada pimpinan.');
		} else {
			AjuVerifSkl::where('tcode', $tcode)->update([
				'check_by' => $validUser->id,
				'verif_at' => now(),
				'metode' => 'on system',
				'verif_note' => $verif_note,
				'status' => 6,
			]);
			return redirect()->route('2024.admin.permohonan.skl.index')->with('success', 'Permohonan Penerbitan SKL berhasil dikembalikan kepada Pelaku Usaha.');
		}
	}

	public function generateRepReqSkl($noIjin, $tcode)
	{
		$payload = $this->payload($noIjin, $tcode);
		$template = view('t2024.verifikasi.skl.result', ['payload' => $payload])->render();

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
				'file_code' => 'PVS_' . $tcode,
				'file_url' => url($path),
			]
		);

		if (!Storage::disk('public')->exists($directory)) {
			Storage::disk('public')->makeDirectory($directory);
		}

		// Generate the PDF and save it
		Browsershot::html($template)
			->showBackground()
			->margins(15, 0, 15, 0,)
			->format('A4')

			->save(Storage::disk('public')->path($path))
		;

		return redirect()->back()->with('success', 'Berkas berhasil dibuat.');
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

			'avsklRecomendBy' => $lastVSkl ?  $lastVSkl->recomend_by : null,
			'avsklRecomendAt' => $lastVSkl ? $lastVSkl->recomend_at : null,
			'avsklRecomendNote' => $lastVSkl ? $lastVSkl->recomend_note : null,

			'avsklApprovedBy' => $lastVSkl ? $lastVSkl->approved_by : null,
			'avsklApprovedAt' => $lastVSkl ? $lastVSkl->approved_at : null,
			'avsklNum' => $lastVSkl ? $lastVSkl->no_skl : null,
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
		$payload = $this->payload($ijin, $tcode);
		return $payload;
		return view('t2024.verifikasi.skl.draft', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'tcode', 'payload'));
	}

	public function draftSkl(Request $request, $noIjin, $tcode)
	{
		$currentApproval = AjuVerifSkl::where('tcode', $tcode)->first();
		$currentStatus = $currentApproval->status;

		//validasi status
		if ($currentStatus != 3) {
			return redirect()->route('2024.admin.permohonan.skl.index')->with('error', 'Draft SKL tidak dapat diakses. Status permohonan tidak sesuai.');
		}

		// ambil data
		$payload = $this->payload($noIjin, $tcode);
		$data = [
			'Perusahaan' => $payload['company'],
			'No. RIPH' => $payload['noIjin'],
			'No. SKL'	=> $payload['avsklNum'],
			'Pejabat'	=> $payload['lastVSkl']->direktur->name,
			'Status' => 'LUNAS',
		];

		$QrCode = QrCode::size(70)->generate('Perusahaan: ' . $data['Perusahaan'] . ', No. RIPH: ' . $data['No. RIPH'] . ', No. SKL: ' . $data['No. SKL'] . ', Disetujui dan Ditandatangani oleh: ' . $data['Pejabat'] . ', Status: ' . $data['Status'] . ', Tautan Berkas: Belum tersedia.');

		$payload['QrCode'] = $QrCode;

		//gunakan template
		$template = view('t2024.verifikasi.skl.draft', ['payload' => $payload])->render();

		$npwp = str_replace(['.', '-'], '', $payload['npwp']);
		$periode = $payload['periode'];
		$tcode = $payload['lastVSkl']->tcode;
		$fileName = 'draft_skl_' .  $noIjin . '_' . '_' . time() . '.pdf';
		$directory = 'uploads/' . $npwp . '/' . $periode . '/' . $noIjin;
		$path = $directory . '/' . $fileName;


		$currentApproval->update([
			'no_skl' => $request->input('no_skl'),
			'draft_url' => url($path),
		]);

		// Generate the PDF and save it
		Browsershot::html($template)
			->showBackground()
			->margins(4, 0, 4, 0,)
			->format('A4')
			->save(Storage::disk('public')->path($path));

		return response()->download(Storage::disk('public')->path($path));
		// return redirect()->back()->with('success', 'Draft SKL berhasil dibuat.');
		// return response()->json([
		// 	'success' => true,
		// 	'file_path' => Storage::disk('public')->url($path)
		// ]);

		//if current status = 3
		//release locked location/spatial
	}

	public function uploadSkl(Request $request, $noIjin, $tcode)
	{
		$noIjin = $this->formatNoIjin($noIjin);
		$Skl = AjuVerifSkl::where('tcode', $tcode)->first();
		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		if (!$commitment) {
			return redirect()->route('2024.admin.permohonan.skl.index')->with('error', 'Commitment tidak ditemukan.');
		}

		$npwp = $commitment->npwp;
		$periode = $commitment->periodetahun;

		if ($request->hasFile('skl_url')) {
			$request->validate([
				'skl_url' => 'mimes:pdf|max:2048',
			]);

			$file = $request->file('skl_url');
			$filename = 'skl_' . $noIjin . '_' . time() . '.' . $file->getClientOriginalExtension();
			$directory = 'uploads/' . $npwp . '/' . $periode . '/' . $noIjin;

			// Menyimpan file ke disk publik
			$path = $file->storeAs($directory, $filename, 'public');

			// Mendapatkan URL lengkap dari file
			$recordedPath = Storage::disk('public')->url($path);

			// Update data SKL
			$Skl->update([
				'skl_url' => $recordedPath,
			]);
		}

		return redirect()->back()->with('success', 'SKL berhasil diunggah.');
	}

	public function publishSkl($noIjin, $tcode)
	{
		// Format nomor izin
		$noIjin = $this->formatNoIjin($noIjin);

		// Cek apakah SKL ada dan statusnya sesuai
		$Skl = AjuVerifSkl::where('tcode', $tcode)->first();
		if (!$Skl || ($Skl->status != 3 && !$Skl->no_skl && !$Skl->draft_skl)) {
			return redirect()->route('2024.admin.permohonan.skl.index')->with('error', 'Status permohonan tidak sesuai. Tindakan tidak diperkenankan.');
		}

		// Cek apakah commitment ada
		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		if (!$commitment) {
			return redirect()->route('2024.admin.permohonan.skl.index')->with('error', 'Commitment tidak ditemukan.');
		}

		$npwp = $commitment->npwp;
		$periode = $commitment->periodetahun;

		// Update data SKL
		$Skl->update([
			'status' => 4,
			'published_at' => now(),
		]);

		Completed::updateOrCreate(
			[
				'no_ijin' => $noIjin,
				'npwp' => $npwp,
				'periodetahun' => $periode,
			],
			[
				'no_skl' => $Skl->no_skl,
				'published_date' => $Skl->published_at,
				'luas_tanam' => $commitment->lokasi->sum('luas_tanam'),
				'volume' => $commitment->lokasi->sum('volume'),
				'status' => 'LUNAS',
				'skl_upload' => $Skl->skl_url,
				'url' => $Skl->skl_url,
			]
		);

		UserFile::updateOrCreate(
			[
				'kind' => 'skl',
				'no_ijin' => $noIjin
			],
			[
				'file_code' => $Skl->tcode,
				'file_url' => $Skl->skl_url,
				'verif_by' => $Skl->check_by,
				'verif_at' => $Skl->verif_at,
				'status' => 1,
			]
		);

		// Update status spatial terkait lokasi
		$lokasis = Lokasi::where('no_ijin', $noIjin)->get();
		foreach ($lokasis as $lokasi) {
			$lokasi->spatial()->update([
				'status' => 0,
			]);
		}

		return redirect()->route('2024.admin.permohonan.skl.index')->with('success', 'SKL berhasil diterbitkan. Anda dapat melihat SKL yang telah diterbitkan di menu SKL Terbit');
	}


	public function SklPayload($noIjin, $tcode) {}
}
