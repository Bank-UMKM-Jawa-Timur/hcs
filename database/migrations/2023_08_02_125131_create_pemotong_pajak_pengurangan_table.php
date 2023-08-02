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
        Schema::create('pemotong_pajak_pengurangan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_profil_kantor', false, true);
            $table->decimal('dpp', 4, 2, true);
            $table->decimal('jp', 4, 2, true);
            $table->decimal('jp_jan_feb', 8, 0, true);
            $table->decimal('jp_mar_des', 8, 0, true);
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
        Schema::dropIfExists('pemotong_pajak_pengurangan');
    }
};
