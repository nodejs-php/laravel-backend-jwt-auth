<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\user;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
    public function index(): LengthAwarePaginator
    {
        return Task::with("project", "user",)->orderBy('deadline')->paginate();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $task = Task::create($request->all());

            return response([
                $task,
                "message" => "Task added to this project"
            ], 201);
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
        return Task::with("project", "user")->find($id);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $task = Task::find($id);
            $task->update($request->all());

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
