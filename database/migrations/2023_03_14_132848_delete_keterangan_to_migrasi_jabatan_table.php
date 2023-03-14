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
        Schema::table('migrasi_jabatan', function (Blueprint $table) {
            DB::statement('ALTER TABLE `migrasi_jabatan` DROP COLUMN `keterangan`');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('migrasi_jabatan', function (Blueprint $table) {
            //
        });
    }
};
