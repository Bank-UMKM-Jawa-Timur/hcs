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
            $table->string('kd_bagian_lama', 15)->nullable();

            $table->foreign('kd_bagian_lama')
                ->references('kd_bagian')
                ->on('mst_bagian')
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
