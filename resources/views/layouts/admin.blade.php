<!DOCTYPE html>
<html lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin Dashboard — Sistema Electoral')</title>
<script src="/js/tailwind.js"></script>
@vite('resources/js/tailwind-config.js')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<style>
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }
  .material-symbols-outlined.fill {
    font-variation-settings: 'FILL' 1;
  }
  body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #191c1d; }
  .nav-item.active {
    border-left-width: 4px;
    border-color: #001e40;
    background-color: #d5e3ff;
    color: #001b3c;
    font-weight: 600;
  }
  .nav-item.active .material-symbols-outlined {
    font-variation-settings: 'FILL' 1;
  }
  .section-content { display: none; }
  .section-content.active { display: block; }
</style>
</head>
<body class="bg-background text-on-background flex h-screen overflow-hidden">
<!-- SideNavBar -->
<nav class="hidden md:flex flex-col h-full py-6 bg-surface-container-lowest border-r border-outline-variant w-[280px] h-screen fixed left-0 top-0 z-20">
<div class="px-6 mb-8 flex items-center gap-4">
<img alt="Institution Logo" class="w-12 h-12 rounded-full border border-outline-variant object-cover" src="/img/logo.png"/>
<div class="overflow-hidden">
<h1 class="font-headline-md text-headline-md text-primary truncate">Sistema Electoral</h1>
<p class="font-body-md text-body-md text-on-surface-variant truncate">Administrador</p>
</div>
</div>
@php $route = request()->path(); @endphp
<ul class="flex-1 space-y-1" id="nav-menu">
<li><a class="nav-item {{ $route == 'admin' ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors transition-all duration-200 active:scale-95" href="{{ route('admin.dashboard') }}"><span class="material-symbols-outlined">dashboard</span>Inicio</a></li>
<li><a class="nav-item {{ str_contains($route, 'configuracion') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors transition-all duration-200 active:scale-95" href="{{ route('admin.settings') }}"><span class="material-symbols-outlined">settings</span>Parámetros</a></li>
<li><a class="nav-item {{ str_contains($route, 'estudiantes') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors transition-all duration-200 active:scale-95" href="{{ route('admin.students') }}"><span class="material-symbols-outlined">person</span>Estudiantes</a></li>
<li><a class="nav-item {{ str_contains($route, 'respaldar') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors transition-all duration-200 active:scale-95" href="{{ route('admin.backup') }}"><span class="material-symbols-outlined">backup</span>Respaldar Base de Datos</a></li>
<li><a class="nav-item {{ str_contains($route, 'bitacora') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors transition-all duration-200 active:scale-95" href="{{ route('admin.bitacora') }}"><span class="material-symbols-outlined">history</span>Consultar bitácora</a></li>
<li><a class="nav-item {{ str_contains($route, 'usuarios') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors transition-all duration-200 active:scale-95" href="{{ route('admin.usuarios') }}"><span class="material-symbols-outlined">group</span>Usuarios</a></li>
<li><a class="nav-item {{ str_contains($route, 'ayuda') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors transition-all duration-200 active:scale-95" href="{{ route('admin.help') }}"><span class="material-symbols-outlined">help</span>Ayuda</a></li>
</ul>
<div class="px-4 mt-auto">
<form id="logout-form" method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-error hover:bg-error-container hover:text-on-error-container rounded-lg transition-colors transition-all duration-200 active:scale-95">
<span class="material-symbols-outlined">logout</span>Cerrar Sesión
</button>
</form>
</div>
</nav>
<!-- TopNavBar -->
<header class="flex items-center justify-between px-gutter w-full h-16 fixed top-0 right-0 md:left-[280px] md:w-[calc(100%-280px)] z-10 bg-surface-container border-b border-outline-variant">
<div class="flex items-center gap-4 min-w-0">
<button class="md:hidden p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full" onclick="document.querySelector('nav').classList.toggle('hidden')"><span class="material-symbols-outlined">menu</span></button>
<span class="font-headline-lg text-headline-lg text-primary hidden md:block truncate">Sistema Electoral Institucional</span>
</div>
<div class="flex items-center gap-4 shrink-0">
<button class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer active:opacity-80 rounded-full"><span class="material-symbols-outlined">notifications</span></button>
<button class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer active:opacity-80 rounded-full"><span class="material-symbols-outlined">account_circle</span></button>
</div>
</header>
<!-- Main Content Canvas -->
<main class="flex-1 ml-0 md:ml-[280px] mt-16 p-container-padding overflow-y-auto" id="main-content">
<div class="max-w-7xl mx-auto space-y-section-margin pb-20">
@yield('content')
</div>
</main>
<!-- Logout Modal -->
<div class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" id="logout-modal">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-sm shadow-xl">
<div class="flex items-center gap-3 text-error mb-4">
<span class="material-symbols-outlined text-3xl">logout</span>
<h3 class="font-headline-sm text-headline-sm">Cerrar Sesión</h3>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">¿Está seguro que desea salir del sistema?</p>
<div class="flex justify-end gap-3">
<button class="px-4 py-2 text-primary font-label-lg hover:bg-surface-container rounded-lg transition-colors" onclick="document.getElementById('logout-modal').classList.add('hidden')">Cancelar</button>
<button class="px-4 py-2 bg-error text-on-error font-label-lg rounded-lg hover:bg-[#93000a] transition-colors" onclick="document.getElementById('logout-form').submit()">Salir</button>
</div>
</div>
</div>
@stack('scripts')
</body></html>