@extends('layouts.admin')

@section('title', 'Bitácora del Sistema')

@section('content')

 @php
  $bitacorasFiltered = $bitacoras;
 @endphp

 <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
  <div class="p-5 border-b border-slate-100 bg-slate-50">
   <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
     <h2 class="text-base font-bold text-slate-800 flex items-center">
      <i class="ph ph-book-open-text text-xl mr-2 text-slate-500"></i>
      Historial de Auditoría
     </h2>
     <p class="text-xs text-slate-500 mt-0.5">Últimas acciones registradas en el sistema</p>
    </div>
    <div class="flex gap-2">
     <input type="text" id="bitacoraSearch" placeholder="Buscar..."
      class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:ring-2 focus:ring-teal-500 outline-none w-40"
      oninput="filterBitacora()">
     <select id="bitacoraFilter" onchange="filterBitacora()"
      class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:ring-2 focus:ring-teal-500 outline-none">
      <option value="">Todas las acciones</option>
      <option value="Crear">Crear</option>
      <option value="Editar">Editar</option>
      <option value="Eliminar">Eliminar</option>
      <option value="Apertura">Apertura</option>
      <option value="Cierre">Cierre</option>
      <option value="Importación">Importación</option>
      <option value="Respaldo">Respaldo</option>
      <option value="Reiniciar">Reiniciar</option>
     </select>
    </div>
   </div>
  </div>

  @if($bitacoras->isEmpty())
   <div class="p-12 text-center">
    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-400">
     <i class="ph ph-clock text-4xl"></i>
    </div>
    <h3 class="text-sm font-bold text-slate-700">Bitácora vacía</h3>
    <p class="text-xs text-slate-500 mt-1">Las acciones del sistema se registrarán automáticamente aquí.</p>
   </div>
  @else
   <div class="overflow-x-auto" id="bitacoraTable">
    <table class="w-full text-sm">
     <thead class="bg-slate-50 text-xs uppercase text-slate-500 tracking-wider">
      <tr>
       <th class="px-5 py-3 text-left">Fecha y Hora</th>
       <th class="px-5 py-3 text-left">Acción</th>
       <th class="px-5 py-3 text-left">Detalle</th>
       <th class="px-5 py-3 text-left">Usuario</th>
      </tr>
     </thead>
     <tbody class="divide-y divide-slate-100">
      @foreach($bitacoras as $log)
       <tr class="bitacora-row hover:bg-slate-50 transition-colors" data-accion="{{ strtolower($log->accion) }}" data-detalle="{{ strtolower($log->detalle) }}" data-user="{{ strtolower($log->user?->name ?? '') }}">
        <td class="px-5 py-3 text-xs text-slate-400 whitespace-nowrap font-mono">
         {{ $log->created_at->format('d/m/Y H:i:s') }}
        </td>
        <td class="px-5 py-3">
         @php
          $color = 'slate';
          if (str_contains($log->accion, 'Apertura'))
           $color = 'teal';
          elseif (str_contains($log->accion, 'Cierre'))
           $color = 'orange';
          elseif (str_contains($log->accion, 'Eliminar'))
           $color = 'red';
          elseif (str_contains($log->accion, 'Respaldo'))
           $color = 'indigo';
          elseif (str_contains($log->accion, 'Importación'))
           $color = 'cyan';
         @endphp
         <span
          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">
          {{ $log->accion }}
         </span>
        </td>
        <td class="px-5 py-3 text-slate-600 max-w-md">
         {{ $log->detalle }}
        </td>
        <td class="px-5 py-3 text-xs text-slate-500">
          {{ $log->user?->name ?? '—' }}
        </td>
       </tr>
      @endforeach
     </tbody>
    </table>
   </div>
  @endif
 </div>

 <script>
  function filterBitacora() {
   const search = document.getElementById('bitacoraSearch').value.toLowerCase();
   const filter = document.getElementById('bitacoraFilter').value.toLowerCase();
   document.querySelectorAll('.bitacora-row').forEach(row => {
    const accion = row.dataset.accion;
    const detalle = row.dataset.detalle;
    const user = row.dataset.user;
    const matchSearch = !search || accion.includes(search) || detalle.includes(search) || user.includes(search);
    const matchFilter = !filter || accion.includes(filter);
    row.style.display = (matchSearch && matchFilter) ? '' : 'none';
   });
  }
 </script>

@endsection
