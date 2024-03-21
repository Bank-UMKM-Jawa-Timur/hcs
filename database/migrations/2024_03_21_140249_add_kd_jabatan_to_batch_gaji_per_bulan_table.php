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
        Schema::table('batch_gaji_per_bulan', function (Blueprint $table) {
            $table->string('kd_jabatan', 10)
                ->nullable()
                ->after('kd_entitas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_gaji_per_bulan', function (Blueprint $table) {
            $table->dropColumn('kd_jabatan');
        });
    }
};
