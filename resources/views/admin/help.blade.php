@extends('layouts.admin')
@section('title', 'Ayuda y Soporte')
@section('content')
<div class="mb-section-margin">
<h2 class="font-headline-lg text-headline-lg text-primary mb-1">Ayuda y Soporte</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Documentación y asistencia técnica.</p>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center max-w-2xl">
<span class="material-symbols-outlined text-primary text-6xl mb-4">help_center</span>
<h3 class="font-headline-sm text-headline-sm text-primary mb-4">Manual de Usuario</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Descargue la guía completa para administradores del Sistema Electoral Institucional.</p>
<button class="bg-surface hover:bg-surface-container border border-outline font-label-lg text-label-lg text-on-surface px-6 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
<span class="material-symbols-outlined">menu_book</span> Ver Documentación
</button>
</div>
@endsection
