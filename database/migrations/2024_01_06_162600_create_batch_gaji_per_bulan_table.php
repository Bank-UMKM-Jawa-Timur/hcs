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
        Schema::create('batch_gaji_per_bulan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_input');
            $table->enum('status', ['proses', 'final'])
                ->default('proses');
            $table->date('tanggal_final')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_gaji_per_bulan');
    }
};
