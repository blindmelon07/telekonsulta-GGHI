<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Dr. Maria Santos',
                'email' => 'dr.santos@mediconnect.ph',
                'specialization' => 'Cardiology',
                'bio' => 'Board-certified cardiologist with 15 years of experience in interventional cardiology.',
                'consultation_fee' => 50000,
                'teleconsultation_fee' => 35000,
                'experience_years' => 15,
                'license_number' => 'PRC-12345',
            ],
            [
                'name' => 'Dr. Jose Reyes',
                'email' => 'dr.reyes@mediconnect.ph',
                'specialization' => 'Pediatrics',
                'bio' => 'Dedicated pediatrician specializing in neonatal care and childhood development.',
                'consultation_fee' => 40000,
                'teleconsultation_fee' => 30000,
                'experience_years' => 10,
                'license_number' => 'PRC-23456',
            ],
            [
                'name' => 'Dr. Ana Cruz',
                'email' => 'dr.cruz@mediconnect.ph',
                'specialization' => 'Dermatology',
                'bio' => 'Expert dermatologist specializing in cosmetic and medical dermatology.',
                'consultation_fee' => 45000,
                'teleconsultation_fee' => 32000,
                'experience_years' => 8,
                'license_number' => 'PRC-34567',
            ],
            [
                'name' => 'Dr. Roberto Garcia',
                'email' => 'dr.garcia@mediconnect.ph',
                'specialization' => 'General Practice',
                'bio' => 'Family medicine physician providing comprehensive primary care services.',
                'consultation_fee' => 30000,
                'teleconsultation_fee' => 20000,
                'experience_years' => 12,
                'license_number' => 'PRC-45678',
            ],
        ];

        foreach ($doctors as $doctorData) {
            $user = User::firstOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            $user->assignRole('doctor');

            $specialization = Specialization::where('name', $doctorData['specialization'])->first();

            $doctor = Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization_id' => $specialization->id,
                    'bio' => $doctorData['bio'],
                    'consultation_fee' => $doctorData['consultation_fee'],
                    'teleconsultation_fee' => $doctorData['teleconsultation_fee'],
                    'experience_years' => $doctorData['experience_years'],
                    'license_number' => $doctorData['license_number'],
                    'is_available_online' => true,
                    'is_active' => true,
                ]
            );

            // Create default schedules (Mon-Fri, 9am-5pm, 30-min slots)
            foreach ([1, 2, 3, 4, 5] as $day) {
                Schedule::firstOrCreate(
                    ['doctor_id' => $doctor->id, 'day_of_week' => $day],
                    [
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                        'slot_duration_minutes' => 30,
                        'appointment_type' => 'both',
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
