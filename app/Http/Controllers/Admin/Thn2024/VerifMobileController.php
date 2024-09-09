<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifikasi;
use App\Models2024\AjuVerifProduksi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\AjuVerifTanam;
use App\Models2024\AssignmentVerifikasi;
use App\Models2024\ForeignApi;
use App\Models2024\Lokasi;
use App\Models2024\MasterSpatial;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models2024\UserFile;
use App\Models\MasterKabupaten;
use App\Models\User;
use App\Models\UserDocs;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;

class VerifTanamController extends Controller
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

	//mobile
	public function findmarker(Request $request)
	{
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Simulator Spatial';
		$page_heading = 'Pencarian Lokasi Verifikasi';
		$heading_class = 'fal fa-map-marked-alt';

		$ijins = PullRiph::select('no_ijin')->get();

		$spatials = MasterSpatial::with('kabupaten')
		->get();

		// dd($spatials);

		$myLocus = [
			[
				'id' => 1,
				'latitude' => -6.286147,
				'longitude' => 106.838966,
				'name' => 'Kantor Ditjen Hortikultura',
			],
			[
				'id' => 2,
				'latitude' => -6.66440,
				'longitude' => 106.863234,
				'name' => 'Lokasi Pengujian 1',
			],
			[
				'id' => 3,
				'latitude' => -7.150,
				'longitude' => 109.952,
				'name' => 'Kebun Kendal',
			],
		];

		$mapkey = ForeignApi::find(1);
		return view('t2024.verifikasi.tanam.simulator', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'ijins', 'myLocus'));
	}

	public function veriflokasimobile(Request $request, $noIjin, $spatial)
	{
		$ijin = $noIjin;
		$spatial = $spatial;
		$module_name = 'Verifikasi Tanam';
		$page_title = 'Simulator Spatial';
		$page_heading = 'Verifikasi Lahan ' . $spatial;
		$heading_class = 'fal fa-map-marker';

		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$data = Lokasi::where('kode_spatial', $spatial)
			->where('no_ijin', $noIjin)
			->with(['masteranggota', 'pks'])
			->first();

		$mapkey = ForeignApi::find(1);
		return view('t2024.verifikasi.tanam.veriflokasimobile', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'mapkey', 'data', 'ijin', 'noIjin', 'spatial'));
	}
}
