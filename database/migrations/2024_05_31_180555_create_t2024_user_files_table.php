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
        Schema::dropIfExists('t2024_user_files');
        Schema::create('t2024_user_files', function (Blueprint $table) {
			$table->bigIncrements('id'); // index
			$table->string('kind', 55); // jenis berkas: spvt, sptjm, foto, dan lainnya
			$table->string('no_ijin'); // relasi dengan nomor ijin
			$table->string('file_code', 255)->unique(); // kode unik untuk identifier
			$table->text('file_url')->nullable(); // alamat url full path
			$table->bigInteger('verif_by')->nullable(); // optional jika diperlukan
			$table->date('verif_at')->nullable(); // optional jika diperlukan
			$table->tinyInteger('status')->nullable(); // status periksa jika dibutuhkan
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
        Schema::dropIfExists('t2024_user_files');
    }
};
