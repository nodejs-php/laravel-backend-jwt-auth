<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
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
        return [
            "project_title" => fake()->sentence(10),
            "description" => fake()->text(),
            "deadline" => fake()->dateTimeInInterval('+1 week', '+3 days')->format('Y-m-d H:i:s'),
            "status" => Task::STATUS_IN_PROGRESS,
            "priority" => Task::PRIORITY_NORMAL
        ];
    }
}
