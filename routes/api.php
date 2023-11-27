<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);

// protected Routes
Route::group(["middleware" => ["auth:sanctum", "cors"]], function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get("projects", [ProjectController::class, 'index']);
    Route::get('projects/{project}', [ProjectController::class, 'show']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::delete('projects/{project}', [ProjectController::class, 'delete']);
    Route::post('projects/{project}', [ProjectController::class, 'update']);

    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::post('tasks/{task}', [TaskController::class, 'update']);

    Route::get('project-form-options', [ProjectController::class, 'projectFormOptions']);
});
