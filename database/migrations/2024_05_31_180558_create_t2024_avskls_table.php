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
		Schema::create('t2024_avskls', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('npwp');
			$table->string('no_ijin');
			$table->string('tcode')->nullable();
			$table->string('status')->nullable();

			//file upload
			$table->text('report_url')->nullable();
			$table->text('baskls')->nullable();
			$table->text('ndhpskl')->nullable(); //nota dinas hasil pemeriksaan realisasi produksi

			$table->bigInteger('check_by')->nullable();
			$table->date('verif_at')->nullable();
			$table->string('metode')->nullable();
			$table->text('verif_note')->nullable();

			$table->bigInteger('recomend_by')->nullable();
			$table->date('recomend_at')->nullable();
			$table->text('recomend_note')->nullable();
			$table->text('draft_url')->nullable(); //untuk di ttd

			$table->bigInteger('approved_by')->nullable();
			$table->date('approved_at')->nullable();
			$table->string('no_skl')->nullable();
			$table->text('published_at')->nullable();
			$table->text('skl_url')->nullable(); //skl sudah ttd

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
		Schema::dropIfExists('t2024_avskls');
	}
};
