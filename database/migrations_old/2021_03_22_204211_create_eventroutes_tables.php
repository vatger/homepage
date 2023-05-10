<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventroutesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->text('description');
            $table->text('link');
            $table->text('img_url');
            $table->boolean('require_order')->default(false);
            $table
                ->char('flight_rules', 1)
                ->nullable()
                ->default(null);
            $table->text('aircrafts');
            $table->timestamp('begins_at')->default(\Carbon\Carbon::now());
            $table->timestamp('ends_at')->default(\Carbon\Carbon::now());
            $table->timestamps();
        });
        Schema::create('event_routelegs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id');
            $table->foreignId('departureaerodrome_id');
            $table->foreignId('arrivalaerodrome_id');
            $table->timestamps();
        });
        Schema::create('event_account_routelegs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('routeleg_id');
            $table
                ->timestamp('completed_at')
                ->nullable()
                ->default(null);
            $table
                ->foreignId('flight_data_id')
                ->nullable()
                ->default(null);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_routes');
        Schema::dropIfExists('event_routelegs');
        Schema::dropIfExists('event_account_routelegs');
    }
}
