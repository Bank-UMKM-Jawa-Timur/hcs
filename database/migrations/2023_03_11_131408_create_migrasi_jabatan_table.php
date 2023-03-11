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
        Schema::create('migrasi_jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25)->nullable();
            $table->string('no_sk')->nullable();
            $table->date('tgl')->nullable();
            $table->string('lama')->nullable();
            $table->string('baru')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('migrasi_jabatan');
    }
};
