@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

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
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600">
            <i class="ph ph-user-plus text-xl"></i>
          </div>
          <div>
            <h2 class="text-md font-bold text-slate-800">Nuevo Usuario</h2>
            <p class="text-xs text-slate-500">Crear cuenta de acceso al sistema</p>
          </div>
        </div>

        <form method="POST" action="/admin/usuarios" class="space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre</label>
            <input type="text" name="name" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
              placeholder="Juan Pérez">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Correo Electrónico</label>
            <input type="email" name="email" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
              placeholder="usuario@votaciones.edu">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Contraseña</label>
            <input type="password" name="password" required minlength="6"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
              placeholder="Mínimo 6 caracteres">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rol</label>
            <select name="role" required id="selectRole"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
              <option value="admin">Administrador</option>
              <option value="tee">Tribunal Electoral (TEE)</option>
              <option value="jrv">Junta Receptora (JRV)</option>
            </select>
          </div>
          <div id="mesaField" class="hidden">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Mesa Asignada</label>
            <select name="mesa_id"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
              <option value="">-- Seleccionar Mesa --</option>
              @php $mesasList = \App\Models\Mesa::orderBy('numero')->get(); @endphp
              @foreach($mesasList as $m)
                <option value="{{ $m->id }}">Mesa N° {{ $m->numero }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
            Crear Usuario
          </button>
        </form>
      </div>
    </div>

    <!-- Listado -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
          <h2 class="text-base font-bold text-slate-800 flex items-center">
            <i class="ph ph-users text-xl mr-2 text-slate-500"></i>
            Usuarios del Sistema ({{ $usuarios->count() }})
          </h2>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 tracking-wider">
              <tr>
                <th class="px-5 py-3 text-left">Nombre</th>
                <th class="px-5 py-3 text-left">Email</th>
                <th class="px-5 py-3 text-left">Rol</th>
                <th class="px-5 py-3 text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach($usuarios as $u)
              <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3 font-medium text-slate-800">{{ $u->name }}</td>
                <td class="px-5 py-3 text-slate-500">{{ $u->email }}</td>
                <td class="px-5 py-3">
                  @php
                    $roleColors = ['admin' => 'bg-teal-100 text-teal-700', 'tee' => 'bg-indigo-100 text-indigo-700', 'jrv' => 'bg-amber-100 text-amber-700'];
                    $roleLabels = ['admin' => 'Administrador', 'tee' => 'TEE', 'jrv' => 'JRV'];
                  @endphp
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $roleColors[$u->role] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $roleLabels[$u->role] ?? $u->role }}
                  </span>
                </td>
                <td class="px-5 py-3 text-center">
                  <div class="flex items-center justify-center gap-2">
                   <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="text-slate-400 hover:text-teal-600 transition-colors" title="Editar">
                    <i class="ph ph-pencil-simple text-lg"></i>
                   </a>
                   @if($u->id != auth()->id())
                    <form method="POST" action="/admin/usuarios/{{ $u->id }}" class="inline"
                      onsubmit="return confirm('¿Eliminar este usuario?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                        <i class="ph ph-trash text-lg"></i>
                      </button>
                    </form>
                   @else
                   <span class="text-xs text-slate-400">Tú</span>
                   @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('selectRole').addEventListener('change', function() {
      document.getElementById('mesaField').classList.toggle('hidden', this.value !== 'jrv');
    });
  </script>

@endsection
