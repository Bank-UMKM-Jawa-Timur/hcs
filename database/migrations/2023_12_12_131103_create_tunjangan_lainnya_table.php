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
        Schema::create('tunjangan_lainnya', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25);
            $table->bigInteger('id_tunjangan', false, true);
            $table->integer('nominal', false, true);
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')
                ->on('mst_karyawan')
                ->cascadeOnDelete();

            $table->foreign('id_tunjangan')
                ->references('id')
                ->on('mst_tunjangan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tunjangan_lainnya');
    }
};
