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
        Schema::create('booking_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('booking_bookings', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('booking_group_id')
                ->nullable()
                ->constrained('booking_groups');
            $table->unsignedBigInteger('vatsim_booking_id')->nullable();
            $table->foreignId('controller_id')->constrained('user_users');
            $table->foreignId('station_id')->constrained('nav_stations');
            $table->boolean('voice')->default(true);
            $table->boolean('training')->default(false);
            $table->boolean('exam')->default(false);
            $table->boolean('event')->default(false);
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('ends_at')->useCurrent();
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
        Schema::dropIfExists('booking_groups');
        Schema::dropIfExists('booking_bookings');
    }
};
