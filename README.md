# Sistema de Votaciones Estudiantiles -- CTP AIRA

Proyecto híbrido: app Laravel 12 con backend + panel HTML standalone (sin backend).

## Stack
Laravel 12 + SQLite + Tailwind CSS + Phosphor Icons + JavaScript vanilla

## Comandos esenciales

| Acción | Comando |
|--------|---------|
| Servidor Laravel | `php artisan serve --port=8000` |
| Servidor HTML | `python -m http.server 8080` |
| Login Laravel | `http://127.0.0.1:8000/login` |
| Login HTML | `http://127.0.0.1:8000/Html/login.html` |
| Panel HTML | `http://127.0.0.1:8000/Html/Tribunal_est.html` |
| Migrar BD | `php artisan migrate --seed` |

## Archivos clave

### HTML standalone
| Archivo | Rol |
|---------|-----|
| `Html/login.html` | Página de login (39 lns) |
| `Html/Tribunal_est.html` | SPA panel electoral (142 lns) |
| `assets/js/login.js` | Validación login (17 lns) |
| `assets/js/app.js` | Router y templates SPA (481 lns) |
| `assets/css/styles.css` | Estilos nav-link (6 lns) |
| `public/Html/` | Symlink a Html/ |
| `public/assets/` | Symlink a assets/ |

### Laravel admin
| Archivo | Rol |
|---------|-----|
| `resources/views/layouts/admin.blade.php` | Layout admin (168 lns) |
| `public/css/admin.css` | CSS extraído (body, scrollbar) |
| `public/js/admin-config.js` | tailwind.config extraído |

### Cambios aplicados
- CSS y tailwind.config inline extraídos a archivos externos
- Logout redirige según rol: admin -> `/Html/login.html`, tribunal -> `/Html/Tribunal_est.html`

## Documentación
| Categoría | Archivo |
|-----------|---------|
| Stack | `docs/modules/stack.md` |
| Estructura | `docs/modules/estructura.md` |
| Inicio | `docs/modules/startup-flow.md` |
| Despliegue | `docs/modules/deploy.md` |
| Workflow (Git Flow) | `docs/modules/workflow.md` |
| Testing | `docs/modules/testing.md` |
| API/Rutas | `docs/modules/api.md` |
| Modelos | `docs/modules/models.md` |
| Convenciones | `docs/modules/convenciones.md` |
| Bugs | `docs/modules/bugs.md` |
| Changelog | `docs/modules/changelog.md` |

## Setup rápido
```bash
git clone <repo>
cd PROYECTO-DE-APP-DE-VOTACIONES-
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve --port=8000
# Abrir http://127.0.0.1:8000/Html/login.html
```
