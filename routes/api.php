<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CalendarTaskController;

// These routes are for authenticating the user with Sanctum (if you're using it)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Group routes that require authentication for the Todo API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::patch('/todos/{id}', [TodoController::class, 'update']);
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
    
    // Group routes for Calendar Task API
    Route::get('/calendar', [CalendarTaskController::class, 'index']); // Fetch tasks by date
    Route::post('/calendar', [CalendarTaskController::class, 'store']); // Add a new task
    Route::put('/calendar/{calTask}', [CalendarTaskController::class, 'update']); // Update a task
    Route::delete('/calendar/{calTask}', [CalendarTaskController::class, 'destroy']); // Delete a task
});

