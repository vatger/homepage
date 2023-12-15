<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_staff_details', function (Blueprint $table) {
            $table
                ->foreignId('user_id')
                ->primary()
                ->constrained('user_users');
            $table->timestamp('joined_staff_at');
            $table->timestamp('leaving_staff_at')->nullable();
            $table->timestamp('accepted_data_protection_at')->nullable();
            $table->string('staff_email')->nullable();
            $table->boolean('staff_email_created')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_staff_details');
    }
};
