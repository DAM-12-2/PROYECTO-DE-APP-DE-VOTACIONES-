---
title: Changelog
keywords: historial, cambios, versiones
size: 0.5KB
---

# Historial de Cambios

## 2026-07-07
- Restauración del proyecto a estado `plantilla` (Laravel + SQLite)
- Adición de HTML standalone: `Html/login.html`, `Html/Tribunal_est.html`, `assets/`
- Extracción de CSS/JS inline de `admin.blade.php` a `public/css/admin.css` y `public/js/admin-config.js`
- Logout en admin redirige según rol: admin -> `/Html/login.html`, tribunal -> `/Html/Tribunal_est.html`
- Symlinks en `public/` para servir HTMLs desde Laravel (`public/Html`, `public/assets`)
- Actualización de documentación modular (`docs/modules/`, `AGENTS.md`, `README.md`)
