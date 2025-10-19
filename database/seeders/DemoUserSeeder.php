<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = \App\Models\Role::where('name', 'user')->first();
        
        if (!$userRole) {
            return;
        }

        $demoUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'mobile' => '9876543210',
                'password' => bcrypt('password123'),
                'preferred_language' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mary Johnson',
                'email' => 'mary.johnson@example.com',
                'mobile' => '9876543211',
                'password' => bcrypt('password123'),
                'preferred_language' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'mobile' => '9876543212',
                'password' => bcrypt('password123'),
                'preferred_language' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Brown',
                'email' => 'sarah.brown@example.com',
                'mobile' => '9876543213',
                'password' => bcrypt('password123'),
                'preferred_language' => 'ta',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Michael Davis',
                'email' => 'michael.davis@example.com',
                'mobile' => '9876543214',
                'password' => bcrypt('password123'),
                'preferred_language' => 'hi',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'mobile' => '9876543215',
                'password' => bcrypt('password123'),
                'preferred_language' => 'kn',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert.taylor@example.com',
                'mobile' => '9876543216',
                'password' => bcrypt('password123'),
                'preferred_language' => 'te',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jennifer Martinez',
                'email' => 'jennifer.martinez@example.com',
                'mobile' => '9876543217',
                'password' => bcrypt('password123'),
                'preferred_language' => 'ml',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Christopher Lee',
                'email' => 'christopher.lee@example.com',
                'mobile' => '9876543218',
                'password' => bcrypt('password123'),
                'preferred_language' => 'en',
                'status' => 'inactive',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Amanda White',
                'email' => 'amanda.white@example.com',
                'mobile' => '9876543219',
                'password' => bcrypt('password123'),
                'preferred_language' => 'en',
                'status' => 'suspended',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($demoUsers as $userData) {
            $user = \App\Models\User::create($userData);
            $user->roles()->attach($userRole->id);
        }
    }
}
