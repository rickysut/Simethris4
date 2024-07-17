<?php

namespace App\Models2024;

use App\Models2024\FotoProduksi;
use App\Models2024\FotoTanam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lokasi extends Model
{
	use \Awobaz\Compoships\Compoships;
	use HasFactory, SoftDeletes;

	protected $table = 't2024_lokasis';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'npwp',
		'no_ijin',
		'kode_poktan',
		'kode_spatial',
		'ktp_petani',
		'luas_lahan',
		'periode_tanam',

		'tgl_tanam',
		'luas_tanam',
		'tanamComment',
		'tanamFoto',

		'lahandate',
		'lahancomment',
		'lahanfoto',

		'benihDate',
		'benihComment',
		'benihFoto',

		'mulsaDate',
		'mulsaComment',
		'mulsaFoto',

		'pupuk1Date',
		'pupuk1Comment',
		'pupuk1Foto',

		'pupuk2Date',
		'pupuk2Comment',
		'pupuk2Foto',

		'pupuk3Date',
		'pupuk3Comment',
		'pupuk3Foto',

		'optDate',
		'optComment',
		'optFoto',

		'tgl_panen',
		'volume',
		'vol_benih',
		'vol_jual',
		'prodComment',
		'prodFoto',

		'distComment',
		'distFoto',

		'status',
		'deleted_at',

		// 'tgl_akhir_panen',
		// 'tgl_akhir_tanam',
		// 'panen_doc',
		// 'panen_pict',
		// 'varietas',
	];

	public function masteranggota()
	{
		return $this->belongsTo(MasterAnggota::class, 'ktp_petani', 'ktp_petani');
	}

	public function pullriph()
	{
		return $this->belongsTo(PullRiph::class, 'no_ijin', 'no_ijin');
	}

	public function pks()
	{
		return $this->belongsTo(Pks::class, ['kode_poktan', 'no_ijin'], ['kode_poktan', 'no_ijin']);
	}

	public function datarealisasi()
	{
		return $this->hasOne(DataRealisasi::class, 'lokasi_id');
	}

	public function spatial()
	{
		return $this->belongsTo(MasterSpatial::class, 'kode_spatial', 'kode_spatial');
	}

	public function logbook()
	{
		return $this->hasMany(LogbookKegiatan::class);
	}

	public function fototanam()
	{
		return $this->hasMany(FotoTanam::class);
	}
	public function fotoproduksi()
	{
		return $this->hasMany(FotoProduksi::class);
	}
}
