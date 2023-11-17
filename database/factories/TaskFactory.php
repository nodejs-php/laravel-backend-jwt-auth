<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "task_title" => fake()->sentence(10),
            "spent_time" => fake()->time(),
            "description" => fake()->text(),
            "deadline" => fake()->dateTimeInInterval('+1 week', '+3 days'),
            "status" => Task::STATUS_IN_PROGRESS,
            "reminder" => false,
            "user_id" => User::all()->random()->id,
        ];
    }
}
