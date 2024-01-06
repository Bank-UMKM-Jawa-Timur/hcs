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
            $table->bigInteger('batch_id', false, true)->after('id');
            $table->foreign('batch_id')
                ->references('id')
                ->on('batch_gaji_per_bulan');
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
            $table->dropForeign('gaji_per_bulan_batch_id_foreign');
            $table->dropColumn('batch_id');
        });
    }
};
