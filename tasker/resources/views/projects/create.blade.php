@extends('layouts.app')

@section('content')
<div class="projects-container">

    <h1>Create Project</h1>

    <form action="{{ route('projects.store') }}" method="POST" class="project-form">
        @csrf
        <input type="text" name="name" placeholder="Project name" required>
        <button type="submit" class="btn btn-primary">Create Project</button>
    </form>

    <a href="{{ route('projects.index') }}" class="btn btn-secondary" style="margin-top: 16px;">Back to Projects</a>

</div>
@endsection

@section('styles')
<style>
body {
    background-color: #e0e7ff; /* soft grey-blue to match palette */
    font-family: system-ui, sans-serif;
}

/* Container */
.projects-container {
    background-color: #f0f4f8; /* same light container */
    border-radius: 12px;
    padding: 32px;
    max-width: 600px;
    margin: 24px auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Header */
.projects-container h1 {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 24px;
    color: #1e293b;
}

/* Form */
.project-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.project-form input[type="text"] {
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #94a3b8;
    font-size: 16px;
    font-weight: 500;
    background-color: #e2e8f0;
    color: #1e293b;
}

/* Buttons */
.btn, .btn button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    line-height: 1.2;
    text-decoration: none;
    transition: opacity 0.2s;
}

.btn-primary { background-color: #2563eb; color: #fff; }
.btn-secondary { background-color: #6b7280; color: #fff; }

.btn:hover { opacity: 0.9; }
</style>
@endsection
