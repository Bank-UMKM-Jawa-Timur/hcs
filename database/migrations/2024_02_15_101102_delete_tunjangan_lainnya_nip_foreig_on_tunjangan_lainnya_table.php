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
        Schema::table('transaksi_tunjangan', function (Blueprint $table) {
            $table->dropForeign('tunjangan_lainnya_nip_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Add the foreign key constraint
        Schema::table('transaksi_tunjangan', function (Blueprint $table) {
            $table->foreign('nip', 'tunjangan_lainnya_nip_foreign')
                    ->references('nip')
                    ->on('mst_karyawan')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
        });

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
