@extends('layouts.app')

@section('content')
<h1>Task List</h1>

<!-- Include the tasks partial -->
@include('tasks._list', ['tasks' => $tasks, 'projectId' => $projectId])
@endsection