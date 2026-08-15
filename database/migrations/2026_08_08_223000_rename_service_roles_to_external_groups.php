<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('team_service_roles', 'team_external_groups');

        Schema::table('team_external_groups', function (Blueprint $table): void {
            $table->renameColumn('service_type', 'external_group_type');
            $table->renameColumn('service_role', 'external_group');
        });
    }

    public function down(): void
    {
        Schema::table('team_external_groups', function (Blueprint $table): void {
            $table->renameColumn('external_group_type', 'service_type');
            $table->renameColumn('external_group', 'service_role');
        });

        Schema::rename('team_external_groups', 'team_service_roles');
    }
};
