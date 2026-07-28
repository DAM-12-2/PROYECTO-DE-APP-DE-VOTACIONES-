@extends('layouts.admin')
@section('title', 'Gestión de Usuarios')
@section('content')
<div class="mb-section-margin">
<h2 class="font-headline-lg text-headline-lg text-primary mb-1">Gestión de Usuarios</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Administración de acceso al sistema.</p>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden h-fit">
<div class="border-b border-outline-variant px-6 py-4 bg-surface-container-low flex items-center gap-3">
<span class="material-symbols-outlined text-primary">admin_panel_settings</span>
<h3 class="font-headline-sm text-headline-sm text-primary">Cambiar Contraseña Administrador</h3>
</div>
<div class="p-6 space-y-4">
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Contraseña Actual</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" type="password"/>
</div>
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Nueva Contraseña</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" type="password"/>
</div>
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Confirmar Nueva Contraseña</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" type="password"/>
</div>
<button class="mt-4 bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg px-4 py-2 rounded-lg transition-colors w-full">Actualizar Contraseña</button>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden h-fit">
<div class="border-b border-outline-variant px-6 py-4 bg-surface-container-low flex items-center gap-3">
<span class="material-symbols-outlined text-primary">person_add</span>
<h3 class="font-headline-sm text-headline-sm text-primary">Añadir Miembro Tribunal</h3>
</div>
<div class="p-6 space-y-4">
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Nombre</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" type="text"/>
</div>
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Usuario (Login)</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" type="text"/>
</div>
<div>
<label class="block font-label-lg text-label-lg text-on-surface mb-1">Contraseña Provisional</label>
<input class="w-full px-3 py-2 bg-surface rounded border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface transition-all" type="password"/>
</div>
<button class="mt-4 bg-surface hover:bg-surface-container border border-outline font-label-lg text-label-lg text-on-surface px-4 py-2 rounded-lg transition-colors w-full flex justify-center items-center gap-2">
<span class="material-symbols-outlined" style="font-size:18px">add</span> Crear Usuario
</button>
</div>
</div>
</div>
@endsection
