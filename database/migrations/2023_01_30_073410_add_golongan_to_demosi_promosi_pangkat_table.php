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
        Schema::table('demosi_promosi_pangkat', function (Blueprint $table) {
            $table->string('kd_panggol_lama', 15)->nullable();
            $table->string('kd_panggol_baru', 15)->nullable();

            $table->foreign('kd_panggol_lama')
                ->references('golongan')
                ->on('mst_pangkat_golongan')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('kd_panggol_baru')
                ->references('golongan')
                ->on('mst_pangkat_golongan')
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
        Schema::table('demosi_promosi_pangkat', function (Blueprint $table) {
            //
        });
    }
};
