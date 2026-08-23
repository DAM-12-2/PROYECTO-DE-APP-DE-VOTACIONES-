@extends('layouts.admin')
@section('title', 'Manual de Usuario — Sistema Electoral CTP AIRA')
@section('content')

<div class="mb-section-margin">
    <h2 class="font-headline-lg text-headline-lg text-primary mb-1">Manual de Usuario</h2>
    <p class="font-body-lg text-body-lg text-on-surface-variant">Guía completa del Sistema Electoral Institucional — CTP AIRA</p>
</div>

{{-- Índice rápido --}}
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 mb-8">
    <h3 class="font-headline-sm text-headline-sm text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">toc</span> Índice
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div>
            <p class="font-bold text-on-surface mb-2">General</p>
            <ul class="space-y-1 text-on-surface-variant">
                <li><a href="#intro" class="text-primary hover:underline">1. Introducción</a></li>
                <li><a href="#login" class="text-primary hover:underline">2. Inicio de Sesión</a></li>
                <li><a href="#roles" class="text-primary hover:underline">3. Roles y Permisos</a></li>
            </ul>
        </div>
        <div>
            <p class="font-bold text-on-surface mb-2">Panel Admin</p>
            <ul class="space-y-1 text-on-surface-variant">
                <li><a href="#admin-dashboard" class="text-primary hover:underline">4. Dashboard</a></li>
                <li><a href="#admin-estudiantes" class="text-primary hover:underline">5. Estudiantes</a></li>
                <li><a href="#admin-config" class="text-primary hover:underline">6. Parámetros</a></li>
                <li><a href="#admin-usuarios" class="text-primary hover:underline">7. Usuarios</a></li>
                <li><a href="#admin-respaldo" class="text-primary hover:underline">8. Respaldo BD</a></li>
                <li><a href="#admin-bitacora" class="text-primary hover:underline">9. Bitácora</a></li>
            </ul>
        </div>
        <div>
            <p class="font-bold text-on-surface mb-2">Tribunal & JRV</p>
            <ul class="space-y-1 text-on-surface-variant">
                <li><a href="#tribunal" class="text-primary hover:underline">10. Panel Tribunal</a></li>
                <li><a href="#tribunal-partidos" class="text-primary hover:underline">11. Partidos y Candidatos</a></li>
                <li><a href="#tribunal-jrv" class="text-primary hover:underline">12. Logística JRV</a></li>
                <li><a href="#tribunal-votacion" class="text-primary hover:underline">13. Apertura / Cierre</a></li>
                <li><a href="#tribunal-resultados" class="text-primary hover:underline">14. Resultados</a></li>
                <li><a href="#jrv-kiosco" class="text-primary hover:underline">15. Kiosco de Votación</a></li>
                <li><a href="#flujo-completo" class="text-primary hover:underline">16. Flujo Completo</a></li>
                <li><a href="#faq" class="text-primary hover:underline">17. Solución de Problemas</a></li>
            </ul>
        </div>
    </div>
</div>

{{-- ========== SECCIÓN 1: INTRODUCCIÓN ========== --}}
<section id="intro" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">info</span> 1. Introducción
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>El <strong>Sistema Electoral Institucional</strong> es una aplicación web para gestionar procesos de votación estudiantil en el Colegio Técnico Profesional Ambientalista Isaías Retana Arias (CTP AIRA).</p>
        <p>Permite administrar padrones de estudiantes, definir partidos políticos estudiantiles, configurar mesas receptoras y urnas electrónicas, realizar votaciones controladas y generar reportes de resultados.</p>
        <div class="bg-primary-container/10 border border-primary/20 rounded-lg p-4">
            <p class="font-bold text-primary mb-2">Tecnologías utilizadas</p>
            <p>Laravel 12 · SQLite · Tailwind CSS · JavaScript vanilla</p>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 2: INICIO DE SESIÓN ========== --}}
<section id="login" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">login</span> 2. Inicio de Sesión
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4">
        <p class="text-on-surface-variant">Para acceder al sistema, abra la página de login e ingrese sus credenciales.</p>
        <p class="text-on-surface-variant"><strong>Dirección:</strong> <code class="bg-surface-container px-2 py-1 rounded text-sm">http://localhost:8000/login</code></p>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-outline-variant rounded-lg overflow-hidden">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="text-left p-3 font-bold">Credenciales</th>
                        <th class="text-left p-3 font-bold">Rol</th>
                        <th class="text-left p-3 font-bold">Panel al que accede</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    <tr>
                        <td class="p-3"><code>admin</code> / <code>admin</code></td>
                        <td class="p-3">Administrador</td>
                        <td class="p-3">/admin — Panel de administración completa</td>
                    </tr>
                    <tr>
                        <td class="p-3"><code>tee</code> / <code>tee</code></td>
                        <td class="p-3">Tribunal Electoral</td>
                        <td class="p-3">/tribunal — Panel electoral SPA</td>
                    </tr>
                    <tr>
                        <td class="p-3"><code>jrv</code> / <code>jrv</code></td>
                        <td class="p-3">Junta Receptora de Votos</td>
                        <td class="p-3">/jrv — Kiosco de votación</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-surface-container-low rounded-lg p-4 mt-4">
            <p class="font-bold text-on-surface mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600">warning</span> Importante
            </p>
            <ul class="list-disc list-inside text-sm text-on-surface-variant space-y-1">
                <li>Si olvida su contraseña, pida al administrador que la restablezca desde <strong>Usuarios</strong>.</li>
                <li>Los usuarios JRV deben cambiar su contraseña en el primer inicio de sesión.</li>
                <li>Para cerrar sesión, haga clic en su nombre de usuario en la barra superior → <em>Cerrar Sesión</em>.</li>
            </ul>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 3: ROLES ========== --}}
<section id="roles" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">shield</span> 3. Roles y Permisos
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
                <h4 class="font-bold text-on-surface">Administrador</h4>
            </div>
            <ul class="text-sm text-on-surface-variant space-y-1">
                <li>• Gestión completa de estudiantes</li>
                <li>• Crear, editar y eliminar usuarios</li>
                <li>• Configurar parámetros del sistema</li>
                <li>• Respaldo de base de datos</li>
                <li>• Ver bitácora de auditoría</li>
                <li>• Activar/desactivar la elección</li>
                <li>• Acceso a todos los reportes</li>
            </ul>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-amber-600">balance</span>
                <h4 class="font-bold text-on-surface">Tribunal Electoral</h4>
            </div>
            <ul class="text-sm text-on-surface-variant space-y-1">
                <li>• Gestionar partidos y candidatos</li>
                <li>• Configurar mesas y secciones</li>
                <li>• Activar/desactivar urnas</li>
                <li>• Abrir y cerrar votaciones</li>
                <li>• Ver resultados en tiempo real</li>
                <li>• Configurar reglas de victoria</li>
                <li>• Registrar incidencias</li>
            </ul>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-green-600">how_to_vote</span>
                <h4 class="font-bold text-on-surface">JRV (Mesa de Votación)</h4>
            </div>
            <ul class="text-sm text-on-surface-variant space-y-1">
                <li>• Buscar estudiantes por cédula</li>
                <li>• Registrar votos</li>
                <li>• Activar/desactivar su urna</li>
                <li>• Solo puede acceder a /jrv</li>
                <li>• No puede ver resultados</li>
                <li>• No puede modificar datos</li>
            </ul>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 4: DASHBOARD ADMIN ========== --}}
<section id="admin-dashboard" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">dashboard</span> 4. Dashboard (Panel Admin)
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>Al iniciar sesión como administrador, se muestra el panel de control principal con un resumen del estado del sistema electoral.</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
            <div class="bg-surface-container-low rounded-lg p-3">
                <span class="material-symbols-outlined text-primary text-2xl">people</span>
                <p class="text-xs mt-1">Total Estudiantes</p>
            </div>
            <div class="bg-surface-container-low rounded-lg p-3">
                <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
                <p class="text-xs mt-1">Ya Votaron</p>
            </div>
            <div class="bg-surface-container-low rounded-lg p-3">
                <span class="material-symbols-outlined text-amber-600 text-2xl">pending</span>
                <p class="text-xs mt-1">Pendientes</p>
            </div>
            <div class="bg-surface-container-low rounded-lg p-3">
                <span class="material-symbols-outlined text-primary text-2xl">percentage</span>
                <p class="text-xs mt-1">Participación</p>
            </div>
        </div>
        <p>Desde el dashboard puede acceder rápidamente a cualquier sección usando el menú lateral izquierdo.</p>
    </div>
</section>

{{-- ========== SECCIÓN 5: ESTUDIANTES ========== --}}
<section id="admin-estudiantes" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">school</span> 5. Gestión de Estudiantes
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>Desde <strong>/admin/estudiantes</strong> puede ver, agregar, editar y eliminar estudiantes del padrón electoral.</p>

        <div class="space-y-3">
            <h4 class="font-bold text-on-surface">Agregar un estudiante</h4>
            <ol class="list-decimal list-inside space-y-1 text-sm">
                <li>Haga clic en el botón <strong>"Añadir Estudiante"</strong> (ícono <span class="material-symbols-outlined text-sm align-middle">person_add</span>).</li>
                <li>Complete los campos:
                    <ul class="ml-6 mt-1 list-disc text-sm">
                        <li><strong>Identificación</strong> — Número de cédula o carnet (único).</li>
                        <li><strong>Nombre</strong> — Nombre del estudiante.</li>
                        <li><strong>Apellidos</strong> — Apellidos del estudiante.</li>
                        <li><strong>Sección</strong> — Grupo/sección del estudiante (ej: "11-1").</li>
                    </ul>
                </li>
                <li>Haga clic en <strong>"Guardar"</strong>.</li>
            </ol>

            <h4 class="font-bold text-on-surface">Importar estudiantes</h4>
            <p class="text-sm">Si tiene un archivo CSV con los datos de los estudiantes, puede importarlos en lote desde el botón <strong>"Importar"</strong>.</p>

            <h4 class="font-bold text-on-surface">Eliminar un estudiante</h4>
            <p class="text-sm">En la lista, haga clic en el ícono de eliminar <span class="material-symbols-outlined text-sm align-middle text-red-600">delete</span> junto al estudiante. Se le pedirá confirmación.</p>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm">
                <p class="font-bold text-amber-800">Nota:</p>
                <p class="text-amber-700">No puede eliminar un estudiante que ya haya votado. El sistema protege la integridad del proceso electoral.</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 6: PARÁMETROS ========== --}}
<section id="admin-config" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">settings</span> 6. Parámetros del Sistema
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>Desde <strong>/admin/configuracion</strong> puede configurar los ajustes generales del sistema electoral.</p>

        <div class="space-y-3">
            <h4 class="font-bold text-on-surface">Toggle de Elección</h4>
            <p class="text-sm">Este interruptor controla el estado del proceso electoral:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Cerrada</strong> (por defecto): Todos los reportes de resultados están accesibles.</li>
                <li><strong>Abierta</strong>: Los reportes de resultados se bloquean automáticamente para proteger la confidencialidad del proceso en curso. Solo se puede ver el padrón.</li>
            </ul>

            <div class="bg-primary-container/10 border border-primary/20 rounded-lg p-3 text-sm">
                <p class="font-bold text-primary">¿Cuándo abrir la elección?</p>
                <p>Cuando todo esté preparado (partidos, candidatos, mesas, urnas activas) y las JRV estén listas para comenzar a recibir votos.</p>
            </div>

            <h4 class="font-bold text-on-surface">Cambiar Contraseña</h4>
            <p class="text-sm">Puede cambiar su propia contraseña desde esta sección. Necesita ingresar su contraseña actual y la nueva contraseña dos veces.</p>

            <h4 class="font-bold text-on-surface">Nombre de la Institución</h4>
            <p class="text-sm">El nombre de la institución se muestra en el encabezado del sistema y en los reportes impresos.</p>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 7: USUARIOS ========== --}}
<section id="admin-usuarios" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">group</span> 7. Gestión de Usuarios
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>Desde <strong>/admin/usuarios</strong> puede crear, editar y eliminar usuarios del sistema.</p>

        <div class="space-y-3">
            <h4 class="font-bold text-on-surface">Crear un usuario</h4>
            <ol class="list-decimal list-inside space-y-1 text-sm">
                <li>Haga clic en <strong>"Crear Usuario"</strong>.</li>
                <li>Ingrese un <strong>nombre de usuario</strong> único.</li>
                <li>Ingrese una <strong>contraseña</strong> (mínimo 6 caracteres) y confírmela.</li>
                <li>Seleccione el <strong>rol</strong>:
                    <ul class="ml-6 mt-1 list-disc">
                        <li><strong>admin</strong> — Acceso total.</li>
                        <li><strong>tee</strong> — Panel del Tribunal Electoral.</li>
                        <li><strong>jrv</strong> — Kiosco de votación.</li>
                    </ul>
                </li>
                <li>Opcionalmente, asigne una <strong>mesa</strong> (solo para usuarios JRV).</li>
                <li>Haga clic en <strong>"Crear Usuario"</strong>.</li>
            </ol>

            <h4 class="font-bold text-on-surface">Cambiar contraseña de otro usuario</h4>
            <p class="text-sm">En la lista, haga clic en <strong>"Editar"</strong> junto al usuario, ingrese la nueva contraseña y guarde.</p>

            <h4 class="font-bold text-on-surface">Eliminar un usuario</h4>
            <p class="text-sm">Haga clic en <strong>"Eliminar"</strong> junto al usuario. <strong>No puede eliminar su propia cuenta.</strong></p>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 8: RESPALDO ========== --}}
<section id="admin-respaldo" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">backup</span> 8. Respaldo de Base de Datos
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>Desde <strong>/admin/respaldar</strong> puede descargar una copia completa de la base de datos del sistema.</p>

        <div class="space-y-3">
            <h4 class="font-bold text-on-surface">Descargar respaldo</h4>
            <p class="text-sm">Haga clic en el botón de respaldo. Se descargará un archivo <code>.sqlite</code> con toda la información del sistema: estudiantes, partidos, votos, configuración, etc.</p>

            <div class="bg-primary-container/10 border border-primary/20 rounded-lg p-3 text-sm">
                <p class="font-bold text-primary">Recomendación</p>
                <p>Realice un respaldo antes de cada elección y después de finalizar el proceso. Guarde el archivo en un lugar seguro.</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 9: BITÁCORA ========== --}}
<section id="admin-bitacora" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">history</span> 9. Bitácora de Auditoría
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>Desde <strong>/admin/bitacora</strong> puede revisar un registro cronológico de todas las acciones realizadas en el sistema.</p>
        <p class="text-sm">La bitácora registra automáticamente: creación/edición/eliminación de registros, votos emitidos, activación/desactivación de urnas y apertura/cierre de la elección. Cada entrada incluye la fecha, hora, usuario responsable y descripción de la acción.</p>
    </div>
</section>

{{-- ========== SECCIÓN 10: TRIBUNAL ========== --}}
<section id="tribunal" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">gavel</span> 10. Panel del Tribunal Electoral
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>El panel del Tribunal Electoral es una aplicación de página única (SPA) que gestiona todo lo relacionado con el proceso electoral. Se accede con el rol <code>tee</code> en <strong>/tribunal</strong>.</p>

        <p>El menú lateral izquierdo muestra las siguientes secciones:</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-outline-variant rounded-lg overflow-hidden">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="text-left p-2 font-bold">Sección</th>
                        <th class="text-left p-2 font-bold">Descripción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    <tr><td class="p-2 font-medium">Dashboard</td><td class="p-2">Resumen del estado de la elección</td></tr>
                    <tr><td class="p-2 font-medium">Estudiantes</td><td class="p-2">Consulta y registro manual de estudiantes</td></tr>
                    <tr><td class="p-2 font-medium">Tribunal Estudiantil</td><td class="p-2">Información del tribunal electoral</td></tr>
                    <tr><td class="p-2 font-medium">Partidos</td><td class="p-2">Crear y gestionar partidos políticos estudiantiles</td></tr>
                    <tr><td class="p-2 font-medium">Logística JRV</td><td class="p-2">Configurar mesas, secciones y urnas</td></tr>
                    <tr><td class="p-2 font-medium">Votación</td><td class="p-2">Abrir y cerrar el proceso de votación</td></tr>
                    <tr><td class="p-2 font-medium">Resultados</td><td class="p-2">Ver resultados parciales y finales</td></tr>
                    <tr><td class="p-2 font-medium">Estructura</td><td class="p-2">Estructura organizativa del tribunal</td></tr>
                    <tr><td class="p-2 font-medium">Ajustes</td><td class="p-2">Configurar umbral mínimo y reglas de victoria</td></tr>
                    <tr><td class="p-2 font-medium">Ayuda</td><td class="p-2">Soporte técnico y manual de usuario</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 11: PARTIDOS Y CANDIDATOS ========== --}}
<section id="tribunal-partidos" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">flag</span> 11. Partidos y Candidatos
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <h4 class="font-bold text-on-surface">Crear un partido</h4>
        <ol class="list-decimal list-inside space-y-1 text-sm">
            <li>En el menú lateral, haga clic en <strong>"Partidos"</strong>.</li>
            <li>Haga clic en <strong>"Crear Partido"</strong>.</li>
            <li>Ingrese las <strong>siglas</strong> (ej: "PVA") y el <strong>nombre completo</strong> del partido.</li>
            <li>Haga clic en <strong>"Guardar"</strong>.</li>
        </ol>

        <h4 class="font-bold text-on-surface mt-4">Asignar candidatos</h4>
        <ol class="list-decimal list-inside space-y-1 text-sm">
            <li>Para cada partido, seleccione un candidato para cada <strong>puesto</strong> (Presidente, Vicepresidente, Tesorero, Secretario).</li>
            <li>Los candidatos deben ser estudiantes registrados en el padrón.</li>
            <li>Cada estudiante puede ser candidato de un solo partido.</li>
        </ol>

        <div class="bg-primary-container/10 border border-primary/20 rounded-lg p-3 text-sm">
            <p class="font-bold text-primary">Antes de abrir la votación</p>
            <p>Asegúrese de que todos los partidos tengan candidatos asignados para todos los puestos. De lo contrario, la papeleta de votación estará incompleta.</p>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 12: LOGÍSTICA JRV ========== --}}
<section id="tribunal-jrv" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">apartment</span> 12. Logística JRV
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>La logística de las Juntas Receptoras de Votos se configura desde la sección <strong>"Logística JRV"</strong> del Tribunal.</p>

        <div class="space-y-3">
            <h4 class="font-bold text-on-surface">1. Crear Mesas</h4>
            <p class="text-sm">Cada mesa es una estación de votación física. Para crear una:</p>
            <ol class="list-decimal list-inside text-sm space-y-1">
                <li>Ingrese el <strong>número/nombre</strong> de la mesa (ej: "Mesa 1").</li>
                <li>Opcionalmente, indique la <strong>ubicación</strong> (ej: "Aula 1").</li>
                <li>Asigne las <strong>secciones</strong> de estudiantes que atenderá esa mesa (ej: "11-1", "11-2").</li>
            </ol>

            <h4 class="font-bold text-on-surface mt-4">2. Crear Urnas</h4>
            <p class="text-sm">Las urnas son los dispositivos electrónicos donde se depositan los votos. Cada urna está asociada a una mesa:</p>
            <ol class="list-decimal list-inside text-sm space-y-1">
                <li>Ingrese el <strong>código</strong> de la urna (ej: "U001").</li>
                <li>Seleccione la <strong>mesa</strong> a la que pertenece.</li>
            </ol>

            <h4 class="font-bold text-on-surface mt-4">3. Activar Urnas</h4>
            <p class="text-sm">Las urnas deben estar en estado <strong>activo</strong> para recibir votos. Puede activarlas desde el panel del Tribunal o desde el kiosco JRV. También puede desactivarlas temporalmente si es necesario.</p>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm">
                <p class="font-bold text-amber-800">Punto de control</p>
                <p class="text-amber-700">Antes de abrir la votación, verifique que haya al menos una urna activa. Sin urnas activas, ningún kiosco JRV podrá emitir votos.</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 13: APERTURA / CIERRE ========== --}}
<section id="tribunal-votacion" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">ballot</span> 13. Apertura y Cierre de Votación
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>El control de apertura y cierre de la votación se gestiona desde el panel del Tribunal o del Admin.</p>

        <div class="space-y-3">
            <h4 class="font-bold text-on-surface">Abrir la elección</h4>
            <ol class="list-decimal list-inside text-sm space-y-1">
                <li>Verifique que todo esté preparado: partidos con candidatos, mesas configuradas, urnas activas.</li>
                <li>Vaya a <strong>Panel Admin → Parámetros</strong> o <strong>Tribunal → Ajustes</strong>.</li>
                <li>Active el interruptor de <strong>"Elección Abierta"</strong>.</li>
                <li>Los reportes de resultados se bloquearán automáticamente hasta que se cierre la elección.</li>
            </ol>

            <h4 class="font-bold text-on-surface mt-4">Cerrar la elección</h4>
            <ol class="list-decimal list-inside text-sm space-y-1">
                <li>Cuando todos los estudiantes hayan votado (o se agote el tiempo), desactive el interruptor.</li>
                <li>Los reportes de resultados volverán a estar disponibles.</li>
                <li>Puede entonces generar los reportes oficiales de cierre.</li>
            </ol>

            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm">
                <p class="font-bold text-green-800">Efecto del toggle</p>
                <ul class="list-disc list-inside text-green-700 mt-1">
                    <li><strong>Elección Abierta:</strong> Se bloquean los reportes: Acta de Cierre, Acta de Resultados, Resultados y Padrón de Votos.</li>
                    <li><strong>Elección Cerrada:</strong> Todos los reportes quedan accesibles.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 14: RESULTADOS ========== --}}
<section id="tribunal-resultados" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">bar_chart</span> 14. Resultados
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>Los resultados se pueden consultar desde el panel del Tribunal en la sección <strong>"Resultados"</strong> o a través de los reportes del Admin.</p>

        <div class="space-y-3">
            <h4 class="font-bold text-on-surface">Reportes disponibles</h4>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Padrón Electoral</strong> — Lista de todos los estudiantes hábiles para votar.</li>
                <li><strong>Padrón con Firmas</strong> — Lista para firmas de los votantes.</li>
                <li><strong>Padrón de Votos</strong> — Lista de quienes ya votaron (bloqueado durante elección abierta).</li>
                <li><strong>Acta de Apertura</strong> — Documento oficial de apertura de la votación.</li>
                <li><strong>Acta de Cierre</strong> — Documento oficial de cierre (bloqueado durante elección abierta).</li>
                <li><strong>Acta de Resultados</strong> — Resultado final por partido y puesto (bloqueado durante elección abierta).</li>
                <li><strong>Conteo de Cero Votos</strong> — Candidatos sin votos.</li>
                <li><strong>Incidentes</strong> — Registro de incidencias reportadas durante la votación.</li>
                <li><strong>Instrucciones</strong> — Guía de uso para las JRV.</li>
            </ul>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 15: KIOSCO JRV ========== --}}
<section id="jrv-kiosco" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">how_to_vote</span> 15. Kiosco de Votación (JRV)
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>El kiosco de votación es la interfaz que usan las Juntas Receptoras de Votos para emitir los sufragios. Se accede con el rol <code>jrv</code> en <strong>/jrv</strong>.</p>

        <div class="space-y-4">
            <div class="bg-surface-container-low rounded-lg p-4">
                <h4 class="font-bold text-on-surface mb-2">Paso 1: Activar la urna</h4>
                <p class="text-sm">Antes de comenzar a recibir votos, la JRV debe activar su urna:</p>
                <ol class="list-decimal list-inside text-sm space-y-1 mt-1">
                    <li>Inicie sesión con las credenciales JRV.</li>
                    <li>En el panel, haga clic en <strong>"Activar Urna"</strong>.</li>
                    <li>Ingrese el código de la urna asignada (ej: "U001").</li>
                </ol>
                <p class="text-sm mt-2 text-red-600">⚠ Si la urna no está activa, el sistema rechazará todos los votos con el error: "No hay una urna activa".</p>
            </div>

            <div class="bg-surface-container-low rounded-lg p-4">
                <h4 class="font-bold text-on-surface mb-2">Paso 2: Buscar al estudiante</h4>
                <ol class="list-decimal list-inside text-sm space-y-1">
                    <li>Ingrese el <strong>número de identificación</strong> del estudiante en el campo de búsqueda.</li>
                    <li>Haga clic en <strong>"Buscar"</strong>.</li>
                    <li>El sistema mostrará los datos del estudiante (nombre, apellidos, sección) y si ya votó o no.</li>
                </ol>
            </div>

            <div class="bg-surface-container-low rounded-lg p-4">
                <h4 class="font-bold text-on-surface mb-2">Paso 3: Emitir el voto</h4>
                <ol class="list-decimal list-inside text-sm space-y-1">
                    <li>Verifique que el estudiante <strong>no haya votado</strong> (aparecerá "Puede votar").</li>
                    <li>El estudiante selecciona su <strong>partido</strong> preferido.</li>
                    <li>Haga clic en <strong>"Votar"</strong>.</li>
                    <li>El sistema registrará el voto de forma encriptada y marcará al estudiante como votado.</li>
                    <li>Aparecerá el mensaje: <em>"Voto registrado correctamente"</em>.</li>
                </ol>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="font-bold text-red-800 mb-2">Errores comunes en el kiosco</h4>
                <ul class="text-sm text-red-700 space-y-2">
                    <li><strong>"Estudiante no encontrado"</strong> (404) → El número de identificación no existe en el padrón. Verifique los datos o registre al estudiante desde el Panel Admin.</li>
                    <li><strong>"Este estudiante ya votó"</strong> (409) → El estudiante ya emitió su voto. No puede votar dos veces.</li>
                    <li><strong>"No hay una urna activa"</strong> (400) → Active la urna desde el panel JRV o pida al Tribunal que la active.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 16: FLUJO COMPLETO ========== --}}
<section id="flujo-completo" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">route</span> 16. Flujo Completo de una Votación Simulada
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4 text-on-surface-variant">
        <p>A continuación se describe el proceso completo, paso a paso, para realizar una votación simulada de principio a fin.</p>

        <div class="space-y-6">
            {{-- Fase 1 --}}
            <div class="border-l-4 border-primary pl-4">
                <h4 class="font-bold text-on-surface text-lg mb-2">FASE 1: Preparación (Panel Admin)</h4>
                <p class="text-sm mb-2"><strong>Usuario:</strong> admin / admin — <strong>Ruta:</strong> /admin</p>
                <ol class="list-decimal list-inside text-sm space-y-2">
                    <li><strong>Registrar estudiantes</strong> → Sección "Estudiantes" → Añadir cada estudiante con su cédula, nombre, apellidos y sección.</li>
                    <li><strong>Crear usuarios JRV</strong> → Sección "Usuarios" → Crear un usuario por cada mesa de votación con rol "jrv".</li>
                </ol>
            </div>

            {{-- Fase 2 --}}
            <div class="border-l-4 border-amber-500 pl-4">
                <h4 class="font-bold text-on-surface text-lg mb-2">FASE 2: Configuración Electoral (Panel Tribunal)</h4>
                <p class="text-sm mb-2"><strong>Usuario:</strong> tee / tee — <strong>Ruta:</strong> /tribunal</p>
                <ol class="list-decimal list-inside text-sm space-y-2">
                    <li><strong>Crear partidos</strong> → Sección "Partidos" → Registrar cada partido con sus siglas y nombre.</li>
                    <li><strong>Asignar candidatos</strong> → Para cada partido, asignar un candidato a cada puesto (Presidente, Vicepresidente, Tesorero, Secretario).</li>
                    <li><strong>Crear mesas</strong> → Sección "Logística JRV" → Crear mesas y asignar secciones de estudiantes a cada una.</li>
                    <li><strong>Crear urnas</strong> → Asociar una urna a cada mesa con un código único.</li>
                    <li><strong>Activar urnas</strong> → Asegurarse de que las urnas estén en estado activo.</li>
                </ol>
            </div>

            {{-- Fase 3 --}}
            <div class="border-l-4 border-green-500 pl-4">
                <h4 class="font-bold text-on-surface text-lg mb-2">FASE 3: Votación (Kiosco JRV)</h4>
                <p class="text-sm mb-2"><strong>Usuario:</strong> jrv / jrv — <strong>Ruta:</strong> /jrv</p>
                <ol class="list-decimal list-inside text-sm space-y-2">
                    <li><strong>Activar urna</strong> → Ingresar código de la urna asignada.</li>
                    <li><strong>Por cada votante:</strong>
                        <ul class="ml-6 mt-1 list-disc text-sm space-y-1">
                            <li>Buscar estudiante por número de identificación.</li>
                            <li>Verificar que no haya votado.</li>
                            <li>El estudiante selecciona su partido.</li>
                            <li>Confirmar el voto.</li>
                        </ul>
                    </li>
                    <li><strong>Cerrar la sesión JRV</strong> cuando todos hayan votado.</li>
                </ol>
            </div>

            {{-- Fase 4 --}}
            <div class="border-l-4 border-red-500 pl-4">
                <h4 class="font-bold text-on-surface text-lg mb-2">FASE 4: Cierre y Resultados (Panel Admin o Tribunal)</h4>
                <p class="text-sm mb-2"><strong>Usuario:</strong> admin o tee — <strong>Ruta:</strong> /admin o /tribunal</p>
                <ol class="list-decimal list-inside text-sm space-y-2">
                    <li><strong>Cerrar la elección</strong> → Desactivar el interruptor "Elección Abierta" en Parámetros.</li>
                    <li><strong>Ver resultados</strong> → Los reportes de resultados quedan disponibles.</li>
                    <li><strong>Generar reportes</strong> → Acta de cierre, acta de resultados, padrón de votos.</li>
                    <li><strong>Descargar respaldo</strong> → Sección "Respaldar BD" → Guardar una copia de la base de datos.</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- ========== SECCIÓN 17: FAQ / SOLUCIÓN DE PROBLEMAS ========== --}}
<section id="faq" class="mb-10">
    <h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">help</span> 17. Solución de Problemas
    </h2>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-4">

        <div class="space-y-4">
            <div class="border-b border-outline-variant pb-4">
                <h4 class="font-bold text-on-surface">El estudiante no aparece en la búsqueda del kiosco</h4>
                <p class="text-sm text-on-surface-variant mt-1">Verifique que el número de identificación sea correcto. Si el estudiante no está registrado, pida al administrador que lo agregue desde el Panel Admin → Estudiantes.</p>
            </div>

            <div class="border-b border-outline-variant pb-4">
                <h4 class="font-bold text-on-surface">El kiosco dice "No hay una urna activa"</h4>
                <p class="text-sm text-on-surface-variant mt-1">Active la urna desde el panel JRV o pida al Tribunal que la active desde Logística JRV. Asegúrese de ingresar el código correcto de la urna.</p>
            </div>

            <div class="border-b border-outline-variant pb-4">
                <h4 class="font-bold text-on-surface">Los reportes de resultados no se pueden abrir</h4>
                <p class="text-sm text-on-surface-variant mt-1">Esto es normal cuando la elección está abierta. Los reportes de resultados se bloquean automáticamente para proteger la confidencialidad. Cierre la elección desde Parámetros para acceder a ellos.</p>
            </div>

            <div class="border-b border-outline-variant pb-4">
                <h4 class="font-bold text-on-surface">No puedo cambiar la contraseña</h4>
                <p class="text-sm text-on-surface-variant mt-1">Verifique que la contraseña actual sea correcta. La nueva contraseña debe tener al menos 6 caracteres y debe confirmarla escribiéndola dos veces.</p>
            </div>

            <div class="border-b border-outline-variant pb-4">
                <h4 class="font-bold text-on-surface">Un estudiante ya votó pero no debería</h4>
                <p class="text-sm text-on-surface-variant mt-1">Esto puede ocurrir si un estudiante tiene dos registros con la misma identificación. Contacte al administrador para verificar la integridad del padrón.</p>
            </div>

            <div class="border-b border-outline-variant pb-4">
                <h4 class="font-bold text-on-surface">Olvidé mi contraseña de administrador</h4>
                <p class="text-sm text-on-surface-variant mt-1">Si tiene acceso a la base de datos, puede restablecerla directamente. De lo contrario, contacte al desarrollador del sistema.</p>
            </div>

            <div>
                <h4 class="font-bold text-on-surface">Necesito ayuda adicional</h4>
                <p class="text-sm text-on-surface-variant mt-1">Contacte al Departamento de Informática del CTP AIRA para soporte técnico.</p>
            </div>
        </div>
    </div>
</section>

{{-- Footer --}}
<div class="text-center text-sm text-on-surface-variant py-8 border-t border-outline-variant">
    <p>Sistema Electoral Institucional — CTP AIRA · Versión 1.0</p>
    <p class="mt-1">Para imprimir esta página como PDF, use <kbd class="bg-surface-container px-2 py-0.5 rounded border border-outline-variant text-xs">Ctrl+P</kbd> (Windows) o <kbd class="bg-surface-container px-2 py-0.5 rounded border border-outline-variant text-xs">⌘+P</kbd> (Mac) y seleccione "Guardar como PDF".</p>
</div>

@endsection
