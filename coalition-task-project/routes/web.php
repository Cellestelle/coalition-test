<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::resource('tasks', TaskController::class)->except(['index', 'show', 'create']);
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');

Route::post('/projects/bulk', [ProjectController::class, 'bulk'])->name('projects.bulk');

Route::resource('projects', ProjectController::class)->only(['index', 'store', 'update', 'destroy']);
