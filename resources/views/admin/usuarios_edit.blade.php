@extends('layouts.admin')

@section('title', 'Editar Usuario')

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
   <h2 class="text-lg font-bold text-slate-800 mb-4">Editar Usuario</h2>
   <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre Completo</label>
     <input type="text" name="name" required value="{{ $usuario->name }}" maxlength="100"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
      placeholder="Juan Pérez">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Correo Electrónico</label>
     <input type="email" name="email" required value="{{ $usuario->email }}"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
      placeholder="usuario@colegio.edu">
    </div>
    <div>
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rol</label>
     <select name="role" id="editSelectRole"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
      <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>Administrador</option>
      <option value="tee" {{ $usuario->role === 'tee' ? 'selected' : '' }}>TEE (Tribunal Electoral)</option>
      <option value="jrv" {{ $usuario->role === 'jrv' ? 'selected' : '' }}>JRV (Junta Receptora)</option>
     </select>
    </div>
    <div id="editMesaField" class="{{ $usuario->role !== 'jrv' ? 'hidden' : '' }}">
     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Mesa Asignada</label>
     <select name="mesa_id"
      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
      <option value="">-- Seleccionar Mesa --</option>
      @php $mesasList = \App\Models\Mesa::orderBy('numero')->get(); @endphp
      @foreach($mesasList as $m)
       <option value="{{ $m->id }}" {{ ($usuario->mesa_id ?? null) == $m->id ? 'selected' : '' }}>Mesa N° {{ $m->numero }}</option>
      @endforeach
     </select>
    </div>
    <p class="text-xs text-slate-400">Para cambiar la contraseña, use la sección de Configuración.</p>
    <div class="flex gap-3 pt-2">
     <button type="submit"
      class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition">
      Guardar Cambios
     </button>
     <a href="{{ route('admin.usuarios') }}"
      class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-4 rounded-xl text-sm text-center transition">
      Cancelar
     </a>
    </div>
   </form>
  </div>
  </div>

  <script>
   document.getElementById('editSelectRole').addEventListener('change', function() {
    document.getElementById('editMesaField').classList.toggle('hidden', this.value !== 'jrv');
   });
  </script>

@endsection
