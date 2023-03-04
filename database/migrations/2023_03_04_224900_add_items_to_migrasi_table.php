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
        Schema::table('migrasi', function (Blueprint $table) {
            DB::statement('alter table `migrasi` add column nip_baru varchar(25) null after `jabatan_tanggal`');
            DB::statement('alter table `migrasi` add column nip_lama varchar(25) null after `jabatan_tanggal`');
            DB::statement('alter table `migrasi` add column kd_entitas_baru varchar(255) null after `jabatan_baru`');
            DB::statement('alter table `migrasi` add column kd_entitas_lama varchar(255) null after `jabatan_baru`');
            DB::statement('alter table `migrasi` add column kd_jabatan_baru varchar(255) null after `jabatan_baru`');
            DB::statement('alter table `migrasi` add column kd_jabatan_lama varchar(255) null after `jabatan_baru`');
            DB::statement('alter table `migrasi` add column panggol_lama varchar(255) null after `jabatan_baru`');
            DB::statement('alter table `migrasi` add column panggol_baru varchar(255) null after `jabatan_baru`');
            DB::statement('alter table `migrasi` add column status_jabatan_baru varchar(255) null after `jabatan_baru`');
            DB::statement('alter table `migrasi` add column status_jabatan_lama varchar(255) null after `jabatan_baru`');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('migrasi', function (Blueprint $table) {
            //
        });
    }
};
