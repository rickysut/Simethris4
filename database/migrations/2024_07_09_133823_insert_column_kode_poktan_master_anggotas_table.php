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
		Schema::table('t2024_master_anggotas', function (Blueprint $table) {
            $table->string('kode_poktan')->nullable()->after('id');
        });

        Schema::table('t2024_master_poktans', function (Blueprint $table) {
            $table->string('kode_poktan')->nullable()->after('id');
        });
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
