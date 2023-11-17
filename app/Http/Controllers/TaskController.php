<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\user;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Collection|array
    {
        $tasks = Task::with("project", "user", 'subtasks')->orderBy('deadline')->get();

        foreach ($tasks as $task) {
            $userID = $task->user->user_id;
            $user = User::where('id', $userID)->get();
            $task->user->name = $user[0]->name;
            $task->user->email = $user[0]->email;
            $subTasks = $task->subtasks;
            $completeSubtasks = [];

            foreach ($subTasks as $subtask) {

                if ($subtask->status == "complete") {

                    $completeSubtasks[] = $subtask;
                }
            }

            if ($completeSubtasks) {
                $task['progress'] = (count($completeSubtasks) / count($subTasks)) * 100;

            } else {
                $task['progress'] = 0;
            }

        }

        return $tasks;

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|array
    {

        try {
            $task = Task::create([
                "task_title" => $request->task_title,
                "project_id" => $request->project_id,
                "user_id" => $request->user_id,
                "description" => $request->description,
                "deadline" => date('Y-m-d', strtotime($request->deadline)),
                "priority" => $request->priority ? 1 : 0
            ]);

            return ([
                $task,
                "message" => "Task added to this project"
            ]);
        } catch (Throwable $th) {
            $response = [
                "status" => 500,
                "message" => "Something went wrong",
                "error" => $th->getMessage()
            ];

            return response()->json($response, 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): Model|Collection|Builder|array|null
    {
        $task = Task::with("project", "user", 'subtasks')->find($id);
        $userID = $task->user->user_id;
        $user = User::where('id', $userID)->get();

        $task->user['name'] = $user[0]->name;
        $task->user['email'] = $user[0]->email;

        return $task;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {

        try {
            $task = Task::find($id);

            $task->update([
                "description" => $request->description,
                "deadline" => date('Y-m-d', strtotime($request->deadline)),
                "status" => $request->status,
                "user_id" => $request->user_id,
                "task_title" => $request->task_title,
            ]);

            return response([
                $task,
                "message" => "Task has been updated",
            ]);
        } catch (Throwable $th) {
            $response = [
                "status" => 500,
                "message" => "Something went wrong",
                "error" => $th->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $task = Task::find($id);
        $task->delete();
        return response()->json(null, 204);
    }
}
