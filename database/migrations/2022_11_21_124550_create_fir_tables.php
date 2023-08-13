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
    public function up(): void
    {
        Schema::create('fir_firs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('mail');
            $table->foreignId('team_id')->constrained('teams');
            $table->timestamps();
        });

        Schema::create('user_firs', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('fir_id')->constrained('fir_firs');
            $table->dateTime('joined_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['user_id', 'fir_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_firs');
        Schema::dropIfExists('fir_firs');
    }
};
