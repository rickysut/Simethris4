<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifikasi;
use App\Models2024\AjuVerifTanam;
use App\Models2024\PullRiph;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class AjuVerifTanamController extends Controller
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
		$page_title = 'Pengajuan Verifikasi Tanam';
		$page_heading = 'Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-invoice';

		$ijin = $noIjin;

		$noIjin = $this->formatNoIjin($noIjin);

		$npwp_company = Auth::user()->data_user->npwp_company;
		$commitment = PullRiph::where('no_ijin', $noIjin)->first();

		return view('t2024.pengajuan.veriftanam.show', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'ijin'));
    }

    public function submitPengajuan(Request $request, $noIjin)
    {
        $ijin = $noIjin;
		$noIjin = $this->formatNoIjin($noIjin);

		$npwp = Auth::user()->data_user->npwp_company;
		$validatedData = $request->validate([
			'kind' => 'required|in:TANAM,PRODUKSI',
			// Tambahkan validasi lain yang diperlukan
		]);

		// jalankan {{route('2024.datafeeder.logbookReport', $ijin)}} baru lanjutkan
		Http::get(route('2024.datafeeder.logbookReport', $ijin));

		AjuVerifikasi::create(
			[
				'kind' => $validatedData['kind'],
				'tcode' => time(),
				'npwp' => $npwp,
				'no_ijin' => $noIjin,
				'status' => 0,
			]
		);

		return redirect()->back()->with('success', 'Permohonan Verifikasi berhasil diajukan.');
    }
}
