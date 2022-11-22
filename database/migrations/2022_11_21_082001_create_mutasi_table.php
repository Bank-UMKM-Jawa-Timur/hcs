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
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 16);
            $table->unsignedBigInteger('id_jabatan_lama');
            $table->unsignedBigInteger('id_jabatan_baru');
            $table->unsignedBigInteger('id_subdiv_lama')->nullable();
            $table->unsignedBigInteger('id_subdiv_baru')->nullable();
            $table->unsignedBigInteger('id_cabang_lama')->nullable();
            $table->unsignedBigInteger('id_cabang_baru')->nullable();
            $table->date('tanggal_pengesahan');
            $table->string('bukti_sk');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')
                ->on('karyawan')
                ->onUpdate('cascade');

            $table->foreign('id_jabatan_lama')
                ->references('id')
                ->on('jabatan')
                ->onUpdate('cascade');

            $table->foreign('id_jabatan_baru')
                ->references('id')
                ->on('jabatan')
                ->onUpdate('cascade');

            $table->foreign('id_subdiv_lama')
                ->references('id')
                ->on('sub_divisi')
                ->onUpdate('cascade');

            $table->foreign('id_cabang_baru')
                ->references('id')
                ->on('cabang')
                ->onUpdate('cascade');

            $table->foreign('id_cabang_lama')
                ->references('id')
                ->on('cabang')
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
        Schema::dropIfExists('mutasi');
    }
};
