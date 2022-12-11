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
        Schema::table('mutasi', function (Blueprint $table) {
            $table->dropForeign(['kd_jabatan_baru']);
            $table->dropForeign(['kd_jabatan_lama']);
            $table->dropForeign(['kd_subdiv_baru']);
            $table->dropForeign(['kd_subdiv_lama']);
            $table->dropForeign(['kd_cabang_baru']);
            $table->dropForeign(['kd_cabang_lama']);
            
            $table->dropColumn(['kd_jabatan_baru']);
            $table->dropColumn(['kd_jabatan_lama']);
            $table->dropColumn(['kd_subdiv_baru']);
            $table->dropColumn(['kd_subdiv_lama']);
            $table->dropColumn(['kd_cabang_baru']);
            $table->dropColumn(['kd_cabang_lama']);
            // $table->dropColumn('kd_entitas_lama');
            // $table->dropColumn('kd_entitas_baru');
        });
        Schema::table('mutasi', function($table) {
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
        Schema::table('mutasi', function (Blueprint $table) {
            $table->dropColumn(['kd_jabatan_baru', 'kd_jabatan_lama', 'kd_subdiv_baru', 'kd_subdiv_lama', 'kd_cabang_baru', 'kd_cabang_lama',]);
        });
    }
};
