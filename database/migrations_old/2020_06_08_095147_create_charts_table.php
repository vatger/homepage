<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_charts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('href');
            $table->integer('airac');
            $table->enum('type', ['aoi', 'afc', 'agc', 'apc', 'sid', 'star', 'iac'])->default('sid');
            $table->enum('fri', ['ifr', 'vfr'])->default('ifr');
            $table->boolean('published')->default(true);
            $table->boolean('public_available')->default(false);
            $table->timestamps();
        });

        Schema::create('navigation_aerodrome_chart', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('aerodrome_id');
            $table->unsignedInteger('chart_id');
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
        Schema::dropIfExists('navigation_aerodrome_chart');
        Schema::dropIfExists('navigation_charts');
    }
}
