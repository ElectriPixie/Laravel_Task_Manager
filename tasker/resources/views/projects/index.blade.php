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

        <!-- Header + Custom Dropdown -->
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; width: 100%; max-width: 600px; margin-left: auto; margin-right: auto;">
            <span style="font-size: 24px; font-weight: 600; white-space: nowrap;">Current Project:</span>

            <div class="custom-dropdown" style="flex-grow: 1; position: relative;">
                <button type="button" class="dropdown-toggle">
                    {{ $currentProject->name }}
                    <span class="arrow">&#9662;</span>
                </button>
                <ul class="dropdown-menu">
                    @foreach($projects as $proj)
                        <li data-id="{{ $proj->id }}">{{ $proj->name }}</li>
                    @endforeach
                </ul>
                <form id="dropdown-form" method="GET" action="{{ route('projects.index') }}">
                    <input type="hidden" name="project_id" value="{{ $projectId }}">
                </form>
            </div>
        </div>

        <!-- Action buttons in one row -->
        <div class="project-actions" style="display: flex; justify-content: center; gap: 12px; margin-bottom: 24px; flex-wrap: nowrap; align-items: center;">
            <a href="{{ route('projects.tasks.create', $currentProject) }}" class="btn btn-primary project-btn">Add Task</a>
            <a href="{{ route('projects.create') }}" class="btn btn-primary project-btn">Add Project</a>
            <a href="{{ route('projects.edit', $currentProject) }}" class="btn btn-secondary project-btn">Edit Project</a>

            <form id="delete-project-form" action="{{ route('projects.destroy', $currentProject) }}" method="POST" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger project-btn"
                        onclick="if(confirm('Are you sure you want to delete this project and all its tasks?')) { this.form.submit(); }">
                    Delete Project
                </button>
            </form>
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
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.custom-dropdown');
    const toggle = dropdown.querySelector('.dropdown-toggle');
    const menu = dropdown.querySelector('.dropdown-menu');
    const hiddenInput = dropdown.querySelector('input[name="project_id"]');
    const form = document.getElementById('dropdown-form');

    toggle.addEventListener('click', () => {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });

    menu.querySelectorAll('li').forEach(item => {
        item.addEventListener('click', () => {
            toggle.firstChild.textContent = item.textContent;
            hiddenInput.value = item.dataset.id;
            form.submit();
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
});
</script>
@endsection

@section('styles')
<style>
/* Container box */
.projects-container {
    max-width: 900px;
    margin: 24px auto;
    padding: 32px;
    background-color: #e0f2fe; /* light blue */
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Base button styles */
.btn, .btn button {
    display: inline-flex;       
    align-items: center;        
    justify-content: center;    
    padding: 10px 16px;         
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    line-height: 1.2;           
    text-decoration: none;
}

/* Colors */
.btn-primary { background-color: #2563eb; color: #fff; }
.btn-secondary { background-color: #6b7280; color: #fff; }
.btn-danger { background-color: #dc2626; color: #fff; }

.btn:hover { opacity: 0.9; }

/* Project actions row */
.project-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: nowrap;
    align-items: center;
    margin-bottom: 24px;
}

/* Project action buttons */
.project-actions .project-btn {
    min-width: 120px;         
    text-align: center;
}
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
.custom-dropdown {
    width: 100%;
}

.dropdown-toggle {
    width: 100%;
    padding: 8px 12px;
    font-size: 16px;
    border-radius: 6px;
    border: 1px solid #94a3b8;
    background-color: #94a3b8;
    color: #000; /* changed to black */
    font-weight: 500;
    cursor: pointer;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-toggle .arrow {
    margin-left: 8px;
    font-size: 12px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: #94a3b8;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-top: 4px;
    display: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 10;
    padding: 0;
    list-style: none;
}

.dropdown-menu li {
    padding: 8px 12px;
    cursor: pointer;
    color: #000; /* changed to black */
}

.dropdown-menu li:hover {
    background-color: #718096;
}

.dropdown-menu li {
    transition: background-color 0.2s;
}
</style>
@endsection
