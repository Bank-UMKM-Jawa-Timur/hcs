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
        Schema::table('batch_penghasilan_tidak_teratur', function(Blueprint $table) {
            $table->bigInteger('penghasilan_tidak_teratur_id', false, true)
                ->nullable()
                ->change();
            $table->foreign('penghasilan_tidak_teratur_id', 'tidak_teratur_id_foreign')
                ->references('id')
                ->on('penghasilan_tidak_teratur')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_penghasilan_tidak_teratur', function(Blueprint $table) {
            $table->bigInteger('penghasilan_tidak_teratur_id', false, true)
                ->nullable(false)
                ->change();
            $table->foreign('penghasilan_tidak_teratur_id', 'tidak_teratur_id_foreign')
                    ->references('id')
                    ->on('penghasilan_tidak_teratur');
        });
    }
};
