<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker ?? FakerFactory::create();
        $startDate = $faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $faker->dateTimeBetween($startDate, '+3 months');

        return [
            'name' => $faker->company() . 'プロジェクト',
            'description' => $faker->optional()->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $faker->randomElement(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled']),
            'created_by' => User::factory(),
        ];
    }
}

