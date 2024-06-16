<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifSkl;
use App\Models2024\PullRiph;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class AjuVerifSKLController extends Controller
{
	//halaman show Ringkasan Pengajuan Verifikasi
    public function index(Request $request, $noIjin)
    {
        $module_name = 'Komitmen';
		$page_title = 'Pengajuan Verifikasi Produksi';
		$page_heading = 'Pengajuan Verifikasi Produksi';
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

		return view('t2024.pengajuan.verifskl.show', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'ijin'));
    }

	//store pengajuan
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
		AjuVerifSkl::create(
			[
				'npwp' => $npwp,
				'commitment_id' => $commitment->id,
				'no_ijin' => $noIjin,
				'status' => 1,
			],
		);

		return redirect()->back()->with('success', 'Verifikasi Produksi berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
