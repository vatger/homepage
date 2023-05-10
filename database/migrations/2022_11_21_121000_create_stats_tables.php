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
        Schema::create('stats_atc', function (Blueprint $table) {
            $table->id();
            $table
                ->unsignedInteger('account_id')
                ->default(0)
                ->index();
            $table
                ->string('callsign', 12)
                ->default('')
                ->index();
            $table->unsignedDouble('frequency', 6, 3)->default(119.998);
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('disconnected_at')->nullable();
            $table->unsignedInteger('minutes_online')->default(0);
        });

        Schema::create('stats_pilots', function (Blueprint $table) {
            $table->id();
            $table
                ->unsignedInteger('account_id')
                ->default(0)
                ->index();
            $table
                ->string('callsign', 12)
                ->default('')
                ->index();

            $table->timestamp('connected_at')->useCurrent();
            $table->timestamp('disconnected_at')->nullable();
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();

            $table
                ->string('departure_airport', 4)
                ->default('')
                ->index();
            $table
                ->string('arrival_airport', 4)
                ->default('')
                ->index();

            $table->string('aircraft', 16)->default('');
            $table->string('cruise_altitude', 16)->default('');
            $table->string('cruise_tas', 16)->default('');
            $table->text('route')->nullable();
            $table->text('remarks')->nullable();

            $table->unsignedInteger('minutes_online')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats_atc');
        Schema::dropIfExists('stats_pilots');
    }
};
