<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ident');
            $table->double('frequency', 6, 3);
            $table->text('description')->nullable();
            $table->boolean('bookable')->default(true);
            $table->boolean('atis')->default(false);
            $table->unsignedTinyInteger('required_rating')->default(0);
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
        Schema::dropIfExists('navigation_stations');
    }
}
