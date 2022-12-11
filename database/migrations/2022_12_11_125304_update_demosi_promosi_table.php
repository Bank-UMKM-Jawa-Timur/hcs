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
            $table->dropForeign(['kd_jabatan_baru']);
            $table->dropForeign(['kd_jabatan_lama']);
            
            $table->dropColumn(['kd_jabatan_baru']);
            $table->dropColumn(['kd_jabatan_lama']);
        });
        Schema::table('demosi_promosi_pangkat', function($table) {
            $table->string('kd_entitas_lama', 15);
            $table->string('kd_entitas_baru', 15);
            $table->string('kd_jabatan_lama', 15);
            $table->string('kd_jabatan_baru', 15);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
