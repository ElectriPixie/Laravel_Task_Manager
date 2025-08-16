<ul id="task-list">
    @foreach($tasks as $task)
        <li draggable="true" data-id="{{ $task->id }}">
            <span class="task-name">{{ $task->name }}</span>
            <div class="task-buttons">
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary">Edit</a>
                <button type="button" class="btn btn-danger delete-button">Delete</button>
            </div>

            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:none;" class="delete-form">
                @csrf
                @method('DELETE')
            </form>
        </li>
    @endforeach
</ul>

<style>
#task-list {
    list-style: none;
    padding: 0;
    margin: 0 auto; /* center ul */
    width: 100%;
    max-width: 600px;
}
#task-list li {
    padding: 16px 20px;
    margin-bottom: 12px;
    border-radius: 10px;
    background-color: #dbeafe;
    border: 1px solid #93c5fd;
    width: 100%;
    display: flex;
    flex-direction: column;
}
#task-list li:hover {
    background-color: #bfdbfe;
    border-color: #60a5fa;
}
.task-name {
    font-weight: 600;
    font-size: 16px;
    color: #1e3a8a;
    margin-bottom: 8px;
}
.task-buttons {
    display: flex;
    justify-content: space-between;
    gap: 8px;
}
.task-buttons .btn {
    flex: 1;
    padding: 6px 12px;
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('task-list');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let draggedItem = null;
    let placeholder = document.createElement('li');
    placeholder.style.height = '60px';
    placeholder.style.border = '2px dashed #60a5fa';
    placeholder.style.marginBottom = '12px';
    placeholder.style.borderRadius = '10px';
    placeholder.style.backgroundColor = '#e0f2fe';

    if(list) {
        list.addEventListener('dragstart', e => {
            const li = e.target.closest('li');
            if(!li) return;
            if(e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
                e.preventDefault();
                return;
            }
            draggedItem = li;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', '');
            li.style.opacity = '0.5';
        });

        list.addEventListener('dragend', e => {
            if(draggedItem) draggedItem.style.opacity = '1';
            placeholder.remove();
        });

        list.addEventListener('dragover', e => {
            e.preventDefault();
            const target = e.target.closest('li');
            if(!target || target === draggedItem) return;

            const rect = target.getBoundingClientRect();
            const next = (e.clientY - rect.top) > rect.height / 2;

            if(next) target.after(placeholder);
            else target.before(placeholder);
        });

        list.addEventListener('drop', e => {
            e.preventDefault();
            if(!draggedItem) return;

            list.insertBefore(draggedItem, placeholder);
            placeholder.remove();
            draggedItem.style.opacity = '1';

            // Build FormData for AJAX
            const formData = new FormData();
            formData.append('project_id', '{{ $projectId }}');
            Array.from(list.children)
                .filter(li => li.dataset.id)
                .forEach(li => formData.append('order[]', li.dataset.id));

            // Send AJAX POST
            fetch(`{{ route('projects.tasks.reorder', $projectId) }}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(res => res.json())
            .then(data => console.log('Reorder saved', data))
            .catch(err => console.error('Error saving order', err));

            draggedItem = null;
        });
    }

    // Delete buttons
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', () => {
            const form = button.closest('li').querySelector('.delete-form');
            if(form) form.submit();
        });
    });
});
</script>
@endsection

