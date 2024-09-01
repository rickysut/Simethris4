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
        Schema::create('data_kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('kabupaten_id');
            $table->string('kecamatan_id');
            $table->text('kode_dagri')->nullable();
            $table->text('nama_kecamatan');
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->text('polygon')->nullable();
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
        Schema::dropIfExists('data_kecamatans');
    }
};
