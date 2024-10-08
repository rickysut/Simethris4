<?php

namespace App\Models2024;

use App\Models\MasterDesa;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterProvinsi;

use App\Models2024\MasterPoktan;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class MasterAnggota extends Model
{
	use HasFactory;
	use Auditable;
	use softDeletes;

	public $table = 't2024_master_anggotas';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'id',
		'kode_poktan',
		'nama_petani',
		'ktp_petani',
		'hp_petani',
		'alamat_petani',
		'kelurahan_id',
		'kecamatan_id',
		'kabupaten_id',
		'provinsi_id',
	];

	/**
	 * enkriptor dan dekriptor ktp
	 */

	// // Accessor untuk mendekripsi ktp_petani
    // public function getKtpPetaniAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }

    // // Mutator untuk mengenkripsi ktp_petani
    // public function setKtpPetaniAttribute($value)
    // {
    //     $this->attributes['ktp_petani'] = Crypt::encryptString($value);
    // }

	//======================================================================//

	public function masterpoktan()
	{
		return $this->belongsTo(MasterPoktan::class, 'kode_poktan', 'kode_poktan');
	}

	public function lokasi()
	{
		return $this->hasMany(Lokasi::class, 'ktp_petani', 'ktp_petani');
	}

	public function spatial()
	{
		return $this->hasMany(MasterSpatial::class, 'ktp_petani', 'ktp_petani');
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
