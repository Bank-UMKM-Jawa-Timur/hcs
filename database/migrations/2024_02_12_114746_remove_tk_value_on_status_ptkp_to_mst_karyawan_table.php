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
        Schema::table('mst_karyawan', function (Blueprint $table) {
            DB::statement("ALTER TABLE mst_karyawan MODIFY status_ptkp ENUM('K/0','K/1','K/2','K/3','K/I/0','K/I/1','K/I/2','K/I/3','TK/0','TK/1','TK/2','TK/3','TK/I')");
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
            DB::statement("ALTER TABLE mst_karyawan MODIFY status_ptkp ENUM('K/0','K/1','K/2','K/3','K/I/0','K/I/1','K/I/2','K/I/3','TK','TK/0','TK/1','TK/2','TK/3','TK/I')");
        });
    }
};
