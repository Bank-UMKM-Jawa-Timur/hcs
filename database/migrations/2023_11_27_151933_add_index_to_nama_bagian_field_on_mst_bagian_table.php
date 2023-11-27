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
        Schema::table('mst_bagian', function (Blueprint $table) {
            $table->fullText('nama_bagian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_bagian', function (Blueprint $table) {
            $table->dropIndex('mst_bagian_nama_bagian_fulltext');
        });
    }
};
