<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tasker</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @yield('content')
    @yield('scripts')
    @yield('styles')
</body>
</html>
<style>
body {
    background-color: #cbd5e1; /* darker, soft grey to complement the blue */
    font-family: system-ui, sans-serif;
    margin: 0;
    padding: 0;
}
</style>
