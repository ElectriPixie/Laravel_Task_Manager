<!-- Hidden reorder form (not used, kept for fallback) -->
<form id="reorder-form" action="{{ route('projects.tasks.reorder', $projectId) }}" method="POST" style="display:none;">
    @csrf
</form>

<!-- Task list -->
<ul id="task-list" style="list-style: none; padding: 0; margin: 0;">
    @foreach($tasks as $task)
        <li draggable="true" data-id="{{ $task->id }}"
            style="margin-bottom: 8px; padding: 8px; border: 1px solid #ccc; cursor: grab; display: flex; align-items: center; gap: 10px; user-select: none;">

            <span>{{ $task->name }}</span>

            <a href="{{ route('tasks.edit', $task) }}" style="margin-left:auto;">Edit</a>

            <button type="button" class="delete-button"
                style="background-color: red; color: white; border: none; padding: 4px 8px; cursor: pointer;">
                Delete
            </button>

            <!-- Hidden delete form -->
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:none;" class="delete-form">
                @csrf
                @method('DELETE')
            </form>
        </li>
    @endforeach
</ul>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('task-list');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let draggedItem = null;
    let placeholder = document.createElement('li');
    placeholder.style.height = '40px';
    placeholder.style.border = '2px dashed #999';
    placeholder.style.marginBottom = '8px';

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
