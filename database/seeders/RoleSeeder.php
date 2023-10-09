<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();
        $roles = [
            [
                'name' => 'admin',
                'description' => 'This user has access to all features of the system. Make sure to only give this access to either the maintainer or owner of the system.',
                'permissions' => $permissions
            ]
        ];

        foreach ($roles as $role) {
            $r = Role::firstOrCreate([
                'name' => $role['name'],
                'description' => $role['description'],
            ]);
            $r->syncPermissions($role['permissions']);
        }
    }
}
