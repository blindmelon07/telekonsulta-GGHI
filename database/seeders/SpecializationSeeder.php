<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            ['name' => 'General Practice', 'icon' => 'heart', 'description' => 'Primary care and general health services'],
            ['name' => 'Cardiology', 'icon' => 'heart-pulse', 'description' => 'Heart and cardiovascular system specialists'],
            ['name' => 'Dermatology', 'icon' => 'sparkles', 'description' => 'Skin, hair, and nail conditions'],
            ['name' => 'Pediatrics', 'icon' => 'baby', 'description' => 'Medical care for infants, children and adolescents'],
            ['name' => 'Neurology', 'icon' => 'brain', 'description' => 'Brain and nervous system disorders'],
            ['name' => 'Orthopedics', 'icon' => 'bone', 'description' => 'Musculoskeletal system specialists'],
            ['name' => 'Psychiatry', 'icon' => 'mind', 'description' => 'Mental health and behavioral disorders'],
            ['name' => 'Ophthalmology', 'icon' => 'eye', 'description' => 'Eye and vision care specialists'],
            ['name' => 'ENT', 'icon' => 'ear', 'description' => 'Ear, nose, and throat specialists'],
            ['name' => 'OB-GYN', 'icon' => 'user', 'description' => 'Women\'s reproductive health and pregnancy'],
            ['name' => 'Endocrinology', 'icon' => 'beaker', 'description' => 'Hormonal and metabolic conditions'],
            ['name' => 'Pulmonology', 'icon' => 'lungs', 'description' => 'Respiratory and lung disorders'],
        ];

        foreach ($specializations as $spec) {
            Specialization::firstOrCreate(['name' => $spec['name']], $spec);
        }
    }
}
