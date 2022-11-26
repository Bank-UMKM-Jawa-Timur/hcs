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
        Schema::create('tunjangan_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25);
            $table->unsignedBigInteger('id_tanggungan');
            $table->integer('nominal');
            $table->timestamps();

            $table->foreign('id_tanggungan')
                ->references('id')
                ->on('tanggungan')
                ->onUpdate('cascade');

            $table->foreign('nip')
                ->references('nip')
                ->on('karyawan')
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
        Schema::dropIfExists('tunjangan_karyawan');
    }
};
