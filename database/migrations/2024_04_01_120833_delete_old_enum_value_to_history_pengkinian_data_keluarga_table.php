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
        Schema::table('history_pengkinian_data_keluarga', function (Blueprint $table) {
            DB::statement("ALTER TABLE `keluarga` CHANGE `enum` `enum` ENUM('Istri', 'Suami', 'Anak')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_pengkinian_data_keluarga', function (Blueprint $table) {
            DB::statement("ALTER TABLE `keluarga` CHANGE `enum` `enum` ENUM('Istri', 'Suami', 'ANAK1', 'ANAK2', 'Anak')");
        });
    }
};
