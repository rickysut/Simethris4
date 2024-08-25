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
		Schema::dropIfExists('t2024_assignment_tanams');
		Schema::dropIfExists('t2024_assignment_verifikasis');
        Schema::create('t2024_assignment_verifikasis', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('tcode')->nullable();
			$table->unsignedBigInteger('pengajuan_id')->nullable();
			$table->string('kode_pengajuan')->nullable();
			$table->string('no_ijin')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('no_sk')->nullable();
			$table->date('tgl_sk')->nullable();
			$table->text('file')->nullable();
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
        Schema::dropIfExists('t2024_assignment_tanams');
    }
};
