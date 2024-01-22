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
        Schema::table('potongan_gaji', function(Blueprint $table) {
            $table->renameColumn('iuran_jk', 'iuran_ik');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('potongan_gaji', function(Blueprint $table) {
            $table->renameColumn('iuran_ik', 'iuran_jk');
        });
    }
};
