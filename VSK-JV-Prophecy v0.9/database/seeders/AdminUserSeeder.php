<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        $superAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@jvprophecy.com'],
            [
                'name' => 'Super Administrator',
                'mobile' => '+919999999999',
                'password' => bcrypt('SuperAdmin@123'),
                'status' => 'active',
                'preferred_language' => 'en',
                'email_verified_at' => now(),
            ]
        );

        // Create Admin User
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@jvprophecy.com'],
            [
                'name' => 'Administrator',
                'mobile' => '+919999999998',
                'password' => bcrypt('Admin@123'),
                'status' => 'active',
                'preferred_language' => 'en',
                'email_verified_at' => now(),
            ]
        );

        // Create Editor User
        $editor = \App\Models\User::firstOrCreate(
            ['email' => 'editor@jvprophecy.com'],
            [
                'name' => 'Content Editor',
                'mobile' => '+919999999997',
                'password' => bcrypt('Editor@123'),
                'status' => 'active',
                'preferred_language' => 'en',
                'email_verified_at' => now(),
            ]
        );

        // Assign roles
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $editorRole = \App\Models\Role::where('name', 'editor')->first();

        if ($superAdminRole && !$superAdmin->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $superAdmin->roles()->attach($superAdminRole->id, [
                'assigned_at' => now(),
                'assigned_by' => $superAdmin->id
            ]);
        }

        if ($adminRole && !$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id, [
                'assigned_at' => now(),
                'assigned_by' => $superAdmin->id
            ]);
        }

        if ($editorRole && !$editor->roles()->where('role_id', $editorRole->id)->exists()) {
            $editor->roles()->attach($editorRole->id, [
                'assigned_at' => now(),
                'assigned_by' => $superAdmin->id
            ]);
        }
    }
}
