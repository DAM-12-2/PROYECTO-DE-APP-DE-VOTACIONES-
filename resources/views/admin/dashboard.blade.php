@extends('layouts.admin')
@section('title', 'Panel de Control')
@section('content')
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-section-margin">
<div>
<h2 class="font-headline-lg text-headline-lg text-primary mb-1">Panel de Control</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Visión general del proceso electoral actual.</p>
</div>
<div class="bg-error-container border border-error text-on-error-container px-4 py-2 rounded-lg flex items-center gap-2">
<span class="material-symbols-outlined">info</span>
<span class="font-label-lg text-label-lg">Las votaciones se encuentran cerradas en este momento</span>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-card-gap mb-section-margin">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:border-primary transition-colors">
<div class="w-12 h-12 rounded-full bg-[#003366]/10 flex items-center justify-center text-[#003366] mb-2">
<span class="material-symbols-outlined">school</span>
</div>
<div>
<p class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider">Estudiantes Padrón</p>
<h3 class="font-headline-lg text-headline-lg text-primary">0</h3>
</div>
<div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
<span class="material-symbols-outlined" style="font-size: 120px;">school</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:border-primary transition-colors">
<div class="w-12 h-12 rounded-full bg-[#003366]/10 flex items-center justify-center text-[#003366] mb-2">
<span class="material-symbols-outlined">how_to_reg</span>
</div>
<div>
<p class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider">Tribunal (0-7)</p>
<h3 class="font-headline-lg text-headline-lg text-primary">0</h3>
</div>
<div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
<span class="material-symbols-outlined" style="font-size: 120px;">how_to_reg</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:border-primary transition-colors">
<div class="w-12 h-12 rounded-full bg-[#003366]/10 flex items-center justify-center text-[#003366] mb-2">
<span class="material-symbols-outlined">groups</span>
</div>
<div>
<p class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider">Partidos Inscritos</p>
<h3 class="font-headline-lg text-headline-lg text-primary">0</h3>
</div>
<div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
<span class="material-symbols-outlined" style="font-size: 120px;">groups</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:border-primary transition-colors">
<div class="w-12 h-12 rounded-full bg-[#003366]/10 flex items-center justify-center text-[#003366] mb-2">
<span class="material-symbols-outlined">table_restaurant</span>
</div>
<div>
<p class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider">Juntas / Mesas</p>
<h3 class="font-headline-lg text-headline-lg text-primary">0</h3>
</div>
<div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
<span class="material-symbols-outlined" style="font-size: 120px;">table_restaurant</span>
</div>
</div>
</div>
@endsection
