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
        Schema::table('authorisation_pilote', function (Blueprint $table) {
            $table->foreign(['process'], 'authorisation_pilote_ibfk_1')->references(['id'])->on('processes');
            $table->foreign(['user'], 'authorisation_pilote_ibfk_2')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authorisation_pilote', function (Blueprint $table) {
            $table->dropForeign('authorisation_pilote_ibfk_1');
            $table->dropForeign('authorisation_pilote_ibfk_2');
        });
    }
};
