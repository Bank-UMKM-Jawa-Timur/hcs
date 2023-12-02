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
        Schema::table('penghasilan_tidak_teratur', function (Blueprint $table) {
            $table->string('keterangan', 150)->after('bulan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penghasilan_tidak_teratur', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
