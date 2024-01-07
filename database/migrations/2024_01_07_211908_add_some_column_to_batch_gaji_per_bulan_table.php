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
            $table->string('file')->nullable()->after('tanggal_final');
            $table->date('tanggal_cetak')->nullable()->after('file');
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
            //
        });
    }
};
