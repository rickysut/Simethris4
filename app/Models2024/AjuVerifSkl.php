<?php

namespace App\Models2024;

use App\Models\DataUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuVerifSkl extends Model
{
	public $table = 't2024_avskls';

	protected $fillable = [
		'npwp',
		'commitment_id',
		'no_ijin',
		'no_pengajuan',
		'status',


		//file upload
		'baskls', //berita acara hasil pemeriksaan realisasi produksi
		'ndhpskl', //nota dinas hasil pemeriksaan realisasi tanam

		'check_by',
		'verif_at',
		'metode',
		'verif_note',

		'recomend_by',
		'recomend_at',
		'recomend_note',

		'approved_by',
		'approved_at',
		'published_at',
	];

	public function commitment()
	{
		return $this->belongsTo(PullRiph::class, 'no_ijin', 'no_ijin');
	}

	public function datauser()
	{
		return $this->belongsTo(DataUser::class, 'npwp', 'npwp_company');
	}

	public static function newPengajuanCount(): int
	{
		return self::where('status', '1')->count();
	}

	public function proceedVerif(): int
	{
		return self::whereIn('status', ['2', '3'])->count();
	}

	public function NewRequest(): int
	{
		return self::where('status', '1')->count();
	}

	public static function getNewPengajuan()
	{
		return self::where('status', '1')->get();
	}

	public function skl()
	{
		return $this->hasOne(Skl::class, 'pengajuan_id', 'id');
	}

	public function verifikator()
	{
		return $this->belongsTo(User::class, 'check_by', 'id');
	}
}
