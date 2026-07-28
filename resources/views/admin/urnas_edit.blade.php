@extends('layouts.admin')

@section('title', 'Editar Urna / Terminal')

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
   <h2 class="text-lg font-bold text-slate-800 mb-4">Editar Urna / Terminal</h2>
   <form method="POST" action="{{ route('admin.urnas.update', $urna->id) }}" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Código de Terminal</label>
     <input type="text" name="codigo" required value="{{ $urna->codigo }}" maxlength="10"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none uppercase font-mono"
      placeholder="URN01">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Mesa Asociada (opcional)</label>
     <select name="mesa_id"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
      <option value="">Sin mesa</option>
      @foreach($mesas as $mesa)
       <option value="{{ $mesa->id }}" {{ $urna->id_mesa == $mesa->id ? 'selected' : '' }}>
        Mesa N° {{ $mesa->numero }}
       </option>
      @endforeach
     </select>
    </div>
    <div class="flex gap-3 pt-2">
     <button type="submit"
      class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition">
      Guardar Cambios
     </button>
     <a href="{{ route('admin.urnas') }}"
      class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-4 rounded-xl text-sm text-center transition">
      Cancelar
     </a>
    </div>
   </form>
  </div>
 </div>

@endsection
