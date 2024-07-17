<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models2024\MasterSpatial;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SpatialController extends Controller
{
	/**
	 * @OA\Get(
	 *      path="/getspatial/{no_ijin}",
	 *      operationId="getspatial",
	 *      tags={"Spatial"},
	 *      summary="Get list of Spatial Data",
	 *      description="Returns list of Kode Spatial",
	 *      security={{"simethrisToken": {}}},
	 *      @OA\Parameter(
	 *          name="s",
	 *          description="Cari berdasarkan status lokasi. 0 = Unavailable; 1= Available",
	 *          required=false,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="p",
	 *          description="Cari berdasarkan Kode Provinsi (provinsi_id).",
	 *          required=false,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="b",
	 *          description="Cari berdasarkan Kode Kabupaten (kabupaten_id).",
	 *          required=false,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="c",
	 *          description="Cari berdasarkan Kode Kecamatan (kecamatan_id).",
	 *          required=false,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="l",
	 *          description="Cari berdasarkan Kode kelurahan (kelurahan_id).",
	 *          required=false,
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
	public function getLokasiTanam(Request $request)
	{
		$informasi = 'Daftar Kode Spatial Wajib Tanam';
		$app = 'Simethris 4 Alpha';
		$Uses = '?s=status&p=provinsi_id&b=kabupaten_id&c=kecamatan_id&l=kelurahan_id';

		$validated = $request->validate([
			's' => 'nullable|integer',
			'p' => 'nullable|integer',
			'b' => 'nullable|integer',
			'c' => 'nullable|integer',
			'l' => 'nullable|integer',
		]);

		$s = $validated['s'] ?? null;
		$p = $validated['p'] ?? null;
		$b = $validated['b'] ?? null;
		$c = $validated['c'] ?? null;
		$l = $validated['l'] ?? null;

		$query = MasterSpatial::select(
			'kode_spatial',
			'ktp_petani',
			'nama_petani',
			'provinsi_id',
			'kabupaten_id',
			'kecamatan_id',
			'kelurahan_id',
			'luas_lahan',
			'status'
		);

		if (!is_null($s)) {
			$query->where('status', $s);
		}
		if (!is_null($p)) {
			$query->where('provinsi_id', $p);
		}
		if (!is_null($b)) {
			$query->where('kabupaten_id', $b);
		}
		if (!is_null($c)) {
			$query->where('kecamatan_id', $c);
		}
		if (!is_null($l)) {
			$query->where('kelurahan_id', $l);
		}

		$spatials = $query->get();

		$formattedSpatials = $spatials->map(function ($spatial) {
			return [
				'kode_spatial' => $spatial->kode_spatial,
				'luas_lahan' => $spatial->luas_lahan,
				'ktp_petani' => $spatial->ktp_petani,
				'nama_petani' => $spatial->anggota->nama_petani,
				'kode_poktan' => $spatial->anggota->masterpoktan->kode_poktan ?? null,
				'nama_kelompok' => $spatial->anggota->masterpoktan->nama_kelompok ?? null,
				'status' => $spatial->status,
				'wilayah' => [
					'provinsi_id' => $spatial->provinsi_id,
					'kabupaten_id' => $spatial->kabupaten_id,
					'kecamatan_id' => $spatial->kecamatan_id,
					'kelurahan_id' => $spatial->kelurahan_id,
				]
			];
		});
		return response()->json([
			'Informasi' => $informasi,
			'Applikasi' => $app,
			'Penggunaan'=> $Uses,
			'data_spatial' => $formattedSpatials,
		]);
	}

	public function batchUpdateStatusLokasi(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|integer',
            'kode_spatial' => 'required|array',
            'kode_spatial.*' => 'required|string', // atau sesuaikan dengan tipe data kode_spatial Anda
        ]);

        $status = $validated['status'];
        $kodeSpatialList = $validated['kode_spatial'];

        DB::beginTransaction();

        try {
            foreach ($kodeSpatialList as $kodeSpatial) {
                // Lakukan update status untuk setiap kode_spatial
                $spatial = MasterSpatial::where('kode_spatial', $kodeSpatial)->first();

                if ($spatial) {
                    $spatial->status = $status;
                    $spatial->save();
                }
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to update spatial data.', 'error' => $e->getMessage()], 500);
        }
    }

	public function updateStatusLokasi(Request $request, $kodeSpatial)
	{
		$validated = $request->validate([
			'status' => 'required|integer',
		]);

		$spatial = MasterSpatial::where('kode_spatial', $kodeSpatial)->first();

		if ($spatial) {
			$spatial->status = $validated['status'];
			$spatial->save();

			return response()->json(['success' => true]);
		} else {
			return response()->json(['success' => false, 'message' => 'Spatial data not found.'], 404);
		}
	}
}
