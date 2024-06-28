<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models2024\Lokasi;
use App\Models2024\PullRiph;
use App\Models\DataAdministrator;
use Illuminate\Foundation\Inspiring;
use App\Models\Post;
use App\Models\User;

class VerifikasiTanamController extends Controller
{
	public function index()
	{
		$user = Auth::user();
		$commitments = PullRiph::select('no_ijin', 'created_at', 'status')
			->with(['latestAjutanam' => function ($query) {
				$query->select('id', 'no_ijin', 'status', 'created_at');
			}])
			->whereHas('latestAjutanam', function ($query) {
				$query->whereIn('status', [1, 2, 3, 4]);
			})
			->get();

		// dd($commitments);

		return view('mobile.verifikator.verifikasitanam', compact('user', 'commitments'));
	}

	public function verifikasiMap($noIjin)
	{
		$user = Auth::user(); //ini verifikator
		$ijin = $noIjin;

		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		return view('mobile.verifikator.verifikasitanammap', compact('user', 'ijin', 'noIjin'));
	}

	public function verifikasilokasitanam ($noIjin, $spatial)
	{
		// show single location

		$user = Auth::user(); //ini verifikator
		$ijin = $noIjin;

		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$lokasi = Lokasi::where('no_ijin', $noIjin)->where('kode_spatial', $spatial)->first();

		return view('mobile.verifikator.verifikasitanamlokasi', compact('user', 'ijin', 'noIjin', 'lokasi'));
	}
}
