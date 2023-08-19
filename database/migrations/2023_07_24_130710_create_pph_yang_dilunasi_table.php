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
        Schema::create('pph_yang_dilunasi', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25);
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('total_pph');
            $table->date('tanggal');
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
        Schema::dropIfExists('pph_yang_dilunasi');
    }
};
