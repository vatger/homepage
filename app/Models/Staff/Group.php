<?php

namespace App\Models\Staff;

use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

class Group extends Role
{
    protected $table = 'staff_group';

    public static function findByName(string $name, null|string $guardName = null): Group
    {
        $role = self::where('name', 'LIKE', $name)->first();
        if (!$role) {
            throw RoleDoesNotExist::named($name);
        }
        return $role;
    }

    public static function findById(int $id, null|string $guardName = null): Group
    {
        $role = self::where('id', $id)->first();
        if (!$role) {
            throw RoleDoesNotExist::withId($id);
        }
        return $role;
    }

    public static function findOrCreate(string $name, null|string $guardName = null): Group
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
