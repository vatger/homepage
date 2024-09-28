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
        \App\Models\Tech\SysLog::truncate();
        Schema::table('syslog', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('syslog', function (Blueprint $table) {
            $table->string('type', 32)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Tech\SysLog::truncate();
    }
};
