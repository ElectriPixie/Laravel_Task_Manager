<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

// Projects
Route::resource('projects', ProjectController::class);

// Nested Tasks (shallow for edit/update/delete)
Route::resource('projects.tasks', TaskController::class)->shallow();

// Task reordering (nested under project, keep project context)
Route::post('/projects/{project}/tasks/reorder', [TaskController::class, 'reorder'])
    ->name('projects.tasks.reorder');

// Optional: redirect root to projects index
Route::get('/', function () {
    return redirect()->route('projects.index');
});
