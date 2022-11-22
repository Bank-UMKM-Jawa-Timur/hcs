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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->string('nip', 25)->primary();
            $table->string('nama_karyawan');
            $table->unsignedBigInteger('id_pangkat');
            $table->string('skangkat');
            $table->date('tanggal_pengangkat');
            $table->timestamps();

            $table->foreign('id_pangkat')
                ->references('id')
                ->on('pangkat_golongan')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
};
