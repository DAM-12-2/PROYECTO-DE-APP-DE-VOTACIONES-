@extends('layouts.admin')
@section('title', 'Respaldar Base de Datos')
@section('content')
<div class="mb-section-margin">
<h2 class="font-headline-lg text-headline-lg text-primary mb-1">Respaldar Base de Datos</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Generar copias de seguridad del sistema.</p>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 max-w-2xl text-center">
<span class="material-symbols-outlined text-primary text-6xl mb-4">backup</span>
<h3 class="font-headline-sm text-headline-sm text-primary mb-2">Crear Nuevo Respaldo</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Genera un archivo descargable con toda la información actual de la base de datos (estudiantes, votos, configuraciones).</p>
<button class="bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg px-6 py-3 rounded-lg transition-colors inline-flex items-center gap-2">
<span class="material-symbols-outlined">download</span> Generar y Descargar Respaldo (.sql)
</button>
</div>
@endsection
