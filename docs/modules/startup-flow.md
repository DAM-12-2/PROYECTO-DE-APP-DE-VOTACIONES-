---
title: Inicio y Desarrollo
keywords: dev, servidor, laravel, html, login
size: 1KB
---

# Cómo Arrancar

## Servidor Laravel (backend)
```bash
php artisan serve --port=8000
# http://127.0.0.1:8000/login
```

## Servidor HTML (standalone)
```bash
python -m http.server 8080
# http://localhost:8080/Html/login.html
```

## Flujo HTML standalone
1. Abrir `Html/login.html` (login, 39 lns)
2. Ingresar cualquier usuario/contraseña
3. `assets/js/login.js:15` redirige a `Tribunal_est.html`
4. Botón "Cerrar Sesión" vuelve a `login.html`

## Flujo Laravel
1. Ir a `http://127.0.0.1:8000/login`
2. Login/Logout via `AuthController` (vacío en plantilla)
3. Ruta `/logout` redirige según rol vía `admin.blade.php:135`

## Tests
No hay test suite configurada.
