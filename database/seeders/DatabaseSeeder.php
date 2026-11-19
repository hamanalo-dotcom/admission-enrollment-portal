<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\DentalRecords;
use App\Models\PrenatalRecords;
use App\Models\User;
use App\Models\Treatment;
use Illuminate\Database\Seeder;
use App\Models\HealthAssessment;
use App\Models\HealthInformation;
use App\Models\Patients;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        User::updateOrCreate(
            ['email' => 'student@gmail.com'],
            [
                'firstname' => 'John',
                'middlename' => 'Az',
                'lastname' => 'Smith',
                'password' => bcrypt('12345678'),
                'role' => 'student',
                'birthdate' => '1990-01-01',
                'sex' => 'male',
                'contact_no' => '09123456789',
                'emergency_contact' => '09123456789',
                'address' => '123 Main St, City, Country',
            ]
        );


        User::updateOrCreate(
            ['email' => 'student2@gmail.com'],
            [
                'firstname' => 'heas',
                'middlename' => 'A',
                'lastname' => 'Sese',
                'password' => bcrypt('12345678'),
                'role' => 'student',
                'birthdate' => '1990-01-01',
                'sex' => 'male',
                'contact_no' => '09123456789',
                'emergency_contact' => '09123456789',
                'address' => '123 Main St, City, Country',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'firstname' => 'Kaneki',
                'middlename' => 'K',
                'lastname' => 'Ken',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'birthdate' => '1990-01-01',
                'sex' => 'male',
                'contact_no' => '09123456789',
                'emergency_contact' => '09123456789',
                'address' => '123 Main St, City, Country',
            ]
        );


        User::updateOrCreate(
            ['email' => 'registrar@gmail.com'],
            [
                'firstname' => 'Haise',
                'middlename' => 'K',
                'lastname' => 'Sasaki',
                'password' => bcrypt('12345678'),
                'role' => 'registrar',
                'birthdate' => '1990-01-01',
                'sex' => 'male',
                'contact_no' => '09123456789',
                'emergency_contact' => '09123456789',
                'address' => '123 Main St, City, Country',
            ]
        );
User::updateOrCreate(
    ['email' => 'teacher@gmail.com'], // checks if email exists
    [
        'firstname' => 'Teacher',
        'middlename' => 'Sara',
        'lastname' => 'Coll',
        'password' => bcrypt('12345678'), // use a password you’ll remember
        'role' => 'teacher',
        'birthdate' => '1990-01-01',
        'sex' => 'male',
        'contact_no' => '09123456789',
        'emergency_contact' => '09123456789',
        'address' => '123 Main St, City, Country',
    ]
);
 // Load Philippine geo reference data if CSVs are present
//  try {
//     $this->call(PhilippineGeoSeeder::class);
// } catch (\Throwable $e) {
//     $this->command?->warn('Skipping PhilippineGeoSeeder: '.$e->getMessage());
// }


    }
}
