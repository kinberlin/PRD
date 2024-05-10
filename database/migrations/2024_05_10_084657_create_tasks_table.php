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
        Schema::create('tasks', function (Blueprint $table){
            $table->increments('id');
            $table->integer('dysfunction');
            $table->string('text');
            $table->integer('duration');
            $table->float('progress');
            $table->dateTime('start_date');
            $table->integer('parent');
            $table->timestamps();
        });
    }
 
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
