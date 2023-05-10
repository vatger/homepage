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
            $table->string('token')->unique();
            $table
                ->foreignId('vatsim_id')
                ->nullable()
                ->constrained('user_users');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->timestamp('valid_till')->nullable();
        });

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('token_id')
                ->nullable()
                ->constrained('api_tokens');
            $table->timestamp('time');
            $table->string('endpoint');
            $table->ipAddress();
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
