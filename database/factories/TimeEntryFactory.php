<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker ?? FakerFactory::create();

        return [
            'project_id' => Project::factory(),
            'task_id' => null, // タスクはシーダーで個別に設定
            'user_id' => User::factory(),
            'date' => $faker->dateTimeBetween('-1 month', 'now'),
            'hours' => $faker->randomFloat(2, 0.5, 8),
            'description' => $faker->optional()->sentence(),
        ];
    }
}

