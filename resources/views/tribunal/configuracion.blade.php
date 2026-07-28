@extends('layouts.tribunal')
@section('title', 'Ajustes')
@section('content')
<div class="space-y-stack-lg">
<section class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
<h3 class="font-headline-md text-primary mb-6 flex items-center gap-2">
<span class="material-symbols-outlined">database</span> Configuración de Datos
</h3>
<div class="space-y-6">
<div class="flex items-center justify-between">
<label class="text-sm font-medium">Motor de Base de Datos</label>
<select class="p-2 border border-outline-variant rounded bg-surface text-sm">
<option>Local (SQLite)</option>
<option>Red (MySQL)</option>
</select>
</div>
<div class="p-4 bg-surface-container-low rounded-lg border border-outline-variant">
<p class="text-[10px] font-bold text-secondary uppercase mb-3">Respaldo Automático</p>
<button class="w-full py-2 bg-secondary text-on-secondary rounded text-xs font-bold flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-sm">backup</span> Generar Respaldo Ahora (.sql)
</button>
</div>
</div>
<div class="mt-8 pt-6 border-t border-outline-variant">
<p class="text-xs text-secondary font-bold mb-2">CONTACTO TÉCNICO</p>
<p class="text-sm font-medium text-primary">Departamento de Informática del CTP AIRA</p>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
<h3 class="font-headline-md text-error mb-6 flex items-center gap-2">
<span class="material-symbols-outlined">warning</span> Zona de Peligro
</h3>
<div class="space-y-4">
<button class="w-full py-2.5 border border-error text-error rounded font-bold hover:bg-error/5 transition-colors flex items-center justify-center gap-2">
<span class="material-symbols-outlined">restart_alt</span> Reiniciar Conteo de Votos
</button>
<button class="w-full py-2.5 bg-error text-on-error rounded font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
<span class="material-symbols-outlined">delete_forever</span> Reseteo Total del Sistema
</button>
<p class="text-[10px] text-secondary italic text-center">Estas acciones son irreversibles y requieren confirmación administrativa.</p>
</div>
</div>
</section>
</div>
@endsection
