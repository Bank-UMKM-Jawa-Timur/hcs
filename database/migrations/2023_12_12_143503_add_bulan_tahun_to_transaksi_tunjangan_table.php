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
        Schema::table('transaksi_tunjangan', function (Blueprint $table) {
            $table->integer('tahun')->nullable()->after('tanggal');
            $table->integer('bulan')->nullable()->after('tahun');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_tunjangan', function (Blueprint $table) {
            $table->dropColumn('tahun');
            $table->dropColumn('bulan');
        });
    }
};
