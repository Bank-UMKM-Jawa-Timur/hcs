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
        DB::statement("ALTER TABLE `mst_karyawan` CHANGE `pendidikan` `pendidikan` ENUM('SD','SMP','SMP/SLTP','SLTP','SLTA','SMK','SMA','SMA/SLTA','SMEA','D1','D2','D3','D3 (Diploma)','D4','D4 (Diploma)','D4/S1','S1','S1 (Sarjana)','S2','S2 (Magister)','S2 (Magister Akuntansi)','S3','PAKET B')");
    }

    /**
     * Reverse the migrations.
     *21
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `mst_karyawan` CHANGE `pendidikan` `pendidikan` ENUM('SD','SMP','SLTP','SLTA','SMK','D1','D2','D3','D4','S1','S2','S3')");
    }
};
