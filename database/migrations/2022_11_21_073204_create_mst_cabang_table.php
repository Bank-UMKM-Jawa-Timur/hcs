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
        Schema::create('mst_cabang', function (Blueprint $table) {
            $table->string('kd_cabang', 15);
            $table->string('nama_cabang');
            $table->string('alamat_cabang');
            $table->unsignedBigInteger('id_kantor');
            $table->timestamps();

            $table->foreign('id_kantor')
                ->references('id')
                ->on('mst_kantor')
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
        Schema::dropIfExists('cabang');
    }
};
