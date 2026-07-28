@extends('layouts.admin')

@section('title', 'Control de Planillas y Candidaturas')

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

    <!-- Formularios a la izquierda -->
    <div class="lg:col-span-1 space-y-6">

      <!-- Crear Puesto -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
            <i class="ph ph-briefcase-metal text-xl"></i>
          </div>
          <div>
            <h2 class="text-md font-bold text-slate-800">Crear Puesto</h2>
            <p class="text-xs text-slate-500">Cargos representativos del TEE</p>
          </div>
        </div>

        <form method="POST" action="/admin/puestos" class="space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre del Puesto</label>
            <input type="text" name="nombre" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
              placeholder="Ej: Presidencia">
          </div>
          <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl shadow-md transition-all text-sm">
            Registrar Puesto
          </button>
        </form>
      </div>

      <!-- Inscribir Candidato -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
            <i class="ph ph-user-plus text-xl"></i>
          </div>
          <div>
            <h2 class="text-md font-bold text-slate-800">Inscribir Candidato</h2>
            <p class="text-xs text-slate-500">Asignar alumno a puesto y partido</p>
          </div>
        </div>

        <form method="POST" action="/admin/candidatos" enctype="multipart/form-data" class="space-y-4">
          @csrf

          <!-- Estudiante -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Estudiante</label>
            <select name="student_id" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
              <option value="">Seleccione un estudiante...</option>
              @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->apellidos }}, {{ $student->nombre }}
                  ({{ $student->seccion }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Partido -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Partido Político</label>
            <select name="party_id" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
              <option value="">Seleccione un partido...</option>
              @foreach($parties as $party)
                <option value="{{ $party->id }}">{{ $party->siglas }} - {{ $party->nombre }}</option>
              @endforeach
            </select>
          </div>

          <!-- Puesto -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Puesto Electivo</label>
            <select name="puesto_id" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
              <option value="">Seleccione un puesto...</option>
              @foreach($puestos as $puesto)
                <option value="{{ $puesto->id }}">{{ $puesto->nombre }}</option>
              @endforeach
            </select>
          </div>

          <!-- Foto del Candidato (Opcional) -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Foto del Candidato (Opcional)</label>
            <div class="flex items-center space-x-3">
              <img id="candidatoPreview" src="{{ asset('storage/img/placeholder-presidente.svg') }}" class="w-14 h-14 rounded-lg object-cover border border-slate-200" alt="Foto">
              <label for="foto_candidato" class="cursor-pointer">
                <span class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold px-3 py-2 rounded-lg transition-colors">
                  Seleccionar archivo
                </span>
                <input type="file" name="foto" id="foto_candidato" accept="image/jpeg,image/png" class="hidden" onchange="previewFoto(this)">
              </label>
            </div>
            <p class="text-xs text-slate-400 mt-1">JPG o PNG, máx. 2MB</p>
          </div>

          <button type="submit"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
            Inscribir Candidatura
          </button>
        </form>
      </div>
    </div>

    <!-- Tabla / Listado a la derecha -->
    <div class="lg:col-span-2 space-y-6">

      @forelse($puestos as $puesto)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div>
              <h3 class="text-base font-bold text-slate-800 flex items-center">
                <i class="ph ph-award text-xl mr-2 text-indigo-500"></i>
                {{ $puesto->nombre }}
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">Postulantes a este cargo representativo</p>
            </div>
            <div class="flex items-center gap-1">
             <a href="{{ route('admin.puestos.edit', $puesto->id) }}" class="text-slate-400 hover:text-teal-600 transition-colors" title="Editar Puesto">
              <i class="ph ph-pencil-simple text-lg"></i>
             </a>
              <form method="POST" action="/admin/puestos/{{ $puesto->id }}" class="inline"
                onsubmit="return confirm('¿Eliminar este puesto? Se eliminarán los candidatos asociados.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Eliminar Puesto">
                  <i class="ph ph-trash text-lg"></i>
                </button>
              </form>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50/50 text-xs uppercase text-slate-500 tracking-wider">
                <tr>
                  <th class="px-5 py-2.5 text-left">Candidato</th>
                  <th class="px-5 py-2.5 text-left">Sección</th>
                  <th class="px-5 py-2.5 text-left">Partido</th>
                  <th class="px-5 py-2.5 text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-150">
                @forelse($puesto->candidatos as $cand)
                  <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-3">
                      @if($cand->student)
                      <div class="flex items-center">
                        @if($cand->foto)
                           <img src="{{ asset($cand->foto) }}?{{ @filemtime(public_path($cand->foto)) }}" class="w-10 h-10 rounded-full object-cover mr-3 border border-slate-200" alt="Foto">
                        @else
                          <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-sm mr-3">
                            {{ substr($cand->student->nombre, 0, 1) }}{{ substr($cand->student->apellidos, 0, 1) }}
                          </div>
                        @endif
                        <div>
                          <div class="font-bold text-slate-700">{{ $cand->student->nombre }} {{ $cand->student->apellidos }}</div>
                          <div class="text-xs text-slate-400">ID: {{ $cand->student->identificacion }}</div>
                        </div>
                      </div>
                      @else
                      <span class="text-slate-400 text-xs">Sin estudiante</span>
                      @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 font-medium">
                      {{ $cand->student->seccion ?? '-' }}
                    </td>
                    <td class="px-5 py-3">
                      @if($cand->party)
                      <span
                        class="inline-flex items-center bg-indigo-50 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $cand->party->siglas }}
                      </span>
                      <span class="text-xs text-slate-500 ml-1.5">{{ $cand->party->nombre }}</span>
                      @else
                      <span class="text-slate-400 text-xs">Sin partido</span>
                      @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                      <form method="POST" action="/admin/candidatos/{{ $cand->id }}" class="inline"
                        onsubmit="return confirm('¿Remover esta candidatura?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors"
                          title="Remover Candidato">
                          <i class="ph ph-user-minus text-lg"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="px-5 py-6 text-center text-slate-400 text-xs">
                      No hay candidatos inscritos para este puesto representativo.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      @empty
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
          <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-400">
            <i class="ph ph-warning text-3xl"></i>
          </div>
          <h3 class="text-sm font-bold text-slate-700">No se han registrado Puestos</h3>
          <p class="text-xs text-slate-500 mt-1">Crea puestos de elección en el panel izquierdo (ej: Presidencia,
            Vicepresidencia, etc.)</p>
        </div>
      @endforelse

    </div>

  </div>

  <script>
    function previewFoto(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('candidatoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>

@endsection
