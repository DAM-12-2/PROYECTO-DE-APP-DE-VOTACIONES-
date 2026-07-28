<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Cierre — {{ $institucion }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none !important; } body { background: white; } }
        body { font-family: 'Inter', sans-serif; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body class="bg-gray-50 p-4 md:p-8">

    {{-- Selector de mesa --}}
    <div class="no-print max-w-4xl mx-auto mb-6 bg-white p-4 rounded-lg shadow-sm border border-slate-200">
        <form method="GET" action="{{ route('admin.reportes.acta_cierre') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-600 mb-1">Junta Receptora de Votos:</label>
                <select name="mesa_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Todas las mesas (Resultados Globales)</option>
                    @foreach($mesas as $m)
                        <option value="{{ $m->id }}" {{ ($mesaSeleccionada && $mesaSeleccionada->id == $m->id) ? 'selected' : '' }}>
                            Mesa N° {{ $m->numero }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-slate-900 text-white px-6 py-2 rounded-lg font-bold text-sm hover:bg-slate-700 transition-colors">
                Generar
            </button>
        </form>
    </div>

    {{-- Resumen General --}}
            <div class="mb-6">
                <p class="text-sm font-bold text-slate-700 mb-2">Resumen General:</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-slate-50 p-3 rounded text-center border border-slate-200">
                        <p class="text-xl font-black text-slate-800">{{ $tally->totalVotes }}</p>
                        <p class="text-xs text-slate-500">Total Votos</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded text-center border border-green-200">
                        <p class="text-xl font-black text-green-700">{{ $votosValidos }}</p>
                        <p class="text-xs text-green-600">Votos Válidos</p>
                    </div>
                    <div class="bg-amber-50 p-3 rounded text-center border border-amber-200">
                        <p class="text-xl font-black text-amber-700">{{ $tally->blancos }}</p>
                        <p class="text-xs text-amber-600">En Blanco</p>
                    </div>
                    <div class="bg-red-50 p-3 rounded text-center border border-red-200">
                        <p class="text-xl font-black text-red-700">{{ $tally->nulos }}</p>
                        <p class="text-xs text-red-600">Nulos</p>
                    </div>
                </div>
            </div>

            {{-- Tabla de Resultados --}}
            @if($isConsultaPopular)
                {{-- MODO CONSULTA POPULAR: SI / NO --}}
                <div class="mb-6">
                    <p class="text-sm font-bold text-slate-700 mb-2">Resultados de la Consulta Popular:</p>
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-slate-100 text-xs uppercase text-slate-600">
                            <tr>
                                <th class="px-4 py-2 text-left border border-slate-300">Opción</th>
                                <th class="px-4 py-2 text-right border border-slate-300">Votos (números)</th>
                                <th class="px-4 py-2 text-right border border-slate-300">Votos (letras)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 border border-slate-300 font-bold text-green-700">SÍ</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->siCount }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->siCount) }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border border-slate-300 font-bold text-red-700">NO</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->noCount }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->noCount) }}</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="px-4 py-2 border border-slate-300 font-bold">VOTOS VÁLIDOS</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $votosValidos }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($votosValidos) }}</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="px-4 py-2 border border-slate-300 font-bold">BLANCOS</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->blancos }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->blancos) }}</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="px-4 py-2 border border-slate-300 font-bold">NULOS</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->nulos }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->nulos) }}</td>
                            </tr>
                            <tr class="bg-slate-200">
                                <td class="px-4 py-2 border border-slate-300 font-black text-base">TOTAL</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-black text-base">{{ $tally->totalVotes }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-black">{{ @num2letras($tally->totalVotes) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            @else
                {{-- MODO PARTIDOS --}}
                <div class="mb-6">
                    <p class="text-sm font-bold text-slate-700 mb-2">Resultados por Partido Político:</p>
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-slate-100 text-xs uppercase text-slate-600">
                            <tr>
                                <th class="px-4 py-2 text-left border border-slate-300">Partido Político</th>
                                <th class="px-4 py-2 text-right border border-slate-300">Votos (números)</th>
                                <th class="px-4 py-2 text-right border border-slate-300">Votos (letras)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parties as $party)
                            <tr>
                                <td class="px-4 py-2 border border-slate-300">
                                    <span class="font-bold">{{ $party->siglas }}</span>
                                    <span class="text-slate-500 text-xs ml-1">{{ $party->nombre }}</span>
                                </td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $results[(string)$party->id] ?? 0 }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($results[(string)$party->id] ?? 0) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-slate-50">
                                <td class="px-4 py-2 border border-slate-300 font-bold">VOTOS VÁLIDOS</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $votosValidos }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($votosValidos) }}</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="px-4 py-2 border border-slate-300 font-bold">BLANCOS</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->blancos }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->blancos) }}</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="px-4 py-2 border border-slate-300 font-bold">NULOS</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->nulos }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->nulos) }}</td>
                            </tr>
                            <tr class="bg-slate-200">
                                <td class="px-4 py-2 border border-slate-300 font-black text-base">TOTAL</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-black text-base">{{ $tally->totalVotes }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-right font-black">{{ @num2letras($tally->totalVotes) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            @endif

            {{-- Firmas --}}
            <div class="mt-8 pt-4 border-t border-dashed border-slate-300 text-xs text-slate-500 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8">
                <p>Firma del Presidente del TEE: _______________________</p>
                <p>Firma del Presidente de Mesa: _______________________</p>
            </div>
        </div>
    @endforeach

    <div class="no-print text-center mt-6">
        <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-xl font-bold text-sm">
            Imprimir Acta de Cierre
        </button>
        <a href="/admin/reportes" class="ml-4 text-slate-500 hover:text-slate-700 text-sm font-bold">Volver a Reportes</a>
    </div>
</body>
</html>
