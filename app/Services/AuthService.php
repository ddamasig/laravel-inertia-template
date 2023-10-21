<?php
namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthService {
    public static function getPermissions()
    {
        return Permission::select([
            'id',
            'guard_name',
            'name',
            'description'
        ])->orderBy('name', 'asc')->get();
    }

    public static function getRoles()
    {
        return Role::select([
            'id',
            'guard_name',
            'name',
            'description'
        ])->orderBy('name', 'asc')->get();
    }
}
