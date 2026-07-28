@extends('layouts.admin')
@section('title', 'Consultar Bitácora')
@section('content')
<div class="mb-section-margin">
<h2 class="font-headline-lg text-headline-lg text-primary mb-1">Consultar Bitácora</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Registro de actividades del sistema.</p>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
<div class="border-b border-outline-variant px-6 py-4 bg-surface-container-low">
<div class="relative max-w-md">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" placeholder="Buscar eventos..." type="text"/>
</div>
</div>
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface border-b border-outline-variant font-label-lg text-label-lg text-on-surface-variant">
<th class="px-6 py-3 font-semibold">Fecha/Hora</th>
<th class="px-6 py-3 font-semibold">Usuario</th>
<th class="px-6 py-3 font-semibold">Acción</th>
<th class="px-6 py-3 font-semibold">Detalles</th>
</tr>
</thead>
<tbody>
<tr>
<td class="px-6 py-8 text-center text-on-surface-variant font-body-md" colspan="4">
<span class="material-symbols-outlined block text-4xl mb-2 opacity-50">history_toggle_off</span>
No hay registros en la bitácora.
</td>
</tr>
</tbody>
</table>
</div>
@endsection
