<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\File;
use App\Models\Task;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index(): Collection|array
    {
        $projects = Project::with( "tasks")->get();

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

        $project = Project::with("department", "tasks", "files")->find($id);
        $projectAssignees = [];

        foreach ($project->tasks as $task) {

            $user = User::with('user')->find($task->user_id);
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

        $project_file = $request->file('file');

        // check if img is available;
        if ($project_file) {

            $projectFile = $project_file;
            $filename = "project-" . time();
            $fileExt = $projectFile->getClientOriginalExtension();
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf', 'docx', 'doc'];
            $destinationPath = public_path('/assets/projects/');

            if (!in_array($fileExt, $allowedExtensions)) return response(['status' => 500, 'message' => "Kindly upload a valid file format"], 500);

            $filename = $filename . '.' . $fileExt;
            $projectFile->move($destinationPath, $filename);
        }

        $file_url = "/assets/projects/";

        try {

            $project = Project::create([
                "project_title" => $request->project_title,
                "department_id" => $request->department_id,
                "description" => $request->description,
                "deadline" => date('Y-m-d', strtotime($request->deadline)),
                "priority" => $request->priority

            ]);

            File::create([
                "project_id" => $project->id,
                "file_name" => $request->project_title,
                "file" => $project_file ? $file_url . $filename : null,
            ]);


            return response([
                "data" => $project,
                "message" => "Project created."
            ]);


        } catch (\Exception $e) {
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


        } catch (\Throwable $th) {
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
            $project_file = $request->file('file');

            // check if file is available;
            if ($project_file) {

                $projectFile = $project_file;
                $filename = "project-" . time();
                $fileExt = $projectFile->getClientOriginalExtension();
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf', 'docx', 'doc'];
                $destinationPath = public_path('/assets/projects/');

                if (!in_array($fileExt, $allowedExtensions)) return response(['status' => 500, 'message' => "Kindly upload a valid file format"], 500);

                $filename = $filename . '.' . $fileExt;
                $projectFile->move($destinationPath, $filename);
            }

            $project = Project::find($id);
            $project->update([
                "project_title" => $request->project_title,
                "department_id" => $request->department_id,
                "description" => $request->description,
                "deadline" => date('Y-m-d', strtotime($request->deadline)),
                "priority" => $request->priority
            ]);

            return response([
                "data" => Project::with("department", "tasks")->find($id),
                "message" => "Project has been updated",
            ]);

        } catch (\Throwable $th) {
            $response = [
                "status" => 500,
                "message" => "Something went wrong",
                "error" => $th->getMessage()
            ];

            return response()->json($response, 500);
        }

    }
}
