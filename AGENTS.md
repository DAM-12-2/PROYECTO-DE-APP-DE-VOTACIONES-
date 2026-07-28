# AGENTS.md -- Sistema Votaciones CTP AIRA

Idioma: español.

## Stack
Laravel 12 + SQLite + Tailwind CSS + JavaScript vanilla. HTML estático standalone en `Html/`.

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
| Servidor HTML | `python -m http.server 8080` |
| Login HTML | `http://localhost:8000/Html/login.html` |
| Login Laravel | `http://localhost:8000/login` |

## Estructura clave
- `Html/login.html` -- Login standalone (39 lns)
- `Html/Tribunal_est.html` -- SPA standalone (142 lns)
- `assets/js/` -- login.js, app.js, tailwind-config.js
- `assets/css/styles.css` -- Estilos nav-link.active
- `resources/views/layouts/admin.blade.php` -- Layout admin Laravel (168 lns)
- `public/css/admin.css` -- CSS extraído del admin
- `public/js/admin-config.js` -- tailwind.config extraído del admin
- `public/Html` -> `../../Html` (symlink)
- `public/assets` -> `../../assets` (symlink)

## Cambios recientes
- CSS/JS inline de admin.blade.php extraído a `public/css/admin.css` y `public/js/admin-config.js`
- Logout redirige según rol: admin -> `/Html/login.html`, tribunal -> `/Html/Tribunal_est.html`

## Documentación completa en docs/modules/
Para profundizar en cualquier tema, leer el archivo correspondiente.
