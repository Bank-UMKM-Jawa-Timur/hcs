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
        Schema::create('mst_karyawan', function (Blueprint $table) {
            $table->string('nip', 25)->primary();
            $table->string('nama_karyawan');
            $table->string('kd_bagian', 15)->nullable();
            $table->string('kd_jabatan', 15);
            $table->string('ket_jabatan');
            $table->string('kd_entitas', 15)->nullable();
            $table->string('kd_panggol');
            $table->unsignedBigInteger('id_is')->nullable();
            $table->string('kd_agama');
            $table->string('tmp_lahir');
            $table->date('tgl_lahir');
            $table->string('kewarganegaraan');
            $table->string('nik', 16);
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->enum('status', ['Kawin', 'Belum Kawin', 'Janda', 'Duda']);
            $table->string('alamat_ktp');
            $table->string('alamat_sek');
            $table->string('kpj');
            $table->string('jkn');
            $table->integer('gj_pokok');
            $table->integer('gj_penyesuaian');
            $table->enum('status_karyawan', ['Tetap', 'IKJP', 'Kontrak Perpanjangan']);
            $table->enum('status_jabatan', ['Definitif', 'Penjabat', 'Penjabat Sementara']);
            $table->string('skangkat');
            $table->date('tanggal_pengangkat');
            $table->timestamps();

            $table->foreign('kd_jabatan')
                ->references('kd_jabatan')
                ->on('mst_jabatan')
                ->onUpdate('cascade');

            $table->foreign('kd_panggol')
                ->references('golongan')
                ->on('mst_pangkat_golongan')
                ->onUpdate('cascade');

            $table->foreign('id_is')
                ->references('id')
                ->on('is')
                ->onUpdate('cascade');

            $table->foreign('kd_agama')
                ->references('kd_agama')
                ->on('mst_agama')
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
        Schema::dropIfExists('karyawan');
    }
};
