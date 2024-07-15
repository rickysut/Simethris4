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
			$table->string('npwp')->nullable();
			$table->string('no_ijin')->nullable();
			$table->string('kode_poktan', 255);
			$table->string('kode_spatial', 16);
			$table->string('ktp_petani', 255)->nullable();
			$table->double('luas_lahan')->nullable();
			$table->string('periode_tanam')->nullable();

			$table->date('tgl_tanam')->nullable();
			$table->decimal('luas_tanam')->nullable();
			$table->text('tanamComment')->nullable();
            $table->text('tanamFoto')->nullable();
			//----------------------------------------------------------------
			// pengolahan lahan
            $table->date('lahandate')->nullable();
            $table->text('lahancomment')->nullable();
            $table->text('lahanfoto')->nullable();
			//----------------------------------------------------------------
            $table->date('benihDate')->nullable();
            $table->text('benihComment')->nullable;
            $table->text('benihFoto')->nullable();
			//----------------------------------------------------------------
            $table->date('mulsaDate')->nullable();
            $table->text('mulsaComment')->nullable();
            $table->text('mulsaFoto')->nullable();
			//----------------------------------------------------------------
            $table->date('pupuk1Date')->nullable();
            $table->text('pupuk1Comment')->nullable();
            $table->text('pupuk1Foto')->nullable();
			//----------------------------------------------------------------
            $table->date('pupuk2Date')->nullable();
            $table->text('pupuk2Comment')->nullable();
            $table->text('pupuk2Foto')->nullable();
			//----------------------------------------------------------------
            $table->date('pupuk3Date')->nullable();
            $table->text('pupuk3Comment')->nullable();
            $table->text('pupuk3Foto')->nullable();
			//----------------------------------------------------------------
            $table->date('optDate')->nullable();
            $table->text('optComment')->nullable();
            $table->text('optFoto')->nullable();
			//----------------------------------------------------------------
			$table->date('tgl_panen')->nullable();
			$table->double('volume')->nullable();
			$table->double('vol_benih')->nullable();
            $table->double('vol_jual')->nullable();
            $table->text('prodComment')->nullable();
            $table->text('prodFoto')->nullable();
			//----------------------------------------------------------------
            $table->text('distComment')->nullable();
            $table->text('distFoto')->nullable();
			//----------------------------------------------------------------
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
