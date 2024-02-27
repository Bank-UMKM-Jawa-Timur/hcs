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
            $table->integer('jkk')
                ->default(0)
                ->comment('penambah')
                ->after('bpjs_tk');
            $table->integer('jht')
                ->default(0)
                ->comment('penambah')
                ->after('jkk');
            $table->integer('jkm')
                ->default(0)
                ->comment('penambah')
                ->after('jht');
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
            $table->dropColumn('jkk');
            $table->dropColumn('jht');
            $table->dropColumn('jkm');
        });
    }
};
