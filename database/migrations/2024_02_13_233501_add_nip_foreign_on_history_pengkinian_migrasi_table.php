<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Add the foreign key constraint
        Schema::table('migrasi', function (Blueprint $table) {
            $table->foreign('nip')
                    ->references('nip')
                    ->on('mst_karyawan')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
        });

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('migrasi', function(Blueprint $table) {
            $table->dropForeign('migrasi_nip_foreign');
        });
    }
};
