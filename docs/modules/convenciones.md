---
title: Convenciones
keywords: estilo, naming, patrones, lineas
size: 1.5KB
---

# Convenciones de C\u00f3digo

## HTML (`Html/`)
- indentaci\u00f3n con 2 espacios
- clases Tailwind en l\u00ednea
- IDs en kebab-case
- data-atributos para targeting
- `login.html:19` formulario con `id="login-form"`
- `Tribunal_est.html:104` nav links con `data-target`

## JavaScript (`assets/js/`)
- Variables/funciones en camelCase
- Templates literales para HTML din\u00e1mico
- Sin frameworks (vanilla)
- `login.js:1-17` l\u00f3gica login: obtiene form, escucha submit, valida, redirige
- `app.js:1-4` DOMContentLoaded, obtiene referencias del DOM
- `app.js:6-370` objeto `templates` con HTML de cada vista
- `app.js:371-431` funci\u00f3n `attachVotacionesListeners()` control switch votaci\u00f3n
- `app.js:433-461` funci\u00f3n `switchSection()` cambia vista activa
- `app.js:463-469` bind de clicks a nav links
- `app.js:471-478` handler de logout -> `login.html`
- `app.js:480-481` init en dashboard

## CSS (`assets/css/`)
- `styles.css:1-6` solo estilos para `.nav-link.active`
- Nombres de clase en kebab-case

## Archivos
- HTML en `Html/`
- assets externos en `assets/{css,js}/`
- documentaci\u00f3n en `docs/modules/`
