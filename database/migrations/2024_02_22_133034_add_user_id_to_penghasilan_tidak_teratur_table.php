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
        Schema::table('penghasilan_tidak_teratur', function (Blueprint $table) {
            $table->bigInteger('user_id', false, true)
                ->nullable()
                ->after('is_lock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penghasilan_tidak_teratur', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
