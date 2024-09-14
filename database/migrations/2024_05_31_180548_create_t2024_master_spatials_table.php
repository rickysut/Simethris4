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
		Schema::dropIfExists('t2024_master_spatials');
        Schema::create('t2024_master_spatials', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('komoditas')->nullable(); //saat ini default Bawang Putih
			$table->string('kode_spatial', 16)->unique();
			$table->string('kode_poktan', 255)->nullable();
			$table->string('ktp_petani', 16);
			$table->string('nama_petani', 255);
			$table->text('latitude');
			$table->text('longitude');
			$table->text('polygon');
			$table->double('altitude')->nullable();
			$table->text('imagery')->nullable();
			$table->double('luas_lahan');
			$table->text('catatan')->nullable();
			$table->string('provinsi_id', 2)->nullable(); //contoh: 11
			$table->string('kabupaten_id', 4)->nullable(); //contoh: 1101
			$table->string('kecamatan_id', 7)->nullable(); //contoh: 1101010
			$table->string('kelurahan_id', 10)->nullable(); //contoh: 1101010003
			$table->string('kml_url');
			$table->tinyInteger('is_active')->default(1)->nullable();
			$table->tinyInteger('status')->default(0)->nullable();
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
        Schema::dropIfExists('t2024_master_spatials');
    }
};
