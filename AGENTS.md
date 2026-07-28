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

## Estructura clave
- `assets/js/app.js` -- SPA del Tribunal (616 lns, 10 secciones vía templates JS + modal system)
- `assets/js/login.js` -- Lógica login standalone
- `assets/js/tailwind-config.js` -- Config Tailwind
- `assets/css/styles.css` -- Estilos nav-link.active
- `resources/views/layouts/tribunal.blade.php` -- Layout Tribunal (incluye app.js como SPA)
- `resources/views/layouts/admin.blade.php` -- Layout admin Laravel
- `resources/views/layouts/voting.blade.php` -- Layout kiosko/urna
- `public/assets` -> `../../assets` (symlink)
- `public/css/admin.css` -- CSS admin
- `public/js/admin-config.js` -- tailwind.config admin

## Tribunal SPA
El layout `tribunal.blade.php` incluye `assets/js/app.js` que maneja toda la navegación del lado cliente (SPA). Las rutas `/tribunal`, `/tribunal/estudiantes`, `/tribunal/configuracion` cargan el mismo layout y app.js determina qué sección mostrar según el pathname. Los enlaces del sidebar usan `data-target` para navegación SPA con `e.preventDefault()`.

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
