<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtcSessionBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_atc', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vatbook_id')->nullable();
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('controller_id');
            $table->boolean('voice')->default(true);
            $table->boolean('training')->default(false);
            $table->boolean('event')->default(false);
            $table->unsignedInteger('event_id')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
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
        Schema::dropIfExists('bookings_atc');
    }
}
