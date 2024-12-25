<?php

namespace App\Http\Controllers;

use App\Models\Task; 
use Illuminate\Http\Request;
use Inertia\Inertia;


class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Todo');

    }
    public function store(Request $request)
    {
     $request->validate(['task' => 'required|string']);
     $task = auth()->user()->tasks()->create(['task' => $request->task]);
     return response()->json($task);
    }
    public function destroy($id)
    {
     $task = auth()->user()->tasks()->findOrFail($id);
     $task->delete();
     return response()->json(null, 204);
    }
}
