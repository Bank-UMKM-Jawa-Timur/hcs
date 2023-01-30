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
        Schema::create('pejabat_sementara', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25);
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->string('kd_jabatan', 15);
            $table->string('kd_entitas', 15)->nullable();
            $table->string('kd_bagian')->nullable();
            $table->string('no_sk', 100);
            $table->string('file_sk', 100);
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')
                ->on('mst_karyawan')
                ->cascadeOnUpdate();

            $table->foreign('kd_jabatan')
                ->references('kd_jabatan')
                ->on('mst_jabatan')
                ->cascadeOnUpdate();

            $table->foreign('kd_bagian')
                ->references('kd_bagian')
                ->on('mst_bagian')
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pejabat_sementara');
    }
};
