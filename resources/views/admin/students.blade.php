@extends('layouts.admin')

@section('title', 'Padrón Electoral — Estudiantes')

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

  <!-- Formulario -->
  <div class="lg:col-span-1">
   <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h2 class="text-lg font-bold text-slate-800 mb-4">Agregar Estudiante</h2>
    <form method="POST" action="/admin/estudiantes" class="space-y-4">
     @csrf
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Identificación / DNI</label>
      <input type="text" name="identificacion" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
       placeholder="1001">
     </div>
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre</label>
      <input type="text" name="nombre" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
       placeholder="Juan Antonio">
     </div>
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Apellidos</label>
      <input type="text" name="apellidos" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
       placeholder="Pérez López">
     </div>
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sección</label>
      <input type="text" name="seccion" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
       placeholder="10-A">
     </div>
     <button type="submit"
      class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
      Registrar Estudiante
     </button>
    </form>

    <!-- Botón Importar Excel -->
    <div class="mt-6 pt-6 border-t border-slate-100">
     <button onclick="document.getElementById('importModal').classList.remove('hidden')"
      class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm flex items-center justify-center gap-2">
      <i class="ph ph-file-xls text-lg"></i>
      Importar desde Excel / CSV
     </button>
    </div>
   </div>
  </div>

  <!-- Tabla -->
  <div class="lg:col-span-2">
   <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
     <h2 class="text-lg font-bold text-slate-800">Padrón ({{ $students->total() }} registros)</h2>
     <form method="GET" action="/admin/estudiantes" class="flex gap-2">
      <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Buscar..."
       class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none w-48">
      <button type="submit" class="bg-slate-900 text-white px-3 py-1.5 rounded-lg text-sm font-bold">Buscar</button>
      @if(!empty($search))
       <a href="/admin/estudiantes" class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-sm font-bold">Limpiar</a>
      @endif
     </form>
    </div>
    <div class="overflow-x-auto">
     <table class="w-full text-sm">
      <thead class="bg-slate-50 text-xs uppercase text-slate-500 tracking-wider">
       <tr>
        <th class="px-5 py-3 text-left">ID</th>
        <th class="px-5 py-3 text-left">Nombre Completo</th>
        <th class="px-5 py-3 text-left">Sección</th>
        <th class="px-5 py-3 text-center">Votó</th>
        <th class="px-5 py-3 text-center">Acción</th>
       </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
       @foreach ($students as $st)
        <tr class="hover:bg-slate-50 transition-colors">
         <td class="px-5 py-3 font-mono font-bold text-slate-700">{{ $st->identificacion }}</td>
         <td class="px-5 py-3 text-slate-800">{{ $st->nombre }} {{ $st->apellidos }}</td>
         <td class="px-5 py-3 text-slate-500">{{ $st->seccion }}</td>
         <td class="px-5 py-3 text-center">
          @if($st->voto == 1)
           <span
            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-700">Sí</span>
          @else
           <span
            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">No</span>
          @endif
         </td>
          <td class="px-5 py-3 text-center">
           <div class="flex items-center justify-center gap-2">
            <a href="{{ route('admin.students.edit', $st->id) }}" class="text-slate-400 hover:text-teal-600 transition-colors" title="Editar">
             <i class="ph ph-pencil-simple text-lg"></i>
            </a>
            @if($st->voto == 0)
              <form method="POST" action="/admin/estudiantes/{{ $st->id }}" class="inline"
               onsubmit="return confirm('¿Eliminar este estudiante?')">
               @csrf
               @method('DELETE')
               <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Eliminar">
                <i class="ph ph-trash text-lg"></i>
               </button>
              </form>
            @else
             <span class="text-slate-300"><i class="ph ph-lock-simple text-lg"></i></span>
            @endif
           </div>
          </td>
        </tr>
       @endforeach
      </tbody>
     </table>
    </div>
    {{-- Paginación --}}
    @if($students->hasPages())
    <div class="p-4 border-t border-slate-100 flex justify-center">
     {{ $students->links() }}
    </div>
    @endif
   </div>
  </div>
 </div>

 <!-- Modal Importar Excel/CSV -->
 <div id="importModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
   <div class="p-6">
    <div class="flex justify-between items-start mb-4">
     <div>
      <h3 class="text-lg font-bold text-slate-800">Importar Padrón Electoral</h3>
      <p class="text-sm text-slate-500 mt-1">Carga un archivo Excel o CSV con los estudiantes</p>
     </div>
     <button onclick="document.getElementById('importModal').classList.add('hidden')"
      class="text-slate-400 hover:text-slate-600 transition-colors">
      <i class="ph ph-x text-xl"></i>
     </button>
    </div>

    <form method="POST" action="/admin/estudiantes/importar" enctype="multipart/form-data" class="space-y-4">
     @csrf
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Archivo</label>
      <input type="file" name="archivo_csv" required accept=".xlsx,.xls,.csv,.txt"
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
     </div>

     <div class="bg-slate-50 rounded-xl p-4">
      <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Formato esperado:</p>
      <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
       <table class="w-full text-xs">
        <thead class="bg-slate-100">
         <tr>
          <th class="px-3 py-1.5 text-left font-bold text-slate-600">identificacion</th>
          <th class="px-3 py-1.5 text-left font-bold text-slate-600">nombre</th>
          <th class="px-3 py-1.5 text-left font-bold text-slate-600">apellidos</th>
          <th class="px-3 py-1.5 text-left font-bold text-slate-600">seccion</th>
         </tr>
        </thead>
        <tbody>
         <tr class="border-t border-slate-200">
          <td class="px-3 py-1.5 font-mono text-slate-700">10012345</td>
          <td class="px-3 py-1.5 text-slate-700">Juan</td>
          <td class="px-3 py-1.5 text-slate-700">Pérez López</td>
          <td class="px-3 py-1.5 text-slate-700">10A</td>
         </tr>
        </tbody>
       </table>
      </div>
      <p class="text-xs text-slate-400 mt-2">La primera fila debe contener los encabezados.</p>
      <p class="text-xs text-slate-400">Formatos: <strong>.xlsx</strong>, <strong>.xls</strong>, <strong>.csv</strong>, <strong>.txt</strong></p>
      <p class="text-xs text-slate-400">Separador CSV: punto y coma (;) o coma (,)</p>
     </div>

     <div class="flex items-start gap-2 bg-amber-50 rounded-xl p-3">
      <i class="ph ph-info text-amber-600 mt-0.5 shrink-0"></i>
      <p class="text-xs text-amber-700">Los estudiantes con la misma identificación serán actualizados. Se permite un máximo de 2,000 registros.</p>
     </div>

     <div class="flex gap-3">
      <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
       class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl transition-all text-sm">
       Cancelar
      </button>
      <button type="submit"
       class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
       Importar
      </button>
     </div>
    </form>
   </div>
  </div>
 </div>

@endsection
