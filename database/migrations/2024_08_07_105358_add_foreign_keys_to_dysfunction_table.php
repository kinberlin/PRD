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
        Schema::table('dysfunction', function (Blueprint $table) {
            $table->foreign(['probability'], 'dysfunction_ibfk_2')->references(['id'])->on('probability');
            $table->foreign(['site_id'], 'dysfunction_ibfk_3')->references(['id'])->on('site')->onUpdate('CASCADE');
            $table->foreign(['origin'], 'dysfunction_ibfk_4')->references(['id'])->on('origin')->onUpdate('CASCADE');
            $table->foreign(['enterprise_id'], 'dysfunction_ibfk_5')->references(['id'])->on('enterprise')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dysfunction', function (Blueprint $table) {
            $table->dropForeign('dysfunction_ibfk_2');
            $table->dropForeign('dysfunction_ibfk_3');
            $table->dropForeign('dysfunction_ibfk_4');
            $table->dropForeign('dysfunction_ibfk_5');
        });
    }
};
