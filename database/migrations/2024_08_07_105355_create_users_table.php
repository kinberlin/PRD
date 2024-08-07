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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();
            $table->string('phone', 100)->nullable();
            $table->string('image', 65535)->nullable();
            $table->string('matricule', 100)->unique('matricule');
            $table->integer('enterprise')->index('enterprise');
            $table->integer('department')->nullable()->index('department');
            $table->integer('role')->nullable()->default(2)->index('role');
            $table->string('poste', 100)->nullable();
            $table->boolean('access')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
