<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SKLResources;
use App\Models2024\MasterSpatial;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Completed;
use Illuminate\Support\Str;


class SKLController extends Controller
{
	/**
	 * Get SKL
	 */
	public function getSKL(Request $request)
	{
		$about = [
			'Surat Keterangan Lunas Wajib Tanam Simethris 4beta',
		];

		$no_riph = $request->no_ijin;
		$nomor = Str::substr($no_riph, 0, 4) . '/' . Str::substr($no_riph, 4, 2) . '.' . Str::substr($no_riph, 6, 3) . '/' .
			Str::substr($no_riph, 9, 1) . '/' . Str::substr($no_riph, 10, 2) . '/' . Str::substr($no_riph, 12, 4);

		// Retrieve the completed records based on the formatted 'nomor'
		$completedRecords = Completed::select('id','no_skl','periodetahun','no_ijin','npwp','published_date','luas_tanam','volume','status','url','created_at')->where('no_ijin', '=', $nomor)->get();

		// Create a new instance of SKLResources with the completed records
		$sklResources = new SKLResources($completedRecords);

		return response()->json([
			'about' => $about,
			'data' => $sklResources
		]);
	}
}
