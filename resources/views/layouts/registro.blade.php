<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="reverb-key" content="{{ env('REVERB_APP_KEY') }}">
    <meta name="reverb-host" content="{{ env('REVERB_HOST', request()->getHost()) }}">
    <meta name="reverb-port" content="{{ env('REVERB_PORT', 8080) }}">
    <meta name="reverb-scheme" content="{{ env('REVERB_SCHEME', 'http') }}">
    <meta name="rea-mesa-id" content="{{ $mesa?->id ?? '' }}">
    <title>Registro de votantes — CTP AIRA</title>
    <script src="/js/tailwind.js"></script>
    <link rel="stylesheet" href="/css/fuentes.css">
    <link rel="stylesheet" href="/css/fuentes.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#001e40',
                        'primary-container': '#003366',
                        surface: '#f7f9fb',
                        'surface-variant': '#e0e3e5',
                        'on-surface-variant': '#43474f',
                        outline: '#c3c6d1',
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
    <style>
        html, body { height: 100%; }
        body { font-family: 'Inter', system-ui, sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .pantalla { animation: fade-in .28s ease-out; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
        .pulse-ring { animation: pulse-ring 1.8s ease-out infinite; }
        @keyframes pulse-ring { 0% { transform: scale(.9); opacity: .6; } 70% { transform: scale(1.3); opacity: 0; } 100% { opacity: 0; } }
    </style>
</head>
<body class="h-full bg-surface text-primary antialiased">
    <div class="min-h-full flex flex-col">
        <header class="border-b border-outline bg-white">
            <div class="mx-auto flex w-full max-w-4xl items-center justify-between gap-4 px-6 py-4">
                <div class="flex items-center gap-3">
                    <img src="/img/logo.png" alt="CTP AIRA" class="h-10 w-10 rounded-full object-cover">
                    <div>
                        <p class="text-sm font-semibold leading-tight">CTP AIRA</p>
                        <p class="text-xs text-on-surface-variant">Punto de registro de votantes</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                        <span class="material-symbols-outlined text-base">logout</span> Salir
                    </button>
                </form>
            </div>
        </header>

        <main class="flex flex-1 items-center justify-center px-4 py-8">
            <div class="w-full max-w-2xl">
                @yield('content')
            </div>
        </main>

        <footer class="border-t border-outline bg-white">
            <div class="mx-auto w-full max-w-4xl px-6 py-3 text-xs text-on-surface-variant">
                {{ $mesa?->nombre ?? 'Sin mesa asignada' }} · Red local del CTP AIRA
            </div>
        </footer>
    </div>
    <script src="/js/pusher.min.js"></script>
    <script src="/js/echo.iife.js"></script>
    <script src="/js/realtime.js"></script>
</body>
</html>
