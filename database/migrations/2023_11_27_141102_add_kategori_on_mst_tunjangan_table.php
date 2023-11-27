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
        Schema::table('mst_tunjangan', function(Blueprint $table) {
            $table->enum('kategori', ['teratur', 'tidak teratur', 'bonus'])
                ->nullable()
                ->comment('Kategori Penghasilan')
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_tunjangan', function(Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
