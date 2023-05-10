<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAerodromesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_aerodromes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('icao', 4)->unique();
            $table->string('iata', 3);
            $table->string('country');
            $table->string('city');
            $table->string('state');
            $table->boolean('military')->default(false);
            $table->boolean('civilian')->default(true);
            $table->boolean('major')->default(false);
            $table->boolean('active')->default(true);
            $table->double('latitude', 12, 8)->nullable();
            $table->double('longitude', 12, 8)->nullable();
            $table->float('elevation')->default(0.0);
            $table->text('departure_procedures')->nullable();
            $table->text('arrival_procedures')->nullable();
            $table->text('vfr_procedures')->nullable();
            $table->text('other_information')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('navigation_aerodromes');
    }
}
