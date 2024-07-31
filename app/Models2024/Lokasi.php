<?php

namespace App\Models2024;

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
		'origin',
		'tcode',
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
		'benihSize',
		'benihComment',
		'benihFoto',

		'mulsaDate',
		'mulsaSize',
		'mulsaComment',
		'mulsaFoto',

		'pupuk1Date',
		'organik1',
		'npk1',
		'dolomit1',
		'za1',
		'pupuk1Comment',
		'pupuk1Foto',

		'pupuk2Date',
		'organik2',
		'npk2',
		'dolomit2',
		'za2',
		'pupuk2Comment',
		'pupuk2Foto',

		'pupuk3Date',
		'organik3',
		'npk3',
		'dolomit3',
		'za3',
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
		'tanamStatus',
		'lahanStatus',
		'benihStatus',
		'mulsaStatus',
		'pupuk1Status',
		'pupuk2Status',
		'pupuk3Status',
		'optStatus',
		'prodStatus',
		'distStatus',
		'deleted_at',

		// 'tgl_akhir_panen',
		// 'tgl_akhir_tanam',
		// 'panen_doc',
		// 'panen_pict',
		// 'varietas',
	];

	//otomatisasi tcode di table lokasi
	protected static function booted()
    {
        static::creating(function ($lokasi) {
            // Set tcode saat record dibuat
            $lokasi->tcode = $lokasi->kode_spatial . '_' . time();
        });

        static::updating(function ($lokasi) {
            // Hanya update kolom yang diizinkan saat record diupdate
            $lokasi->tcode = $lokasi->getOriginal('tcode');
        });
    }

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

	public function spatial()
	{
		return $this->belongsTo(MasterSpatial::class, 'kode_spatial', 'kode_spatial');
	}

	public function logbook()
	{
		return $this->hasMany(LogbookKegiatan::class);
	}
}
