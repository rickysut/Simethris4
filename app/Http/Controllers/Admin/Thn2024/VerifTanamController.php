<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
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

	public function check(Request $request, $noIjin)
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

		return view('t2024.verifikasi.tanam.check', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'ijin', 'user', 'noIjin', 'userDocs', 'periodetahun', 'npwp'));
	}

	public function store (Request $request, $noIjin)
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
