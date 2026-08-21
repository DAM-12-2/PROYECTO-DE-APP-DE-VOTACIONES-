@extends('layouts.tribunal')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-6">
	<div class="flex items-center justify-between gap-4">
		<div>
			<h3 class="font-headline-md text-primary">Resultados electorales</h3>
			<p class="text-sm text-secondary">Resumen actualizado de la votación.</p>
		</div>
		<a class="px-4 py-2 bg-primary text-on-primary rounded-lg text-sm font-bold flex items-center gap-2 shadow-md" href="{{ url('/resultados/exportar-csv') }}">
			<span class="material-symbols-outlined text-sm">download</span>
			Descargar CSV
		</a>
	</div>
	<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
		<div class="h-80 relative">
			<canvas id="graficoVotos" aria-label="Gráfico de votos por partido"></canvas>
		</div>
	</div>
</div>
@endsection
