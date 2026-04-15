<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(SpecializationSeeder::class);

        // Super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@mediconnect.ph'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@mediconnect.ph'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        $this->call(DoctorSeeder::class);
        $this->call(PatientSeeder::class);
    }
}
