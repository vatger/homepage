<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationAidTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_runways', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('aerodrome_id');
            $table->string('ident', 3);
            $table->string('heading', 3);
            $table->unsignedInteger('width');
            $table->unsignedInteger('length');
            $table->unsignedSmallInteger('surface_type');
            $table->unsignedInteger('opposite_id')->nullable();
            $table->timestamps();
        });

        Schema::create('navigation_navaids', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->string('name')->nullable();
            $table->string('heading', 3)->nullable();
            $table->string('ident', 5);
            $table->decimal('frequency', 6, 3);
            $table->unsignedSmallInteger('frequency_band');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('navigation_aerodrome_navaid', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('aerodrome_id');
            $table->unsignedInteger('navaid_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation_runways');
        Schema::dropIfExists('navigation_navaids');
        Schema::dropIfExists('navigation_aerodrome_navaid');
    }
}
