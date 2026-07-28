@extends('layouts.admin')

@section('title', 'Tribunal Electoral Estudiantil')

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
          <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
            <i class="ph ph-scales text-xl"></i>
          </div>
          <div>
            <h2 class="text-md font-bold text-slate-800">Agregar Miembro TEE</h2>
            <p class="text-xs text-slate-500">Asignar estudiante al tribunal</p>
          </div>
        </div>

        <form method="POST" action="/admin/tee" class="space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Puesto en el TEE</label>
            <input type="text" name="puesto" required placeholder="Ej: Presidente TEE"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Estudiante</label>
            <select name="student_id" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
              <option value="">-- Seleccionar --</option>
              @foreach($students as $st)
                <option value="{{ $st->id }}">{{ $st->identificacion }} — {{ $st->apellidos }} {{ $st->nombre }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
            Agregar al TEE
          </button>
        </form>
      </div>
    </div>

    <!-- Listado -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
          <h2 class="text-base font-bold text-slate-800 flex items-center">
            <i class="ph ph-scales text-xl mr-2 text-slate-500"></i>
            Miembros del TEE ({{ $teeMembers->count() }})
          </h2>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 tracking-wider">
              <tr>
                <th class="px-5 py-3 text-left">Puesto</th>
                <th class="px-5 py-3 text-left">Estudiante</th>
                <th class="px-5 py-3 text-left">Sección</th>
                <th class="px-5 py-3 text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse($teeMembers as $tm)
              <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                    {{ $tm->puesto }}
                  </span>
                </td>
                <td class="px-5 py-3 font-medium text-slate-800">
                   {{ $tm->student?->nombre }} {{ $tm->student?->apellidos }}
                 </td>
                 <td class="px-5 py-3 text-slate-500">{{ $tm->student?->seccion }}</td>
                <td class="px-5 py-3 text-center">
                  <form method="POST" action="/admin/tee/{{ $tm->id }}" class="inline"
                    onsubmit="return confirm('¿Remover este miembro del TEE?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                      <i class="ph ph-trash text-lg"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                  <i class="ph ph-scales text-3xl mb-2 block"></i>
                  <p class="text-sm">No hay miembros del TEE registrados.</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

@endsection
