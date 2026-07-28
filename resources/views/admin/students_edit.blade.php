@extends('layouts.admin')

@section('title', 'Editar Estudiante')

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

 <div class="max-w-xl">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
   <h2 class="text-lg font-bold text-slate-800 mb-4">Editar Estudiante</h2>
   <form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Identificación / DNI</label>
     <input type="text" name="identificacion" required value="{{ $student->identificacion }}"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
      placeholder="1001">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre</label>
     <input type="text" name="nombre" required value="{{ $student->nombre }}"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
      placeholder="Juan Antonio">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Apellidos</label>
     <input type="text" name="apellidos" required value="{{ $student->apellidos }}"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
      placeholder="Pérez López">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sección</label>
     <input type="text" name="seccion" required value="{{ $student->seccion }}"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
      placeholder="10-A">
    </div>
    <div class="flex gap-3 pt-2">
     <button type="submit"
      class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition">
      Guardar Cambios
     </button>
     <a href="{{ route('admin.students') }}"
      class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-4 rounded-xl text-sm text-center transition">
      Cancelar
     </a>
    </div>
   </form>
  </div>
 </div>

@endsection
