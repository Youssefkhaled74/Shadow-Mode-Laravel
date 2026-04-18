<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'sessions.view',
            'sessions.create',
            'sessions.update',
            'sessions.manage',
            'reports.generate',
            'reports.view',
            'users.manage',
            'platform.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user = Role::findOrCreate('user', 'web');
        $coach = Role::findOrCreate('coach', 'web');
        $admin = Role::findOrCreate('admin', 'web');

        $user->syncPermissions(['sessions.view', 'reports.view']);
        $coach->syncPermissions(['sessions.view', 'sessions.create', 'sessions.update', 'reports.generate', 'reports.view']);
        $admin->syncPermissions($permissions);
    }
}
