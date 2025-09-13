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
        Schema::table('user_bans', function (Blueprint $table) {
            $table->foreignId('canceled_by')->nullable()->constrained('user_users');
            $table->timestamp('canceled_at')->nullable();
            $table->text('canceled_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
