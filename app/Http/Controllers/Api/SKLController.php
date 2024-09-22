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

		try {
			// Validate incoming request
			$validated = $request->validate([
				'noIjin' => 'nullable|string',
			]);
			$no_riph = $validated['noIjin'] ?? null;

			// Format 'noIjin' (if provided) to match the desired pattern
			$ijin = null;
			if ($no_riph) {
				$ijin = Str::substr($no_riph, 0, 4) . '/' . Str::substr($no_riph, 4, 2) . '.' . Str::substr($no_riph, 6, 3) . '/' .
					Str::substr($no_riph, 9, 1) . '/' . Str::substr($no_riph, 10, 2) . '/' . Str::substr($no_riph, 12, 4);
			}

			// Start building the query
			$query = Completed::select(
				'id',
				'no_skl',
				'periodetahun',
				'no_ijin',
				'npwp',
				'published_date',
				'luas_tanam',
				'volume',
				'status',
				'url',
				'created_at'
			);

			if (!is_null($ijin)) {
				$query->where('no_ijin', $ijin);
			}
			$completedRecords = $query->get();

			$sklResources = new SKLResources($completedRecords);

			return response()->json([
				'success' => true,
				'Status' => 'SUCCESS',
				'Tentang' => $about,
				'data' => $sklResources,
			]);
		} catch (\Exception $e) {
			// Error handling
			return response()->json([
				'success' => false,
				'error' => $e->getMessage(),
				'message' => 'Failed to fetch skl data.',
				'Status' => 'FAIL',
				'Tentang' => $about,
				'data' => [],
			]);
		}
	}
}
