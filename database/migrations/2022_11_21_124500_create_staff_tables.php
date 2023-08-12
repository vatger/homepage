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
        Schema::create('staff_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id');
            $table
                ->foreign('group_id')
                ->references('id')
                ->on('staff_groups');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('staff_leaderships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id');
            $table
                ->foreign('group_id')
                ->references('id')
                ->on('staff_groups');
            $table->string('name')->unique();
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
