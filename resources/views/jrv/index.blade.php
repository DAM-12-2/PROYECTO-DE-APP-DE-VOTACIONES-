@extends('layouts.jrv')
@push('head')
<title>JRV - Mesa de Votación</title>
@endpush
@section('content')
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
<div class="h-16 w-full px-container-padding flex items-center justify-between">
<div class="flex items-center gap-base">
<img alt="CTP AIRA" class="w-8 h-8 rounded-full object-cover" src="https://lh3.googleusercontent.com/aida/AP1WRLuTBFcc-2slsMcGa9bAzK26iSHmQZRm4Lj5DMIgu32aygymwnLN_p_Q52xZlsMIjknAXAE6yTZWVJWqcJC6QOrPTb88ofuJC6hxdyOOiPha5kcxHKH3j2GI1IeAb-hy4E8I6oCPBPBThHKsORXUSye7CtS4cReERkk3MiLVpz74AYqFC_QxnnustxuPimMg-yyLeW4EMOokj1xBcw5_cFV3lmLo53IrOhmGhCCHIRaRUxKt-QyVRpCnSxKHbxlw4MRMLjWZbiuQ"/>
<span class="font-headline-sm text-headline-sm text-primary tracking-tight">CTP AIRA</span>
</div>
<div class="flex items-center gap-card-gap">
<nav class="hidden md:flex items-center gap-gutter">
<a class="text-label-lg font-label-lg text-on-surface-variant hover:text-on-surface transition-colors" href="#">Votación Activa</a>
<a class="text-label-lg font-label-lg text-on-surface-variant hover:text-on-surface transition-colors" href="#">Instrucciones</a>
</nav>
<div class="flex items-center gap-base border-l border-outline-variant pl-gutter">
<span class="hidden sm:block text-label-sm font-label-sm text-on-surface-variant">{{ Auth::user()->name ?? 'Estudiante' }}</span>
<form method="POST" action="{{ route('logout') }}" class="inline">
@csrf
<button type="submit" class="p-2 text-error hover:bg-error-container/20 rounded-full transition-colors flex items-center gap-1" title="Cerrar sesión">
<span class="material-symbols-outlined text-sm">logout</span>
</button>
</form>
</div>
</div>
</div>
</header>
<main class="w-full pt-16 bg-background">
<div class="flex flex-col w-full">
<div class="relative w-full px-container-padding pb-section-margin">
<div class="absolute inset-0 overflow-hidden pointer-events-none">
<div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] -mr-64 -mt-64"></div>
<div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-secondary-container/10 rounded-full blur-[100px] -ml-48 -mb-48"></div>
</div>
<div class="relative z-10 flex flex-col md:flex-row justify-between items-end md:items-center py-base gap-base mb-gutter border-b border-outline-variant/30">
<div>
<div class="flex items-center gap-2 mb-1">
<span class="w-2 h-2 rounded-full bg-on-tertiary-container animate-pulse"></span>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Sesión de Votación Oficial</span>
</div>
<h1 class="font-headline-lg text-headline-lg text-primary">Mesa de Votación Asignada: <span class="opacity-30">[MESA_NUMERO]</span></h1>
</div>
<div class="bg-surface-container-highest px-gutter py-base rounded-lg flex items-center gap-base shadow-sm">
<span class="material-symbols-outlined text-error">timer</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant leading-none">Tiempo restante</span>
<span class="font-headline-sm text-headline-sm text-error tabular-nums" id="voting-timer">90 segundos</span>
</div>
</div>
</div>
<div class="relative z-10 mb-section-margin">
<div class="bg-primary text-on-primary p-gutter rounded-xl flex items-center gap-gutter shadow-xl">
<div class="bg-on-primary/20 p-2 rounded-full">
<span class="material-symbols-outlined">info</span>
</div>
<div>
<p class="font-headline-sm text-headline-sm">Instrucciones de Votación</p>
<p class="font-body-md text-body-md opacity-90">Selecciona una opción por cada papeleta. Tu voto es secreto y personal.</p>
</div>
</div>
</div>
<div class="relative z-10" id="voting-step-container">
<div class="space-y-section-margin" id="party-selection-view">
<div class="flex items-center justify-between border-l-4 border-primary pl-4 mb-gutter">
<div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Paso 01</span>
<h2 class="font-headline-md text-headline-md text-primary">Seleccione su Partido o Planilla</h2>
</div>
<span class="material-symbols-outlined text-primary/40 text-[40px]">account_balance</span>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-card-gap">
<div class="group bg-surface-container-lowest p-gutter rounded-xl border-2 border-transparent hover:border-primary transition-all cursor-pointer shadow-sm hover:shadow-xl">
<div class="aspect-video bg-surface-container rounded-lg mb-base flex items-center justify-center">
<span class="material-symbols-outlined text-primary/20 text-[48px]">image</span>
</div>
<div class="text-center">
<p class="font-headline-sm text-headline-sm text-primary">[Nombre del Partido]</p>
<p class="font-body-md text-body-md text-on-surface-variant">Lema o Eslogan del Partido</p>
</div>
</div>
<div class="h-[280px] bg-surface-container-low rounded-xl animate-pulse"></div>
<div class="h-[280px] bg-surface-container-low rounded-xl animate-pulse"></div>
</div>
</div>
<div class="hidden space-y-section-margin" id="party-details-view">
<div class="flex items-center gap-gutter mb-gutter">
<button class="p-2 rounded-full hover:bg-surface-container-high transition-colors">
<span class="material-symbols-outlined text-primary">arrow_back</span>
</button>
<div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Paso 02</span>
<h2 class="font-headline-md text-headline-md text-primary">Detalles de la Planilla</h2>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-section-margin">
<div class="lg:col-span-1 space-y-gutter">
<div class="bg-surface-container-lowest p-gutter rounded-xl border border-outline-variant/30">
<div class="aspect-square bg-surface-container rounded-lg mb-gutter flex items-center justify-center">
<span class="material-symbols-outlined text-primary/20 text-[64px]">account_balance</span>
</div>
<h3 class="font-headline-md text-headline-md text-primary mb-1">[Nombre del Partido]</h3>
<p class="font-body-md text-body-md text-on-surface-variant italic">"Lema institucional del partido político estudiantil 2024"</p>
</div>
</div>
<div class="lg:col-span-2 space-y-gutter">
<h4 class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-widest">Integrantes de la Papeleta</h4>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<div class="flex items-center gap-base p-base bg-surface-container-low rounded-lg">
<div class="w-12 h-12 rounded-full bg-surface-container flex-shrink-0"></div>
<div>
<p class="font-label-lg text-label-lg text-primary">[Nombre del Candidato]</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">Presidente(a)</p>
</div>
</div>
<div class="flex items-center gap-base p-base bg-surface-container-low rounded-lg">
<div class="w-12 h-12 rounded-full bg-surface-container flex-shrink-0"></div>
<div>
<p class="font-label-lg text-label-lg text-primary">[Nombre del Candidato]</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">Vicepresidente(a)</p>
</div>
</div>
<div class="h-16 bg-surface-container-low/50 rounded-lg animate-pulse"></div>
<div class="h-16 bg-surface-container-low/50 rounded-lg animate-pulse"></div>
</div>
</div>
</div>
</div>
</div>
<div class="fixed bottom-8 left-1/2 -translate-x-1/2 w-full max-w-2xl px-container-padding z-50">
<div class="bg-surface/90 backdrop-blur-xl p-base rounded-full shadow-2xl border border-outline-variant flex gap-base">
<button class="flex-1 bg-surface-container-high text-on-surface hover:bg-surface-variant transition-colors py-4 px-gutter rounded-full font-label-lg text-label-lg flex items-center justify-center gap-base">
<span class="material-symbols-outlined">block</span>
Votar en Blanco
</button>
<button class="flex-[2] bg-primary text-on-primary hover:bg-on-primary-container transition-all hover:scale-[1.02] active:scale-[0.98] py-4 px-gutter rounded-full font-label-lg text-label-lg flex items-center justify-center gap-base shadow-lg shadow-primary/20">
<span class="material-symbols-outlined">how_to_vote</span>
Emitir Voto
</button>
</div>
</div>
</div>
</main>
<footer class="w-full bg-surface-container-lowest border-t border-outline-variant py-gutter mt-section-margin">
<div class="w-full px-container-padding flex flex-col md:flex-row justify-between items-center gap-base text-on-surface-variant">
<span class="text-label-sm font-label-sm">© {{ date('Y') }} Sistema Electoral Estudiantil - CTP AIRA</span>
<div class="flex gap-gutter">
<span class="material-symbols-outlined text-[18px]">verified_user</span>
<span class="text-label-sm font-label-sm italic">Conexión Segura</span>
</div>
</div>
</footer>
<script>
let timeLeft = 90;
const timerElement = document.getElementById('voting-timer');
const countdown = setInterval(() => {
timeLeft--;
timerElement.textContent = `${timeLeft} segundos`;
if (timeLeft <= 20) {
timerElement.classList.remove('text-error');
timerElement.classList.add('animate-bounce', 'text-red-600');
}
if (timeLeft <= 0) {
clearInterval(countdown);
timerElement.textContent = "Tiempo agotado";
}
}, 1000);
document.querySelectorAll('.candidate-option-wrapper, .group').forEach(item => {
item.addEventListener('click', function() {
this.classList.toggle('ring-4');
this.classList.toggle('ring-primary/20');
});
});
</script>
@endsection