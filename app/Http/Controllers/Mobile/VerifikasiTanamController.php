<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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

		return view('mobile.verifikator.verifikasitanam', compact('user','commitments'));
    }
}
