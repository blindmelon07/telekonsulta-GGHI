<?php

namespace Database\Seeders;

use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['name' => 'Juan dela Cruz', 'email' => 'juan@example.com'],
            ['name' => 'Maria Reyes', 'email' => 'maria@example.com'],
            ['name' => 'Pedro Santos', 'email' => 'pedro@example.com'],
        ];

        foreach ($patients as $patientData) {
            $user = User::firstOrCreate(
                ['email' => $patientData['email']],
                [
                    'name' => $patientData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            $user->assignRole('patient');

            PatientProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_type' => collect(['A+', 'B+', 'O+', 'AB+'])->random(),
                    'allergies' => 'None known',
                    'medical_history' => 'No significant medical history',
                ]
            );
        }
    }
}
