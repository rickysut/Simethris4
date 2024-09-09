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
        Schema::create('t2024_logbook_kegiatans', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('lokasi_id');
			$table->string('no_ijin')->nullable();
			$table->string('kode_spatial', 16)->nullable();
			$table->string('judul_keg', 55)->nullable();
			$table->text('keterangan')->nullable();
			$table->text('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logbook_kegiatans');
    }
};
