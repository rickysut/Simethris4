<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('t2024_lokasis', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('npwp')->nullable();
			$table->string('no_ijin')->nullable();
			$table->unsignedBigInteger('poktan_id')->nullable();
			$table->string('kode_spatial', 16)->unique();
			$table->string('ktp_petani', 255)->nullable();
			$table->unsignedBigInteger('anggota_id')->nullable();
			$table->string('nama_lokasi')->nullable();
			$table->integer('jml_titik')->nullable();
			$table->double('luas_lahan')->nullable();
			$table->string('periode_tanam')->nullable();
			$table->text('latitude')->nullable();
			$table->text('longitude')->nullable();
			$table->double('altitude')->nullable();
			$table->text('polygon')->nullable();
			$table->decimal('luas_kira')->nullable();
			$table->date('tgl_tanam')->nullable();
			$table->date('tgl_akhir_tanam')->nullable();
			$table->decimal('luas_tanam')->nullable();
			$table->string('tanam_doc')->nullable();
			$table->string('tanam_pict')->nullable();
			$table->date('tgl_panen')->nullable();
			$table->date('tgl_akhir_panen')->nullable();
			$table->double('volume')->nullable();
			$table->string('panen_doc')->nullable();
			$table->string('panen_pict')->nullable();
			$table->integer('status')->nullable();
			$table->string('varietas')->nullable(); //unused
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('t2024_lokasis');
	}
};
