<?php

namespace App\Models2024;

use App\Models\MasterDesa;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterProvinsi;
use App\Models\Varietas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pks extends Model
{
	use \Awobaz\Compoships\Compoships;
	use HasFactory;
	use SoftDeletes;

	public $table = 't2024_pks';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'kode_poktan',
		'npwp',
		'no_ijin',
		'nama_poktan',
		'no_perjanjian',
		'tgl_perjanjian_start',
		'tgl_perjanjian_end',
		'jumlah_anggota',
		'luas_rencana',
		'varietas_tanam',
		'periode_tanam',
		'provinsi_id',
		'kabupaten_id',
		'kecamatan_id',
		'kelurahan_id',
		'status',
		'note',
		'verif_by',
		'verif_at',
		'berkas_pks',
		'deleted_at',
	];

	public function lokasi()
	{
		return $this->hasMany(Lokasi::class, ['kode_poktan', 'no_ijin'], ['kode_poktan', 'no_ijin']);
	}

	public function userfile()
	{
		return $this->hasMany(userfile::class, ['kode_poktan', 'no_ijin'], ['kode_poktan', 'no_ijin']);
	}

	public function masterpoktan()
	{
		return $this->belongsTo(MasterPoktan::class, 'kode_poktan', 'kode_poktan');
	}

	public function anggota()
	{
		return $this->hasMany(MasterAnggota::class, 'kode_poktan', 'kode_poktan');
	}

	public function commitment()
	{
		return $this->belongsTo(PullRiph::class, 'no_ijin', 'no_ijin');
	}

	public function varietas()
	{
		return $this->belongsTo(Varietas::class, 'varietas_tanam');
	}

	public function provinsi()
	{
		return $this->belongsTo(MasterProvinsi::class, 'provinsi_id', 'provinsi_id');
	}
	public function kabupaten()
	{
		return $this->belongsTo(MasterKabupaten::class, 'kabupaten_id', 'kabupaten_id');
	}
	public function kecamatan()
	{
		return $this->belongsTo(MasterKecamatan::class, 'kecamatan_id', 'kecamatan_id');
	}
	public function desa()
	{
		return $this->belongsTo(MasterDesa::class, 'kelurahan_id', 'kelurahan_id');
	}
}
