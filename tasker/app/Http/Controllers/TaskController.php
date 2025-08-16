<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks()->orderBy('priority')->get();
        return view('tasks.index', compact('tasks', 'project'));
    }

    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxPriority = $project->tasks()->max('priority') ?? 0;

        $project->tasks()->create([
            'name' => $request->name,
            'priority' => $maxPriority + 1,
        ]);

        return redirect()->route('projects.index', ['project_id' => $project->id])
                         ->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $task->update(['name' => $request->name]);

        return redirect()->route('projects.index', ['project_id' => $task->project_id])
                         ->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('projects.index', ['project_id' => $task->project_id])
                         ->with('success', 'Task deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'order' => 'required|array',
        ]);

        $project = Project::findOrFail($request->project_id);

        foreach ($request->order as $priority => $taskId) {
            $project->tasks()->where('id', $taskId)->update(['priority' => $priority + 1]);
        }

        if($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('projects.index', ['project_id' => $project->id])
                        ->with('success', 'Tasks reordered.');
    }
}
