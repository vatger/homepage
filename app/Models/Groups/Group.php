<?php

namespace App\Models\Groups;

use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Contracts\Role as SpatieRoleInterface;

class Group extends SpatieRole implements SpatieRoleInterface
{
    protected $table = 'staff_group';

    public static function findByName(string $name, $guardName = null): Group
    {
        $role = self::where('name', 'LIKE', $name)->first();
        if (!$role) {
            throw RoleDoesNotExist::named($name);
        }
        return $role;
    }

    public static function findById(int $id, $guardName = null): Group
    {
        $role = self::where('id', $id)->first();
        if (!$role) {
            throw RoleDoesNotExist::withId($id);
        }
        return $role;
    }

    public static function findOrCreate(string $name, $guardName = null): Group
    {
        $role = self::where('name', 'LIKE', $name)->first();
        if (!$role) {
            $role = new self();
            $role->name = $name;
            $role->save();
        }
        return $role;
    }
}
