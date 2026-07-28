<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Popular — Resumen — {{ $institucion }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none !important; } body { background: white; } }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 p-4 md:p-8">
    <div class="max-w-4xl mx-auto bg-white p-4 md:p-8 shadow-sm rounded-lg">
        <div class="text-center mb-8 border-b-2 border-slate-800 pb-6">
            @if($logoPath && file_exists(public_path($logoPath)))
                <img src="/{{ $logoPath }}" alt="Logo" class="h-16 mx-auto mb-3">
            @endif
            <h1 class="text-xl font-bold text-slate-800 uppercase">{{ $institucion }}</h1>
            <h2 class="text-lg font-bold text-slate-600 mt-1">CONSULTA POPULAR — RESUMEN GENERAL</h2>
            <p class="text-sm text-slate-500 mt-1">Proceso Electoral Estudiantil {{ date('Y') }}</p>
            <p class="text-xs text-slate-400 mt-2">Hora: {{ date('H:i') }} | Fecha: {{ date('d/m/Y') }}</p>
        </div>

        @if($tally->totalVotes == 0)
            <div class="text-center py-8 text-slate-400">
                <p class="text-lg">No se han registrado votos aún.</p>
            </div>
        @else
            {{-- Resumen Principal --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div class="bg-slate-50 p-4 rounded-xl text-center border border-slate-200">
                    <p class="text-3xl font-black text-slate-800">{{ $tally->totalVotes }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total Votos</p>
                </div>
                <div class="bg-green-50 p-4 rounded-xl text-center border border-green-200">
                    <p class="text-3xl font-black text-green-700">{{ $tally->siCount }}</p>
                    <p class="text-xs text-green-600 mt-1">Votos SÍ ({{ $tally->siPct() }}%)</p>
                </div>
                <div class="bg-red-50 p-4 rounded-xl text-center border border-red-200">
                    <p class="text-3xl font-black text-red-700">{{ $tally->noCount }}</p>
                    <p class="text-xs text-red-600 mt-1">Votos NO ({{ $tally->noPct() }}%)</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl text-center border border-blue-200">
                    <p class="text-3xl font-black text-blue-700">{{ $participacion }}%</p>
                    <p class="text-xs text-blue-600 mt-1">Participación</p>
                </div>
            </div>

            {{-- Tabla Detallada --}}
            <div class="mb-6">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left border border-slate-300">Concepto</th>
                            <th class="px-4 py-2 text-right border border-slate-300">Cantidad</th>
                            <th class="px-4 py-2 text-right border border-slate-300">En Letras</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border border-slate-300 font-bold text-green-700">SÍ (A Favor)</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->siCount }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->siCount) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border border-slate-300 font-bold text-red-700">NO (En Contra)</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $tally->noCount }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->noCount) }}</td>
                        </tr>
                        <tr class="bg-slate-50">
                            <td class="px-4 py-2 border border-slate-300 font-bold">VOTOS VÁLIDOS</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-bold">{{ $votosValidos }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($votosValidos) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border border-slate-300">En Blanco</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->blancos }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->blancos) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border border-slate-300">Nulos</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ $tally->nulos }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right">{{ @num2letras($tally->nulos) }}</td>
                        </tr>
                        <tr class="bg-slate-200">
                            <td class="px-4 py-2 border border-slate-300 font-black">TOTAL</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-black">{{ $tally->totalVotes }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-right font-black">{{ @num2letras($tally->totalVotes) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Resultado --}}
            <div class="p-4 rounded-xl border-2 {{ $ganador === 'EMPATE' ? 'border-amber-400 bg-amber-50' : 'border-green-400 bg-green-50' }}">
                @if($ganador === 'EMPATE')
                    <p class="text-center font-bold text-amber-700 text-lg">EMPATE TÉCNICO</p>
                @else
                    <p class="text-center font-bold text-green-700 text-lg">RESULTADO: LA PROPUESTA FUE APROBADA POR MAYORÍA "{{ $ganador }}"</p>
                @endif
            </div>
        @endif

        <div class="mt-8 pt-4 border-t border-dashed border-slate-300 text-xs text-slate-500">
            <p>Firma del Presidente del TEE: _______________________</p>
        </div>
    </div>

    <div class="no-print text-center mt-6">
        <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-xl font-bold text-sm">
            Imprimir Reporte
        </button>
        <a href="/admin/reportes" class="ml-4 text-slate-500 hover:text-slate-700 text-sm font-bold">Volver a Reportes</a>
    </div>
</body>
</html>
