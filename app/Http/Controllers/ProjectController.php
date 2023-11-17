<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class ProjectController extends Controller
{
    public function index(): Collection|array
    {
        $projects = Project::with("tasks")->get();

        foreach ($projects as $project) {
            $projectAssignees = [];
            $completeTasks = [];
            $tasks = $project->tasks;

            foreach ($tasks as $task) {
                $user = User::find($task->user_id);

                if (!in_array($user, $projectAssignees)) {
                    if ($project->id == $task->project_id) {
                        $projectAssignees[] = $user;
                    }
                }

                if ($task->status == Task::STATUS_COMPLETED) {
                    $completeTasks[] = $task;
                }
            }

            if ($completeTasks) {
                $project['progress'] = (count($completeTasks) / count($tasks)) * 100;
                $project['completed_tasks'] = $completeTasks;

            } else {
                $project['progress'] = 0;
            }

            $project['assignees'] = $projectAssignees;

        }

        return $projects;
    }


    public function show($id): Model|Collection|Builder|array
    {
        $project = Project::with("tasks")->find($id);
        $projectAssignees = [];

        foreach ($project->tasks as $task) {
            $user = User::find($task->user_id);
            $task['assignee'] = $user;

            if (!in_array($task->assignee, $projectAssignees)) {
                $projectAssignees[] = $task->assignee;
            }
        }

        $project['assignees'] = $projectAssignees;

        return $project;
    }


    public function store(Request $request): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $project = Project::create($request->all());

            return response([
                "data" => $project,
                "message" => "Project created."
            ], 201);


        } catch (Exception $e) {
            $response = [
                "status" => 500,
                "message" => "Something went wrong",
                "error" => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }


    public function delete($id): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $project = Project::find($id);
            $project->delete();

            return response([
                "message" => "Project Deleted"
            ], 204);


        } catch (Throwable $th) {
            $response = [
                "status" => 500,
                "message" => "Something went wrong",
                "error" => $th->getMessage()
            ];

            return response()->json($response, 500);
        }
    }


    public function update(Request $request, $id): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $project = Project::find($id);
            $project->update($request->all());

            return response([
                "data" => $project,
                "message" => "Project has been updated."
            ], 201);


        } catch (Exception $e) {
            $response = [
                "status" => 500,
                "message" => "Something went wrong",
                "error" => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }
}
