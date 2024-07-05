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
        Schema::table('t2024_lokasis', function (Blueprint $table) {
            $table->text('tanamComment')->nullable()->after('luas_tanam');
            $table->text('tanamFoto')->nullable()->after('tanamComment');
			//----------------------------------------------------------------
            $table->date('lahandate')->nullable()->after('tanamFoto');
            $table->text('lahancomment')->nullable()->after('lahandate');
            $table->text('lahanfoto')->nullable()->after('lahancomment');
			//----------------------------------------------------------------
            $table->date('benihDate')->nullable()->after('lahanfoto');
            $table->text('benihComment')->nullable()->after('benihDate');
            $table->text('benihFoto')->nullable()->after('benihComment');
			//----------------------------------------------------------------
            $table->date('mulsaDate')->nullable()->after('benihFoto');
            $table->text('mulsaComment')->nullable()->after('mulsaDate');
            $table->text('mulsaFoto')->nullable()->after('mulsaComment');
			//----------------------------------------------------------------
            $table->date('pupuk1Date')->nullable()->after('mulsaFoto');
            $table->text('pupuk1Comment')->nullable()->after('pupuk1Date');
            $table->text('pupuk1Foto')->nullable()->after('pupuk1Comment');
			//----------------------------------------------------------------
            $table->date('pupuk2Date')->nullable()->after('pupuk1Foto');
            $table->text('pupuk2Comment')->nullable()->after('pupuk2Date');
            $table->text('pupuk2Foto')->nullable()->after('pupuk2Comment');
			//----------------------------------------------------------------
            $table->date('pupuk3Date')->nullable()->after('pupuk2Foto');
            $table->text('pupuk3Comment')->nullable()->after('pupuk3Date');
            $table->text('pupuk3Foto')->nullable()->after('pupuk3Comment');
			//----------------------------------------------------------------
            $table->date('optDate')->nullable()->after('pupuk3Foto');
            $table->text('optComment')->nullable()->after('optDate');
            $table->text('optFoto')->nullable()->after('optComment');
			//----------------------------------------------------------------
            $table->text('prodComment')->nullable()->after('tgl_akhir_panen');
            $table->text('prodFoto')->nullable()->after('prodComment');
			//----------------------------------------------------------------
            $table->text('distComment')->nullable()->after('vol_jual');
            $table->text('distFoto')->nullable()->after('distComment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t2024_lokasis', function (Blueprint $table) {
            //
        });
    }
};
