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
        Schema::create('gravity', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 20)->unique('name');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->softDeletes();
            $table->integer('note');
            $table->integer('least_price')->default(0);
            $table->integer('max_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gravity');
    }
};
