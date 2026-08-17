<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_bookings', function (Blueprint $table) {
            $table->string('event_reference', 191)->nullable()->after('vatger_event');
            $table->index('event_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_bookings', function (Blueprint $table) {
            $table->dropIndex(['event_reference']);
            $table->dropColumn('event_reference');
        });
    }
};
