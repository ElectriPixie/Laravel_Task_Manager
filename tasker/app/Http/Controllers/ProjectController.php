<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;

class ProjectController extends Controller
{
    // List all projects
    public function index()
    {
        $projects = Project::orderBy('name')->get();
        return view('projects.index', compact('projects'));
    }

    // Show form to create a new project
    public function create()
    {
        return view('projects.create');
    }

    // Store new project
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Project::create($request->only('name'));

        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    // Show single project (optional)
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    // Show form to edit a project
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Update a project
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project->update($request->only('name'));

        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    // Delete a project
    public function destroy(Project $project)
    {
        $project->delete(); // tasks will cascade if foreign key is set
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
