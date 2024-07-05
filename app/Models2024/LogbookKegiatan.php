<?php

namespace App\Models2024;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogbookKegiatan extends Model
{
    use HasFactory, Auditable;

	public $table = 't2024_logbook_kegiatans';

	protected $fillable = [
		'lokasi_id',
		'no_ijin',
		'kode_spatial',
		'judul_keg',
		'keterangan',
		'foto',
	];

	public function lokasi()
	{
		return $this->belongsTo(Lokasi::class);
	}
}
