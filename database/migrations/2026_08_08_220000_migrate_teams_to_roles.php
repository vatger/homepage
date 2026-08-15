<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Move teams into the Spatie role table and make the role pivot the team
     * membership pivot. Team IDs become the former role IDs so all existing
     * permission and membership rows can be copied without losing references.
     */
    public function up(): void
    {
        // MySQL commits CREATE TABLE statements even when a later migration
        // statement fails. Remove only the target tables so this migration can
        // be safely retried after a partial run; the legacy tables are still
        // the source of truth until the migration completes.
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('group_team_memberships');
        Schema::dropIfExists('group_teams');

        Schema::create('group_teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('guard_name');
            $table->unsignedBigInteger('super_team_id')->nullable();
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
            $table->index('super_team_id');
        });

        Schema::create('group_team_memberships', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->index(['model_id', 'model_type'], 'group_team_memberships_model_id_model_type_index');
            $table->foreign('role_id')
                ->references('id')
                ->on('group_teams')
                ->cascadeOnDelete();
            $table->primary(['role_id', 'model_id', 'model_type'], 'group_team_memberships_primary');
        });

        // The role ID is retained as the new Team ID. Only roles linked to a
        // legacy team are copied; this prevents unrelated roles being exposed
        // through the Team model after the migration.
        DB::table('roles as roles')
            ->join('teams', 'teams.role_id', '=', 'roles.id')
            ->select([
                'roles.id',
                'teams.name',
                'roles.type',
                'roles.guard_name',
                'roles.created_at',
                'roles.updated_at',
            ])
            ->orderBy('roles.id')
            ->chunk(500, function ($roles): void {
                DB::table('group_teams')->insert($roles->map(fn ($role): array => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'type' => $role->type,
                    'guard_name' => $role->guard_name,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                ])->all());
            });

        // The child tables still have foreign keys to teams at this point.
        // Disable checks before translating legacy team IDs to role IDs.
        Schema::disableForeignKeyConstraints();

        $teamIds = DB::table('teams')->pluck('role_id', 'id');
        foreach ($teamIds as $legacyTeamId => $newTeamId) {
            $parentId = DB::table('teams')->where('id', $legacyTeamId)->value('super_team_id');

            DB::table('group_teams')
                ->where('id', $newTeamId)
                ->update(['super_team_id' => $parentId ? $teamIds[$parentId] : null]);

        }

        // Update by row ID rather than by the old team_id. This avoids a
        // second mapping pass matching rows that were already translated.
        foreach (DB::table('team_service_roles')->get() as $serviceRole) {
            DB::table('team_service_roles')
                ->where('id', $serviceRole->id)
                ->update(['team_id' => $teamIds[$serviceRole->team_id]]);
        }

        foreach (DB::table('fir_firs')->get() as $fir) {
            DB::table('fir_firs')
                ->where('id', $fir->id)
                ->update(['team_id' => $teamIds[$fir->team_id]]);
        }

        DB::table('model_has_roles as memberships')
            ->join('teams', 'teams.role_id', '=', 'memberships.role_id')
            ->select('memberships.role_id', 'memberships.model_type', 'memberships.model_id')
            ->orderBy('memberships.role_id')
            ->chunk(500, function ($memberships): void {
                DB::table('group_team_memberships')->insertOrIgnore($memberships->map(fn ($membership): array => [
                    'role_id' => $membership->role_id,
                    'model_type' => $membership->model_type,
                    'model_id' => $membership->model_id,
                ])->all());
            });

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->foreign('role_id')->references('id')->on('group_teams')->cascadeOnDelete();
        });

        Schema::drop('model_has_roles');
        Schema::drop('teams');
        Schema::drop('roles');
        Schema::enableForeignKeyConstraints();

        Schema::table('group_teams', function (Blueprint $table) {
            $table->foreign('super_team_id')->references('id')->on('group_teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id');
            $table->foreignId('super_team_id')->nullable();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('group_teams')->orderBy('id')->each(function ($team): void {
            DB::table('roles')->insert((array) collect($team)->only([
                'id', 'name', 'type', 'guard_name', 'created_at', 'updated_at',
            ])->all());
            DB::table('teams')->insert([
                'id' => $team->id,
                'role_id' => $team->id,
                'super_team_id' => $team->super_team_id,
                'name' => $team->name,
                'created_at' => $team->created_at,
                'updated_at' => $team->updated_at,
            ]);
        });

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });

        Schema::rename('group_team_memberships', 'model_has_roles');
        Schema::drop('group_teams');
        Schema::enableForeignKeyConstraints();
    }
};
