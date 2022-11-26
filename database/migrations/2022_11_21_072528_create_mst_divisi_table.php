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
        Schema::create('mst_divisi', function (Blueprint $table) {
            $table->string('kd_divisi', 15)->primary();
            $table->string('nama_divisi');
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
        Schema::dropIfExists('divisi');
    }
};
