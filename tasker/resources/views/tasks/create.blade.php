@extends('layouts.app')

@section('content')
<h1>Create Task</h1>

<form action="{{ route('tasks.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Task name" required>
    <button type="submit">Create</button>
</form>

<a href="{{ route('tasks.index') }}">Back to list</a>
@endsection