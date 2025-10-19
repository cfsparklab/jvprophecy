<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access and management',
                'level' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Prophecy management and user oversight',
                'level' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'editor',
                'display_name' => 'Editor',
                'description' => 'Content creation and editing',
                'level' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Read-only access to approved content',
                'level' => 4,
                'status' => 'active',
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
