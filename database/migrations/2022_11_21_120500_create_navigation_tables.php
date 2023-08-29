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
        Schema::create('nav_firs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('name');
            $table->boolean('uir')->default(false);
            $table->timestamps();
        });

        Schema::create('nav_aerodromes', function (Blueprint $table) {
            $table->id();
            $table->string('icao', 4)->unique();
            $table
                ->foreignId('fir_id')
                ->nullable()
                ->constrained('nav_firs');
            $table->string('name');
            $table->text('description');
            $table->string('iata', 3);
            $table->string('country_short', 4);
            $table->string('country_long');
            $table->string('city');
            $table->string('state');
            $table->boolean('military')->default(false);
            $table->boolean('civilian')->default(true);
            $table->boolean('major')->default(false);
            $table->boolean('restricted_minor')->default(false);
            $table->boolean('active')->default(true);
            $table->double('latitude', 12, 8)->nullable();
            $table->double('longitude', 12, 8)->nullable();
            $table->float('elevation')->default(0.0);
            $table->unsignedBigInteger('selection')->default(0);
            $table->timestamps();
        });

        Schema::create('nav_stations', function (Blueprint $table) {
            $table->id();
            $table->string('ident', 32)->unique();
            $table->string('name');
            $table->double('frequency', 6, 3);
            $table->text('description')->nullable();
            $table->boolean('bookable')->default(true);
            $table->unsignedBigInteger('selection')->default(0);
            $table->timestamps();
        });

        Schema::create('nav_aerodrome_stations', function (Blueprint $table) {
            $table->foreignId('aerodrome_id')->constrained('nav_aerodromes');
            $table->foreignId('station_id')->constrained('nav_stations');
            $table->primary(['aerodrome_id', 'station_id']);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        /*
        Schema::create('nav_runways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aerodrome_id')->constrained('nav_aerodromes');
            $table->string('ident', 3);
            $table->string('heading', 3);
            $table->unsignedInteger('width');
            $table->unsignedInteger('length');
            $table->unsignedSmallInteger('surface_type');
            $table
                ->foreignId('opposite_id')
                ->nullable()
                ->constrained('nav_runways');
            $table->timestamps();
        });

        Schema::create('nav_navaids', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->enum('type_', ['ils', 'vor', 'ndb'])->default('ils');
            $table->string('name')->nullable();
            $table->string('heading', 3)->nullable();
            $table->string('ident', 5);
            $table->decimal('frequency', 6, 3);
            $table->unsignedSmallInteger('frequency_band');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('nav_aerodrome_navaids', function (Blueprint $table) {
            $table->foreignId('aerodrome_id')->constrained('nav_aerodromes');
            $table->foreignId('navaid_id')->constrained('nav_navaids');
            $table->primary(['aerodrome_id', 'navaid_id']);
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nav_aerodrome_navaids');
        Schema::dropIfExists('nav_navaids');
        Schema::dropIfExists('nav_runways');
        Schema::dropIfExists('nav_aerodrome_stations');
        Schema::dropIfExists('nav_stations');
        Schema::dropIfExists('nav_aerodromes');
        Schema::dropIfExists('nav_firs');
    }
};
