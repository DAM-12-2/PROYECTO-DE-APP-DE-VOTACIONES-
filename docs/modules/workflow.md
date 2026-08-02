# Workflow de trabajo — Git Flow

Este proyecto se organiza con **Git Flow**. Cada miembro del equipo trabaja en su propia rama y todo llega a `develop` mediante **Pull Requests** (PR). Nunca se hace push directo a `develop` ni a `main`.

## Estructura de ramas

| Rama | Uso | ¿Quién la toca? |
|------|-----|-----------------|
| `main` | Versión estable / producción | Solo via PR desde `develop` |
| `develop` | Integración de todo el equipo | Solo via PR (nunca push directo) |
| `feat/<algo>` | Tu rama de trabajo personal | Solo tú |

> `feat/<algo>` se nombra con lo que estás haciendo: `feat/login`, `feat/dashboard`, `feat/api-estudiantes`.

## Flujo diario

### 1. Tomar una tarea
```bash
git checkout develop
git pull origin develop            # trae lo último
git checkout -b feat/mi-tarea      # crea tu rama desde develop
```

### 2. Trabajar
```bash
git add <archivos>
git commit -m "feat: describo qué hice"
git push -u origin feat/mi-tarea   # sube tu rama
```

### 3. Siempre actualizar tu rama con develop
Si ya pasaron días, trae los cambios de tus compañeros antes de abrir el PR:
```bash
git checkout develop
git pull origin develop
git checkout feat/mi-tarea
git merge develop
# resolver conflictos si los hay y commitear
git push
```

### 4. Abrir Pull Request
1. En GitHub: `feat/mi-tarea` → `develop`
2. Título claro y descripción breve de lo que cambió
3. Esperar review y resolver comentarios
4. Merge solo cuando esté aprobado

### 5. Limpiar la rama terminada
```bash
git checkout develop
git pull origin develop
git branch -d feat/mi-tarea
```

## Reglas de oro

1. **Nunca** pushear directo a `develop` ni a `main`
2. **Pull Request** para todo lo que entra a `develop`
3. `main` solo se actualiza desde `develop` (una PR de release)
4. Commits descriptivos: `feat:`, `fix:`, `refactor:`, `docs:`
5. Antes de cada PR: actualizar tu rama con `develop` y probar que no rompas nada
6. Si hay conflicto, resolverlo en tu rama local, **no** en GitHub

## Roles del equipo

- **Backend**: modelos, migraciones, controllers, API, WebSocket
- **Frontend**: vistas, layouts, estilos, componentes HTML/JS
- **Middle (tú)**: integración, revisión de PRs, orden del repo, unión de las partes

Cada uno vive en su rama `feat/*`. `develop` es el punto de encuentro.
