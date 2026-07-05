<?php

use App\Models\Tech\SysLog;
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
        SysLog::truncate();
        try {
            Schema::table('syslog', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        } catch (Exception $exception) {
        }
        try {
            Schema::table('syslog', function (Blueprint $table) {
                $table->dropColumn('path');
            });
        } catch (Exception $exception) {
        }
        try {
            Schema::table('syslog', function (Blueprint $table) {
                $table->dropColumn('channel');
            });
        } catch (Exception $exception) {
        }
        Schema::table('syslog', function (Blueprint $table) {
            $table->string('type', 32)->after('user_id');
        });
        Schema::table('syslog', function (Blueprint $table) {
            $table->string('channel', 32)->nullable()->after('type');
        });
        Schema::table('syslog', function (Blueprint $table) {
            $table->string('path')->nullable()->after('channel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SysLog::truncate();
    }
};
