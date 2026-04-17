<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialization_id' => Specialization::factory(),
            'bio' => fake()->paragraph(),
            'consultation_fee' => fake()->numberBetween(30000, 100000),
            'teleconsultation_fee' => fake()->numberBetween(20000, 80000),
            'experience_years' => fake()->numberBetween(1, 30),
            'license_number' => fake()->unique()->bothify('PRC-####-???'),
            'clinic_address' => fake()->address(),
            'is_available_online' => true,
            'is_active' => true,
        ];
    }

    public function offlineOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available_online' => false,
        ]);
    }
}
