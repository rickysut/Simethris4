<?php

namespace App\Models2024;

use App\Models2024\Lokasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoProduksi extends Model
{
	use HasFactory, SoftDeletes;

	public $table = 't2024_foto_produksis';

	protected $fillable = [
		'lokasi_id',
		'filename',
		'url',
	];

	public function lokasi()
	{
		return $this->belongsTo(Lokasi::class, 'lokasi_id', 'id');
	}
}
