@extends('layouts.app')

@section('content')
<h1>Task List</h1>
<a href="{{ route('tasks.create') }}">Add Task</a>

<form id="reorder-form" action="{{ route('tasks.reorder') }}" method="POST">
    @csrf
    <ul id="task-list" style="list-style: none; padding: 0; margin: 0;">
        @foreach($tasks as $task)
        <li draggable="true" data-id="{{ $task->id }}" 
            style="margin-bottom: 8px; padding: 8px; border: 1px solid #ccc; cursor: grab; display: flex; align-items: center; gap: 10px;">

            <!-- Task name -->
            <span>{{ $task->name }}</span>

            <!-- Edit link -->
            <a href="{{ route('tasks.edit', $task) }}" style="margin-left: 0;">Edit</a>

            <!-- Delete button immediately after Edit -->
            <button class="delete-button" data-task-id="{{ $task->id }}" 
                style="background-color: red; color: white; border: none; padding: 4px 8px; cursor: pointer;">
                Delete
            </button>
        </li>
        @endforeach
    </ul>
</form>

<!-- Keep all forms outside -->
@foreach($tasks as $task)
<form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:none;" 
      class="delete-form" data-task-id="{{ $task->id }}">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('task-list');
    const reorderForm = document.getElementById('reorder-form');

    let draggedItem = null;

    // Drag start
    list.addEventListener('dragstart', e => {
        draggedItem = e.target.closest('li');
    });

    // Drag over
    list.addEventListener('dragover', e => {
        e.preventDefault();
        const target = e.target.closest('li');
        if (!target || target === draggedItem || target.parentNode !== list) return;

        const rect = target.getBoundingClientRect();
        const next = (e.clientY - rect.top) > rect.height / 2;
        list.insertBefore(draggedItem, next ? target.nextSibling : target);
    });

    // Update order after drop/dragend
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

    // Delete button triggers form submission
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.dataset.taskId;
            const form = document.querySelector(`.delete-form[data-task-id='${taskId}']`);
            if (form) form.submit();
        });
    });
});
</script>
@endsection
