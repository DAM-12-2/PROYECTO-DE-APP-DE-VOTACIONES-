@extends('layouts.voting')

@section('content')

<div class="rounded-2xl border border-outline bg-white p-6 shadow-sm sm:p-10">

    {{-- Paso 1: identificación --}}
    <section id="pantalla-identificacion" class="pantalla">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-container text-white">
                <span class="material-symbols-outlined text-3xl">how_to_vote</span>
            </div>
            <h1 class="text-2xl font-bold sm:text-3xl">Votación Estudiantil</h1>
            <p class="mt-2 text-on-surface-variant">Ingrese su número de identificación para recibir la papeleta.</p>
        </div>

        <form id="student-form" class="mx-auto max-w-md" novalidate>
            <label for="identificacion" class="mb-2 block text-sm font-semibold">Número de identificación</label>
            <input
                type="text"
                id="identificacion"
                name="identificacion"
                inputmode="numeric"
                autocomplete="off"
                autofocus
                placeholder="Ej. 1000001"
                class="w-full rounded-xl border-2 border-outline bg-surface px-4 py-4 text-center text-2xl font-semibold tracking-wider outline-none transition focus:border-primary focus:bg-white">

            <div id="mensaje-identificacion" class="mt-4 hidden rounded-lg px-4 py-3 text-sm"></div>

            <button
                type="submit"
                id="btn-continuar"
                class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-4 text-lg font-semibold text-white transition hover:bg-primary-container focus:outline-none focus:ring-4 focus:ring-primary/20">
                Continuar
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-on-surface-variant">
            Si su identificación no aparece en el padrón, solicite ayuda a la Junta Receptora de Votos.
        </p>
    </section>

    {{-- Paso 2: selección --}}
    <section id="pantalla-votacion" class="hidden pantalla">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant">Paso 2 de 3</p>
                <h2 class="mt-1 text-2xl font-bold">Seleccione su opción</h2>
            </div>

            <div id="cronometro" class="flex items-center gap-3 rounded-xl border border-outline px-4 py-2">
                <span class="material-symbols-outlined text-primary">timer</span>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-on-surface-variant">Tiempo restante</p>
                    <p id="tiempo-restante" class="text-xl font-bold tabular-nums">90 s</p>
                </div>
            </div>
        </div>

        <div class="mb-4 h-1.5 w-full overflow-hidden rounded-full bg-surface-variant">
            <div id="barra-tiempo" class="h-full w-full rounded-full bg-primary transition-[width] duration-1000 ease-linear"></div>
        </div>

        <div id="lista-candidatos" class="grid gap-3 sm:grid-cols-2"></div>

        <div id="mensaje-votacion" class="mt-4 hidden rounded-lg px-4 py-3 text-sm"></div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button
                type="button"
                id="btn-blanco"
                class="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-outline px-6 py-4 font-semibold text-on-surface-variant transition hover:border-primary hover:text-primary">
                <span class="material-symbols-outlined">block</span>
                Votar en blanco
            </button>
            <button
                type="button"
                id="btn-confirmar"
                class="flex flex-[2] items-center justify-center gap-2 rounded-xl bg-primary px-6 py-4 text-lg font-semibold text-white transition hover:bg-primary-container focus:outline-none focus:ring-4 focus:ring-primary/20">
                Continuar
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </div>
    </section>

    {{-- Paso 3: confirmación --}}
    <section id="pantalla-confirmacion" class="hidden pantalla">
        <div class="mx-auto max-w-md text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                <span class="material-symbols-outlined text-3xl">fact_check</span>
            </div>
            <h2 class="text-2xl font-bold">Confirmar voto</h2>
            <p class="mt-2 text-on-surface-variant">Verifique su selección. Esta acción no podrá deshacerse.</p>

            <div id="opcion-confirmacion" class="mt-6 rounded-xl border-2 border-primary/20 bg-surface px-4 py-5"></div>

            <div class="mt-6 flex gap-3">
                <button
                    type="button"
                    id="btn-volver"
                    class="flex-1 rounded-xl border-2 border-outline px-6 py-4 font-semibold transition hover:border-primary">
                    Volver
                </button>
                <button
                    type="button"
                    id="btn-enviar-voto"
                    class="flex-[2] rounded-xl bg-emerald-600 px-6 py-4 font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-600/20">
                    Emitir voto
                </button>
            </div>
        </div>
    </section>

    {{-- Éxito --}}
    <section id="pantalla-exito" class="hidden pantalla">
        <div class="mx-auto max-w-md text-center">
            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <span class="material-symbols-outlined text-5xl">check_circle</span>
            </div>
            <h2 class="text-3xl font-bold">¡Voto registrado!</h2>
            <p class="mt-3 text-on-surface-variant">Su voto fue registrado correctamente y de forma anónima.</p>
            <p class="mt-6 text-sm text-on-surface-variant">La urna se reiniciará automáticamente para el siguiente estudiante.</p>
        </div>
    </section>

</div>
@endsection
