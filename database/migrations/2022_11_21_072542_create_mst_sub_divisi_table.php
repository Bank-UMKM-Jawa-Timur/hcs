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
        Schema::create('mst_sub_divisi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_subdivisi');
            $table->unsignedBigInteger('kd_divisi', 15);
            $table->timestamps();

            $table->foreign('kd_divisi')
                ->references('kd_divisi')
                ->on('mst_divisi')
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
        Schema::dropIfExists('sub_divisi');
    }
};
