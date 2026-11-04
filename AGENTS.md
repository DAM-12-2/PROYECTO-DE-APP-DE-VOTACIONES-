# AGENTS.md -- Sistema Votaciones CTP AIRA

Idioma: español.

## Stack
Laravel 12 + SQLite + Tailwind CSS + JavaScript vanilla. Las vistas Blade reemplazaron los HTML standalone.

## Reglas de trabajo
1. Código mínimo necesario. Sin flexibilidad futura.
2. Toca solo las líneas necesarias. Sin refactorizar no-relacionado.
3. Antes de escribir, lee los archivos relevantes.
4. Si fallas 2+ veces, detente y pide ayuda.
5. Verifica antes de dar por terminado (abrir en navegador, `php artisan serve`).

## Comandos esenciales
| Acción | Comando |
|--------|---------|
| Servidor Laravel | `php artisan serve --port=8000` |
| Login (todos) | `http://localhost:8000/login` |

## Usuarios de prueba
| Usuario | Contraseña | Rol | Ruta |
|---------|-----------|-----|------|
| admin | admin | admin | /admin |
| tee | tee | tee (Tribunal) | /tribunal |
| jrv | jrv | jrv (JRV) | /jrv |

## Flujo de operación

1. **Admin/TEE** (`/login` → `/admin` o `/tribunal`): configura institución, partidos, mesas, secciones, candidatos, usuarios y abre/cierra la elección.
2. **JRV** (`/login` → `/jrv`): busca al estudiante, lo asigna a una urna disponible y la urna se libera al votar.
3. **Urna** (`/kiosko`): se activa con código de urna (`U001`) + usuario/contraseña JRV, espera la asignación y muestra la papeleta.
4. **Cierre**: al cerrar la elección se sella el conteo por mesa (SHA-256); `/admin/integridad` y `php artisan tally:verify` detectan alteraciones.

## Estructura clave
- `app/Http/Controllers/KioskoController.php` -- activación, estado y cierre de urna
- `public/js/voting.js` -- kiosco: polling, cronómetro, voto en blanco
- `assets/js/jrv.js` -- portal JRV: búsqueda, urnas, asignación
- `resources/views/layouts/tribunal.blade.php` -- navegación server-side del panel TEE
- `resources/views/layouts/voting.blade.php` -- layout del kiosco
- `public/assets` -> `../../assets` (symlink)

## Usuarios y urnas de prueba
| Usuario | Contraseña | Rol | Mesa |
|---------|-----------|-----|------|
| admin | admin | admin | — |
| tee | tee | TEE | — |
| jrv | jrv | JRV | Mesa 1 |

Urnas sembradas: `U001` (Mesa 1), `U002` (Mesa 2), `U003` (Mesa 3, inactiva).

## Cambios recientes
- `/Html/` eliminado (login.html, Tribunal_est.html, admin.html) — convertido a Blade
- Login: `resources/views/auth/login.blade.php`
- Admin: 7 vistas en `resources/views/admin/`
- Tribunal: layout SPA con app.js (dashboard, estudiantes, tribunal-estudiantil, partidos, jrv, votaciones, resultados, estructura, ayuda, configuracion)
- JRV: vista básica en `resources/views/jrv/index.blade.php`
- Logout redirige a `/login` (Blade) para todos los roles
- AuthController login redirige según rol: admin→/admin, tee→/tribunal, jrv→/jrv

## Documentación completa en docs/modules/
Para profundizar en cualquier tema, leer el archivo correspondiente.
