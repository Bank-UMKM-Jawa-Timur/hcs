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
        Schema::create('dokumen_karyawan', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id', false, true);
            $table->string('foto_diri');
            $table->string('foto_ktp')
                    ->nullable();
            $table->string('foto_kk')
                    ->nullable();
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
        Schema::dropIfExists('dokumen_karyawan');
    }
};
