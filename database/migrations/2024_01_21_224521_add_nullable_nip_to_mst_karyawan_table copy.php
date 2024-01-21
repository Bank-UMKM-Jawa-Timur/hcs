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
        Schema::table('mst_karyawan', function (Blueprint $table) {
            $table->increments('id')->after('nip');
            $table->string('nip', 25)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_karyawan', function (Blueprint $table) {
            $table->dropPrimary('PRIMARY');
            $table->dropColumn('id');
            $table->string('nip', 25)->nullable(false)->primary()->change();
        });
    }
};
