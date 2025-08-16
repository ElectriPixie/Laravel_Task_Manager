@extends('layouts.app')

@section('content')
<h1>Edit Project</h1>

<form action="{{ route('projects.update', $project) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $project->name }}" required>
    <button type="submit">Update Project</button>
</form>

<a href="{{ route('projects.index', ['project_id' => $project->id]) }}">Back to Projects</a>
@endsection
