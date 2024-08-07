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
        Schema::create('dysfunction', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('enterprise', 100);
            $table->integer('enterprise_id')->index('enterprise_id');
            $table->string('site', 100);
            $table->integer('site_id')->nullable()->index('site_id');
            $table->string('emp_signaling', 100);
            $table->string('emp_matricule', 100);
            $table->string('emp_email', 100)->nullable();
            $table->string('description', 65535)->nullable();
            $table->json('concern_processes')->nullable();
            $table->json('impact_processes')->nullable();
            $table->string('gravity', 100)->nullable();
            $table->integer('gravity_id')->nullable();
            $table->integer('probability')->nullable()->index('probability');
            $table->json('corrective_acts')->nullable();
            $table->integer('status')->nullable()->default(1)->index('status');
            $table->integer('progression')->nullable()->default(0);
            $table->json('pj')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->softDeletes();
            $table->string('occur_date', 0);
            $table->string('cause', 100)->nullable();
            $table->string('rej_reasons', 100)->nullable();
            $table->string('code', 50)->nullable();
            $table->tinyInteger('solved')->nullable()->default(0);
            $table->integer('cost')->nullable()->default(0);
            $table->string('satisfaction_description', 65535)->nullable();
            $table->string('closed_by', 100)->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->integer('origin')->nullable()->index('origin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dysfunction');
    }
};
