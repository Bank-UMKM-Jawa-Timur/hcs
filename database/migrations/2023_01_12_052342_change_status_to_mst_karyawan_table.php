<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            DB::statement("ALTER TABLE `mst_karyawan` CHANGE `status` `status` ENUM('Kawin','Belum Kawin','Janda','Duda','Tidak Diketahui','Cerai','Cerai Mati', 'K', 'TK')");
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
            //
        });
    }
};
