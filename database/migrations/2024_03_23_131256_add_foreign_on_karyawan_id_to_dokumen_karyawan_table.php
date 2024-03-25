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
        Schema::table('dokumen_karyawan', function (Blueprint $table) {
            $table->foreign('karyawan_id')
                    ->references('id')
                    ->on('mst_karyawan')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dokumen_karyawan', function (Blueprint $table) {
            $table->dropForeign('dokumen_karyawan_mst_karyawan_karyawan_id_foreign');
        });
    }
};
