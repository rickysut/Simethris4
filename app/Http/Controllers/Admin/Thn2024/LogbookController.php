<?php

namespace App\Http\Controllers\Admin\Thn2024;

use App\Http\Controllers\Controller;
use App\Models2024\AjuVerifProduksi;
use App\Models2024\AjuVerifSkl;
use App\Models2024\AjuVerifTanam;
use App\Models2024\ForeignApi;
use App\Models2024\Lokasi;
use App\Models2024\Pks;
use App\Models2024\PullRiph;
use App\Models2024\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class LogbookController extends Controller
{

	public function index($noIjin)
	{
		// Using the datareport function to get the payload
		$payload = $this->logbookReport($noIjin);

		// Render the HTML template with payload data
		$template = view('t2024.logbook.index', ['payload' => $payload])->render();

		// Prepare the directory and file name
		$npwp = str_replace(['.', '-'], '', $payload['npwp']);
		$periode = $payload['periode'];
		$fileName = 'logbook_' .  $noIjin . '_' . '_' . time() . '.pdf';
		$directory = 'uploads/' . $npwp . '/' . $periode . '/' . $noIjin;
		$path = $directory . '/' . $fileName;

		UserFile::updateOrCreate(
			[
				'no_ijin' => $payload['noIjin'],
				'kind' => 'logbook',
			],
			[
				'file_code' => $fileName,
				'file_url' => url($path),
			]
		);

		if (!Storage::disk('public')->exists($directory)) {
			Storage::disk('public')->makeDirectory($directory);
		}

		// Generate the PDF and save it
		Browsershot::html($template)
			->showBackground()
			->margins(4, 0, 4, 0,)
			->format('A4')
			->save(Storage::disk('public')->path($path));

		return redirect()->back()->with('success', 'Berkas berhasil dibuat.');
		//langsung download
		// return response()->download(Storage::disk('public')->path($path));
	}

    public function generateLogbook($noIjin)
	{
		$payload = $this->logbookReport($noIjin);
		$mapkey = ForeignApi::find(1);
		// return $payload;
		return view('t2024.logbook.index', compact('payload', 'mapkey'));
	}

	public function logbookReport($noIjin)
	{
		$ijin = $noIjin;
		$noIjin = substr($noIjin, 0, 4) . '/' .
			substr($noIjin, 4, 2) . '.' .
			substr($noIjin, 6, 3) . '/' .
			substr($noIjin, 9, 1) . '/' .
			substr($noIjin, 10, 2) . '/' .
			substr($noIjin, 12, 4);

		$commitment = PullRiph::where('no_ijin', $noIjin)->first();
		$lokasis = Lokasi::where('no_ijin', $noIjin)
		->with([
			'pullriph' => function ($query){
				$query->select(
					'id',
					'nama',
					'no_ijin'
				);
			}
		])
		->get() ?? new Lokasi();
		$userFiles = UserFile::where('no_ijin', $noIjin)->first() ?? new UserFile();

		$payload = [
			'company' => $commitment->datauser->company_name,
			'npwp' => $commitment->datauser->npwp_company,
			'ijin' => $ijin,
			'noIjin' => $commitment->no_ijin,
			'periode' => $commitment->periodetahun,
			'lokasis' => $lokasis,
			'userFiles' => $userFiles,
		];

		return $payload;
	}
}
