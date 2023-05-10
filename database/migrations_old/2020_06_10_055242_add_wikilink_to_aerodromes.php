<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWikilinkToAerodromes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navigation_aerodromes', function (Blueprint $table) {
            $table
                ->string('wiki_link')
                ->nullable()
                ->after('other_information');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aerodromes', function (Blueprint $table) {
            $table->dropColumn('wiki_link');
        });
    }
}
