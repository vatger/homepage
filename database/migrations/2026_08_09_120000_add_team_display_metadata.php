<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_teams', function (Blueprint $table): void {
            $table->string('title_de')->nullable()->after('name');
            $table->string('title_en')->nullable()->after('title_de');
            $table->boolean('show')->default(false)->after('title_en');
            $table->unsignedInteger('order')->default(0)->after('show');
            $table->string('email')->nullable()->after('order');
        });

        Schema::table('group_team_memberships', function (Blueprint $table): void {
            $table->string('title_de')->nullable()->after('model_id');
            $table->string('title_en')->nullable()->after('title_de');
            $table->boolean('show')->default(true)->after('title_en');
            $table->unsignedInteger('order')->default(0)->after('show');
        });
    }

    public function down(): void
    {
        Schema::table('group_team_memberships', function (Blueprint $table): void {
            $table->dropColumn(['order', 'title_de', 'title_en', 'show']);
        });

        Schema::table('group_teams', function (Blueprint $table): void {
            $table->dropColumn(['order', 'show', 'title_de', 'title_en', 'email']);
        });
    }
};
