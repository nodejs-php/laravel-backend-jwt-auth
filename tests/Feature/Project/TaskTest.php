<?php

namespace Feature\Project;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetTaskList(): void
    {
        $user = User::factory()->create();
        $numItems = 5;
        $projectUser = User::factory()->create();
        Project::factory()->count($numItems)->for($projectUser)
            ->has(Task::factory()->count(3)->for($projectUser))->create();

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])->get('/api/tasks');
        $response->assertStatus(200);
        $response->assertJsonCount($numItems * 3);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'user_id',]
        ]);
    }

    public function testCanAddTask(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $project = Task::factory()->make(['user_id' => $user->id, 'project_id' => $project->id])->toArray();
        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->postJson('/api/tasks', $project);
        $response->assertStatus(201);
        $response->assertJsonFragment($project);
    }

    public function testCanUpdateProject(): void
    {
        $user = User::factory()->create();
        $projectUser = User::factory()->create();
        $project = Project::factory()->for($projectUser)->create();
        $task = Task::factory()->for($projectUser)->for($project)->create();

        $taskUpdate = [
            'id' => $task->id,
            'task_title' => "Team One F.C.2",
            'status' => Task::STATUS_COMPLETED,
        ];

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->postJson(
                sprintf(
                    '/api/tasks/%s',
                    $task->id
                ),
                $taskUpdate);
        $response->assertStatus(200);
        $response->assertJsonFragment($taskUpdate);
    }

    public function testCanShowTask(): void
    {
        $user = User::factory()->create();
        $projectUser = User::factory()->create();
        $project = Project::factory()->for($projectUser)->create();
        $task = Task::factory()->for($projectUser)->for($project)->create();

        $response = $this->actingAs($user)->withSession(['banned' => false])->get(
            sprintf(
                '/api/tasks/%s',
                $task->id
            ));
        $response->assertStatus(200);
      //   dd($response->json());
        $response->assertJsonStructure([
            'id',
            'task_title',
            'deadline',
            'description',
            'status',
        ]);
    }

}
