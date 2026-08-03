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

## Flujo de trabajo (Git Flow)

El equipo organiza el trabajo con ramas. `main` es la versión estable, `develop` es donde todos integran, y cada quien trabaja en su propia rama `feat/*`. **Nada entra a `main` ni a `develop` sin Pull Request.**

| Rama | Uso | Quién la toca |
|------|-----|---------------|
| `main` | Versión estable / producción | Solo vía PR desde `develop` |
| `develop` | Integración de todo el equipo | Solo vía PR (nunca push directo) |
| `feat/<algo>` | Rama personal de trabajo | Solo su dueño |

> `feat/<algo>` se nombra por la tarea: `feat/login`, `feat/dashboard`, `feat/api-estudiantes`.

### Ramas `feat/*` por miembro del equipo

Cada quien usa su propio prefijo para que se sepa de quién es cada rama:

| Miembro | Rol | Prefijo de rama | Ejemplo |
|---------|-----|-----------------|---------|
| **Backend** | Modelos, migraciones, controllers, API, WebSocket | `feat/backend-...` | `feat/backend-api-estudiantes` |
| **Frontend** | Vistas, layouts, estilos, HTML/JS | `feat/frontend-...` | `feat/frontend-login` |
| **Middle (tú)** | Integración, PRs, orden del repo | `feat/integracion-...` | `feat/integracion-juntar-modulos` |

Regla: si no dice de quién es, pregunta antes de abrir el PR. El dueño de la rama es quien la integra.

### Tomar una tarea
```bash
git checkout develop
git pull origin develop
git checkout -b feat/mi-tarea
```

### Trabajar y subir
```bash
git add <archivos>
git commit -m "feat: qué hice"
git push -u origin feat/mi-tarea
```

### Mantener tu rama al día
```bash
git checkout develop
git pull origin develop
git checkout feat/mi-tarea
git merge develop
git push
```

### Abrir Pull Request
1. En GitHub: `feat/mi-tarea` → `develop`
2. Título claro + breve descripción
3. Resolver comentarios y merge solo tras aprobación

### Reglas de oro
1. **Nadie puede hacer push directo** a `develop` ni a `main`. Punto. Es política del equipo, no opcional.
2. Todo entra por **Pull Request** (aunque seas tú mismo, aunque sea un typo)
3. `main` solo se actualiza desde `develop`
4. Commits descriptivos: cada commit se categoriza según su tipo (ver tabla abajo)
5. Antes de cada PR: traer lo último de `develop` y probar
6. Conflictos se resuelven en tu rama local, no en GitHub
7. Si intentas `git push origin develop` y te rechaza, **no es un error**: es la protección funcionando. Crea tu rama `feat/*` y abre el PR.

### Convenciones de commits

Todo commit comienza con un prefijo que dice qué tipo de cambio es. Esto mantiene el historial ordenado y fácil de leer.

| Prefijo | Para qué se usa | Ejemplo |
|---------|-----------------|---------|
| `feat:` | Nueva funcionalidad | `feat: agrega registro de estudiantes` |
| `fix:` | Corrección de un bug | `fix: corrige error en migraciones` |
| `refactor:` | Reestructurar código sin cambiar comportamiento | `refactor: simplifica el controlador de votos` |
| `docs:` | Cambios solo de documentación | `docs: agrega guía de instalación` |
| `chore:` | Tareas de mantenimiento/limpieza (no tocan la lógica) | `chore: limpia archivos no versionados` |
| `test:` | Agregar o modificar pruebas | `test: cubre el helper de números a letras` |
| `style:` | Formato, espacios, orden de imports (sin lógica) | `style: ordena imports del seeder` |
| `perf:` | Mejoras de rendimiento | `perf: optimiza consulta de resultados` |

Reglas rápidas:
- Escribe el prefijo en minúsculas seguido de `:` y una descripción breve en presente ("corrige", no "corregido").
- Un commit debe hacer una sola cosa. Si toca dos áreas, haz dos commits.
- Ejemplo correcto: `fix: corrige error al registrar un voto` — Ejemplo incorrecto: `arreglo cosas`.

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
cd PROYECTO-DE-APP-DE-VOTACIONES
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve --port=8000
# Abrir http://127.0.0.1:8000/login
```

## Cómo correr el servidor

### 1. Instalar dependencias
```bash
composer install
npm install
```

### 2. Configurar el entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Crear y sembrar la base de datos
```bash
touch database/database.sqlite
php artisan migrate --seed
```

### 4. Correr el servidor Laravel
```bash
php artisan serve --port=8000
```

### 5. Abrir en el navegador
- Login: `http://127.0.0.1:8000/login`

### Usuarios de prueba
| Usuario | Contraseña | Rol | Ruta |
|---------|-----------|-----|------|
| admin | admin | admin | `/admin` |
| tee | tee | tribunal | `/tribunal` |
| jrv | jrv | jrv | `/jrv` |

> Nota: Si el puerto 8000 está ocupado, cierra el proceso anterior con `pkill -f "artisan serve"` o usa otro puerto con `php artisan serve --port=8001`.
