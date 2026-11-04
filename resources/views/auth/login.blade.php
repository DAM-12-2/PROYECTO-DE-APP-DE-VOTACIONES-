<!DOCTYPE html><html lang="es"><head>
<link rel="icon" type="image/png" href="/img/logo.png">
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/css/fuentes.css">
<link rel="stylesheet" href="/css/fuentes.css">
<script src="/js/tailwind.js"></script>
<script src="/assets/js/tailwind-config.js"></script>
<link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex items-center justify-center p-4">
<x-splash />
<div class="w-full max-w-md">
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl overflow-hidden">
<div class="p-8 text-center">
<div class="w-20 h-20 rounded-full overflow-hidden mx-auto mb-4 border border-outline-variant bg-surface-container-lowest">
<img alt="CTP AIRA Logo" class="w-full h-full object-cover" src="/img/logo.png">
</div>
<h1 class="font-headline-lg text-primary mb-1">Elecciones CTP AIRA</h1>
<p class="text-sm text-secondary mb-8">Panel electoral &mdash; Iniciar sesi&oacute;n</p>
<form id="login-form" class="space-y-5" method="POST" action="{{ route('login') }}">
@csrf
<div class="text-left">
<label class="text-xs font-bold text-secondary uppercase mb-1.5 block" for="username">Usuario</label>
<input class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" id="username" name="username" placeholder="Ingrese su usuario" type="text" required autocomplete="username">
</div>
<div class="text-left">
<label class="text-xs font-bold text-secondary uppercase mb-1.5 block" for="password">Contrase&ntilde;a</label>
<input class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" id="password" name="password" placeholder="Ingrese su contrase&ntilde;a" type="password" required autocomplete="current-password">
</div>
@if($errors->any())
<div class="text-left text-error text-sm font-medium bg-error-container/10 p-3 rounded-xl border border-error/20">{{ $errors->first() }}</div>
@endif
<button class="w-full py-3.5 bg-primary text-on-primary rounded-xl font-bold text-sm shadow-md hover:opacity-90 transition-opacity flex items-center justify-center gap-2" type="submit">
<span class="material-symbols-outlined text-sm">login</span> Ingresar
</button>
</form>
</div>
<div class="px-8 pb-6 text-center">
<p class="text-[10px] text-secondary">Colegio T&eacute;cnico Profesional Ambientalista Isa&iacute;as Retana Arias</p>
<x-creditos class="mt-3" />
</div>
</div>
</div>
<script src="/assets/js/login.js"></script>
</body></html>
