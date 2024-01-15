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
        Schema::table('mst_profil_kantor', function(Blueprint $table) {
            $table->dropUnique('mst_profil_kantor_npwp_pemimpin_cabang_unique');
            $table->dropUnique('mst_profil_kantor_nama_pemimpin_cabang_unique');
            $table->dropUnique('mst_profil_kantor_telp_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_profil_kantor', function(Blueprint $table) {
            $table->unique('npwp_pemimpin_cabang');
            $table->unique('nama_pemimpin_cabang');
            $table->unique('telp');
        });
    }
};
