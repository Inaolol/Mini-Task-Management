<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasksQuery = Task::query();

        if ($request->filled('q')) {
            $searchText = (string) $request->input('q');
            $tasksQuery->where(function ($query) use ($searchText) {
                $query->where('title', 'like', "%{$searchText}%")
                    ->orWhere('description', 'like', "%{$searchText}%");
            });
        }

        $status = (string) $request->input('status', '');
        if ($status === 'completed') {
            $tasksQuery->where('completed', true);
        } elseif ($status === 'pending') {
            $tasksQuery->where('completed', false);
        }

        $tasks = $tasksQuery->orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $validated['completed'] = (bool)($validated['completed'] ?? false);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Details page not used; redirect to index
        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Inline editing on index; redirect to index
        return redirect()->route('tasks.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $task = Task::findOrFail($id);
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'completed' => (bool)($validated['completed'] ?? false),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }
}