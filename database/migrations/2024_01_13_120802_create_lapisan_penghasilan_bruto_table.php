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
        Schema::create('lapisan_penghasilan_bruto', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ptkp', 100);
            $table->enum('kategori', ['Ter A', 'Ter B', 'Ter C']);
            $table->integer('nominal_start', false, true);
            $table->integer('nominal_end', false, true);
            $table->double('pengali', 4, 2, true);
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
        Schema::dropIfExists('lapisan_penghasilan_bruto');
    }
};
