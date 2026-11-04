@extends('layouts.jrv')
@push('head')
<title>JRV - Mesa de Votación</title>
@endpush
@section('content')
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-16 w-full px-container-padding flex items-center justify-between">
        <div class="flex items-center gap-base">
            <img alt="CTP AIRA" class="w-8 h-8 rounded-full object-cover" src="/img/logo.png"/>
            <span class="font-headline-sm text-headline-sm text-primary tracking-tight">CTP AIRA</span>
        </div>
        <div class="flex items-center gap-card-gap">
            <div class="flex items-center gap-base border-l border-outline-variant pl-gutter">
                <span class="hidden sm:block text-label-sm font-label-sm text-on-surface-variant">{{ Auth::user()->name ?? 'JRV' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-2 text-error hover:bg-error-container/20 rounded-full transition-colors flex items-center gap-1" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-sm">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<main class="w-full pt-16 bg-background min-h-screen">
    <div class="w-full px-container-padding py-section-margin">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-base mb-gutter">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full {{ $eleccionAbierta ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">
                        {{ $eleccionAbierta ? 'Votación en curso' : 'Votación cerrada' }}
                    </span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-primary">
                    {{ $mesa?->nombre ?? 'Sin mesa asignada' }}
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    {{ $mesa?->ubicacion ?? 'Solicite al Tribunal Electoral la asignación de su mesa.' }}
                </p>
            </div>
            <a href="/kiosko" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary hover:bg-primary-container transition">
                <span class="material-symbols-outlined text-sm">open_in_new</span>
                Abrir urna
            </a>
        </div>

        <div id="jrv-mensaje" class="hidden mb-gutter"></div>

        @if(!$mesa)
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
                Su usuario no tiene una mesa asignada. Contacte al Tribunal Electoral Estudiantil.
            </div>
        @else
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-gutter mb-card-gap">
                <div class="flex items-center justify-between mb-base">
                    <h2 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">pending_actions</span>
                        Solicitudes pendientes
                    </h2>
                    <span class="text-xs text-on-surface-variant">Actualización automática</span>
                </div>
                <div id="cola-solicitudes" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-card-gap">
                <div class="lg:col-span-1 space-y-card-gap">
                    <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-gutter">
                        <h2 class="font-headline-sm text-headline-sm text-primary mb-base flex items-center gap-2">
                            <span class="material-symbols-outlined">search</span>
                            Buscar estudiante
                        </h2>
                        <form id="buscar-form" class="flex gap-2">
                            <input id="buscar-input" type="text" inputmode="numeric" autocomplete="off"
                                   placeholder="Identificación"
                                   class="w-full rounded-lg border-2 border-outline-variant bg-surface px-3 py-2 outline-none focus:border-primary">
                            <button type="submit"
                                    class="rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-on-primary hover:bg-primary-container">
                                Buscar
                            </button>
                        </form>

                        <div id="resultado-estudiante" class="mt-4 hidden rounded-lg border border-outline-variant p-4"></div>
                    </div>

                    <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-gutter">
                        <h2 class="font-headline-sm text-headline-sm text-primary mb-base">Instrucciones</h2>
                        <ol class="list-decimal list-inside space-y-1 text-sm text-on-surface-variant">
                            <li>Verifique la identificación en el padrón.</li>
                            <li>Asigne al estudiante a una urna disponible.</li>
                            <li>El estudiante vota en la terminal y firma el padrón.</li>
                            <li>La urna se libera automáticamente.</li>
                        </ol>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-gutter">
                        <div class="flex items-center justify-between mb-base">
                            <h2 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined">ballot</span>
                                Urnas de la mesa
                            </h2>
                            <span class="text-xs text-on-surface-variant">Actualización automática</span>
                        </div>
                        <div id="lista-urnas" class="grid grid-cols-1 sm:grid-cols-2 gap-gutter"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <x-creditos class="mt-8 pb-4" />
</main>
@endsection
