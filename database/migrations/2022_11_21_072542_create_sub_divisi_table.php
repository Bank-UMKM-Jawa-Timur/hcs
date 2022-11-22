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
        Schema::create('sub_divisi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_subdivisi');
            $table->unsignedBigInteger('id_divisi');
            $table->timestamps();

            $table->foreign('id_divisi')
                ->references('id')
                ->on('divisi')
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
