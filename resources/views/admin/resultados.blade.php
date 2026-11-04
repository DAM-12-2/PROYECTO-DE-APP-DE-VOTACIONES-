@extends('layouts.admin')

@section('title', 'Resultados — Sistema Electoral')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Resultados</h1>
            <p class="text-sm text-on-surface-variant mt-1">Escrutinio general del proceso electoral.</p>
        </div>
        <a href="{{ route('admin.integridad') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-outline-variant px-4 py-2 text-sm font-semibold hover:border-primary">
            <span class="material-symbols-outlined text-sm">verified_user</span>
            Ver integridad del conteo
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Votos emitidos</p>
            <p class="mt-2 text-3xl font-bold text-primary">{{ $resultados['totalVotes'] ?? 0 }}</p>
        </div>
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Blancos</p>
            <p class="mt-2 text-3xl font-bold text-primary">{{ $resultados['blancos'] ?? 0 }}</p>
        </div>
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Nulos</p>
            <p class="mt-2 text-3xl font-bold text-primary">{{ $resultados['nulos'] ?? 0 }}</p>
        </div>
    </div>

    @if($ganador)
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Ganador</p>
            <p class="mt-1 text-xl font-bold text-emerald-900">
                {{ $ganador['nombre'] ?? $ganador['opcion'] ?? '—' }}
                @if(!empty($ganador['siglas'])) ({{ $ganador['siglas'] }}) @endif
            </p>
            <p class="text-sm text-emerald-800">{{ $ganador['votos'] ?? 0 }} votos · {{ $ganador['porcentaje'] ?? 0 }}%</p>
        </div>
    @else
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-800">
            Todavía no hay un ganador definido con la regla configurada.
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-outline-variant bg-surface-container-lowest">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface border-b border-outline-variant text-sm text-on-surface-variant">
                    <th class="px-6 py-3 font-semibold">Partido</th>
                    <th class="px-6 py-3 font-semibold">Votos</th>
                    <th class="px-6 py-3 font-semibold">Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resultados['partidos'] ?? [] as $partido)
                    <tr class="border-b border-outline-variant/50">
                        <td class="px-6 py-3">
                            <span class="font-semibold">{{ $partido['siglas'] ?? '' }}</span>
                            <span class="text-on-surface-variant"> — {{ $partido['nombre'] ?? '' }}</span>
                        </td>
                        <td class="px-6 py-3">{{ $partido['votos'] ?? 0 }}</td>
                        <td class="px-6 py-3">{{ $partido['porcentaje'] ?? 0 }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-on-surface-variant">
                            No hay votos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
