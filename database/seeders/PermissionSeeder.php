<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Users
            [
                'description' => 'List Users',
                'name' => 'list:users',
            ],
            [
                'description' => 'View Users',
                'name' => 'view:users',
            ],
            [
                'description' => 'Create Users',
                'name' => 'create:users',
            ],
            [
                'description' => 'Update Users',
                'name' => 'update:users',
            ],
            [
                'description' => 'Activate Users',
                'name' => 'activate:users',
            ],
            [
                'description' => 'Deactivate Users',
                'name' => 'deactivate:users',
            ],
            // Roles
            [
                'description' => 'List Roles',
                'name' => 'list:roles',
            ],
            [
                'description' => 'View Roles',
                'name' => 'view:roles',
            ],
            [
                'description' => 'Create Roles',
                'name' => 'create:roles',
            ],
            [
                'description' => 'Update Roles',
                'name' => 'update:roles',
            ],
            // Permission
            [
                'description' => 'List Permissions',
                'name' => 'list:permissions',
            ],
            [
                'description' => 'View Permissions',
                'name' => 'view:permissions',
            ],
            [
                'description' => 'Create Permissions',
                'name' => 'create:permissions',
            ],
            [
                'description' => 'Update Permissions',
                'name' => 'update:permissions',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}
