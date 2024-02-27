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
        Schema::table('gaji_per_bulan', function (Blueprint $table) {
            $table->integer('dpp')
                ->comment('potongan')
                ->change();
            $table->integer('bpjs_tk')
                ->comment('bpjs tk / jp1% (pengurang)')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gaji_per_bulan', function (Blueprint $table) {
            $table->integer('dpp')
                ->comment('')
                ->change();
            $table->integer('bpjs_tk')
                ->comment('')
                ->change();
        });
    }
};
