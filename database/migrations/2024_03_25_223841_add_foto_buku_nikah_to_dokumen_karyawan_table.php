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
        Schema::table('dokumen_karyawan', function (Blueprint $table) {
            $table->string('foto_buku_nikah')
                ->nullable()
                ->after('foto_kk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dokumen_karyawan', function (Blueprint $table) {
            $table->dropColumn('foto_buku_nikah');
        });
    }
};
