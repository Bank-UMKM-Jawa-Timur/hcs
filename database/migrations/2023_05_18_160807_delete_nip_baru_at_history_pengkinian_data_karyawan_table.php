<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('history_pengkinian_data_karyawan', function(Blueprint $table){
            $table->dropForeign(['nip_baru']);
            $table->dropColumn('nip_baru');
            DB::statement('alter table history_pengkinian_data_karyawan change nip_lama nip varchar(25) not null');
            $table->foreign('nip')->on('mst_karyawan')->references('nip')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
