<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*

        Schema::create('ptd_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('ptd_modules', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('url');
            $table->unsignedInteger('current_version')->default(1);
            $table->timestamps();
        });

        Schema::create('ptd_module_requirements', function (Blueprint $table) {
            $table->foreignId('module_id')->constrained('ptd_modules');
            $table->foreignId('requirement_id')->constrained('ptd_modules');
            $table->primary(['module_id', 'requirement_id']);
        });

        Schema::create('ptd_rating_modules', function (Blueprint $table) {
            $table->foreignId('rating_id')->constrained('ptd_ratings');
            $table->foreignId('module_id')->constrained('ptd_modules');
            $table->primary(['rating_id', 'module_id']);
        });

        Schema::create('ptd_module_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('ptd_modules');
            $table->string('comment');
            $table->timestamp('starts_at')->useCurrent();
            $table->integer('max_participants')->default(0);
            $table->integer('version')->default(1);
            $table
                ->foreignId('trainer_id')
                ->nullable()
                ->constrained('user_users');
            $table->timestamps();
        });

        Schema::create('ptd_module_session_registrations', function (Blueprint $table) {
            $table->foreignId('session_id')->constrained('ptd_module_sessions');
            $table->foreignId('user_id')->constrained('user_users');
            $table->primary(['session_id', 'user_id']);
            $table->string('comment');
            $table->timestamps();
        });

        Schema::create('ptd_module_completions', function (Blueprint $table) {
            $table->foreignId('module_id')->constrained('ptd_modules');
            $table->foreignId('user_id')->constrained('user_users');
            $table->primary(['module_id', 'user_id']);
            $table->unsignedInteger('completed_version')->default(1);
            $table->timestamp('completed_at')->useCurrent();
            $table->foreignId('completed_trainer_id')->constrained('user_users');
            $table->unsignedInteger('amended_version')->nullable();
            $table->timestamp('amended_at')->nullable();
            $table
                ->foreignId('amended_trainer_id')
                ->nullable()
                ->constrained('user_users');
        });

        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ptd_module_completions');
        Schema::dropIfExists('ptd_module_session_registrations');
        Schema::dropIfExists('ptd_module_sessions');
        Schema::dropIfExists('ptd_rating_modules');
        Schema::dropIfExists('ptd_module_requirements');
        Schema::dropIfExists('ptd_modules');
        Schema::dropIfExists('ptd_ratings');
    }
};
