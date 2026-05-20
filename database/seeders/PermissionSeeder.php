<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'users', 'description' => 'View user list'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'users', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'users', 'description' => 'Edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'users', 'description' => 'Delete users'],

            // Role Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'module' => 'roles', 'description' => 'View role list'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'module' => 'roles', 'description' => 'Create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'module' => 'roles', 'description' => 'Edit existing roles'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'module' => 'roles', 'description' => 'Delete roles'],

            // Permission Management
            ['name' => 'View Permissions', 'slug' => 'permissions.view', 'module' => 'permissions', 'description' => 'View permission list'],
            ['name' => 'Create Permissions', 'slug' => 'permissions.create', 'module' => 'permissions', 'description' => 'Create new permissions'],
            ['name' => 'Edit Permissions', 'slug' => 'permissions.edit', 'module' => 'permissions', 'description' => 'Edit existing permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'permissions.delete', 'module' => 'permissions', 'description' => 'Delete permissions'],

            // Activity Log
            ['name' => 'View Activity Log', 'slug' => 'activity-log.view', 'module' => 'activity-log', 'description' => 'View activity log'],

            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'module' => 'dashboard', 'description' => 'View admin dashboard'],

            // Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'module' => 'settings', 'description' => 'View system settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'module' => 'settings', 'description' => 'Edit system settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::pluck('id');
            $adminRole->permissions()->sync($allPermissions);
        }

        // Assign limited permissions to manager role
        $managerRole = Role::where('slug', 'manager')->first();
        if ($managerRole) {
            $managerPermissions = Permission::whereIn('slug', [
                'users.view',
                'users.create',
                'users.edit',
                'roles.view',
                'permissions.view',
                'activity-log.view',
                'dashboard.view',
            ])->pluck('id');
            $managerRole->permissions()->sync($managerPermissions);
        }

        // Assign basic permissions to editor role
        $editorRole = Role::where('slug', 'editor')->first();
        if ($editorRole) {
            $editorPermissions = Permission::whereIn('slug', [
                'users.view',
                'dashboard.view',
            ])->pluck('id');
            $editorRole->permissions()->sync($editorPermissions);
        }
    }
}
