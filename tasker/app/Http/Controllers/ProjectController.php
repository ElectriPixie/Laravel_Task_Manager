<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $projectId = $request->get('project_id');

        // Default to first project if none selected
        $project = $projectId ? Project::find($projectId) : $projects->first();

        // Get tasks for the selected project
        $tasks = $project ? $project->tasks()->orderBy('priority')->get() : collect();

        return view('projects.index', compact('projects', 'project', 'tasks'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create($request->only('name'));

        return redirect()->route('projects.index', ['project_id' => $project->id])
                         ->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $project->update($request->only('name'));

        return redirect()->route('projects.index', ['project_id' => $project->id])
                         ->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
