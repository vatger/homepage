<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_staff_details', function (Blueprint $table): void {
            $table
                ->string('staff_name_format')
                ->default('fullname')
                ->after('accepted_data_protection_at');
        });
    }

    public function down(): void
    {
        Schema::table('user_staff_details', function (Blueprint $table): void {
            $table->dropColumn('staff_name_format');
        });
    }
};
