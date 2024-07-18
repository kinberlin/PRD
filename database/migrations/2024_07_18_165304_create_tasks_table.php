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
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dysfunction')->nullable();
            $table->string('text');
            $table->integer('duration');
            $table->double('progress', 8, 2);
            $table->string('start_date', 0)->nullable();
            $table->integer('parent');
            $table->timestamps();
            $table->integer('sortorder')->default(0);
            $table->boolean('unscheduled')->nullable()->default(false);
            $table->string('process', 100)->nullable();
            $table->string('description', 65535)->nullable();
            $table->boolean('open')->nullable()->default(true);
            $table->string('proof', 65535)->nullable();
            $table->json('view_by')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
