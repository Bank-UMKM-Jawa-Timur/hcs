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
        \DB::statement("ALTER TABLE `history_pengkinian_data_keluarga` CHANGE `enum` `enum` ENUM('Istri', 'Suami', 'ANAK1', 'ANAK2', 'Anak')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `history_pengkinian_data_keluarga` CHANGE `enum` `enum` ENUM('Istri', 'Suami', 'ANAK1', 'ANAK2')");
    }
};
