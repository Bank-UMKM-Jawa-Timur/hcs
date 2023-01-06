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
            DB::statement("ALTER TABLE `mst_karyawan` CHANGE `status_karyawan` `status_karyawan` ENUM('Tetap', 'IKJP', 'Kontrak Perpanjangan', 'Nonaktif')");
            $table->date('tanggal_penonaktifan')->after('tanggal_pengangkat')->nullable();
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
            DB::statement("ALTER TABLE `mst_karyawan` CHANGE `status_karyawan` `status_karyawan` ENUM('Tetap', 'IKJP', 'Kontrak Perpanjangan')");
            $table->dropColumn('tanggal_penonaktifan');
        });
    }
};
