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
        Schema::table('bookings_atc', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('vatsimbooking_id')
                ->nullable()
                ->default(null)
                ->after('id');
            $table
                ->boolean('exam')
                ->default(false)
                ->after('training');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings_atc', function (Blueprint $table) {
            $table->dropColumn('vatsimbooking_id');
            $table->dropColumn('exam');
        });
    }
};
