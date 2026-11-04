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

1. **PC1 registro** (`/registro`): el votante ingresa su identificación; se crea una solicitud `pendiente`.
2. **PC2 JRV** (`/jrv`): recibe la solicitud en vivo, la aprueba y asigna una urna libre.
3. **PC3 urna** (`/kiosko`): se activa con código `U001` + usuario/contraseña JRV, recibe al estudiante y registra el voto.
4. **Cierre**: el voto marca la solicitud como `votada`, libera la urna y PC1 lo refleja; al cerrar la elección se sella el conteo (`/admin/integridad`, `php artisan tally:verify`).

La comunicación entre terminales usa **Laravel Reverb** (WebSocket local): `php artisan reverb:start --host=0.0.0.0 --port=8080`.
Si el WebSocket cae, el polling de 2–3 segundos mantiene el ciclo operativo.

## Seguridad local

- `/api/*` exige sesión y rol; el padrón ya no es público.
- La JRV solo puede buscar y registrar estudiantes de su mesa.
- Cabeceras CSP, X-Frame-Options y nosniff en todas las respuestas.
- Contraseñas con Argon2id y cambio obligatorio inicial.
- CSV neutralizado contra fórmulas y limitado a 10 000 filas.
- Todos los assets (Tailwind, Chart.js, iconos y fuentes) son locales: funciona sin internet.

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
