<?php

namespace Database\Seeders;

use App\Models\Groups\Team;
use App\Models\Membership\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        // Administration
        'administration.access',
        'administration.bypass.maintenance',

        // Membership
        'membership.users.view',
        'membership.users.details.view',
        'membership.users.details.edit',
        'membership.teams.view',
        'membership.teams.edit',
        'membership.teams.edit.members',
        'membership.teams.edit.members.subteam',

        // Tech
        'tech.access',

        // Nav
        'navigation.aerodromes.view',
        'navigation.aerodromes.edit',
        'navigation.stations.view',

        // ATD
        'atd.solos.edit',
        'atd.solos.manage',

        // Media
        'media.create',
        'media.admin',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove all roles and permissions
        $tableNames = config('permission.table_names');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DELETE FROM ' . $tableNames['model_has_permissions']);
        DB::statement('DELETE FROM ' . $tableNames['model_has_roles']);
        DB::statement('DELETE FROM ' . $tableNames['role_has_permissions']);
        $this->command->getOutput()->writeln('Truncated relations tables.');
        DB::statement('DELETE FROM ' . $tableNames['permissions']);
        $this->command->getOutput()->writeln('Truncated permissions table.');
        DB::statement('DELETE FROM ' . $tableNames['roles']);
        $this->command->getOutput()->writeln('Truncated roles table.');
        Team::truncate();
        $this->command->getOutput()->writeln('Truncated teams table.');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->getOutput()->writeln('Starting seeding of new information...');
        $this->command->getOutput()->progressStart(count($this->permissions));

        // Create permissions
        foreach ($this->permissions as $name) {
            $p = new Permission();
            $p->name = $name;
            $p->save();
            $this->command->getOutput()->progressAdvance();
        }

        // Create administration role with all permissions
        $team = Team::where('name', 'LIKE', 'Tech Leitung')->first();
        if (!$team) {
            $team = new Team();
        }
        $team->name = 'Tech Leitung';
        $team->save();

        $team->role->givePermissionTo(Permission::all());

        // IF WE ARE IN DEVELOPMENT ASSIGN TESTUSER WEB10 TO THE ADMINROLE
        $user = User::first();
        $user?->assignRole($team->role);

        $this->command->getOutput()->progressFinish();
        $this->command->getOutput()->writeln('Finished seeding.');
    }
}
