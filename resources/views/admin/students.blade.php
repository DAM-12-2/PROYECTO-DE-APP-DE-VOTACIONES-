@extends('layouts.admin')
@section('title', 'Gestión de Estudiantes')
@section('content')
<div class="mb-section-margin">
<h2 class="font-headline-lg text-headline-lg text-primary mb-1">Gestión de Estudiantes</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Administración del padrón electoral.</p>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
<div class="bg-surface-container-low border border-outline-variant rounded-xl p-6">
<h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
<span class="material-symbols-outlined">person_add</span> Añadir Estudiante Manual
</h3>
<form class="space-y-4">
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Cédula</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Ej: 101110111" type="text"/>
</div>
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Nombre Completo</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Ej: Juan Pérez" type="text"/>
</div>
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Sección</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Ej: 10-1" type="text"/>
</div>
<button class="bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg px-4 py-2 rounded-lg transition-colors w-full" type="button">Guardar Estudiante</button>
</form>
</div>
<div class="bg-surface-container-low border border-outline-variant rounded-xl p-6 flex flex-col">
<h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
<span class="material-symbols-outlined">upload_file</span> Importar desde Excel (PIAD)
</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">Puede cargar masivamente a los estudiantes utilizando el formato exportado del sistema PIAD.</p>
<div class="border-2 border-dashed border-outline-variant rounded-lg p-8 flex flex-col items-center justify-center text-center bg-surface hover:bg-surface-container-lowest transition-colors cursor-pointer flex-1 min-h-[160px]">
<span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">cloud_upload</span>
<p class="font-label-lg text-label-lg text-on-surface mb-1">Haga clic o arrastre un archivo .xlsx aquí</p>
<p class="font-body-md text-body-md text-on-surface-variant text-sm">Máximo 5MB</p>
</div>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden mb-8">
<div class="border-b border-outline-variant px-6 py-4 bg-surface-container-low flex flex-col sm:flex-row sm:items-center justify-between gap-4">
<div class="flex items-center gap-4 flex-1">
<div class="relative w-full sm:max-w-md">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" placeholder="Buscar por cédula o nombre..." type="text"/>
</div>
<button class="bg-surface hover:bg-surface-container border border-outline font-label-lg text-label-lg text-on-surface px-4 py-2 rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap">
<span class="material-symbols-outlined" style="font-size:18px">filter_list</span> Filtros
</button>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface border-b border-outline-variant font-label-lg text-label-lg text-on-surface-variant">
<th class="px-6 py-3 font-semibold">Cédula</th>
<th class="px-6 py-3 font-semibold">Nombre Completo</th>
<th class="px-6 py-3 font-semibold">Sección</th>
<th class="px-6 py-3 font-semibold">Estado Voto</th>
<th class="px-6 py-3 font-semibold text-right">Acciones</th>
</tr>
</thead>
<tbody>
<tr>
<td class="px-6 py-8 text-center text-on-surface-variant font-body-md" colspan="5">
<span class="material-symbols-outlined block text-4xl mb-2 opacity-50">person_off</span>
No hay estudiantes registrados.
</td>
</tr>
</tbody>
</table>
</div>
<div class="border-t border-outline-variant px-6 py-3 bg-surface flex items-center justify-between">
<span class="font-body-md text-body-md text-on-surface-variant">Mostrando 0 de 0</span>
<div class="flex items-center gap-2">
<button class="p-1 rounded hover:bg-surface-container disabled:opacity-50" disabled=""><span class="material-symbols-outlined">chevron_left</span></button>
<button class="p-1 rounded hover:bg-surface-container disabled:opacity-50" disabled=""><span class="material-symbols-outlined">chevron_right</span></button>
</div>
</div>
</div>
@endsection
