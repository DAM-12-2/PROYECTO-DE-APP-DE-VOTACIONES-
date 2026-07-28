@extends('layouts.admin')

@section('title', 'Terminales / Urnas Electrónicas')

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
    <h2 class="text-lg font-bold text-slate-800 mb-4">Nueva Terminal</h2>
    <form method="POST" action="/admin/urnas" class="space-y-4">
     @csrf
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Código de Terminal</label>
      <input type="text" name="codigo" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none uppercase font-mono text-center text-lg"
       placeholder="12A" maxlength="10">
      <p class="text-xs text-slate-400 mt-1">Este código se usa en la pantalla del kiosko para vincular la terminal.</p>
     </div>
     <button type="submit"
      class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
      Crear Terminal
     </button>
    </form>
   </div>
  </div>

  <!-- Listado -->
  <div class="lg:col-span-2">
   <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    @foreach ($urnas as $u)
     <div
      class="bg-white rounded-2xl shadow-sm border {{ $u->estado == 2 ? 'border-teal-300' : 'border-slate-200' }} p-5 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-1 h-full {{ $u->estado == 2 ? 'bg-teal-500' : 'bg-slate-300' }}"></div>
      <div class="pl-3 flex justify-between items-start">
       <div>
        <h3 class="font-bold text-slate-800 text-xl font-mono">{{ $u->codigo }}</h3>
        <p class="text-xs font-bold uppercase {{ $u->estado == 2 ? 'text-teal-600' : 'text-slate-400' }} mt-1">
         {{ $u->estado == 2 ? '● En uso' : '○ Disponible' }}
        </p>
        @if($u->student)
         <p class="text-sm text-slate-600 mt-2">{{ $u->student->nombre }} {{ $u->student->apellidos }}</p>
        @endif
       </div>
       <div class="flex items-center gap-1">
        <a href="{{ route('admin.urnas.edit', $u->id) }}" class="text-slate-400 hover:text-teal-600 transition-colors p-1" title="Editar">
         <i class="ph ph-pencil-simple text-lg"></i>
        </a>
        <form method="POST" action="/admin/urnas/{{ $u->id }}"
         onsubmit="return confirm('¿Eliminar terminal {{ $u->codigo }}?')">
         @csrf
         @method('DELETE')
         <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-1" title="Eliminar">
          <i class="ph ph-trash text-lg"></i>
         </button>
        </form>
       </div>
      </div>
     </div>
    @endforeach

    @if($urnas->isEmpty())
     <div class="col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-200 text-center text-slate-400">
      <i class="ph ph-desktop text-4xl mb-2"></i>
      <p>No hay terminales creadas.</p>
     </div>
    @endif
   </div>
  </div>
 </div>

@endsection
