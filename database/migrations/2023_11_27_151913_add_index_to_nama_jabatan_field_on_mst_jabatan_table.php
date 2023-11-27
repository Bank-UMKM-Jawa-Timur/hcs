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
        Schema::table('mst_jabatan', function (Blueprint $table) {
            $table->fullText('nama_jabatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_jabatan', function (Blueprint $table) {
            $table->dropIndex('mst_jabatan_nama_jabatan_fulltext');
        });
    }
};
