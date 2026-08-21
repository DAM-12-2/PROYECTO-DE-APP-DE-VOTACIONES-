<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Votacion</title>
    <script src="/js/tailwind.js"></script>
</head>
<body class="bg-inverse-surface text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl p-8">
        @yield('content')
    </div>
</body>
</html>
