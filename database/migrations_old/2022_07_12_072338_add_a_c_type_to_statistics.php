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
        Schema::table('statistics_pilots', function (Blueprint $table) {
            $table
                ->string('aircraft_short')
                ->default('')
                ->after('alternate_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statistics_pilots', function (Blueprint $table) {
            $table->dropColumn('aircraft_short');
        });
    }
};
