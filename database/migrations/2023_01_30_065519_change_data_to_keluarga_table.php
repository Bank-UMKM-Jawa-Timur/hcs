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
        Schema::table('keluarga', function (Blueprint $table) {
            DB::statement("ALTER TABLE keluarga CHANGE `enum` `enum` ENUM('Istri','Suami', 'ANAK1', 'ANAK2') NULL");
            DB::statement("ALTER TABLE keluarga CHANGE `is_nama` `nama` varchar(255) null");
            DB::statement("ALTER TABLE keluarga CHANGE `is_tgl_lahir` `tgl_lahir` date null");
            DB::statement("ALTER TABLE keluarga CHANGE `is_alamat` `alamat` varchar(255) null");
            DB::statement("ALTER TABLE keluarga CHANGE `is_pekerjaan` `pekerjaan` varchar(255) null");
            DB::statement("ALTER TABLE keluarga CHANGE `is_jml_anak` `jml_anak` int(11) null");
            $table->string('nip', 25)->nullable();

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
        Schema::table('keluarga', function (Blueprint $table) {
            //
        });
    }
};
