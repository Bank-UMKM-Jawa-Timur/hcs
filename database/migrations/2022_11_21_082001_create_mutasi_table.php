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
            $table->string('kd_jabatan_lama', 15)->nullable();
            $table->string('kd_jabatan_baru', 15)->nullable();
            $table->string('kd_subdiv_lama', 15)->nullable();
            $table->string('kd_subdiv_baru', 15)->nullable();
            $table->string('kd_cabang_lama', 15)->nullable();
            $table->string('kd_cabang_baru', 15)->nullable();
            $table->date('tanggal_pengesahan');
            $table->string('bukti_sk');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')
                ->on('mst_karyawan')
                ->onUpdate('cascade');

            $table->foreign('kd_jabatan_lama')
                ->references('kd_jabatan')
                ->on('mst_jabatan')
                ->onUpdate('cascade');

            $table->foreign('kd_jabatan_baru')
                ->references('kd_jabatan')
                ->on('mst_jabatan')
                ->onUpdate('cascade');

            $table->foreign('kd_subdiv_lama')
                ->references('kd_subdiv')
                ->on('mst_sub_divisi')
                ->onUpdate('cascade');

            $table->foreign('kd_subdiv_baru')
            ->references('kd_subdiv')
            ->on('mst_sub_divisi')
            ->onUpdate('cascade');

            $table->foreign('kd_cabang_baru')
                ->references('kd_cabang')
                ->on('mst_cabang')
                ->onUpdate('cascade');

            $table->foreign('kd_cabang_lama')
                ->references('kd_cabang')
                ->on('mst_cabang')
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
