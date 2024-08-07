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
        Schema::create('enterprise', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->unique('name');
            $table->string('surfix', 50)->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->boolean('visible')->nullable()->default(true);
            $table->string('logo', 65535)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enterprise');
    }
};
