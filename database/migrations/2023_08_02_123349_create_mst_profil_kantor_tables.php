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
        Schema::create('mst_profil_kantor', function (Blueprint $table) {
            $table->id();
            $table->string('kd_cabang', 15)->unique()->nullable();
            $table->string('masa_pajak', 7)->nullable();
            $table->date('tanggal_lapor')->nullable();
            $table->string('npwp_pemotong', 20);
            $table->string('nama_pemotong', 50);
            $table->string('telp', 20)->unique()->nullable();
            $table->string('email', 50)->unique();
            $table->string('npwp_pemimpin_cabang', 20)->unique();
            $table->string('nama_pemimpin_cabang', 50)->unique();
            $table->timestamps();

            $table->foreign('kd_cabang')->references('kd_cabang')->on('mst_cabang')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_profil_kantor');
    }
};
