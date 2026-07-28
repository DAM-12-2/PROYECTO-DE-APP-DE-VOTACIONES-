---
title: API y Rutas
keywords: endpoints, router, spa, login
size: 2KB
---

# API y Rutas

## P\u00e1ginas (archivos HTML)
| P\u00e1gina | Archivo | L\u00edneas | Rol |
|-----------|---------|-----------|-----|
| Login | `Html/login.html` | 1-39 | Formulario de inicio de sesi\u00f3n |
| App | `Html/Tribunal_est.html` | 1-142 | Panel electoral SPA |

## Rutas del SPA (cliente)

Enrutamiento del lado del cliente en `assets/js/app.js:433-461` (funci\u00f3n `switchSection`):

| Ruta | Vista | Template en app.js |
|------|-------|-------------------|
| `#dashboard` | Dashboard | L\u00edneas 7-76 |
| `#estudiantes` | Estudiantes | L\u00edneas 77-130 |
| `#tribunal-estudiantil` | Tribunal | L\u00edneas 131-174 |
| `#partidos` | Partidos | L\u00edneas 175-220 |
| `#jrv` | JRV | L\u00edneas 221-282 |
| `#votaciones` | Votaci\u00f3n | L\u00edneas 283-332 |
| `#resultados` | Resultados | L\u00edneas 333-356 |
| `#estructura` | Estructura | L\u00edneas 357-409 |
| `#ayuda` | Ayuda | L\u00edneas 410-440 |
| `#configuracion` | Ajustes | L\u00edneas 441-481 |

## Flujo de autenticaci\u00f3n

1. Usuario ingresa en `Html/login.html`
2. `assets/js/login.js:5-16` captura el submit, valida campos, redirige a `Tribunal_est.html`
3. `assets/js/app.js:471-478` bot\u00f3n "Cerrar Sesión" redirige a `login.html`

## API Backend

No hay backend. Sin endpoints REST/GraphQL.
