---
title: Estructura del Proyecto
keywords: carpetas, directorios, organización
size: 2KB
---

# Estructura del Proyecto

```
/
├── Html/                          # HTML standalone (sin backend)
│   ├── login.html                 # Login (39 lns)
│   └── Tribunal_est.html          # SPA panel (142 lns)
├── assets/                        # Assets de HTML standalone
│   ├── css/styles.css             # .nav-link.active (6 lns)
│   └── js/
│       ├── tailwind-config.js     # Config Tailwind (73 lns)
│       ├── login.js               # Lógica login (17 lns)
│       └── app.js                 # Router SPA + templates (481 lns)
├── app/                           # Laravel (MVC)
├── resources/views/layouts/
│   └── admin.blade.php            # Layout admin Laravel (168 lns)
├── public/
│   ├── css/admin.css              # CSS extraído del admin (11 lns)
│   ├── js/admin-config.js         # tailwind.config extraído (14 lns)
│   ├── Html -> ../../Html         # Symlink
│   └── assets -> ../../assets     # Symlink
├── docs/modules/                  # Documentación modular
├── AGENTS.md
├── opencode.jsonc
└── README.md
```

## Directorios clave

| Directorio | Contenido |
|------------|-----------|
| `Html/` | Páginas HTML standalone (login, SPA) |
| `assets/` | CSS/JS del HTML standalone |
| `resources/views/` | Vistas Blade Laravel (41 vistas) |
| `public/` | Web root Laravel + symlinks a Html/ y assets/ |
| `public/css/` | CSS extraído de admin.blade.php |
| `public/js/` | JS extraído de admin.blade.php |
| `docs/modules/` | 10 archivos de documentación modular |
