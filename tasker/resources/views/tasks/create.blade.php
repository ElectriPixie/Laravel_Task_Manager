@extends('layouts.app')

@section('content')
<h1>Create Task for {{ $project->name }}</h1>

<form action="{{ route('projects.tasks.store', $project) }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Task name" required>
    <button type="submit">Create Task</button>
</form>

<a href="{{ route('projects.index', ['project_id' => $project->id]) }}">Back to Project</a>
@endsection
