<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_surveykeys', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained('user_users', 'id');
            $table->string('name');
            $table->string('token');
            $table->string('url');
            $table->timestamp('valid_till')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_surveykeys');
    }
};
