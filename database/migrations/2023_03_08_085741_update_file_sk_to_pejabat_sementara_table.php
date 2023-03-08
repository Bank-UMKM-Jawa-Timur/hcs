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
        Schema::table('pejabat_sementara', function (Blueprint $table) {
            DB::statement('alter table `pejabat_sementara` change `file_sk` `file_sk` varchar(100) null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pejabat_sementara', function (Blueprint $table) {
            //
        });
    }
};
