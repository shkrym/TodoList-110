<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->id())->get();

        return Inertia::render('Todo', [ // Render `Todo.vue`
            'todos' => $todos, // Pass todos data to Vue component
        ]);
    }

    
  
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        Todo::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'is_done' => false,
        ]);

        return redirect()->route('todos');
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'content' => 'nullable|string|max:255', // Validate content (optional if it's being edited)
            'is_done' => 'nullable|boolean', // Validate is_done (optional if it's being toggled)
        ]);

        // Update content and is_done status if provided
        if ($request->has('content')) {
            $todo->content = $validated['content'];
        }
        if ($request->has('is_done')) {
            $todo->is_done = $validated['is_done'];
        }

        $todo->save();

        return redirect()->route('todos');
    }

    public function destroy($id)
    {
        $todo = Todo::where('user_id', auth()->id())->findOrFail($id);
        $todo->delete();

        return redirect()->route('todos');
    }
}