<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dysfunction');
            $table->string('type');
            $table->integer('source');
            $table->integer('target');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('links');
    }
};
