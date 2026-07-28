@extends('layouts.admin')

@section('title', 'Editar Partido')

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
   <h2 class="text-lg font-bold text-slate-800 mb-4">Editar Partido</h2>
   <form method="POST" action="{{ route('admin.parties.update', $party->id) }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Siglas</label>
     <input type="text" name="siglas" required value="{{ $party->siglas }}" maxlength="20"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none uppercase"
      placeholder="MOVI">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre del Partido</label>
     <input type="text" name="nombre" required value="{{ $party->nombre }}" maxlength="150"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
      placeholder="Movimiento Independiente">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Bandera (opcional, JPG/PNG)</label>
     <input type="file" name="bandera" accept="image/jpeg,image/png"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
     @if($party->bandera)
      <p class="text-xs text-slate-400 mt-1">Actual: {{ basename($party->bandera) }}</p>
     @endif
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Foto Presidente (opcional, JPG/PNG)</label>
     <input type="file" name="fotopresidente" accept="image/jpeg,image/png"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
     @if($party->fotopresidente)
      <p class="text-xs text-slate-400 mt-1">Actual: {{ basename($party->fotopresidente) }}</p>
     @endif
    </div>
    <div class="flex gap-3 pt-2">
     <button type="submit"
      class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition">
      Guardar Cambios
     </button>
     <a href="{{ route('admin.parties') }}"
      class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-4 rounded-xl text-sm text-center transition">
      Cancelar
     </a>
    </div>
   </form>
  </div>
 </div>

@endsection
