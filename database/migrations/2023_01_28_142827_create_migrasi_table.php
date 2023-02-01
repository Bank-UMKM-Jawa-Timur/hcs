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
        Schema::create('migrasi', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 15)->nullable();
            $table->date('jabatan_tanggal')->nullable();
            $table->string('no_sk', 100)->nullable();
            $table->string('jabatan_lama')->nullable();
            $table->string('jabatan_baru')->nullable();
            $table->double('lama_menjabat')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('no_sp', 100)->nullable();
            $table->string('sp_pelanggaran')->nullable();
            $table->string('sp_sanksi')->nullable();
            $table->string('pjs_jabatan_asli')->nullable();
            $table->string('pjs_jabatan')->nullable();
            $table->date('pjs_mulai')->nullable();
            $table->date('pjs_berakhir')->nullable();
            $table->string('pjs_status')->nullable();
            $table->enum('tipe', ['Jabatan', 'SP', 'PJS'])->nullable();
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
        Schema::dropIfExists('migrasi');
    }
};
