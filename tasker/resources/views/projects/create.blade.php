@extends('layouts.app')

@section('content')
<h1>Create Project</h1>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Project name" required>
    <button type="submit">Create Project</button>
</form>

<a href="{{ route('projects.index') }}">Back to Projects</a>
@endsection
