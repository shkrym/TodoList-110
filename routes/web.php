<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CalendarTaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', function (){
        return Inertia ::render ('Dashboard');
    })->name('dashboard');

    Route::get('/todos', [TodoController::class, 'index'])->name('todos');
    Route::post('/todos',[TodoController::class,'store'])->name('todos.store');
    Route::put('/todos/{todo}/update',[TodoController::class,'update'])->name('todos.update');
    Route::delete('/todos/{todo}',[TodoController::class,'destroy'])->name('todos.destroy');

    Route::get('/calendar', [CalendarTaskController::class, 'index'])->name('calendar');
    Route::post('/calendar', [CalendarTaskController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/{calTask}', [CalendarTaskController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{calTask}', [CalendarTaskController::class, 'destroy'])->name('calendar.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
