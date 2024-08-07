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
        Schema::create('probability', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('description', 65535);
            $table->integer('note');
            $table->string('name', 50);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->boolean('visible')->nullable()->default(true);
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
        Schema::dropIfExists('probability');
    }
};
