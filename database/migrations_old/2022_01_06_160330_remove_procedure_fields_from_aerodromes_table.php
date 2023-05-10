<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProcedureFieldsFromAerodromesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navigation_aerodromes', function (Blueprint $table) {
            $table->dropColumn('departure_procedures');
            $table->dropColumn('arrival_procedures');
            $table->dropColumn('vfr_procedures');
            $table->dropColumn('other_information');
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
            $table->text('departure_procedures')->nullable();
            $table->text('arrival_procedures')->nullable();
            $table->text('vfr_procedures')->nullable();
            $table->text('other_information')->nullable();
        });
    }
}
