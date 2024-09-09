<?php

namespace App\Models2024;

use App\Models2024\PullRiph;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFile extends Model
{
    use HasFactory, SoftDeletes, Auditable;

	public $table = 't2024_user_files';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'kind',
		/**
		 * spvt = surat pengajuan verifikasi tanam
		 * spvp = surat pengajuan verifikasi produksi
		 * spskl = surat pengajuan penerbitan SKL
		 * sptjmt = surat pernyataan tanggung jawab mutlak periode tanam
		 * sptjmp = surat pernyataan tanggung jawab mutlak periode produksi
		 * rta = form realisasi tanam
		 * rpo = form realisasi produksi
		 * spht = statistik pertanian hortikultura periode tanam
		 * sphb = statistik pertanian hortikultura periode produksi
		 * spdst = surat pengantar dinas telah selesai tanam
		 * spdsp = surat pengantar dinas telah selesai produksi
		 * lbt = logbook tanam
		 * lbp = logbook produksi
		 * la = laporan akhir
		 * skl = surat keterangan lunas
		 * ft = foto tanam
		 * fp = foto produksi
		 * pks = berkas pks
		 */
		'no_ijin',
		'file_code', //gunakan 'kind' sebagai prefix + no_ijin + time()
		'file_url', //gunakan 'kind' sebagai prefix
		'verif_by',
		'verif_at',
		'status',
	];

	public function commitment()
	{
		return $this->belongsTo(PullRiph::class, 'no_ijin', 'no_ijin');
	}
	public function pks()
	{
		return $this->belongsTo(Pks::class, ['kode_poktan', 'no_ijin'], ['kode_poktan', 'no_ijin']);
	}
}
