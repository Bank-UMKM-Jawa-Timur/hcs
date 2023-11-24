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
        Schema::table('mst_karyawan', function (Blueprint $table) {
            $table->index('nip');
            $table->index('nama_karyawan');
            $table->index('kd_bagian');
            $table->index('kd_jabatan');
            $table->index('kd_entitas');
            $table->index('kd_panggol');
            $table->index('kd_agama');
            $table->index('tmp_lahir');
            $table->index('tgl_lahir');
            $table->index('kewarganegaraan');
            $table->index('nik');
            $table->index('jk');
            $table->index('status');
            $table->index('alamat_ktp');
            $table->index('alamat_sek');
            $table->index('kpj');
            $table->index('jkn');
            $table->index('gj_pokok');
            $table->index('gj_penyesuaian');
            $table->index('status_karyawan');
            $table->index('status_jabatan');
            $table->index('skangkat');
            $table->index('tanggal_pengangkat');
            $table->index('kategori_penonaktifan');
            $table->index('sk_pemberhentian');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('no_rekening');
            $table->index('npwp');
            $table->index('tgl_mulai');
            $table->index('ket');
            $table->index('pendidikan');
            $table->index('pendidikan_major');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_karyawan', function (Blueprint $table) {
            $table->dropIndex('mst_karyawan_nip_index');
            $table->dropIndex('mst_karyawan_nama_karyawan_index');
            $table->dropIndex('mst_karyawan_kd_bagian_index');
            $table->dropIndex('mst_karyawan_kd_jabatan_index');
            $table->dropIndex('mst_karyawan_kd_entitas_index');
            $table->dropIndex('mst_karyawan_kd_panggol_index');
            $table->dropIndex('mst_karyawan_kd_agama_index');
            $table->dropIndex('mst_karyawan_tmp_lahir_index');
            $table->dropIndex('mst_karyawan_tgl_lahir_index');
            $table->dropIndex('mst_karyawan_kewarganegaraan_index');
            $table->dropIndex('mst_karyawan_nik_index');
            $table->dropIndex('mst_karyawan_jk_index');
            $table->dropIndex('mst_karyawan_status_index');
            $table->dropIndex('mst_karyawan_alamat_ktp_index');
            $table->dropIndex('mst_karyawan_alamat_sek_index');
            $table->dropIndex('mst_karyawan_kpj_index');
            $table->dropIndex('mst_karyawan_jkn_index');
            $table->dropIndex('mst_karyawan_gj_pokok_index');
            $table->dropIndex('mst_karyawan_gj_penyesuaian_index');
            $table->dropIndex('mst_karyawan_status_karyawan_index');
            $table->dropIndex('mst_karyawan_status_jabatan_index');
            $table->dropIndex('mst_karyawan_skangkat_index');
            $table->dropIndex('mst_karyawan_tanggal_pengangkat_index');
            $table->dropIndex('mst_karyawan_kategori_penonaktifan_index');
            $table->dropIndex('mst_karyawan_sk_pemberhentian_index');
            $table->dropIndex('mst_karyawan_created_at_index');
            $table->dropIndex('mst_karyawan_updated_at_index');
            $table->dropIndex('mst_karyawan_no_rekening_index');
            $table->dropIndex('mst_karyawan_npwp_index');
            $table->dropIndex('mst_karyawan_tgl_mulai_index');
            $table->dropIndex('mst_karyawan_ket_index');
            $table->dropIndex('mst_karyawan_pendidikan_index');
            $table->dropIndex('mst_karyawan_pendidikan_major_index');
        });
    }
};
