<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifTanam;
use App\Models2024\PullRiph;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class AjuVerifTanamController extends Controller
{
	//halaman show Ringkasan Pengajuan Verifikasi
    public function index(Request $request, $noIjin)
    {
        $module_name = 'Komitmen';
		$page_title = 'Pengajuan Verifikasi Tanam';
		$page_heading = 'Pengajuan Verifikasi Tanam';
		$heading_class = 'fal fa-file-invoice';

		$ijin = $noIjin;

		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$npwp_company = Auth::user()->data_user->npwp_company;
		$commitment = PullRiph::where('no_ijin', $noIjin)->first();

		return view('t2024.pengajuan.veriftanam.show', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'ijin'));
    }

    public function submitPengajuan(Request $request, $noIjin)
    {
        $ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$npwp = Auth::user()->data_user->npwp_company;
		$commitment = PullRiph::where('npwp', $npwp)->where('no_ijin', $noIjin)->firstOrFail();
		AjuVerifTanam::create(
			[
				'npwp' => $npwp,
				'commitment_id' => $commitment->id,
				'no_ijin' => $noIjin,
				'status' => 1,
			],
		);

		return redirect()->back()->with('success', 'Verifikasi Tanam berhasil diajukan.');
    }
}
