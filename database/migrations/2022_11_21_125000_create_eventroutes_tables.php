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
        Schema::create('event_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img_url')->nullable();
            $table->string('link')->nullable();
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('ends_at')->useCurrent();
            $table->text('description')->nullable();
            $table->boolean('visible')->default(false);
            $table->string('aircrafts')->nullable();
            $table->enum('flight_rules', ['ifr+vfr', 'ifr', 'vfr'])->default('ifr+vfr');
            $table->boolean('require_order')->default(false);
            $table->timestamps();
        });

        Schema::create('event_legs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('event_routes');
            $table->foreignId('departure_aerodrome_id')->constrained('nav_aerodromes');
            $table->foreignId('arrival_aerodrome_id')->constrained('nav_aerodromes');
            $table->timestamps();
        });

        Schema::create('event_user_legs', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('leg_id')->constrained('event_legs');
            $table->primary(['user_id', 'leg_id']);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('flight_data_id')->constrained('stats_pilots');
            $table->timestamps();
        });

        Schema::create('event_user_leg_approvals', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('leg_id')->constrained('event_legs');
            $table->primary(['user_id', 'leg_id']);
            $table->timestamp('completed_at')->useCurrent();
            $table->text('reason');
            $table->string('link');
            $table->timestamps();
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
        Schema::dropIfExists('event_routes');
        Schema::dropIfExists('event_legs');
        Schema::dropIfExists('event_user_legs');
        Schema::dropIfExists('event_user_leg_approvals');
    }
};
