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
            DB::statement("ALTER TABLE mst_karyawan change `pendidikan` `pendidikan` ENUM('SD', 'SMP', 'SLTA', 'SMK', 'S1', 'S2', 'S3')");
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
