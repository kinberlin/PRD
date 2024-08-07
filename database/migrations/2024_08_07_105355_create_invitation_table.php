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
        Schema::create('invitation', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('dysfonction')->index('dysfonction');
            $table->string('object', 150);
            $table->timestamp('odates');
            $table->string('place', 65535)->nullable();
            $table->string('link', 65535)->nullable();
            $table->string('description', 65535)->nullable();
            $table->string('rq', 100);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->softDeletes();
            $table->string('motif', 100)->nullable();
            $table->json('internal_invites')->nullable();
            $table->json('external_invites')->nullable();
            $table->string('begin', 10);
            $table->string('end', 10);
            $table->json('participation')->nullable();
            $table->timestamp('closed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitation');
    }
};
