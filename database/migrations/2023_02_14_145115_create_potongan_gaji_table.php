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
        Schema::create('potongan_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25)->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('kredit_koperasi')->nullable();
            $table->integer('iuran_koperasi')->nullable();
            $table->integer('kredit_pegawai')->nullable();
            $table->integer('iuran_jk')->nullable();
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
        Schema::dropIfExists('potongan_gaji');
    }
};
