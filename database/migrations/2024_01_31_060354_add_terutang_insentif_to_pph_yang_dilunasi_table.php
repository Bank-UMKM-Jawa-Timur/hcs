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
            $table->integer('terutang_insentif', false, true)->default(0)->after('terutang');
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
            $table->dropColumn('terutang_insentif');
        });
    }
};
