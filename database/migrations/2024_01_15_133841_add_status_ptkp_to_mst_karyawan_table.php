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
        $status = [
            'K/0',
            'K/1',
            'K/2',
            'K/3',
            'K/I/0',
            'K/I/1',
            'K/I/2',
            'K/I/3',
            'TK',
            'TK/1',
            'TK/2',
            'TK/3',
        ];
        Schema::table('mst_karyawan', function (Blueprint $table) use ($status) {
            $table->enum('status_ptkp', $status)
                ->nullable()
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
        Schema::table('mst_karyawan', function (Blueprint $table) {
            $table->dropColumn('status_ptkp');
        });
    }
};
