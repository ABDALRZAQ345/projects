<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Signied;
Route::bind('task', function ($value) {
    return Task::findOrFail($value);
});

Route::bind('project', function ($value) {
    return Project::findOrFail($value);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/tasks', TaskController::class)->only(['index', 'show', 'store','update','destroy'])->middleware('auth:sanctum');;
Route::apiResource('/projects', ProjectController::class)->middleware('auth:sanctum');
Route::apiResource('projects.members', MemberController::class)->middleware('auth:sanctum');
Route::apiResource('projects.comments', CommentController::class)->middleware('auth:sanctum');
Route::apiResource('tasks.comments', CommentController::class)->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

