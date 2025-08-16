@extends('layouts.app')

@section('content')
<h1>Edit Task</h1>

<form action="{{ route('tasks.update', $task) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $task->name }}" required>
    <button type="submit">Update Task</button>
</form>

<a href="{{ route('projects.index', ['project_id' => $task->project_id]) }}">Back to Project</a>
@endsection
