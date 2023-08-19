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
        Schema::create('pemotong_pajak_tambahan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_profil_kantor', false, true);
            $table->decimal('jkk', 4, 2, true);
            $table->decimal('jht', 4, 2, true);
            $table->decimal('jkm', 4, 2, true);
            $table->decimal('kesehatan', 4, 2, true);
            $table->decimal('kesehatan_batas_atas', 8, 0, true);
            $table->decimal('kesehatan_batas_bawah', 8, 0, true);
            $table->decimal('jp', 4, 2, true);
            $table->decimal('total', 4, 2, true);
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->foreign('id_profil_kantor')->references('id')->on('mst_profil_kantor')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemotong_pajak_tambahan');
    }
};
