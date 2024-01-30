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
        Schema::create('batch_penghasilan_tidak_teratur', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gaji_per_bulan_id', false, true);
            $table->bigInteger('penghasilan_tidak_teratur_id', false, true);
            $table->bigInteger('id_tunjangan', false, true);
            $table->integer('nominal', false);
            $table->timestamps();

            $table->foreign('gaji_per_bulan_id')
                ->references('id')
                ->on('gaji_per_bulan');
            $table->foreign('penghasilan_tidak_teratur_id', 'tidak_teratur_id_foreign')
                ->references('id')
                ->on('penghasilan_tidak_teratur');
            $table->foreign('id_tunjangan')
                ->references('id')
                ->on('mst_tunjangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_penghasilan_tidak_teratur');
    }
};
