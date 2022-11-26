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
            $table->string('golongan_lama', 15);
            $table->string('golongan_baru', 15);
            $table->string('nip', 16);
            $table->date('tanggal_pengesahan');
            $table->string('bukti_sk');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')
                ->on('mst_karyawan')
                ->onUpdate('cascade');

            $table->foreign('golongan_lama')
                ->references('golongan')
                ->on('mst_pangkat_golongan')
                ->onUpdate('cascade');
                
            $table->foreign('golongan_baru')
                ->references('golongan')
                ->on('mst_pangkat_golongan')
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
