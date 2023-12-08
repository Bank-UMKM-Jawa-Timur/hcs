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
        Schema::table('potongan_gaji', function (Blueprint $table) {
            $table->integer('kredit_koperasi', false, true)
                ->nullable()
                ->default(0)
                ->change();
            $table->integer('iuran_koperasi', false, true)
                ->nullable()
                ->default(0)
                ->change();
            $table->integer('kredit_pegawai', false, true)
                ->nullable()
                ->default(0)
                ->change();
            $table->integer('iuran_ik', false, true)
                ->nullable()
                ->default(0)
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
        Schema::table('potongan_gaji', function (Blueprint $table) {
            $table->integer('kredit_koperasi', false, true)
                ->nullable()
                ->default()
                ->change();
            $table->integer('kredit_koperasi', false, true)
                ->nullable()
                ->default()
                ->change();
            $table->integer('kredit_koperasi', false, true)
                ->nullable()
                ->default()
                ->change();
            $table->integer('kredit_koperasi', false, true)
                ->nullable()
                ->default()
                ->change();
        });
    }
};
