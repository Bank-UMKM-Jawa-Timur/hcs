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
        Schema::table('gaji_per_bulan', function (Blueprint $table) {
            $table->integer('kredit_koperasi', false, true);
            $table->integer('iuran_koperasi', false, true);
            $table->integer('kredit_pegawai', false, true);
            $table->integer('iuran_ik', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gaji_per_bulan', function (Blueprint $table) {
            $table->dropColumn('kredit_koperasi');
            $table->dropColumn('iuran_koperasi');
            $table->dropColumn('kredit_pegawai');
            $table->dropColumn('iuran_ik');
        });
    }
};
