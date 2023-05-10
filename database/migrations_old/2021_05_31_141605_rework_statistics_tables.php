<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReworkStatisticsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('statistics_atc');
        Schema::create('statistics_atc', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('rating');
            $table->string('station_ident'); // Might be a station not in our database, so better use the ident
            $table->timestamp('connected_at')->nullable(); // Otherwise this field will be AUTOUPDATED....
            $table->timestamp('disconnected_at')->nullable();
            $table->timestamps();
        });

        Schema::dropIfExists('statistics_pilots');
        Schema::create('statistics_pilots', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id');
            $table->string('callsign');
            $table->unsignedBigInteger('departure_id')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->unsignedBigInteger('alternate_id')->nullable();
            $table->text('route');
            $table->unsignedInteger('revision_id')->default(0);
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('arrived_alternate_at')->nullable();
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('disconnected_at')->nullable();
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
        Schema::dropIfExists('statistics_atc');
        Schema::dropIfExists('statistics_pilots');

        // DONT REVERT THESE CHANGES
    }
}
