<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta Oficial de Resultados — {{ $institucion }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none !important; } body { background: white; } }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 p-4 md:p-8">
    <div class="max-w-4xl mx-auto bg-white p-4 md:p-8 shadow-sm rounded-lg">
        <div class="text-center mb-8 border-b-2 border-slate-800 pb-6">
            @php
                $logoPath = $logoPath ?? null;
            @endphp
            @if($logoPath && file_exists(public_path($logoPath)))
                <img src="/{{ $logoPath }}" alt="Logo" class="h-16 mx-auto mb-3">
            @endif
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tribunal Electoral Estudiantil</p>
            <h1 class="text-xl font-bold text-slate-800 uppercase mt-2">{{ $institucion }}</h1>
            <h2 class="text-lg font-bold text-slate-600 mt-1">ACTA OFICIAL CON EL RESULTADO DE LA VOTACIÓN</h2>
            <p class="text-sm text-slate-500 mt-2">Año {{ date('Y') }}</p>
        </div>

        @if($tally->totalVotes == 0)
            <div class="text-center py-8 text-slate-400">
                <p class="text-lg">No se han registrado votos aún.</p>
            </div>
        @else
            {{-- Tabla de Resultados --}}
            <div class="mb-6">
                <p class="text-sm font-bold text-slate-700 mb-2">Resultados de la Votación:</p>
                <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-slate-100 text-xs uppercase text-slate-600">
                        <tr>
                            <th class="px-4 py-2 text-left border border-slate-300">{{ $isConsultaPopular ? 'Opción' : 'Partido Político' }}</th>
                            <th class="px-4 py-2 text-right border border-slate-300">Votos</th>
                            <th class="px-4 py-2 text-right border border-slate-300">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($isConsultaPopular)
                            <tr>
                                <td class="px-4 py-2 border border-slate-300 font-bold text-green-700">SÍ</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->siCount }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->totalVotes > 0 ? round(($tally->siCount / $tally->totalVotes) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border border-slate-300 font-bold text-red-700">NO</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->noCount }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->totalVotes > 0 ? round(($tally->noCount / $tally->totalVotes) * 100, 1) : 0 }}%</td>
                            </tr>
                        @else
                            @foreach($parties as $party)
                            <tr>
                                <td class="px-4 py-2 border border-slate-300">
                                    <span class="font-bold">{{ $party->siglas }}</span>
                                    <span class="text-slate-500 text-xs ml-1">{{ $party->nombre }}</span>
                                </td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $results[(string)$party->id] ?? 0 }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->totalVotes > 0 ? round((($results[(string)$party->id] ?? 0) / $tally->totalVotes) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        @endif
                        <tr class="bg-slate-50">
                            <td class="px-4 py-2 border border-slate-300 font-bold">VOTOS VÁLIDOS</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $votosValidos }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->totalVotes > 0 ? round(($votosValidos / $tally->totalVotes) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr class="bg-slate-50">
                            <td class="px-4 py-2 border border-slate-300 font-bold">BLANCOS</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->blancos }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->totalVotes > 0 ? round(($tally->blancos / $tally->totalVotes) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr class="bg-slate-50">
                            <td class="px-4 py-2 border border-slate-300 font-bold">NULOS</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->nulos }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->totalVotes > 0 ? round(($tally->nulos / $tally->totalVotes) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr class="bg-slate-200">
                            <td class="px-4 py-2 border border-slate-300 font-black">TOTAL</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-black">{{ $tally->totalVotes }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-black">100%</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>

            {{-- Declaración del ganador --}}
            <div class="mb-6 p-4 rounded-xl border-2 {{ $empate ? 'border-amber-400 bg-amber-50' : 'border-green-400 bg-green-50' }}">
                @if($empate)
                    <p class="text-center font-bold text-amber-700 text-lg">EMPATE TÉCNICO</p>
                    <p class="text-center text-amber-600 text-sm mt-1">No se ha podido determinar un ganador. Se requiere una segunda vuelta.</p>
                @else
                    <p class="text-center font-bold text-green-700 text-lg">GANADOR: {{ $ganador->siglas }}</p>
                    <p class="text-center text-green-600 text-sm mt-1">
                        {{ $ganador->nombre }} — {{ $maxVotos }} voto(s) ({{ $porcentajeGanador }}% de votos válidos)
                    </p>
                @endif
            </div>
        @endif

        {{-- Miembros del TEE --}}
        <div class="mt-8">
            <p class="text-sm font-bold text-slate-700 mb-2">Miembros del Tribunal Electoral Estudiantil:</p>
            <table class="w-full text-xs border-collapse">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-3 py-2 text-left border border-slate-300">Puesto en TEE</th>
                        <th class="px-3 py-2 text-left border border-slate-300">Nombre y Apellidos</th>
                        <th class="px-3 py-2 text-center border border-slate-300 w-24">Firma</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teeMembers as $tee)
                    <tr>
                        <td class="px-3 py-2 border border-slate-300">{{ $tee->puesto }}</td>
                        <td class="px-3 py-2 border border-slate-300">{{ $tee->student->nombre ?? '' }} {{ $tee->student->apellidos ?? '' }}</td>
                        <td class="px-3 py-2 border border-slate-300 h-8"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-xs text-slate-500 text-center">
            <p>El presente acta deberá ser registrada en el libro de actas del TEE.</p>
            <p class="mt-1">Fecha: {{ date('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="no-print text-center mt-6">
        <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-xl font-bold text-sm">
            Imprimir Acta de Resultados
        </button>
        <a href="/admin/reportes" class="ml-4 text-slate-500 hover:text-slate-700 text-sm font-bold">Volver a Reportes</a>
    </div>
</body>
</html>
