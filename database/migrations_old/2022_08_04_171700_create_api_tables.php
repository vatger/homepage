<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique(); // Unique API key.
            $table->unsignedInteger('vatsim_id')->nullable();
            $table->string('description')->nullable();
            $table->timestamps(); // Timestamps of creation and updates
            $table->timestamp('valid_till')->nullable();

            $table
                ->foreign('vatsim_id')
                ->references('id')
                ->on('membership_accounts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('token_id')->nullable();
            $table->timestamp('time');
            $table->string('endpoint');
            $table->ipAddress();

            $table
                ->foreign('token_id')
                ->references('id')
                ->on('api_tokens')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('api_logs');
    }
};
