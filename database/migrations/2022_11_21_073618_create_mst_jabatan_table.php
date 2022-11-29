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
        Schema::create('mst_jabatan', function (Blueprint $table) {
            $table->string('kd_jabatan', 15)->primary();
            $table->string('nama_jabatan');
            $table->enum('status_jabatan', ['Definitif', 'Penjabat', 'Penjabat Sementara']);
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
        Schema::dropIfExists('jabatan');
    }
};
