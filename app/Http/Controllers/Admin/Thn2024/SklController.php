<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\Completed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SklController extends Controller
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
		$module_name = 'SKL';
		$page_title = 'Daftar SKL Terbit';
		$page_heading = 'Daftar SKL';
		$heading_class = 'fal fa-award';

		return view('t2024.skl.index', compact('module_name', 'page_title', 'page_heading', 'heading_class'));
	}

	public function mySkls(Request $request)
	{
		$module_name = 'SKL';
		$page_title = 'Daftar SKL Terbit';
		$page_heading = 'Daftar SKL';
		$heading_class = 'fal fa-award';

		$myNpwp = Auth::user()->data_user->npwp_company;

		$draw = $request->input('draw', 1);
		$start = $request->input('start', 0);
		$length = $request->input('length', 10);
		$searchValue = $request->input('search.value', '');
		$order = $request->input('order', []);
		$columns = $request->input('columns', []);

		$data = Completed::where('npwp_company', $myNpwp)
		->with(['datauser:id,npwp_company,company_name'])->get();

		return view('t2024.skl.index', compact('module_name', 'page_title', 'page_heading', 'heading_class','data'));
	}
}
