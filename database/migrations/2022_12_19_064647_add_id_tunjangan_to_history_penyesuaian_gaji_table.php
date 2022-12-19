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
        Schema::table('history_penyesuaian_gaji', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tunjangan')->nullable();

            $table->foreign('id_tunjangan')
                ->references('id')
                ->on('mst_tunjangan')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_penyesuaian_gaji', function (Blueprint $table) {
            //
        });
    }
};
