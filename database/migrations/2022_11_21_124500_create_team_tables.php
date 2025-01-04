<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id');
            $table
                ->foreign('role_id')
                ->references('id')
                ->on('roles');
            $table->foreignId('super_team_id')->nullable();
            $table
                ->foreign('super_team_id')
                ->references('id')
                ->on('teams');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('team_service_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table
                ->foreign('team_id')
                ->references('id')
                ->on('teams');
            $table->string('service_type');
            $table->string('service_role');
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
        Schema::dropIfExists('team_service_roles');
        Schema::dropIfExists('teams');
    }
};
