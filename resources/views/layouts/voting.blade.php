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
    <meta name="rea-urna-id" content="{{ $urna?->id ?? '' }}">

    <title>Urna Electoral — CTP AIRA</title>

    <script src="/js/tailwind.js"></script>
    <script src="/js/pusher.min.js" defer></script>
    <script src="/js/echo.iife.js" defer></script>
    <script src="/js/realtime.js" defer></script>
    <script src="/js/voting.js" defer></script>
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
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        html, body { height: 100%; }
        body { font-family: 'Inter', system-ui, sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .pantalla { animation: fade-in .25s ease-out; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="h-full bg-surface text-primary antialiased" data-tiempo-votacion="{{ $tiempoVotacion ?? 90 }}" data-urna-id="{{ $urna?->id ?? '' }}">

    <div class="min-h-full flex flex-col">
        <header class="no-print border-b border-outline bg-white">
            <div class="mx-auto flex w-full max-w-5xl items-center justify-between gap-4 px-6 py-4">
                <div class="flex items-center gap-3">
                    <img src="/img/logo.png" alt="CTP AIRA" class="h-10 w-10 rounded-full object-cover">
                    <div>
                        <p class="text-sm font-semibold leading-tight">CTP AIRA</p>
                        <p class="text-xs text-on-surface-variant">Sistema Electoral Estudiantil</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Urna habilitada
                </div>
            </div>
        </header>

        <main class="flex flex-1 items-center justify-center px-4 py-8 sm:px-6">
            <div class="w-full max-w-3xl">
                @yield('content')
            </div>
        </main>

        <footer class="no-print border-t border-outline bg-white">
            <div class="mx-auto flex w-full max-w-5xl flex-col items-center justify-between gap-2 px-6 py-3 text-xs text-on-surface-variant sm:flex-row">
                <span>&copy; {{ date('Y') }} CTP AIRA — Proceso electoral estudiantil</span>
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">lock</span>
                    Voto secreto y cifrado
                </span>
            </div>
            <x-creditos class="pb-2" />
        </footer>
    </div>

</body>
</html>
