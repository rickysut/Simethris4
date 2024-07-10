<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models2024\MasterSpatial;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class SpatialController extends Controller
{
	/**
	 * @OA\Get(
	 *      path="/getSKL/{no_ijin}",
	 *      operationId="getSKL",
	 *      tags={"SKL"},
	 *      summary="Get list of completed skl",
	 *      description="Returns list of skl",
	 *      security={{"simethrisToken": {}}},
	 *      @OA\Parameter(
	 *          name="no_ijin",
	 *          description="No ijin/Riph yg dicari datanya (* tanpa . & /)",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="Successful operation",
	 *          @OA\JsonContent()
	 *       ),
	 *      @OA\Response(
	 *          response=401,
	 *          description="Unauthenticated"
	 *      ),
	 *      @OA\Response(
	 *          response=403,
	 *          description="Forbidden"
	 *      )
	 * )
	 */
	public function getspatial(Request $request)
	{
		$validated = $request->validate([
            'status' => 'required|integer'
        ]);

		$status = 1; // status: All = findAll; status = 1 where status = 1; status = 2 where status = 2;

		if ($status == 'All') {
			$spatials = MasterSpatial::select('kode_spatial', 'ktp_petani', 'nama_petani', 'poktan_id', 'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'luas_lahan', 'status')
				->get();
		} else {
			$spatials = MasterSpatial::select('kode_spatial', 'ktp_petani', 'nama_petani', 'poktan_id', 'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'luas_lahan', 'status')
				->where('status', $status)
				->get();
		}

		return response()->json($spatials);
	}
}
