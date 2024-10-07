<?php

namespace Database\Seeders;

use App\Models\Groups\Team;
use App\Models\Membership\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        // Administration
        'administration.access',
        'administration.bypass.maintenance',

        // Membership
        'membership.users.view',
        'membership.users.details.view',
        'membership.users.details.view.email',
        'membership.users.details.edit',
        'membership.teams.view',
        'membership.teams.edit',
        'membership.teams.edit.members',
        'membership.teams.edit.members.subteam',

        // Survey Keys
        'survey',

        // Tech
        'tech.access',

        // Nav
        'navigation.aerodromes.view',
        'navigation.aerodromes.edit',
        'navigation.stations.view',

        // Mail
        'mail.use',
        'mail.manage',
    ];

    /**
     * Run the database seeds.
     * @throws Throwable
     */
    public function run(): void
    {
        // Remove all roles and permissions
        //$tableNames = config('permission.table_names');
        //DB::statement('SET FOREIGN_KEY_CHECKS=0');
        //DB::statement('DELETE FROM ' . $tableNames['model_has_permissions']);
        //DB::statement('DELETE FROM ' . $tableNames['model_has_roles']);
        //DB::statement('DELETE FROM ' . $tableNames['role_has_permissions']);
        //$this->command->getOutput()->writeln('Truncated relations tables.');
        //DB::statement('DELETE FROM ' . $tableNames['permissions']);
        //$this->command->getOutput()->writeln('Truncated permissions table.');
        //DB::statement('DELETE FROM ' . $tableNames['roles']);
        //$this->command->getOutput()->writeln('Truncated roles table.');
        //Team::truncate();
        //$this->command->getOutput()->writeln('Truncated teams table.');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->getOutput()->writeln('Starting seeding of permissions...');

        // Create permissions
        foreach ($this->permissions as $name) {
            if (Permission::where('name', 'LIKE', $name)->exists()) {
                continue;
            }
            $permission = Permission::make(['name' => $name]);
            $permission->saveOrFail();
            $this->command->getOutput()->writeln("Added $permission->name");
        }

        // Delete unused permissions
        foreach (Permission::all() as $permission) {
            $delete = true;
            foreach ($this->permissions as $name) {
                if ($permission->name == $name) {
                    $delete = false;
                    break;
                }
            }
            if ($delete) {
                foreach (Role::all() as $role) {
                    $role->revokePermissionTo($permission);
                }
                $this->command->getOutput()->writeln("Deleted $permission->name");
                $permission->delete();
            }
        }

        // Create administration role with all permissions
        $team = Team::where('name', 'LIKE', 'Tech Leitung')->first();
        if (!$team) {
            $team = new Team();
        }
        $team->name = 'Tech Leitung';
        $team->save();

        $team->role->givePermissionTo(Permission::all());

        // IF WE ARE IN DEVELOPMENT ASSIGN TESTUSER WEB10 TO THE ADMIN-ROLE
        if (config('app.env') != 'production') {
            $user = User::first();
            $user?->assignRole($team->role);
        }

        $this->command->getOutput()->writeln('Finished seeding.');
    }
}
