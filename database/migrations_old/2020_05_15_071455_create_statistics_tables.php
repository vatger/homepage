<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_atc', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedInteger('account_id')->default(0); // Reference to networkdata_accounts.id
            $table->string('callsign', 12)->default('');
            $table
                ->double('frequency', 6, 3)
                ->unsigned()
                ->nullable();
            $table->smallInteger('qualification_id')->unsigned();
            $table->tinyInteger('facility_type')->unsigned();
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('disconnected_at')->nullable();
            $table
                ->integer('minutes_online')
                ->unsigned()
                ->nullable();
            $table->timestamps();
        });

        Schema::create('statistics_pilots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->default(0); // Reference to networkdata_accounts.id
            $table->string('callsign', 10)->default('');
            $table->string('flight_type', 1)->default('V');
            $table
                ->string('departure_airport')
                ->default('')
                ->index();
            $table
                ->string('arrival_airport')
                ->default('')
                ->index();
            $table->string('alternative_airport')->default('');
            $table->string('aircraft')->default('');
            $table->string('cruise_altitude')->default('');
            $table->string('cruise_tas')->default('');
            $table->text('route')->nullable();
            $table->text('remarks')->nullable();
            $table->double('current_latitude', 12, 8)->nullable();
            $table->double('current_longitude', 12, 8)->nullable();
            $table->mediumInteger('current_altitude')->nullable();
            $table->unsignedSmallInteger('current_groundspeed')->nullable();
            $table->unsignedSmallInteger('current_heading')->nullable();
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('disconnected_at')->nullable();
            $table->unsignedInteger('minutes_online')->nullable();
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
        Schema::dropIfExists('statistics_pilots');
        Schema::dropIfExists('statistics_atc');
    }
}
