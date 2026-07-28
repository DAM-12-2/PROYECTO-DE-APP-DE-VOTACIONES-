<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Panel de Administración — Votaciones</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <script src="/js/admin-config.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/admin.css">
</head>

<body class="text-slate-800 antialiased flex h-screen overflow-hidden">

 <!-- Sidebar -->
 <aside class="w-56 lg:w-64 bg-slate-900 text-slate-300 flex flex-col shadow-xl z-20 shrink-0">
  <div class="h-16 flex items-center px-6 border-b border-slate-800">
   <i class="ph ph-shield-check text-brand-500 text-2xl mr-3"></i>
   <span class="text-white font-bold text-lg tracking-wide">Cómputo<span class="text-brand-500">SYS</span></span>
  </div>

   <div class="flex-1 overflow-y-auto py-4">
    @php $currentRoute = request()->path(); @endphp
     <nav class="space-y-1 px-3">
      <a href="/admin"
       class="flex items-center px-3 py-2.5 {{ $currentRoute == 'admin' ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-squares-four text-xl mr-3"></i>
       <span class="font-medium">Panel de Control</span>
      </a>

      @if(auth()->user()?->isAdmin() || auth()->user()?->isTee())
      <p class="px-3 pt-4 pb-1 text-xs font-bold text-slate-600 uppercase tracking-widest">Padrón & Partidos</p>

      <a href="/admin/estudiantes"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'estudiantes') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-users text-xl mr-3"></i>
       <span class="font-medium">Estudiantes</span>
      </a>

      <a href="/admin/partidos"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'partidos') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-flag-banner text-xl mr-3"></i>
       <span class="font-medium">Partidos</span>
      </a>

      <a href="/admin/candidatos"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'candidatos') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-medal text-xl mr-3"></i>
       <span class="font-medium">Candidaturas</span>
      </a>

      <p class="px-3 pt-4 pb-1 text-xs font-bold text-slate-600 uppercase tracking-widest">Infraestructura</p>

      <a href="/admin/urnas"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'urnas') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-desktop text-xl mr-3"></i>
       <span class="font-medium">Urnas / Terminales</span>
      </a>

      <a href="/admin/mesas"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'mesas') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-table text-xl mr-3"></i>
       <span class="font-medium">Mesas Electorales</span>
      </a>

       <p class="px-3 pt-4 pb-1 text-xs font-bold text-slate-600 uppercase tracking-widest">Resultados & Auditoría</p>

       <a href="/admin/resultados"
        class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'resultados') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
        <i class="ph ph-chart-pie-slice text-xl mr-3"></i>
        <span class="font-medium">Resultados Oficiales</span>
       </a>

       <a href="/admin/reportes"
        class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'reportes') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
        <i class="ph ph-file-text text-xl mr-3"></i>
        <span class="font-medium">Reportes</span>
       </a>
      @endif

      <a href="/admin/incidentes"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'incidentes') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-warning-octagon text-xl mr-3"></i>
       <span class="font-medium">Incidentes</span>
      </a>

      @if(auth()->user()?->isAdmin() || auth()->user()?->isTee())
      <a href="/admin/bitacora"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'bitacora') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-book-open-text text-xl mr-3"></i>
       <span class="font-medium">Bitácora</span>
      </a>
      @endif

      @if(auth()->user()?->isAdmin())
      <p class="px-3 pt-4 pb-1 text-xs font-bold text-slate-600 uppercase tracking-widest">Sistema</p>

      <a href="/admin/usuarios"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'usuarios') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-user-circle-gear text-xl mr-3"></i>
       <span class="font-medium">Usuarios</span>
      </a>
      @endif

      <a href="/admin/tee"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'tee') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-scales text-xl mr-3"></i>
       <span class="font-medium">Tribunal (TEE)</span>
      </a>

      @if(auth()->user()?->isAdmin())
      <a href="/admin/configuracion"
       class="flex items-center px-3 py-2.5 {{ str_contains($currentRoute, 'configuracion') ? 'bg-brand-600/10 text-brand-500' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-lg transition-colors">
       <i class="ph ph-gear text-xl mr-3"></i>
       <span class="font-medium">Configuración</span>
      </a>
      @endif
     </nav>
   </div>

  <div class="p-4 border-t border-slate-800">
   <div class="flex items-center justify-between">
    <div class="flex items-center">
     <div
      class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold shadow-inner text-sm">
      {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
     </div>
     <div class="ml-3">
      <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
     </div>
    </div>
     <a href="{{ auth()->user()?->role === 'admin' ? '/Html/login.html' : '/Html/Tribunal_est.html' }}"
        class="text-slate-500 hover:text-red-400 transition-colors" title="Cerrar Sesión">
      <i class="ph ph-sign-out text-xl"></i>
     </a>
   </div>
  </div>
 </aside>

 <!-- Main Content -->
 <main class="flex-1 flex flex-col h-screen overflow-hidden">
  <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 z-10 shrink-0">
   <h1 class="text-base md:text-xl font-semibold text-slate-800 truncate">@yield('title', 'Dashboard')</h1>
   <div class="flex items-center space-x-2 md:space-x-3 shrink-0">
    <a href="/kiosko" target="_blank"
     class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 px-2 md:px-3 py-1.5 rounded-lg font-bold transition-colors whitespace-nowrap">
     <i class="ph ph-monitor mr-1"></i> <span class="hidden sm:inline">Ver Kiosko</span><span class="sm:hidden">Kiosko</span>
    </a>
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest hidden lg:inline">Junta Receptora</span>
   </div>
  </header>

  <div class="flex-1 overflow-auto bg-slate-50 p-4 md:p-8">
   <div class="max-w-7xl mx-auto">
    @yield('content')
   </div>
  </div>
 </main>

 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 @stack('scripts')
</body>

</html>
