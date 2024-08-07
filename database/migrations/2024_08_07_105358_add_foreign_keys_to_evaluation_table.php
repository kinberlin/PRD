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
        Schema::table('evaluation', function (Blueprint $table) {
            $table->foreign(['task'], 'evaluation_ibfk_1')->references(['id'])->on('tasks')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            $table->dropForeign('evaluation_ibfk_1');
        });
    }
};
