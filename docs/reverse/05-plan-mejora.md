# 05 — Plan de mejora y rediseño

Objetivo: conservar la funcionalidad del sistema legado, cerrar sus fallas y
modernizar la interfaz, manteniendo el stack actual (Laravel 12 + Blade +
Tailwind + JS vanilla).

## Fase 1 — Integridad del voto ✅ aplicada

1. `votes.student_id` con índice único (migración `2026_11_04_000001`).
2. Emisión dentro de `DB::transaction()` con `lockForUpdate()` sobre el estudiante.
3. La urna activa debe pertenecer a la mesa del usuario JRV.
4. Registro en bitácora de cada emisión (sin datos del voto).
5. `tests/Feature/VotoIntegridadTest.php`: doble voto rechazado con 409.

## Fase 2 — Controles electorales

1. ✅ Tiempo máximo de sesión (`tiempo_votacion`, 90 s por defecto) con cronómetro,
   barra de progreso y auto-emisión al agotarse.
2. ✅ Bloqueo del padrón con elección abierta (`block.padron`).
3. ❌ Verificación de conteo: recalcular y comparar contra un hash guardado al
   cerrar cada mesa (sustituye a `tajustes`/`tcomp`, con una sola fuente de verdad).
4. ✅ Voto en blanco explícito (`party_id = 0`), contabilizado aparte en el escrutinio.
5. ❌ Sincronización en tiempo real: Laravel Reverb (WebSocket) en lugar del polling.
6. ✅ Regla de ganador configurable (`requisito_ganar`, `porc_minimo`,
   `base_porcentaje`, `sumar_n`).
7. ✅ Desbloqueo de estudiante con anulación de su voto.

## Fase 3 — Rediseño de interfaz

Problemas del diseño legado: fondo de imagen fija, fuente Century Gothic,
jQuery 1.8.2, tablas sin responsive, hacks para IE6-8, todo el estilo inline.

Propuesta:

| Área | Cambio |
|---|---|
| Sistema de diseño | Tokens CSS (color, espacio, tipografía) en un solo archivo; escala 4/8 px |
| Tipografía | Una sola familia variable del sistema; sin webfonts de 1998 |
| Layout admin | Sidebar colapsable, breadcrumb, tabla con estados vacíos y skeletons |
| Urna/kiosco | ✅ Pantalla completa, tipografía grande, cronómetro, estados de foco y contraste |
| Accesibilidad | Foco visible, `aria-live` en mensajes, contraste AA, navegación por teclado |
| Responsive | Mobile-first para JRV (tablet) y escritorio para admin |
| Componentes | Un `<x-*>` por patrón repetido (badge de estado, modal, alerta, tabla) |
| Impresión | Actas y padrón con hoja de estilo `@media print` dedicada |

Se mantiene Blade + Tailwind ya presentes; no se introduce framework JS.

## Fase 4 — Operación

1. Migrar de SQLite a MySQL/PostgreSQL para producción (SQLite no soporta
   concurrencia real de escritura).
2. Respaldos fuera del webroot y con retención.
3. Registro estructurado (canal `election`) para auditar apertura, voto y cierre.
4. Pruebas de carga del flujo de voto.

## Orden recomendado

```
Fase 1 ✅ → Fase 2.1-2.2 ✅ → Fase 3 (kiosco ✅, admin pendiente)
→ Fase 2.3-2.5 → Fase 4
```

## Cambios aplicados en esta iteración

| Archivo | Cambio |
|---|---|
| `database/migrations/2026_11_04_000001_add_student_id_to_votes_table.php` | `student_id` único en `votes` |
| `app/Http/Controllers/VoteController.php` | Transacción, lock, validación de mesa, bitácora |
| `app/Http/Controllers/StudentController.php` | `desbloquear()` |
| `app/Http/Middleware/BlockPadronDuringVoting.php` | Bloqueo de padrón |
| `app/Services/VoteTallyService.php` | Regla de ganador configurable y voto en blanco |
| `resources/views/layouts/voting.blade.php` | Rediseño del kiosco |
| `resources/views/voting/urna.blade.php` | Pantallas de identificación, voto, confirmación y éxito |
| `public/js/voting.js` | Cronómetro, auto-emisión, voto en blanco, estilos |
| `database/seeders/DatabaseSeeder.php` | JRV con mesa asignada |
| `tests/Feature/{VotoIntegridad,BloqueoPadron,Desbloqueo,ReglaGanador,Kiosco}Test.php` | Cobertura nueva |
