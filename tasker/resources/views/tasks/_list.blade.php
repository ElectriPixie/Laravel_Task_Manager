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
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}
#task-list li {
    padding: 16px 20px;
    margin-bottom: 12px;
    border-radius: 10px;
    background-color: #dbeafe; /* lighter blue */
    border: 1px solid #93c5fd;
    width: 100%;
    max-width: 600px;
    display: flex;
    flex-direction: column;
}
#task-list li:hover {
    background-color: #bfdbfe; /* slightly darker on hover */
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
