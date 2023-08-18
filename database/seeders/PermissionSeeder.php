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

        // Tech
        'tech.access',

        /*
        // Navigation
        'navigation.aerodromes.viewAny' => 'Grants access to the navigation aerodrome administration',
        'navigation.aerodromes.view' => 'View details of an aerodrome',
        'navigation.aerodromes.create' => 'Create new aerodromes.',
        'navigation.aerodromes.update' => 'Update aerodrome data',
        'navigation.aerodromes.delete' => 'Remove an aerodrome',
        'navigation.stations.viewAny' => 'Grants access to the navigation station administration',
        'navigation.stations.view' => 'View details of an station',
        'navigation.stations.create' => 'Create new station.',
        'navigation.stations.update' => 'Update station data',
        'navigation.stations.delete' => 'Remove a station',
        'navigation.charts.viewAny' => 'Grants access to Chart administraton',
        'navigation.charts.view' => 'Can view a chart',
        'navigation.charts.create' => 'Can create new chart',
        'navigation.charts.update' => 'Can edit chart',
        'navigation.charts.delete' => 'Can remove chart',
        */

        /*
        // Regionalgroups
        'regionalgroup.viewAny' => 'Grants access to regionalgroup administration. NOT NEEDED FOR RG STAFF.',
        'regionalgroup.view' => 'Allows to view details of a regionalgroup. NOT NEEDED FOR RG STAFF.',
        'regionalgroup.create' => 'Create new regionalgroups',
        'regionalgroup.update' => 'Update regionalgroups. NOT NEEDED FOR RG STAFF.',
        'regionalgroup.delete' => 'Delete regionalgroups',

        // MediaFiles
        'media.viewAny' => 'Grants access to media file management',
        'media.view' => 'View media file',
        'media.create' => 'Upload media files',
        'media.update' => 'Update / Approve or Disapprove media files',
        'media.delete' => 'Remove media files.',

        // Tech
        'tech.viewAny' => 'Allow TECH members to view log files',
        */
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
