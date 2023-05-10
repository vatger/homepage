<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAerodromeRegionalgroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_aerodrome_regionalgroup', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('aerodrome_id');
            $table->unsignedInteger('regionalgroup_id');
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
        Schema::dropIfExists('navigation_aerodrome_regionalgroup');
    }
}
