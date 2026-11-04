<!DOCTYPE html><html class="light" lang="es" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Tribunal Electoral')</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=block" rel="stylesheet">
<script src="/js/tailwind.js"></script>
<script src="/assets/js/tailwind-config.js"></script>
<link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="bg-background text-on-background font-body-md h-screen flex overflow-hidden">
<!-- SideNavBar -->
<nav class="hidden md:flex flex-col h-full py-6 fixed left-0 top-0 w-[280px] bg-surface border-r border-outline-variant z-20">
<div class="px-6 mb-8 flex items-center gap-4">
<div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-outline-variant bg-surface-container-lowest">
<img alt="CTP AIRA Logo" class="w-full h-full object-cover" src="/img/logo.png">
</div>
<div>
<h1 class="font-headline-md text-headline-md font-bold text-primary">Elecciones</h1>
<p class="text-sm text-secondary">Panel electoral</p>
</div>
</div>
<div class="px-4 mb-6">
<div class="p-3 bg-primary-container/10 border border-primary-container/20 rounded-lg">
<p class="text-[10px] font-bold text-primary uppercase tracking-wider mb-1">Estado Global</p>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-green-500 animate-pulse" id="global-status-dot"></span>
<span class="font-label-md text-green-700" id="global-status-text">Votaciones ABIERTAS</span>
</div>
</div>
</div>
<div class="flex-1 overflow-y-auto px-2 space-y-1" id="nav-links-container">
@php $url = request()->path(); @endphp
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200 {{ $url == 'tribunal' ? 'active' : '' }}" data-target="dashboard" href="#"><span class="material-symbols-outlined">dashboard</span><span>Dashboard</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200 {{ $url == 'tribunal/estudiantes' ? 'active' : '' }}" data-target="estudiantes" href="#"><span class="material-symbols-outlined">school</span><span>Estudiantes</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="tribunal-estudiantil" href="#"><span class="material-symbols-outlined">gavel</span><span>Tribunal Estudiantil</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="partidos" href="#"><span class="material-symbols-outlined">groups</span><span>Partidos</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="jrv" href="#"><span class="material-symbols-outlined">how_to_vote</span><span>Logística JRV</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="votaciones" href="#"><span class="material-symbols-outlined">power_settings_new</span><span>Votación</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="resultados" href="#"><span class="material-symbols-outlined">bar_chart</span><span>Resultados</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="estructura" href="#"><span class="material-symbols-outlined">account_tree</span><span>Estructura</span></a>
<div class="pt-4 pb-2 px-4"><p class="text-[10px] font-bold text-secondary uppercase tracking-widest">Soporte</p></div>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="ayuda" href="#"><span class="material-symbols-outlined">help</span><span>Ayuda</span></a>
<a class="nav-link flex items-center gap-3 px-4 py-3 text-secondary border-l-4 border-transparent transition-all duration-200" data-target="configuracion" href="#"><span class="material-symbols-outlined">settings</span><span>Ajustes</span></a>
</div>
<div class="px-2 mt-auto pt-4 border-t border-outline-variant">
<div class="px-4 py-2 mb-4 bg-surface-container-low rounded-lg">
<p class="text-[10px] text-secondary">USUARIO ACTUAL</p>
<p class="text-xs font-bold text-primary truncate">{{ Auth::user()->name ?? 'Usuario' }}</p>
</div>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-error hover:bg-error-container/20 transition-colors">
<span class="material-symbols-outlined">logout</span><span>Cerrar Sesión</span>
</button>
</form>
</div>
</nav>
<!-- Main Content -->
<main class="flex-1 md:ml-[280px] flex flex-col h-full bg-background overflow-y-auto relative">
<header class="h-16 border-b border-outline-variant bg-surface-container-lowest sticky top-0 z-10 px-gutter flex items-center justify-between">
<h2 class="font-headline-md text-primary capitalize" id="view-title">@yield('title', 'Panel')</h2>
<div class="flex items-center gap-4">
<button class="p-2 text-secondary hover:bg-surface-container-high rounded-full"><span class="material-symbols-outlined">notifications</span></button>
</div>
</header>
<div class="p-gutter max-w-container-max mx-auto w-full" id="router-view">
@yield('content')
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/assets/js/app.js"></script>
@stack('scripts')
</body></html>
