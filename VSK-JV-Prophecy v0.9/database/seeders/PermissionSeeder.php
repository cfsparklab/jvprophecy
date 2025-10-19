<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'view_users', 'display_name' => 'View Users', 'description' => 'View user list and details', 'module' => 'user'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'description' => 'Create new users', 'module' => 'user'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'description' => 'Edit user information', 'module' => 'user'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'description' => 'Delete users', 'module' => 'user'],
            ['name' => 'manage_user_roles', 'display_name' => 'Manage User Roles', 'description' => 'Assign/remove user roles', 'module' => 'user'],
            
            // Prophecy Management
            ['name' => 'view_prophecies', 'display_name' => 'View Prophecies', 'description' => 'View prophecy list and details', 'module' => 'prophecy'],
            ['name' => 'create_prophecies', 'display_name' => 'Create Prophecies', 'description' => 'Create new prophecies', 'module' => 'prophecy'],
            ['name' => 'edit_prophecies', 'display_name' => 'Edit Prophecies', 'description' => 'Edit prophecy content', 'module' => 'prophecy'],
            ['name' => 'delete_prophecies', 'display_name' => 'Delete Prophecies', 'description' => 'Delete prophecies', 'module' => 'prophecy'],
            ['name' => 'publish_prophecies', 'display_name' => 'Publish Prophecies', 'description' => 'Publish/unpublish prophecies', 'module' => 'prophecy'],
            ['name' => 'manage_prophecy_translations', 'display_name' => 'Manage Translations', 'description' => 'Manage multi-language translations', 'module' => 'prophecy'],
            
            // Category Management
            ['name' => 'view_categories', 'display_name' => 'View Categories', 'description' => 'View category list and hierarchy', 'module' => 'category'],
            ['name' => 'create_categories', 'display_name' => 'Create Categories', 'description' => 'Create new categories', 'module' => 'category'],
            ['name' => 'edit_categories', 'display_name' => 'Edit Categories', 'description' => 'Edit category information', 'module' => 'category'],
            ['name' => 'delete_categories', 'display_name' => 'Delete Categories', 'description' => 'Delete categories', 'module' => 'category'],
            
            // Role & Permission Management
            ['name' => 'view_roles', 'display_name' => 'View Roles', 'description' => 'View roles and permissions', 'module' => 'role'],
            ['name' => 'create_roles', 'display_name' => 'Create Roles', 'description' => 'Create new roles', 'module' => 'role'],
            ['name' => 'edit_roles', 'display_name' => 'Edit Roles', 'description' => 'Edit role information', 'module' => 'role'],
            ['name' => 'delete_roles', 'display_name' => 'Delete Roles', 'description' => 'Delete roles', 'module' => 'role'],
            ['name' => 'manage_permissions', 'display_name' => 'Manage Permissions', 'description' => 'Assign/remove permissions', 'module' => 'role'],
            
            // Security & Audit
            ['name' => 'view_security_logs', 'display_name' => 'View Security Logs', 'description' => 'View security and audit logs', 'module' => 'security'],
            ['name' => 'manage_security_settings', 'display_name' => 'Manage Security', 'description' => 'Configure security settings', 'module' => 'security'],
            
            // System Administration
            ['name' => 'system_settings', 'display_name' => 'System Settings', 'description' => 'Configure system settings', 'module' => 'system'],
            ['name' => 'backup_restore', 'display_name' => 'Backup & Restore', 'description' => 'Manage system backups', 'module' => 'system'],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::firstOrCreate(
                ['name' => $permission['name']],
                array_merge($permission, ['status' => 'active'])
            );
        }
    }
}
