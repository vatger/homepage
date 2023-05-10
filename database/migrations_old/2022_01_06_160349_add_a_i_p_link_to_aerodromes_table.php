<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAIPLinkToAerodromesTable extends Migration
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
                ->string('aipLink')
                ->after('elevation')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('navigation_aerodromes', function (Blueprint $table) {
            $table->dropColumn('aipLink');
        });
    }
}
