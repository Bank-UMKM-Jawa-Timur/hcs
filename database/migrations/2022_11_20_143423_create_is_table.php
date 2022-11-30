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
        Schema::create('is', function (Blueprint $table) {
            $table->id();
            $table->enum('enum', ['Istri', 'Suami']);
            $table->string('is_nama');
            $table->date('is_tgl_lahir');
            $table->string('is_alamat');
            $table->string('is_pekerjaan');
            $table->integer('is_jml_anak');
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
        Schema::dropIfExists('is');
    }
};
