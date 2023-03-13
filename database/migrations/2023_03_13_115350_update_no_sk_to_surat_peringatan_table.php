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
        Schema::table('surat_peringatan', function (Blueprint $table) {
            DB::statement('ALTER TABLE `surat_peringatan` CHANGE `no_sp` `no_sp` varchar(255) null');
            DB::statement('ALTER TABLE `surat_peringatan` CHANGE `pelanggaran` `pelanggaran` varchar(255) null');
            DB::statement('ALTER TABLE `surat_peringatan` CHANGE `sanksi` `sanksi` varchar(255) null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surat_peringatan', function (Blueprint $table) {
            //
        });
    }
};
