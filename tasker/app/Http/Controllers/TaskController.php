<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('priority')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $maxPriority = Task::max('priority') ?? 0;

        Task::create([
            'name' => $request->name,
            'priority' => $maxPriority + 1,
        ]);

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $task->update(['name' => $request->name]);
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function reorder(Request $request)
    {
        $newOrder = $request->order; // Array of task IDs

        // Fetch all existing task IDs
        $allTaskIds = Task::pluck('id')->toArray();

        // Sort both arrays to check completeness (order-insensitive)
        $sortedNewOrder = $newOrder;
        $sortedAllTaskIds = $allTaskIds;
        sort($sortedNewOrder);
        sort($sortedAllTaskIds);

        // Fail if list is incomplete or contains invalid IDs
        if ($sortedNewOrder !== $sortedAllTaskIds) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided task list is incomplete or contains invalid IDs.'
            ], 422);
        }

        // Update priorities according to the new order
        foreach ($newOrder as $index => $id) {
            Task::where('id', $id)->update(['priority' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }
}
