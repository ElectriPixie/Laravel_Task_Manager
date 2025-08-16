@extends('layouts.app')

@section('content')
<h1>Projects</h1>

@if($projects->isEmpty())
    <p>No projects found.</p>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">Create a Project</a>
@else
    <a href="{{ route('projects.create') }}" class="btn btn-primary">Add Project</a>

    @php
        $currentProject = $project ?? $projects->first();
        $projectId = $currentProject->id;
    @endphp

    <!-- Project filter dropdown -->
    <form method="GET" action="{{ route('projects.index') }}" style="margin: 16px 0;">
        <label for="project_id">Select Project:</label>
        <select name="project_id" id="project_id" onchange="this.form.submit()">
            @foreach($projects as $proj)
                <option value="{{ $proj->id }}" {{ $projectId == $proj->id ? 'selected' : '' }}>
                    {{ $proj->name }}
                </option>
            @endforeach
        </select>
    </form>

    <!-- Add task button -->
    <a href="{{ route('projects.tasks.create', $currentProject) }}" class="btn btn-primary">
        Add Task to {{ $currentProject->name }}
    </a>

    <!-- Task list -->
    @if($tasks->isNotEmpty())
        @include('tasks._list', ['tasks' => $tasks, 'projectId' => $projectId])
    @else
        <p>No tasks in this project.</p>
    @endif

<div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
    <!-- Edit Project (left) -->
    <a href="{{ route('projects.edit', $currentProject) }}" class="btn btn-secondary">
        Edit Project
    </a>

    <!-- Delete Project (right) -->
    <form id="delete-project-form" action="{{ route('projects.destroy', $currentProject) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-danger" 
                onclick="if(confirm('Are you sure you want to delete this project and all its tasks?')) { this.form.submit(); }">
            Delete Project
        </button>
    </form>
</div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('task-list');
    const reorderForm = document.getElementById('reorder-form');
    let draggedItem = null;

    if(list && reorderForm) {
        list.addEventListener('dragstart', e => {
            draggedItem = e.target.closest('li');
        });

        list.addEventListener('dragover', e => {
            e.preventDefault();
            const target = e.target.closest('li');
            if (!target || target === draggedItem) return;
            const rect = target.getBoundingClientRect();
            const next = (e.clientY - rect.top) > rect.height / 2;
            list.insertBefore(draggedItem, next ? target.nextSibling : target);
        });

        function updateOrder() {
            reorderForm.querySelectorAll('input[name="order[]"]').forEach(input => input.remove());
            Array.from(list.children).forEach(li => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order[]';
                input.value = li.dataset.id;
                reorderForm.appendChild(input);
            });
            reorderForm.submit();
        }

        list.addEventListener('drop', e => { e.preventDefault(); updateOrder(); });
        list.addEventListener('dragend', updateOrder);
    }

    // Delete task buttons
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', () => {
            const form = button.closest('li').querySelector('.delete-form');
            if (form) form.submit();
        });
    });
});
</script>
@endsection
