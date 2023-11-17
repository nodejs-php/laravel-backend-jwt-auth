<?php

namespace Feature\Project;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Team;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

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
    /*
        public function testCanGetEmptyTeamList(): void
        {
            $numItems = 0;

            $response = $this->get('/api/teams');
            $response->assertStatus(200);
            $response->assertJsonCount($numItems);
            $response->assertJsonStructure([]);
        }

        public function testCanGetInfoOfTeamById(): void
        {

            $team = Team::factory()->create();

            $response = $this->get(
                sprintf(
                    '/api/teams/%s',
                    $team->id
                )
            );
            $response->assertStatus(200);
            $response->assertJsonFragment($team->only('id', 'name', 'slug_name'));

        }

        public function testCanAddTeam(): void
        {
            $team = [
                'id' => 1,
                'name' => "Team One F.C.",
                'slug_name' => 'TO F.C.',
            ];

            $response = $response = $this->postJson('/api/teams', $team);
            $response->assertStatus(201);
            $response->assertJsonFragment($team);
        }

        public function testCanSeeErrorMsgWhenDuplicateTeamNameIsAdded(): void
        {
            $team = [
                'id' => 1,
                'name' => "Team One F.C.",
                'slug_name' => 'TO F.C.',
            ];
            $response = $response = $this->postJson('/api/teams', $team);

            $response = $response = $this->postJson('/api/teams', $team);
            $response->assertStatus(422);
            $response->assertInvalid([
                'name' => 'The name has already been taken.',
            ]);
            $response->assertJsonStructure(["message", "errors" => ["name"]]);
        }

        public function testCanUpdateATeam(): void
        {
            $originalTeam = Team::factory()->create([
                'id' => 1,
                'name' => "Team One F.C.",
                'slug_name' => 'TO F.C.',
            ]);

            $team = [
                'id' => 1,
                'name' => "Team One F.C.2",
                'slug_name' => 'TO F.C.',
            ];

            $response = $response = $this->putJson(
                sprintf(
                    '/api/teams/%s',
                    $originalTeam->id
                ),
                $team);
            $response->assertStatus(201);
            $response->assertJsonFragment($team);
        }

        public function testCanSeeErrorMsgWhenDuplicateTeamNameIsUpdated(): void
        {
            Team::factory()->create([
                'id' => 1,
                'name' => "Team One F.C.",
                'slug_name' => 'TO F.C.',
            ]);
            $originalTeam = Team::factory()->create([
                'id' => 2,
                'name' => "Team One F.C. 2",
                'slug_name' => 'TO F.C. 2',
            ]);

            $team = [
                'id' => 2,
                'name' => "Team One F.C.",
                'slug_name' => 'TO F.C.',
            ];

            $response = $response = $this->putJson(
                sprintf(
                    '/api/teams/%s',
                    $originalTeam->id
                ),
                $team);
            $response->assertStatus(422);
            $response->assertInvalid([
                'name' => 'The name has already been taken.',
            ]);
            $response->assertJsonStructure(["message", "errors" => ["name"]]);
        }

        public function testCanDeleteATeam(): void
        {
            $team = Team::factory()->create([
                'id' => 1,
                'name' => "Team One F.C.",
                'slug_name' => 'TO F.C.',
            ]);

            $response = $response = $this->deleteJson(
                sprintf(
                    '/api/teams/%s',
                    $team->id
                ));
            $response->assertStatus(204);
        }

        public function testCanotDeleteATeamNonExistent(): void
        {
            $team = Team::factory()->create([
                'id' => 1,
                'name' => "Team One F.C.",
                'slug_name' => 'TO F.C.',
            ]);

            $response = $response = $this->deleteJson(
                sprintf(
                    '/api/teams/%s',
                    2
                ));
            $response->assertStatus(404);
        }
    */
}
