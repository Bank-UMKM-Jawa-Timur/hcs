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
        \DB::statement("ALTER TABLE gaji_per_bulan CHANGE jp jp int(11) NOT NULL DEFAULT 0 COMMENT 'penambah' AFTER kesehatan");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE gaji_per_bulan CHANGE jp jp int(11) NOT NULL DEFAULT 0 AFTER dpp");
    }
};
