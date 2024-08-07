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
        Schema::create('authorisation_rq', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user')->index('user');
            $table->integer('enterprise')->index('enterprise');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->softDeletes();
            $table->boolean('interim')->nullable()->default(false);
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authorisation_rq');
    }
};
