@extends('layouts.admin')
@section('title', 'Parámetros del Sistema')
@section('content')
<div class="mb-section-margin">
<h2 class="font-headline-lg text-headline-lg text-primary mb-1">Parámetros del Sistema</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Configuración general e institucional.</p>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden mb-8">
<div class="border-b border-outline-variant px-6 py-4 bg-surface-container-low flex items-center justify-between">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">settings</span>
<h3 class="font-headline-sm text-headline-sm text-primary">Institución</h3>
</div>
<button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">expand_more</button>
</div>
<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
<form action="{{ route('admin.settings') }}" method="POST" class="space-y-0">
@csrf
<div class="space-y-6">
<div class="flex items-start gap-6">
<div class="shrink-0">
<img alt="Escudo actual" class="w-24 h-24 rounded-lg border border-outline-variant object-cover bg-surface-variant" src="/img/logo.png"/>
</div>
<div class="flex-1 space-y-3">
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Nombre de institución</label>
<input name="institucion_nombre" class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" placeholder="Nombre de institución" type="text" value="{{ $settings['institucion_nombre'] ?? 'CTP AIRA' }}"/>
</div>
<div>
<button class="bg-surface hover:bg-surface-container border border-outline font-label-lg text-label-lg text-on-surface px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
<span class="material-symbols-outlined" style="font-size:18px">upload</span> Cambiar escudo
</button>
</div>
</div>
</div>
</div>
<div class="space-y-6">
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Tiempo de votación por estudiante</label>
<div class="flex items-center gap-2">
<input class="w-24 px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all text-right" type="number" value="90"/>
<span class="font-body-md text-body-md text-on-surface-variant">segundos</span>
</div>
</div>
<div class="flex gap-3 pt-2">
<button type="submit" class="bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg px-6 py-2 rounded-lg transition-colors">Guardar Cambios</button>
<button class="bg-transparent hover:bg-surface-container-high text-primary font-label-lg text-label-lg px-4 py-2 rounded-lg transition-colors">Cancelar</button>
</div>
</div>
</div>
</form>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4">
<div class="flex items-center gap-3 text-on-surface">
<span class="material-symbols-outlined">restart_alt</span>
<h3 class="font-headline-sm text-headline-sm">Reiniciar Conteo</h3>
</div>
<p class="font-body-md text-body-md text-on-surface-variant">Pone a cero los contadores de votos, manteniendo los partidos y estudiantes.</p>
<button class="mt-auto bg-surface hover:bg-surface-container border border-outline font-label-lg text-label-lg text-on-surface px-4 py-2 rounded-lg transition-colors w-full">Ejecutar</button>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4">
<div class="flex items-center gap-3 text-on-surface">
<span class="material-symbols-outlined">delete_sweep</span>
<h3 class="font-headline-sm text-headline-sm">Reiniciar Votos</h3>
</div>
<p class="font-body-md text-body-md text-on-surface-variant">Elimina el registro de quién ya votó, permitiendo que todos voten nuevamente.</p>
<button class="mt-auto bg-surface hover:bg-surface-container border border-outline font-label-lg text-label-lg text-on-surface px-4 py-2 rounded-lg transition-colors w-full">Ejecutar</button>
</div>
<div class="bg-error-container border border-error rounded-xl p-6 flex flex-col gap-4">
<div class="flex items-center gap-3 text-error">
<span class="material-symbols-outlined">warning</span>
<h3 class="font-headline-sm text-headline-sm">Reseteo Total</h3>
</div>
<p class="font-body-md text-body-md text-on-error-container">Elimina toda la información del sistema. Esta acción no se puede deshacer.</p>
<button class="mt-auto bg-error hover:bg-[#93000a] text-on-error font-label-lg text-label-lg px-4 py-2 rounded-lg transition-colors w-full">Ejecutar Peligro</button>
</div>
</div>
@endsection
