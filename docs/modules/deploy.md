---
title: Despliegue
keywords: deploy, producci\u00f3n, servidor
size: 0.5KB
---

# Despliegue a Producci\u00f3n

No hay pipeline CI/CD.

## Opciones

1. Servir con Nginx:
   ```nginx
   server {
       listen 80;
       root /var/www/votaciones;
       index Html/login.html;
   }
   ```

2. Hosting est\u00e1tico (Netlify, Vercel, GitHub Pages)

3. Servidor local con Python

## Archivos a desplegar

| Archivo | Rutas relativas |
|---------|----------------|
| `Html/login.html` | `/Html/login.html` |
| `Html/Tribunal_est.html` | `/Html/Tribunal_est.html` |
| `assets/` | `/assets/css/`, `/assets/js/` |

## Servicios externos

- Solo CDN (Tailwind, Google Fonts, Material Symbols requieren internet).
