@extends('layouts.admin')

@section('title', 'Estudiantes — Sistema Electoral')

@section('content')
<div class="p-6 space-y-6">
    <x-flash-messages />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Estudiantes</h1>
            <p class="text-sm text-on-surface-variant mt-1">Padrón electoral del centro educativo.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Alta --}}
        <div class="bg-surface-container-low border border-outline-variant rounded-xl p-6">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">person_add</span> Agregar estudiante
            </h3>
            <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-on-surface-variant mb-1">Identificación</label>
                    <input type="text" name="identificacion" required value="{{ old('identificacion') }}"
                        class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2 outline-none focus:border-primary">
                    @error('identificacion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-on-surface-variant mb-1">Nombre</label>
                    <input type="text" name="nombre" required value="{{ old('nombre') }}"
                        class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2 outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-on-surface-variant mb-1">Apellidos</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                        class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2 outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-on-surface-variant mb-1">Sección</label>
                    <input type="text" name="seccion" value="{{ old('seccion') }}"
                        class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2 outline-none focus:border-primary">
                </div>
                <button type="submit"
                    class="w-full rounded-lg bg-primary px-4 py-2 font-semibold text-on-primary hover:bg-primary-container transition">
                    Guardar estudiante
                </button>
            </form>
        </div>

        {{-- Importación --}}
        <div class="bg-surface-container-low border border-outline-variant rounded-xl p-6 lg:col-span-2">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">upload_file</span> Importar padrón (PIAD)
            </h3>
            <p class="text-sm text-on-surface-variant mb-4">
                Formato CSV con columnas <code>identificacion,nombre,apellidos,seccion</code>. Máximo 10 MB.
            </p>
            <form method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data"
                class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="file" name="csv_file" accept=".csv,.txt" required
                    class="flex-1 rounded-lg border border-outline-variant bg-surface px-3 py-2 text-sm">
                <button type="submit"
                    class="rounded-lg bg-primary px-4 py-2 font-semibold text-on-primary hover:bg-primary-container transition">
                    Importar
                </button>
            </form>
            @error('csv_file') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Listado --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
        <div class="border-b border-outline-variant px-6 py-4 bg-surface-container-low flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.students') }}" class="flex gap-2 w-full sm:max-w-md">
                <input type="text" name="q" value="{{ $busqueda }}" placeholder="Buscar por cédula o nombre..."
                    class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2 outline-none focus:border-primary">
                <button type="submit"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-on-primary hover:bg-primary-container">
                    Buscar
                </button>
            </form>
            <span class="text-sm text-on-surface-variant">
                {{ $students->total() }} estudiante(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface border-b border-outline-variant text-sm text-on-surface-variant">
                        <th class="px-6 py-3 font-semibold">Cédula</th>
                        <th class="px-6 py-3 font-semibold">Nombre completo</th>
                        <th class="px-6 py-3 font-semibold">Sección</th>
                        <th class="px-6 py-3 font-semibold">Estado voto</th>
                        <th class="px-6 py-3 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr class="border-b border-outline-variant/50 hover:bg-surface-container-low/50">
                            <td class="px-6 py-3 font-mono text-sm">{{ $student->identificacion }}</td>
                            <td class="px-6 py-3">{{ $student->nombre }} {{ $student->apellidos }}</td>
                            <td class="px-6 py-3">{{ $student->seccion ?? '—' }}</td>
                            <td class="px-6 py-3">
                                @if($student->voto)
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Votó</span>
                                @else
                                    <span class="rounded-full bg-surface-variant px-3 py-1 text-xs font-semibold text-on-surface-variant">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.students.edit', $student->id) }}"
                                       class="rounded-lg p-2 text-primary hover:bg-primary/10" title="Editar">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>

                                    @if($student->voto)
                                        <form method="POST" action="{{ route('admin.students.desbloquear', $student->id) }}"
                                              class="inline" onsubmit="return confirm('¿Anular el voto de este estudiante y desbloquearlo?')">
                                            @csrf
                                            <button type="submit" class="rounded-lg p-2 text-amber-600 hover:bg-amber-50" title="Desbloquear">
                                                <span class="material-symbols-outlined text-lg">lock_open</span>
                                            </button>
                                        </form>
                                    @endif

                                    <x-delete-form :action="route('admin.students.destroy', $student->id)" confirmMessage="¿Eliminar este estudiante del padrón?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-on-surface-variant">
                                No hay estudiantes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-outline-variant px-6 py-3 bg-surface">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
