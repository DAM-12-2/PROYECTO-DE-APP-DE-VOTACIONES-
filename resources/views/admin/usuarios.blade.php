@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-primary">Gestión de Usuarios</h2>
    <p class="text-gray-500">Administración de acceso al sistema.</p>
</div>

@if(session('success'))
    <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- Cambiar Contraseña -->
    <div class="bg-white border rounded-xl overflow-hidden">
        <div class="border-b px-6 py-4 bg-gray-50 flex items-center gap-3">
            <h3 class="text-lg font-semibold text-primary">Cambiar Contraseña</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.change_password') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Contraseña Actual</label>
                        <input type="password" name="current_password" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nueva Contraseña</label>
                        <input type="password" name="new_password" required minlength="6" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Confirmar Nueva Contraseña</label>
                        <input type="password" name="new_password_confirmation" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg w-full">Actualizar Contraseña</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Crear Usuario -->
    <div class="bg-white border rounded-xl overflow-hidden">
        <div class="border-b px-6 py-4 bg-gray-50 flex items-center gap-3">
            <h3 class="text-lg font-semibold text-primary">Crear Usuario</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Contraseña</label>
                        <input type="password" name="password" required minlength="6" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Rol</label>
                        <select name="role" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="admin">Administrador</option>
                            <option value="tee">TEE (Tribunal)</option>
                            <option value="jrv">JRV</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Mesa (opcional)</label>
                        <select name="mesa_id" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Sin mesa</option>
                            @php $mesasList = \App\Models\Mesa::orderBy('nombre')->get(); @endphp
                            @foreach($mesasList as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg w-full">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lista de Usuarios -->
<div class="mt-8 bg-white border rounded-xl overflow-hidden">
    <div class="border-b px-6 py-4 bg-gray-50">
        <h3 class="text-lg font-semibold text-primary">Usuarios Existentes</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-sm font-medium">ID</th>
                    <th class="px-6 py-3 text-sm font-medium">Nombre</th>
                    <th class="px-6 py-3 text-sm font-medium">Rol</th>
                    <th class="px-6 py-3 text-sm font-medium">Mesa</th>
                    <th class="px-6 py-3 text-sm font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $u->id }}</td>
                        <td class="px-6 py-3 text-sm">{{ $u->name }}</td>
                        <td class="px-6 py-3 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($u->role === 'tee' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ strtoupper($u->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm">{{ $u->mesa_id ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm flex gap-2">
                            <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="text-blue-600 hover:underline">Editar</a>
                            @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.usuarios.destroy', $u->id) }}" class="inline" onsubmit="return confirm('¿Eliminar este usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
