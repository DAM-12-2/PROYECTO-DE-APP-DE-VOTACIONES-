<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/img/logo.png">
    <title>Acerca del sistema — CTP AIRA</title>
    <script src="/js/tailwind.js"></script>
    <link rel="stylesheet" href="/css/fuentes.css">
    <script src="/assets/js/tailwind-config.js"></script>
    <style>body { font-family: Inter, system-ui, sans-serif; }</style>
</head>
<body class="min-h-screen bg-background text-on-background flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="overflow-hidden rounded-2xl border border-outline-variant bg-surface-container-lowest shadow-xl">
            <div class="border-b border-outline-variant bg-primary px-8 py-8 text-center text-on-primary">
                <img src="/img/logo.png" alt="CTP AIRA" class="mx-auto mb-4 h-20 w-20 rounded-full border-2 border-white/30 object-cover">
                <h1 class="font-headline-lg text-headline-lg">Sistema Electoral Estudiantil</h1>
                <p class="mt-1 text-sm opacity-80">CTP Ambientalista Isaías Retana Arias</p>
                <p class="text-sm opacity-80">Pedregoso · Buenos Aires · Puntarenas</p>
            </div>

            <div class="p-8">
                <p class="text-xs font-bold uppercase tracking-widest text-secondary">Desarrollado por</p>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-outline-variant bg-surface p-5">
                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-primary text-on-primary font-bold">JG</div>
                        <p class="font-headline-sm text-headline-sm text-primary">Jefferson G.</p>
                        <p class="mt-1 text-sm text-on-surface-variant">Desarrollo, base de datos y seguridad</p>
                    </div>

                    <div class="rounded-xl border border-outline-variant bg-surface p-5">
                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-secondary text-on-secondary font-bold">SM</div>
                        <p class="font-headline-sm text-headline-sm text-primary">Sahir M.</p>
                        <p class="mt-1 text-sm text-on-surface-variant">Diseño de interfaz y pruebas</p>
                    </div>
                </div>

                <div class="mt-6 rounded-xl border border-outline-variant bg-surface p-5 text-sm text-on-surface-variant">
                    <p><strong class="text-on-surface">Versión:</strong> 1.0 · {{ date('Y') }}</p>
                    <p class="mt-1"><strong class="text-on-surface">Función:</strong> registro de votantes, aprobación por JRV, urna electrónica, escrutinio y actas.</p>
                    <p class="mt-1"><strong class="text-on-surface">Operación:</strong> red local del centro educativo, sin dependencia de internet.</p>
                </div>

                <div class="mt-6 flex justify-center">
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-on-primary transition hover:bg-primary-container">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <x-creditos class="mt-4" />
    </div>
</body>
</html>
