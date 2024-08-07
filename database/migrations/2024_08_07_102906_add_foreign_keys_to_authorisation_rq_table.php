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
        Schema::table('authorisation_rq', function (Blueprint $table) {
            $table->foreign(['user'], 'authorisation_rq_ibfk_1')->references(['id'])->on('users');
            $table->foreign(['enterprise'], 'authorisation_rq_ibfk_2')->references(['id'])->on('enterprise');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authorisation_rq', function (Blueprint $table) {
            $table->dropForeign('authorisation_rq_ibfk_1');
            $table->dropForeign('authorisation_rq_ibfk_2');
        });
    }
};
