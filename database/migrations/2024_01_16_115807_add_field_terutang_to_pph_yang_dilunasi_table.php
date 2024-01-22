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
            $table->integer('terutang')->default(0)->after('total_pph');
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
            $table->dropColumn('terutang');
        });
    }
};
