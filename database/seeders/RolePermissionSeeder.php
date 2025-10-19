<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define role permissions
        $rolePermissions = [
            'super_admin' => [
                // All permissions for super admin
                'view_users', 'create_users', 'edit_users', 'delete_users', 'manage_user_roles',
                'view_prophecies', 'create_prophecies', 'edit_prophecies', 'delete_prophecies', 'publish_prophecies', 'manage_prophecy_translations',
                'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles', 'manage_permissions',
                'view_security_logs', 'manage_security_settings',
                'system_settings', 'backup_restore'
            ],
            'admin' => [
                // Admin permissions (no role/permission management)
                'view_users', 'create_users', 'edit_users', 'manage_user_roles',
                'view_prophecies', 'create_prophecies', 'edit_prophecies', 'delete_prophecies', 'publish_prophecies', 'manage_prophecy_translations',
                'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
                'view_security_logs', 'manage_security_settings'
            ],
            'editor' => [
                // Editor permissions (content focused)
                'view_prophecies', 'create_prophecies', 'edit_prophecies', 'manage_prophecy_translations',
                'view_categories'
            ],
            'user' => [
                // Basic user permissions (read-only)
                'view_prophecies'
            ]
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = \App\Models\Role::where('name', $roleName)->first();
            
            if ($role) {
                foreach ($permissions as $permissionName) {
                    $permission = \App\Models\Permission::where('name', $permissionName)->first();
                    
                    if ($permission && !$role->permissions()->where('permission_id', $permission->id)->exists()) {
                        $role->permissions()->attach($permission->id);
                    }
                }
            }
        }
    }
}
