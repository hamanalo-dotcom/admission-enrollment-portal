<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@srhs.edu.ph'],
            [
                'firstname' => 'System',
                'middlename' => '',
                'lastname' => 'Administrator',
                'email' => 'admin@srhs.edu.ph',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
                'birthdate' => '1990-01-01',
                'sex' => 'Male',
                'contact_no' => '09123456789',
                'emergency_contact' => '09123456789',
                'address' => 'San Roque, Marikina City',
            ]
        );

        $this->command->info('Admin user created/updated successfully.');
        $this->command->info('Email: admin@srhs.edu.ph');
        $this->command->info('Password: admin123');
    }
}
