@extends('layouts.admin')

@section('title', 'Registro de Incidentes Electorales')

@section('content')

 @if(session('success'))
  <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
   {{ session('success') }}
  </div>
 @endif
 @if(session('error'))
  <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
   {{ session('error') }}
  </div>
 @endif

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

  <!-- Formulario Nuevo Incidente -->
  <div class="lg:col-span-1">
   <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="flex items-center space-x-3 mb-5">
     <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
      <i class="ph ph-warning-octagon text-xl"></i>
     </div>
     <div>
      <h2 class="text-md font-bold text-slate-800">Reportar Incidente</h2>
      <p class="text-xs text-slate-500">Registrar anomalía en proceso electoral</p>
     </div>
    </div>

    <form method="POST" action="/admin/incidentes" class="space-y-4">
     @csrf
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Mesa Electoral</label>
      <select name="mesa_id" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-400 outline-none">
       <option value="">Seleccione una mesa...</option>
       @foreach($mesas as $mesa)
        <option value="{{ $mesa->id }}">Mesa N° {{ $mesa->numero }}</option>
       @endforeach
      </select>
     </div>
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Descripción del
       Incidente</label>
      <textarea name="detalle" required rows="4"
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-400 outline-none resize-none"
       placeholder="Describa la anomalía detectada..."></textarea>
     </div>
     <button type="submit"
      class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl shadow-md transition-all text-sm">
      Registrar Incidente
     </button>
    </form>
   </div>
  </div>

  <!-- Tabla de Incidentes -->
  <div class="lg:col-span-2">
   <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-5 border-b border-slate-100 bg-slate-50">
     <h2 class="text-base font-bold text-slate-800 flex items-center">
      <i class="ph ph-clipboard-text text-xl mr-2 text-red-500"></i>
      Incidentes Registrados ({{ $incidentes->count() }})
     </h2>
    </div>

    @if($incidentes->isEmpty())
     <div class="p-12 text-center">
      <div class="w-16 h-16 rounded-full bg-teal-50 flex items-center justify-center mx-auto mb-4 text-teal-400">
       <i class="ph ph-check-circle text-4xl"></i>
      </div>
      <h3 class="text-sm font-bold text-slate-700">Sin Incidentes</h3>
      <p class="text-xs text-slate-500 mt-1">El proceso electoral transcurre sin anomalías reportadas.</p>
     </div>
    @else
     <div class="overflow-x-auto">
      <table class="w-full text-sm">
       <thead class="bg-slate-50 text-xs uppercase text-slate-500 tracking-wider">
        <tr>
         <th class="px-5 py-3 text-left">Fecha y Hora</th>
         <th class="px-5 py-3 text-left">Mesa</th>
         <th class="px-5 py-3 text-left">Descripción</th>
         <th class="px-5 py-3 text-left">Reportado por</th>
         <th class="px-5 py-3 text-center">Acción</th>
        </tr>
       </thead>
       <tbody class="divide-y divide-slate-100">
        @foreach($incidentes as $inc)
         <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-5 py-3 text-xs text-slate-500 whitespace-nowrap">
           {{ $inc->created_at->format('d/m/Y H:i') }}
          </td>
          <td class="px-5 py-3">
           <span class="bg-red-50 text-red-700 text-xs font-bold px-2 py-0.5 rounded-full">Mesa
            {{ $inc->mesa?->numero }}</span>
          </td>
          <td class="px-5 py-3 text-slate-700 max-w-xs">
           {{ $inc->detalle }}
          </td>
          <td class="px-5 py-3 text-xs text-slate-500">
            {{ $inc->user?->name ?? 'Sistema' }}
          </td>
          <td class="px-5 py-3 text-center">
           <form method="POST" action="/admin/incidentes/{{ $inc->id }}" class="inline"
            onsubmit="return confirm('¿Eliminar este registro de incidente?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
             <i class="ph ph-trash text-lg"></i>
            </button>
           </form>
          </td>
         </tr>
        @endforeach
       </tbody>
      </table>
     </div>
    @endif
   </div>
  </div>
 </div>

@endsection
