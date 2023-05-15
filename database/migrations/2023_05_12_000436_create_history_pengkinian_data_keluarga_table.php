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
        Schema::create('history_pengkinian_data_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25);
            $table->enum('enum', ['Istri', 'Suami', 'ANAK1', 'ANAK2'])->nullable();
            $table->string('nama')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->integer('jml_anak')->nullable();
            $table->string('sk_tunjangan')->nullable();
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')
                ->on('mst_karyawan')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_pengkinian_data_keluarga');
    }
};
