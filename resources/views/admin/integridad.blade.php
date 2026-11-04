@extends('layouts.admin')

@section('title', 'Integridad del conteo — Sistema Electoral')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary">Integridad del conteo</h1>
            <p class="text-sm text-on-surface-variant mt-1">
                Sello SHA-256 por mesa. Si un voto se altera después del cierre, la verificación lo detecta.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.integridad.sellar') }}">
            @csrf
            <button type="submit"
                class="bg-primary text-on-primary px-4 py-2 rounded-lg font-semibold hover:bg-primary-container transition">
                Sellar conteo actual
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(!empty($sinSellar))
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
            Mesas sin sellar: {{ implode(', ', $sinSellar) }}
        </div>
    @endif

    @if(!empty($incidencias))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <strong>{{ count($incidencias) }} mesa(s) con diferencias</strong> respecto al conteo sellado.
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-outline-variant bg-surface-container-lowest">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface border-b border-outline-variant text-sm text-on-surface-variant">
                    <th class="px-6 py-3 font-semibold">Mesa</th>
                    <th class="px-6 py-3 font-semibold">Sellada</th>
                    <th class="px-6 py-3 font-semibold">Total</th>
                    <th class="px-6 py-3 font-semibold">Blancos</th>
                    <th class="px-6 py-3 font-semibold">Nulos</th>
                    <th class="px-6 py-3 font-semibold">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sellos as $sello)
                    @php $alterada = collect($incidencias)->firstWhere('mesa_id', $sello->mesa_id); @endphp
                    <tr class="border-b border-outline-variant/50">
                        <td class="px-6 py-3">{{ $sello->mesa?->nombre ?? 'Mesa '.$sello->mesa_id }}</td>
                        <td class="px-6 py-3">{{ $sello->sealed_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="px-6 py-3">{{ $sello->total }}</td>
                        <td class="px-6 py-3">{{ $sello->blancos }}</td>
                        <td class="px-6 py-3">{{ $sello->nulos }}</td>
                        <td class="px-6 py-3">
                            @if($alterada)
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Alterada ({{ $sello->total }} → {{ $alterada['total_actual'] }})
                                </span>
                            @else
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Íntegra
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-on-surface-variant">
                            Aún no hay conteo sellado. Cierre la elección o use «Sellar conteo actual».
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
