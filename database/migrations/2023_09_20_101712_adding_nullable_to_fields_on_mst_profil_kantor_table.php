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
        Schema::table('mst_profil_kantor', function (Blueprint $table) {
            $table->string('npwp_pemotong', 20)->nullable()->change();
            $table->string('nama_pemotong', 50)->nullable()->change();
            $table->string('npwp_pemimpin_cabang', 20)->nullable()->change();
            $table->string('nama_pemimpin_cabang', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_profil_kantor', function (Blueprint $table) {
            $table->string('npwp_pemotong', 20)->nullable(false)->change();
            $table->string('nama_pemotong', 50)->nullable(false)->change();
            $table->string('npwp_pemimpin_cabang', 20)->nullable(false)->change();
            $table->string('nama_pemimpin_cabang', 50)->nullable(false)->change();
        });
    }
};
