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
		Schema::create('t2024_avtanams', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('tcode')->nullable();
			$table->string('npwp');
			$table->string('no_ijin');
			$table->string('status')->nullable(); //
			$table->text('note')->nullable();

			//file verifikasi
			$table->string('batanam')->nullable();
			$table->string('ndhprt')->nullable(); //nota dinas hasil pemeriksaan realisasi tanam

			$table->bigInteger('check_by')->nullable();
			$table->date('verif_at')->nullable();
			$table->text('report_url')->nullable();
			$table->string('metode')->nullable();
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
		Schema::dropIfExists('t2024_avtanams');
	}
};
