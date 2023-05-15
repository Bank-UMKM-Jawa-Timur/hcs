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
        Schema::create('history_pengkinian_data_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nip_baru', 25);
            $table->string('nip_lama', 25);
            $table->string('nama_karyawan');
            $table->string('kd_bagian', 15)->nullable();
            $table->string('kd_jabatan', 15);
            $table->string('ket_jabatan')->nullable();
            $table->string('kd_entitas', 15)->nullable();
            $table->string('kd_panggol')->nullable();
            $table->string('kd_agama')->nullable();
            $table->string('tmp_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('kewarganegaraan', ['WNA', 'WNI'])->nullable();
            $table->string('nik', 16);
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->enum('status', ['Kawin', 'Belum Kawin', 'Cerai', 'Cerai Mati', 'Janda', 'Duda', 'Tidak Diketahui']);
            $table->string('alamat_ktp')->nullable();
            $table->string('alamat_sek')->nullable();
            $table->string('kpj')->nullable();
            $table->string('jkn')->nullable();
            $table->integer('gj_pokok');
            $table->integer('gj_penyesuaian');
            $table->enum('status_karyawan', ['Tetap', 'IKJP', 'Kontrak Perpanjangan']);
            $table->enum('status_jabatan', ['Definitif', 'Penjabat', 'Penjabat Sementara']);
            $table->string('skangkat')->nullable();
            $table->date('tanggal_pengangkat')->nullable();
            $table->date('tanggal_penonaktifan')->nullable();
            $table->string('kategori_penonaktifan')->nullable();
            $table->string('sk_pemberhentian')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('npwp')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->enum('pendidikan', ['SD', 'SMP', 'SLTA', 'SMK', 'S1', 'S2', 'S3'])->nullable();
            $table->string('pendidikan_major')->nullable();
            $table->timestamps();

            $table->foreign('nip_baru')
                ->references('nip')
                ->on('mst_karyawan')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kd_jabatan')
                ->references('kd_jabatan')
                ->on('mst_jabatan')
                ->onUpdate('cascade');

            $table->foreign('kd_panggol')
                ->references('golongan')
                ->on('mst_pangkat_golongan')
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
        Schema::dropIfExists('history_pengkinian_data_karyawan');
    }
};
