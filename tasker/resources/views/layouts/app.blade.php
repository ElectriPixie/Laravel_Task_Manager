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
</body>
</html>
