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
        Schema::table('dokumen_pengkinian_data', function (Blueprint $table) {
            $table->foreign('pengkinian_id')
                ->references('id')
                ->on('history_pengkinian_data_karyawan')
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
        Schema::table('dokumen_pengkinian_data', function (Blueprint $table) {
            $table->dropForeign('dokumen_pengkinian_data_history_pengkinian_data_karyawan_pengkinian_id_foreign');
        });
    }
};
