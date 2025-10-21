<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@yahoo.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'status' => true,
            ]
        );

        // Create or update regular test user
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'status' => true,
            ]
        );

        // Create or update regular test user
        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('demo'),
                'role' => User::ROLE_USER,
                'status' => true,
            ]
        );

        // Create an inactive user for testing
        User::updateOrCreate(
            ['email' => 'inactive@example.com'],
            [
                'name' => 'Inactive User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'status' => false,
            ]
        );

        $this->command->info('Users seeded successfully!');
        $this->command->info('');
        $this->command->info('Admin Account:');
        $this->command->info('  Email: admin@yahoo.com');
        $this->command->info('  Password: password');
        $this->command->info('  Role: Admin');
        $this->command->info('');
        $this->command->info('Regular User Account:');
        $this->command->info('  Email: user@example.com');
        $this->command->info('  Password: password');
        $this->command->info('  Role: User');
        $this->command->info('');
        $this->command->info('Regular User Account:');
        $this->command->info('  Email: demo@example.com');
        $this->command->info('  Password: demo');
        $this->command->info('  Role: User');
        $this->command->info('');
        $this->command->info('Inactive User Account:');
        $this->command->info('  Email: inactive@example.com');
        $this->command->info('  Password: password');
        $this->command->info('  Role: User');
        $this->command->info('  Status: Inactive');
    }
}
