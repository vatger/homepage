<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NetworkdataTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Table to keep network accounts
         * Needed for atc and pilot statistics
         */
        Schema::create('networkdata_accounts', function (Blueprint $table) {
            $table->integer('id')->primary(); // No autoincrement here. We do get only valid network id's
            $table->string('firstname', 128);
            $table->string('lastname', 128);
            $table->integer('rating_atc');
            $table->integer('rating_pilot');
            $table->timestamps();
        });

        Schema::create('networkdata_atc', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedInteger('account_id'); // Reference to networkdata_accounts.id
            $table->string('callsign', 12);
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

        // Schema::table('networkdata_atc', function (Blueprint $table) {
        //     $table->foreign('account_id')->references('id')->on('networkdata_accounts');
        // });

        Schema::create('networkdata_pilots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id'); // Reference to networkdata_accounts.id
            $table->string('callsign', 10);
            $table->string('flight_type', 1);
            $table->string('departure_airport');
            $table->string('arrival_airport');
            $table->string('alternative_airport');
            $table->string('aircraft');
            $table->string('cruise_altitude');
            $table->string('cruise_tas');
            $table->text('route');
            $table->text('remarks');
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

        // Schema::table('networkdata_pilots', function (Blueprint $table) {
        //     $table->foreign('account_id')->references('id')->on('networkdata_accounts');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('networkdata_pilots', function (Blueprint $table) {
        //     $table->dropForeign('networkdata_pilots_account_id_foreign');
        // });
        Schema::dropIfExists('networkdata_pilots');
        // Schema::table('networkdata_atc', function (Blueprint $table) {
        //     $table->dropForeign('networkdata_pilots_account_id_foreign');
        // });
        Schema::dropIfExists('networkdata_atc');
        Schema::dropIfExists('networkdata_accounts');
    }
}
