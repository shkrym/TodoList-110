<?php

namespace App\Http\Controllers;

use App\Models\CalTask;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CalendarTaskController extends Controller
{
    public function index(Request $request)
    {
        // Return tasks for a specific date (or all tasks)
        $tasks = CalTask::where('task_date', $request->date)->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        // Create a new calendar task
        $task = CalTask::create([
            'task_date' => $request->task_date,
            'task' => $request->task,
        ]);
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        // Update an existing task
        $task = CalTask::find($id);
        $task->update([
            'task' => $request->task,
            'completed' => $request->completed, // handle checkboxes
        ]);
        return response()->json($task);
    }

    public function destroy($id)
    {
        // Delete a task
        $task = CalTask::find($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
