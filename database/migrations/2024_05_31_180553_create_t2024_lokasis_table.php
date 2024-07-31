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
		Schema::dropIfExists('t2024_lokasis');
        Schema::create('t2024_lokasis', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('origin');
			$table->string('tcode', 55);
			$table->string('npwp');
			$table->string('no_ijin');
			$table->string('kode_poktan', 255);
			$table->string('kode_spatial', 16);
			$table->string('ktp_petani', 255);
			$table->double('luas_lahan')->nullable();
			$table->string('periode_tanam')->nullable();

			$table->date('tgl_tanam')->nullable();
			$table->decimal('luas_tanam')->nullable();
			$table->text('tanamComment')->nullable();
            $table->text('tanamFoto')->nullable();
            $table->tinyInteger('tanamStatus')->nullable();
			//----------------------------------------------------------------
			// pengolahan lahan
            $table->date('lahandate')->nullable();
            $table->text('lahancomment')->nullable();
            $table->text('lahanfoto')->nullable();
            $table->tinyInteger('lahanStatus')->nullable();
			//----------------------------------------------------------------
            $table->date('benihDate')->nullable();
            $table->integer('benihsize')->nullable();
            $table->text('benihComment')->nullable();
            $table->text('benihFoto')->nullable();
            $table->tinyInteger('benihStatus')->nullable();
			//----------------------------------------------------------------
            $table->date('mulsaDate')->nullable();
            $table->integer('mulsaSize')->nullable();
            $table->text('mulsaComment')->nullable();
            $table->text('mulsaFoto')->nullable();
            $table->tinyInteger('mulsaStatus')->nullable();
			//----------------------------------------------------------------
            $table->date('pupuk1Date')->nullable();
            $table->integer('organik1')->nullable();
            $table->integer('npk1')->nullable();
            $table->integer('dolomit1')->nullable();
            $table->integer('za1')->nullable();
            $table->text('pupuk1Comment')->nullable();
            $table->text('pupuk1Foto')->nullable();
            $table->tinyInteger('pupuk1Status')->nullable();
			//----------------------------------------------------------------
            $table->date('pupuk2Date')->nullable();
            $table->integer('organik2')->nullable();
            $table->integer('npk2')->nullable();
            $table->integer('dolomit2')->nullable();
            $table->integer('za2')->nullable();
            $table->text('pupuk2Comment')->nullable();
            $table->text('pupuk2Foto')->nullable();
            $table->tinyInteger('pupuk2Status')->nullable();
			//----------------------------------------------------------------
            $table->date('pupuk3Date')->nullable();
            $table->integer('organik3')->nullable();
            $table->integer('npk3')->nullable();
            $table->integer('dolomit3')->nullable();
            $table->integer('za3')->nullable();
            $table->text('pupuk3Comment')->nullable();
            $table->text('pupuk3Foto')->nullable();
            $table->tinyInteger('pupuk3Status')->nullable();
			//----------------------------------------------------------------
            $table->date('optDate')->nullable();
            $table->text('optComment')->nullable();
            $table->text('optFoto')->nullable();
			$table->tinyInteger('optStatus')->nullable();
			//----------------------------------------------------------------
			$table->date('tgl_panen')->nullable();
			$table->double('volume')->nullable();
			$table->double('vol_benih')->nullable();
            $table->double('vol_jual')->nullable();
            $table->text('prodComment')->nullable();
            $table->text('prodFoto')->nullable();
            $table->tinyInteger('prodStatus')->nullable();
			//----------------------------------------------------------------
            $table->text('distComment')->nullable();
            $table->text('distFoto')->nullable();
            $table->tinyInteger('distStatus')->nullable();
			//----------------------------------------------------------------
			$table->integer('status')->nullable();
			$table->date('verifAt')->nullable();
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
