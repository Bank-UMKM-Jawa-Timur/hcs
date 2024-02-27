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
        DB::statement("ALTER TABLE gaji_per_bulan CHANGE created_at created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER iuran_ik");
        DB::statement("ALTER TABLE gaji_per_bulan CHANGE updated_at updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER created_at");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE gaji_per_bulan CHANGE created_at created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER uang_makan");
        DB::statement("ALTER TABLE gaji_per_bulan CHANGE updated_at updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER created_at");
    }
};
