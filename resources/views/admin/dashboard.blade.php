@extends('layouts.admin')

@section('title', 'Monitor de Votación')

@section('content')

<!-- Header Estadísticas Dinámicas -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center">
        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-4 shrink-0">
            <i class="ph-fill ph-check-square-offset text-2xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase">Votos Emitidos</p>
            <p class="text-xl md:text-2xl font-black text-slate-800" data-stat="votaron">{{ $votaron }}</p>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center">
        <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 mr-4 shrink-0">
            <i class="ph-fill ph-users-three text-2xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase">Faltantes</p>
            <p class="text-xl md:text-2xl font-black text-slate-800" data-stat="faltantes">{{ $faltantes }}</p>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center">
        <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 mr-4 shrink-0">
            <i class="ph-fill ph-chart-line-up text-2xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase">Participación</p>
            <p class="text-xl md:text-2xl font-black text-slate-800" data-stat="participacion">{{ $participacion }}%</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Columna Izquierda: Control de Votantes -->
    <div class="lg:col-span-1 border border-slate-200 bg-white rounded-2xl shadow-sm p-6 flex flex-col" style="max-height: calc(100vh - 12rem);">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <i class="ph-fill ph-user-circle-plus text-teal-500 text-xl mr-2"></i>
            Control de Votantes
        </h2>

        <!-- Buscador AJAX -->
        <div class="mb-4">
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-3 text-slate-400"></i>
                <input type="text" id="busquedaEstudiante" placeholder="Buscar por ID o Nombre..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
            </div>
        </div>

        <!-- Lista de estudiantes que NO han votado -->
        <div class="flex-1 overflow-y-auto mb-4 border border-slate-100 rounded-lg divide-y divide-slate-100" id="listaEstudiantes">
            @foreach ($students as $st)
            <div class="p-3 hover:bg-slate-50 cursor-pointer flex justify-between items-center group transition-colors student-row"
                 data-id="{{ $st->id }}" data-name="{{ $st->nombre }} {{ $st->apellidos }}" data-ident="{{ $st->identificacion }}" data-section="{{ $st->seccion }}"
                 onclick="selectStudent(this)">
                <div>
                    <p class="text-sm font-bold text-slate-700">{{ $st->identificacion }}</p>
                    <p class="text-xs text-slate-500">{{ $st->nombre }} {{ $st->apellidos }} ({{ $st->seccion }})</p>
                </div>
                <div class="w-2 h-2 rounded-full bg-slate-300 group-hover:bg-teal-400 transition-colors shrink-0"></div>
            </div>
            @endforeach
            @if($students->isEmpty())
            <div class="p-6 text-center text-slate-400 text-sm">
                <i class="ph ph-check-circle text-3xl mb-2 block"></i>
                Todos los estudiantes han votado.
            </div>
            @endif
        </div>

        <!-- Acción: Asignar a Urna -->
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mt-auto">
            <p class="text-xs text-slate-500 font-bold mb-1 uppercase tracking-wider">Seleccionado</p>
            <p id="selectedStudentInfo" class="font-medium text-slate-800 mb-3 truncate">Ninguno</p>
            <input type="hidden" id="selectedStudentId" value="">
            
            <div class="flex flex-col sm:flex-row gap-2">
                <select id="selectUrna" class="flex-1 bg-white border border-slate-300 text-sm rounded-lg px-2 py-2 shadow-sm focus:ring-teal-500 outline-none min-w-0">
                    <option value="">-- Elige Urna --</option>
                    @foreach ($urnas as $u)
                    <option value="{{ $u->id }}" {{ $u->estado == 2 ? 'disabled' : '' }}>
                        {{ $u->codigo }} {{ $u->estado == 2 ? '(En uso)' : '' }}
                    </option>
                    @endforeach
                </select>
                <button onclick="activarPapeleta()" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg shadow-sm transition-colors font-bold text-sm">
                    Activar
                </button>
            </div>
        </div>
    </div>

    <!-- Columna Derecha: Estado de Terminales -->
    <div class="lg:col-span-2 space-y-6">
        <h2 class="text-lg font-bold text-slate-800 flex items-center border-b border-slate-200 pb-3">
            <i class="ph-fill ph-desktop text-slate-400 mr-2 text-xl"></i>
            Estado de Terminales Conectadas
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="urnasGrid">
            @foreach ($urnas as $u)
            <div class="bg-white p-5 rounded-2xl shadow-sm border {{ $u->estado == 2 ? 'border-teal-300' : 'border-slate-200' }} relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full {{ $u->estado == 2 ? 'bg-teal-500' : 'bg-slate-300' }}"></div>
                <div class="flex justify-between items-start mb-2 pl-3">
                    <div>
                        <h3 class="font-bold text-slate-800">Urna {{ $u->codigo }}</h3>
                        <p class="text-xs font-bold uppercase {{ $u->estado == 2 ? 'text-teal-600' : 'text-slate-400' }}">
                            {{ $u->estado == 2 ? 'Votando' : 'Disponible' }}
                        </p>
                    </div>
                    <div class="w-3 h-3 rounded-full {{ $u->estado == 2 ? 'bg-teal-500 animate-pulse' : 'bg-slate-300' }}"></div>
                </div>
                <div class="pl-3 mt-3">
                    <p class="text-sm font-medium text-slate-700">
                        @if($u->student)
                            {{ $u->student->identificacion }} — {{ $u->student->nombre }} {{ $u->student->apellidos }}
                        @else
                            Sin estudiante asignado
                        @endif
                    </p>
                </div>
                @if($u->estado == 2)
                <div class="pl-3 mt-4">
                    <button class="px-3 py-1.5 text-xs font-bold bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" onclick="desactivarUrna({{ $u->id }})">
                        Forzar Reinicio
                    </button>
                </div>
                @endif
            </div>
            @endforeach

            @if($urnas->isEmpty())
            <div class="col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-200 text-center text-slate-400">
                <i class="ph ph-desktop text-4xl mb-2"></i>
                <p class="text-sm">No hay urnas registradas. <a href="/admin/urnas" class="text-teal-600 font-bold hover:underline">Crear una</a></p>
            </div>
            @endif
        </div>

        {{-- Gráfica de votos por sección --}}
        @if(count($secciones) > 0)
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mt-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                <i class="ph-fill ph-chart-bar text-slate-400 mr-2 text-xl"></i>
                Votos por Sección / Grado
            </h2>
            <div style="height: 300px;">
                <canvas id="chartSecciones"></canvas>
            </div>
        </div>
        @endif

        {{-- Métricas adicionales en tiempo real --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 text-center">
          <p class="text-2xl font-black text-slate-800" data-stat="totalStudents">{{ $totalStudents }}</p>
          <p class="text-xs font-bold text-slate-400 uppercase mt-1">Total Padrón</p>
         </div>
         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 text-center">
          <p class="text-2xl font-black text-teal-600" data-stat="urnasActivas">{{ $urnas->where('estado', 2)->count() }}</p>
          <p class="text-xs font-bold text-slate-400 uppercase mt-1">Urnas Activas</p>
         </div>
         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 text-center">
          @php
           $eleccionAbierta = \App\Models\Setting::where('nombre', 'eleccion_abierta')->value('detalle') ?? '0';
          @endphp
          <p class="text-2xl font-black {{ $eleccionAbierta == '1' ? 'text-green-600' : 'text-red-500' }}">{{ $eleccionAbierta == '1' ? 'ABIERTA' : 'CERRADA' }}</p>
          <p class="text-xs font-bold text-slate-400 uppercase mt-1">Estado Elección</p>
         </div>
         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 text-center">
          <p class="text-2xl font-black text-indigo-600">{{ $urnas->count() }}</p>
          <p class="text-xs font-bold text-slate-400 uppercase mt-1">Urnas Totales</p>
         </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Gráfica de votos por sección
    const seccionesData = @json($secciones);
    if (seccionesData.length > 0) {
        const ctx = document.getElementById('chartSecciones');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: seccionesData.map(s => s.seccion + '°'),
                    datasets: [
                        {
                            label: 'Total Estudiantes',
                            data: seccionesData.map(s => s.total),
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Votos Registrados',
                            data: seccionesData.map(s => s.votaron),
                            backgroundColor: 'rgba(20, 184, 166, 0.7)',
                            borderColor: 'rgba(20, 184, 166, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    }

    // ── Función para refrescar datos del dashboard ──
    function refreshDashboardData(data) {
        document.querySelector('[data-stat="votaron"]').textContent = data.votaron;
        document.querySelector('[data-stat="faltantes"]').textContent = data.faltantes;
        document.querySelector('[data-stat="participacion"]').textContent = data.participacion + '%';
        document.querySelector('[data-stat="urnasActivas"]').textContent = data.urnasActivas;

        const urnasContainer = document.getElementById('urnasGrid');
        if (urnasContainer) {
            let html = '';
            data.urnas.forEach(u => {
                const isActive = u.estado == 2;
                const codigo = escapeHtml(u.codigo);
                const studentInfo = u.student ? escapeHtml(u.student.identificacion) + ' — ' + escapeHtml(u.student.nombre) + ' ' + escapeHtml(u.student.apellidos) : 'Sin estudiante asignado';
                html += `
                    <div class="bg-white p-5 rounded-2xl shadow-sm border ${isActive ? 'border-teal-300' : 'border-slate-200'} relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full ${isActive ? 'bg-teal-500' : 'bg-slate-300'}"></div>
                        <div class="flex justify-between items-start mb-2 pl-3">
                            <div>
                                <h3 class="font-bold text-slate-800">Urna ${codigo}</h3>
                                <p class="text-xs font-bold uppercase ${isActive ? 'text-teal-600' : 'text-slate-400'}">
                                    ${isActive ? 'Votando' : 'Disponible'}
                                </p>
                            </div>
                            <div class="w-3 h-3 rounded-full ${isActive ? 'bg-teal-500 animate-pulse' : 'bg-slate-300'}"></div>
                        </div>
                        <div class="pl-3 mt-3">
                            <p class="text-sm font-medium text-slate-700">
                                ${studentInfo}
                            </p>
                        </div>
                        ${isActive ? `
                        <div class="pl-3 mt-4">
                            <button class="px-3 py-1.5 text-xs font-bold bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" onclick="desactivarUrna(${u.id})">
                                Forzar Reinicio
                            </button>
                        </div>
                        ` : ''}
                    </div>
                `;
            });
            if (data.urnas.length === 0) {
                html = `<div class="col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-200 text-center text-slate-400">
                    <i class="ph ph-desktop text-4xl mb-2"></i>
                    <p class="text-sm">No hay urnas registradas. <a href="/admin/urnas" class="text-teal-600 font-bold hover:underline">Crear una</a></p>
                </div>`;
            }
            urnasContainer.innerHTML = html;
        }

        const studentsContainer = document.getElementById('listaEstudiantes');
        if (studentsContainer) {
            let html = '';
            data.students.forEach(st => {
                const ident = escapeHtml(st.identificacion);
                const nombre = escapeHtml(st.nombre);
                const apellidos = escapeHtml(st.apellidos);
                const seccion = escapeHtml(st.seccion);
                html += `
                    <div class="p-3 hover:bg-slate-50 cursor-pointer flex justify-between items-center group transition-colors student-row"
                         data-id="${st.id}" data-name="${nombre} ${apellidos}" data-ident="${ident}" data-section="${seccion}"
                         onclick="selectStudent(this)">
                        <div>
                            <p class="text-sm font-bold text-slate-700">${ident}</p>
                            <p class="text-xs text-slate-500">${nombre} ${apellidos} (${seccion})</p>
                        </div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 group-hover:bg-teal-400 transition-colors shrink-0"></div>
                    </div>
                `;
            });
            if (data.students.length === 0) {
                html = `<div class="p-6 text-center text-slate-400 text-sm">
                    <i class="ph ph-check-circle text-3xl mb-2 block"></i>
                    Todos los estudiantes han votado.
                </div>`;
            }
            studentsContainer.innerHTML = html;
        }
    }

    function fetchDashboardData() {
        fetch('/admin/dashboard/data', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => refreshDashboardData(data))
        .catch(() => {});
    }

    // ── WebSocket con fallback a AJAX polling ──
    let ws = null;
    let wsConnected = false;
    let pollInterval = null;

    function connectWebSocket() {
        try {
            const wsUrl = 'ws://' + window.location.hostname + ':8080';
            ws = new WebSocket(wsUrl);

            ws.onopen = function() {
                wsConnected = true;
                console.log('WebSocket conectado');
                if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
            };

            ws.onmessage = function(evt) {
                try {
                    const msg = JSON.parse(evt.data);
                    if (msg.tipo === 6 || msg.tipo === 4 || msg.tipo === 5) {
                        // Voto registrado o urna cambio -> refrescar datos
                        fetchDashboardData();
                    }
                } catch(e) {}
            };

            ws.onclose = function() {
                wsConnected = false;
                console.log('WebSocket desconectado, usando AJAX polling');
                startPolling();
            };

            ws.onerror = function() {
                wsConnected = false;
                ws = null;
                startPolling();
            };
        } catch(e) {
            startPolling();
        }
    }

    function startPolling() {
        if (!pollInterval) {
            pollInterval = setInterval(fetchDashboardData, 5000);
        }
    }

    // Intentar WebSocket, si falla usar polling
    connectWebSocket();
    setTimeout(() => {
        if (!wsConnected) startPolling();
    }, 3000);

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function selectStudent(el) {
        document.querySelectorAll('.student-row').forEach(r => r.classList.remove('bg-teal-50'));
        el.classList.add('bg-teal-50');
        document.getElementById('selectedStudentId').value = el.dataset.id;
        document.getElementById('selectedStudentInfo').textContent = el.dataset.ident + ' — ' + el.dataset.name;
    }

    // Filtro local de búsqueda
    document.getElementById('busquedaEstudiante').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.student-row').forEach(row => {
            const text = (row.dataset.ident + ' ' + row.dataset.name + ' ' + row.dataset.section).toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });

    function activarPapeleta() {
        const idUrna = document.getElementById('selectUrna').value;
        const idEstudiante = document.getElementById('selectedStudentId').value;
        if (!idUrna || !idEstudiante) {
            alert('Selecciona un estudiante y una urna.');
            return;
        }

        fetch('/admin/api/urna/activar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ idUrna: idUrna, idEstudiante: idEstudiante })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('⚠️ ' + data.message);
            }
        })
        .catch(err => alert('Error de red: ' + err));
    }

    function desactivarUrna(id) {
        if (!confirm('¿Forzar reinicio de esta urna? El voto en progreso se cancelará.')) return;

        fetch('/admin/api/urna/desactivar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ idUrna: id })
        })
        .then(r => r.json())
        .then(data => {
            alert('✅ ' + data.message);
            location.reload();
        })
        .catch(err => alert('Error: ' + err));
    }
</script>
@endpush
