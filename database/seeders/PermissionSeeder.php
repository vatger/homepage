<?php

namespace Database\Seeders;

use App\Models\Membership\Permission;
use App\Models\Membership\Role;
use App\Models\Membership\User\User;
use App\Models\Membership\User\UserData;
use App\Models\Membership\User\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        // Administration
        'administration.access' => 'Grants access to the administration interfaces',
        'administration.tech.access' => 'Grants access to the tech section of the administration interface',
        'administration.bypass.maintenance' => 'Grants permissions to bypass maintenance (artisan down)',

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

        // Membership
        'membership.roles.viewAny' => 'Grants access to role administration',
        'membership.roles.view' => 'View details of a role',
        'membership.roles.create' => 'Create new membership roles',
        'membership.roles.update' => 'Update membership roles',
        'membership.roles.delete' => 'Remove membership roles',
        'membership.users.viewAny' => 'Grants access to user administration',
        'membership.users.view' => 'View details of a user',
        'membership.users.create' => 'Create new user',
        'membership.users.update' => 'Update user',
        'membership.users.delete' => 'Remove user',

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
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Remove all roles and permissions
        $tableNames = config('permission.table_names');
        DB::statement('DELETE FROM ' . $tableNames['model_has_permissions']);
        DB::statement('DELETE FROM ' . $tableNames['model_has_roles']);
        DB::statement('DELETE FROM ' . $tableNames['role_has_permissions']);
        $this->command->getOutput()->writeln('Truncated relations tables.');
        DB::statement('DELETE FROM ' . $tableNames['permissions']);
        $this->command->getOutput()->writeln('Truncated permissions table.');
        DB::statement('DELETE FROM ' . $tableNames['roles']);
        $this->command->getOutput()->writeln('Truncated roles table.');

        $this->command->getOutput()->writeln('Starting seeding of new information...');
        $this->command->getOutput()->progressStart(count($this->permissions));

        // Create permissions
        foreach ($this->permissions as $name => $description) {
            $p = new Permission();
            $p->name = $name;
            $p->description = $description;
            $p->save();
            $this->command->getOutput()->progressAdvance();
        }

        // Create administration role with all permissions
        $adminRole = new Role();
        $adminRole->name = 'Administrator';
        $adminRole->save();

        // Grab all generated permissions and assign then to the role
        $adminRole->givePermissionTo(Permission::all());

        // IF WE ARE IN DEVELOPMENT ASSIGN TESTUSER WEB10 TO THE ADMINROLE
        if (config('app.env') !== 'production') {
            if (
                !User::query()
                    ->where('id', 10000010)
                    ->exists()
            ) {
                User::query()->updateOrCreate([
                    'id' => 10000010,
                    'firstname' => 'Test',
                    'lastname' => '10000010',
                    'email' => '10000010@mail.com',
                ]);
            }
            if (
                !UserData::query()
                    ->where('account_id', 10000010)
                    ->exists()
            ) {
                UserData::query()->updateOrCreate([
                    'account_id' => 10000010,
                    'rating_atc' => 3,
                    'rating_pilot' => 1,
                    'region_code' => 'EMEA',
                    'region_name' => 'Europe, Middle East and Africa',
                    'division_code' => 'EUD',
                    'division_name' => 'Europe (except UK)',
                    'subdivision_code' => null,
                    'subdivision_name' => null,
                ]);
            }
            if (
                !UserSetting::query()
                    ->where('account_id', 10000010)
                    ->exists()
            ) {
                UserSetting::query()->updateOrCreate([
                    'account_id' => 10000010,
                    'language' => 'en',
                ]);
            }

            $adminUser = User::find(10000010);
            if ($adminUser !== null) {
                $adminUser->assignRole($adminRole);
            }
        }

        $this->command->getOutput()->progressFinish();
        $this->command->getOutput()->writeln('Finished seeding.');
    }
}
