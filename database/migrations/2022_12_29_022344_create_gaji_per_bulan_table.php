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
        Schema::create('gaji_per_bulan', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25);
            $table->string('bulan');
            $table->string('tahun');
            $table->integer('gj_pokok');
            $table->integer('gj_penyesuaian');
            $table->integer('tj_keluarga');
            $table->integer('tj_telepon');
            $table->integer('tj_jabatan');
            $table->integer('tj_teller');
            $table->integer('tj_perumahan');
            $table->integer('tj_kemahalan');
            $table->integer('tj_pelaksana');
            $table->integer('tj_kesejahteraan');
            $table->integer('tj_multilevel');
            $table->integer('tj_ti');
            $table->integer('tj_transport');
            $table->integer('tj_pulsa');
            $table->integer('tj_vitamin');
            $table->integer('uang_makan');
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')
                ->on('mst_karyawan')
                ->onDelete('cascade')
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
        Schema::dropIfExists('gaji_per_bulan');
    }
};
