<?php

namespace Feature\Project;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
/*
    public function testCanGetProjectList(): void
    {
        $user = User::factory()->create();
        $numItems = 5;
        $projectUser = User::factory()->create();
        Project::factory()->count($numItems)->for($projectUser)
            ->has(Task::factory()->count(10))->create();

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])->get('/api/projects');
        $response->assertStatus(200);
        $response->assertJsonCount($numItems);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'user_id',
                'project_title',
                'deadline',
                'description',
                'status',
                'priority',
                'assignees' => [
                    [
                        'id',
                        'name',
                        'email'
                    ]
                ],
                'tasks' => [
                    [
                        'task_title',
                        'deadline',
                        'description',
                    ]
                ]
            ]
        ]);
    }

    public function testCanAddProject(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->make(['user_id' => $user->id])->toArray();
        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->postJson('/api/projects', $project);
        $response->assertStatus(201);
        $response->assertJsonFragment($project);
    }

    public function testCanUpdateProject(): void
    {
        $user = User::factory()->create();
        $projectUser = User::factory()->create();
        $project = Project::factory()->for($projectUser)->create();

        $projectUpdate = [
            'id' => $project->id,
            'project_title' => "Team One F.C.2",
            'status' => Task::STATUS_COMPLETED,
            'priority' => Task::PRIORITY_URGENT,
        ];

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->postJson(
                sprintf(
                    '/api/projects/%s',
                    $project->id
                ),
                $projectUpdate);
        $response->assertStatus(200);
        $response->assertJsonFragment($projectUpdate);
    }


    public function testCanShowProject(): void
    {
        $user = User::factory()->create();
        $projectUser = User::factory()->create();
        $project = Project::factory()->for($projectUser)
            ->has(Task::factory()->count(10))->create();

        $response = $this->actingAs($user)->withSession(['banned' => false])->get(
            sprintf(
                '/api/projects/%s',
                $project->id
            ));
        $response->assertStatus(200);
       // dd($response->json());
        $response->assertJsonStructure( [
                'id',
                'user_id',
                'project_title',
                'deadline',
                'description',
                'status',
                'priority',
                'assignees' => [
                    [
                        'id',
                        'name',
                        'email'
                    ]
                ],
                'tasks' => [
                    [
                        'task_title',
                        'deadline',
                        'description',
                    ]
                ]
        ]);
    }
*/

}
