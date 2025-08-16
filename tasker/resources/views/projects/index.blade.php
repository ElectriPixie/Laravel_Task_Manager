@extends('layouts.app')

@section('content')
<div class="projects-container">

    @if($projects->isEmpty())
        <p>No projects found.</p>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Create a Project</a>
    @else
        @php
            $currentProject = $project ?? $projects->first();
            $projectId = $currentProject->id;
        @endphp

        <!-- Wrapper to match task list width -->
        <div class="task-width-wrapper" style="max-width: 640px; width: 100%; margin: 0 auto; box-sizing: border-box; padding-left: 40px; padding-right: 0;">            
            <!-- Header + Project Select -->
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <span style="font-size: 24px; font-weight: 600; white-space: nowrap;">Current Project:</span>
                <form id="project-select-form" method="GET" action="{{ route('projects.index') }}" style="flex: 1;">
                    <select name="project_id" onchange="this.form.submit()"
                            style="width: 100%; padding: 8px 12px; font-size: 16px; border-radius: 6px; border: 1px solid #94a3b8; background-color: #94a3b8; color: black;">
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ $proj->id == $currentProject->id ? 'selected' : '' }}>
                                {{ $proj->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                <a href="{{ route('projects.tasks.create', $currentProject) }}" class="btn btn-primary project-btn" style="flex: 1;">Add Task</a>
                <a href="{{ route('projects.create') }}" class="btn btn-primary project-btn" style="flex: 1;">Add Project</a>
                <a href="{{ route('projects.edit', $currentProject) }}" class="btn btn-secondary project-btn" style="flex: 1;">Edit Project</a>
                <form id="delete-project-form" action="{{ route('projects.destroy', $currentProject) }}" method="POST" style="margin: 0; flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger project-btn" style="width: 100%; flex: 1;"
                            onclick="if(confirm('Are you sure you want to delete this project and all its tasks?')) { this.form.submit(); }">
                        Delete Project
                    </button>
                </form>
            </div>

        </div>

        <!-- Task list -->
        @if($tasks->isNotEmpty())
            @include('tasks._list', ['tasks' => $tasks, 'projectId' => $projectId])
        @else
            <p>No tasks in this project.</p>
        @endif

    @endif

</div>
@endsection

@section('styles')
<style>
.projects-container {
    background-color: #e0f2fe;   /* light blue */
    border-radius: 12px;
    padding: 32px;
    margin: 24px auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    width: (100% - 48px);
    max-width: 90vw;
    box-sizing: border-box;

    /* vertical sizing */
    min-height: 90vh;      /* takes 90% of viewport height */
    display: flex;
    flex-direction: column;
}
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
}
.btn-primary { background-color: #2563eb; color: #fff; }
.btn-secondary { background-color: #6b7280; color: #fff; }
.btn-danger { background-color: #dc2626; color: #fff; }
.btn:hover { opacity: 0.9; }

.project-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: nowrap;
    align-items: center;
    margin-bottom: 24px;
}
.project-actions .project-btn {
    min-width: 120px;
    text-align: center;
}
</style>
@endsection
