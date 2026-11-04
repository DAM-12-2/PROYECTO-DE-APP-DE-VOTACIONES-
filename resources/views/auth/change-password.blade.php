<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cambiar contraseña — Sistema Electoral</title>
    <script src="/js/tailwind.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: Inter, system-ui, sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900">Cambiar contraseña</h1>
            <p class="mt-1 text-sm text-slate-500">
                Por seguridad, debe actualizar su contraseña antes de continuar.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Contraseña actual</label>
                <input type="password" name="current_password" required autocomplete="current-password"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-slate-900">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Nueva contraseña</label>
                <input type="password" name="password" required minlength="8" autocomplete="new-password"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-slate-900">
                <p class="mt-1 text-xs text-slate-500">Mínimo 8 caracteres.</p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-slate-900">
            </div>
            <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2.5 font-semibold text-white hover:bg-slate-800 transition">
                Actualizar contraseña
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="text-sm text-slate-500 underline hover:text-red-600">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
