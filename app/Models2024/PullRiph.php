<?php

namespace App\Models2024;

use App\Models\DataUser;
// use App\Models\PenangkarRiph;
use App\Models\User;
use App\Models\UserDocs;
use App\Traits\Auditable;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PullRiph extends Model
{
	use HasFactory;
	use SoftDeletes;
	use Auditable;

	public $table = 't2024_pull_riphs';

	protected $fillable = [
		'user_id',
		'npwp',
		'keterangan',
		'nama',
		'no_ijin',
		'periodetahun',
		'tgl_ijin',
		'tgl_akhir',
		'no_hs',
		'volume_riph',
		'volume_produksi',
		'luas_wajib_tanam',
		'stok_mandiri',
		'pupuk_organik',
		'npk',
		'dolomit',
		'za',
		'mulsa',
		'status',
		'formRiph',
		'formSptjm',
		'logBook',
		'formRt',
		'formRta',
		'formRpo',
		'formLa',
		'no_doc',
		'poktan_share',
		'importir_share',
		'status',
		'skl',
		'datariph'
	];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	public function getFormattedNoIjinAttribute()
    {
        return str_replace(['/', '.', ' '], '', $this->no_ijin);
    }

	public function user()
	{
		return $this->belongsTo(User::class, 'npwp_company', 'npwp');
	}

	public function datauser()
	{
		return $this->belongsTo(DataUser::class, 'npwp', 'npwp_company');
	}

	public function userDocs()
	{
		return $this->hasOne(UserDocs::class, 'no_ijin', 'no_ijin');
	}

	public function penangkar_riph()
	{
		return $this->hasMany(PenangkarRiph::class, 'no_ijin', 'no_ijin');
	}

	public function pks()
	{
		return $this->hasMany(Pks::class, 'no_ijin', 'no_ijin');
	}

	public function lokasi()
	{
		return $this->hasMany(Lokasi::class, 'no_ijin', 'no_ijin');
	}

	public function ajutanam()
	{
		return $this->hasMany(AjuVerifTanam::class, 'no_ijin', 'no_ijin');
	}

	public function latestAjutanam()
    {
        return $this->hasOne(AjuVerifTanam::class, 'no_ijin', 'no_ijin')->latest();
    }

	public function ajuproduksi()
	{
		return $this->hasMany(AjuVerifProduksi::class, 'no_ijin', 'no_ijin');
	}

	public function ajuskl()
	{
		return $this->hasMany(AjuVerifSkl::class, 'no_ijin', 'no_ijin');
	}

	public function skl()
	{
		return $this->hasOne(Skl::class, 'no_ijin', 'no_ijin');
	}

	public function completed()
	{
		return $this->hasOne(Completed::class, 'no_ijin', 'no_ijin');
	}

	public function datarealisasi()
	{
		return $this->hasMany(DataRealisasi::class, 'no_ijin', 'no_ijin');
	}
}
