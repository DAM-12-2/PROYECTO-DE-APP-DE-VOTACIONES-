@extends('layouts.admin')

@section('title', 'Gestión de Mesas Electorales (JRV)')

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
    <!-- Panel Lateral: Crear Mesa -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600">
            <i class="ph ph-plus-circle text-xl"></i>
          </div>
          <div>
            <h2 class="text-md font-bold text-slate-800">Nueva Mesa Electoral</h2>
            <p class="text-xs text-slate-500">Registrar una Junta Receptora (JRV)</p>
          </div>
        </div>

        <form method="POST" action="/admin/mesas" class="space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Número de Mesa</label>
            <input type="number" name="numero" required min="1"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
              placeholder="Ej: 1">
          </div>
          <button type="submit"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
            Crear Mesa Electoral
          </button>
        </form>
      </div>

      <div class="bg-slate-900 rounded-2xl shadow-sm border border-slate-800 p-6 text-white">
        <h3 class="text-sm font-bold text-teal-400 uppercase tracking-wider mb-2">Información sobre JRV</h3>
        <p class="text-xs text-slate-300 leading-relaxed mb-3">
          Cada mesa electoral (JRV) puede procesar el voto de los alumnos matriculados en las secciones académicas
          vinculadas.
        </p>
        <p class="text-xs text-slate-300 leading-relaxed">
          Asegúrese de vincular las secciones antes del inicio del proceso. El padrón se filtrará de acuerdo a estas
          reglas.
        </p>
      </div>
    </div>

    <!-- Panel Central: Lista de Mesas -->
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
          <h2 class="text-base font-bold text-slate-800 flex items-center">
            <i class="ph ph-list-bullets text-xl mr-2 text-slate-500"></i>
            Juntas Receptoras Activas ({{ $mesas->count() }})
          </h2>
        </div>

        @if($mesas->isEmpty())
          <div class="p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-400">
              <i class="ph ph-warning text-3xl"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-700">No hay mesas electorales</h3>
            <p class="text-xs text-slate-500 mt-1">Crea tu primera mesa usando el panel de la izquierda.</p>
          </div>
        @else
          <div class="divide-y divide-slate-100">
            @foreach($mesas as $mesa)
              <div class="p-6 hover:bg-slate-50 transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                  <div>
                    <div class="flex items-center space-x-2">
                      <span class="text-lg font-bold text-slate-800">Mesa Electoral N° {{ $mesa->numero }}</span>
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-700">
                        Activa
                      </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                      Control de terminales y actas para el día electoral.
                    </p>
                  </div>
                  <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.mesas.edit', $mesa->id) }}"
                      class="bg-slate-50 hover:bg-slate-100 text-slate-600 p-2 rounded-xl border border-slate-100 transition-colors text-xs font-semibold flex items-center">
                      <i class="ph ph-pencil-simple text-base mr-1"></i> Editar
                    </a>
                    <form method="POST" action="/admin/mesas/{{ $mesa->id }}" class="inline"
                      onsubmit="return confirm('¿Eliminar esta mesa y desvincular todas sus secciones?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-xl border border-red-100 transition-colors text-xs font-semibold flex items-center">
                        <i class="ph ph-trash text-base mr-1"></i> Eliminar
                      </button>
                    </form>
                  </div>
                </div>

                <!-- Grid de Secciones Vinculadas y Miembros JRV -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-slate-100">
                  <!-- Secciones -->
                  <div>
                    <div class="flex items-center justify-between mb-3">
                      <span class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">Secciones Vinculadas</span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-3 drop-zone" data-mesa-id="{{ $mesa->id }}">
                      @forelse($mesa->secciones as $secc)
                        <span
                          draggable="true"
                          data-seccion-id="{{ $secc->id }}"
                          class="draggable-seccion inline-flex items-center bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-lg border border-slate-200 cursor-grab active:cursor-grabbing hover:bg-teal-50 hover:border-teal-300 transition-colors">
                          <i class="ph ph-dots-six-vertical text-slate-400 mr-1"></i>
                          {{ $secc->seccion }}
                          <form method="POST" action="/admin/secciones/{{ $secc->id }}" class="inline ml-1.5"
                            onsubmit="return confirm('¿Desvincular sección?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors">
                              <i class="ph ph-x-circle text-sm"></i>
                            </button>
                          </form>
                        </span>
                      @empty
                        <span class="text-xs text-slate-400 no-sections-msg">Sin secciones asignadas</span>
                      @endforelse
                      <span class="text-xs text-teal-400 hidden drop-hint">Soltar aquí...</span>
                    </div>

                    <!-- Formulario rápido para agregar sección -->
                    <form method="POST" action="/admin/mesas/{{ $mesa->id }}/secciones" class="flex gap-2">
                      @csrf
                      <input type="text" name="seccion" required placeholder="Ej: 11-A"
                        class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:ring-2 focus:ring-teal-500 outline-none w-32 uppercase">
                      <button type="submit"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold transition-colors">
                        Vincular
                      </button>
                    </form>
                  </div>

                  <!-- Miembros JRV -->
                  <div>
                    <div class="flex items-center justify-between mb-3">
                      <span class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">Tribunal de Mesa
                        (JRV)</span>
                    </div>

                    <!-- Lista de miembros -->
                    <div class="space-y-2 text-xs">
                      @forelse($mesa->miembros as $miembro)
                        <div class="flex items-center justify-between bg-slate-50 p-2 rounded-lg border border-slate-150">
                          <div>
                            @php
                             $puestoLabels = [0 => 'Miembro', 1 => 'Fiscal', 2 => 'Auxiliar', 3 => 'Presidente JRV'];
                             $puestoLabel = $puestoLabels[$miembro->puesto] ?? 'Miembro';
                            @endphp
                            <span class="font-bold text-slate-700">{{ $puestoLabel }}</span>
                            @if($miembro->student)
                            <span class="text-slate-500 ml-1">({{ $miembro->student->nombre }}
                              {{ $miembro->student->apellidos }})</span>
                            @else
                            <span class="text-slate-400 ml-1">(Sin estudiante)</span>
                            @endif
                          </div>
                          @if($miembro->party)
                            <span
                              class="font-mono text-teal-600 font-bold bg-teal-50 px-1.5 py-0.5 rounded">{{ $miembro->party->siglas }}</span>
                          @endif
                        </div>
                      @empty
                        <p class="text-xs text-slate-400">Sin miembros asignados en el Seeder o base de datos.</p>
                      @endforelse
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
   let draggedSeccionId = null;

   document.querySelectorAll('.draggable-seccion').forEach(el => {
    el.addEventListener('dragstart', e => {
     draggedSeccionId = e.target.dataset.seccionId;
     e.target.classList.add('opacity-50');
     e.dataTransfer.effectAllowed = 'move';
    });
    el.addEventListener('dragend', e => {
     e.target.classList.remove('opacity-50');
     document.querySelectorAll('.drop-hint').forEach(h => h.classList.add('hidden'));
     document.querySelectorAll('.drop-zone').forEach(z => z.classList.remove('bg-teal-50', 'border-teal-300'));
    });
   });

   document.querySelectorAll('.drop-zone').forEach(zone => {
    zone.addEventListener('dragover', e => {
     e.preventDefault();
     e.dataTransfer.dropEffect = 'move';
     zone.classList.add('bg-teal-50', 'border-teal-300');
     zone.querySelector('.drop-hint')?.classList.remove('hidden');
    });
    zone.addEventListener('dragleave', e => {
     zone.classList.remove('bg-teal-50', 'border-teal-300');
     zone.querySelector('.drop-hint')?.classList.add('hidden');
    });
    zone.addEventListener('drop', e => {
     e.preventDefault();
     const mesaDestinoId = zone.dataset.mesaId;
     if (!draggedSeccionId || !mesaDestinoId) return;

     fetch('/admin/secciones/mover', {
      method: 'POST',
      headers: {
       'Content-Type': 'application/json',
       'Accept': 'application/json',
       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ seccion_id: draggedSeccionId, mesa_destino_id: mesaDestinoId })
     })
     .then(r => r.json())
     .then(data => {
      if (data.success) {
       location.reload();
      } else {
       alert(data.message || 'Error al mover sección');
      }
     })
     .catch(() => alert('Error de red'));
    });
   });
  </script>

@endsection
