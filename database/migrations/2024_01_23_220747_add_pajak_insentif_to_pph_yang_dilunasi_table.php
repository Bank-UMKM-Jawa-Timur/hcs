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
            $table->integer('insentif_kredit', false, true)->default(0)->after('total_pph');
            $table->integer('insentif_penagihan', false, true)->default(0)->after('insentif_kredit');
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
            $table->dropColumn('insentif_kredit');
            $table->dropColumn('insentif_penagihan');
        });
    }
};
