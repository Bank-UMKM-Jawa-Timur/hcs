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
        Schema::table('pph_yang_dilunasi', function (Blueprint $table) {
            $table->bigInteger('gaji_per_bulan_id', false, true)->after('id');
            $table->foreign('gaji_per_bulan_id')
                ->references('id')
                ->on('gaji_per_bulan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pph_yang_dilunasi', function (Blueprint $table) {
            $table->dropForeign('pph_yang_dilunasi_gaji_per_bulan_id_foreign');
            $table->dropColumn('gaji_per_bulan_id');
        });
    }
};
