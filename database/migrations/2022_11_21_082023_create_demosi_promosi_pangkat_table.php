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
        Schema::create('demosi_promosi_pangkat', function (Blueprint $table) {
            $table->id();
            $table->string('kd_jabatan_lama', 15);
            $table->string('kd_jabatan_baru', 15);
            $table->string('nip', 16);
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demosi_promosi_pangkat');
    }
};
