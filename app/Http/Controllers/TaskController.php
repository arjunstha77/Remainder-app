<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $filter = request('filter', 'all');
        $query = auth()->user()->tasks();

        $query = match ($filter) {
            'completed' => $query->where('completed', true),
            'active' => $query->where('completed', false),
            'work', 'personal', 'groceries', 'health', 'other' => $query->where('category', $filter),
            default => $query
        };

        $tasks = $query->orderBy('created_at', 'desc')->get();

        return view('welcome', compact('tasks', 'filter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:work,personal,groceries,health,other',
            'priority' => 'required|string|in:low,medium,high',
            'due_date' => 'nullable|date',
            'reminder' => 'nullable|date',
        ]);

        $task = auth()->user()->tasks()->create($validated);

        return redirect()->route('tasks.index');
    }

    public function update(Request $request, Task $task)
    {
        if ($request->has('completed')) {
            $task->update(['completed' => $request->completed]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }
}