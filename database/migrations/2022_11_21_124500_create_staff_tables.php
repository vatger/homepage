<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // on hold until new satzung is more clear
        Schema::create('staff_atd_mentors', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            // trainings system
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('staff_nav_navigators', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('staff_event_eventlers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('staff_tech_members', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->string('description');
            $table->text('permissions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('table');
    }
};
