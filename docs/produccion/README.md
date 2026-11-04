# Validación para producción

Evidencias y resultados de la validación del sistema para una prueba real en el
CTP AIRA de Pedregoso.

## Documentos

| Archivo | Contenido |
|---|---|
| `informe-produccion.pdf` | Informe técnico completo (12 páginas): veredicto, configuración, casos límite, carga, recursos, capturas y checklist |
| `informe-simple.pdf` | Guía sencilla (7 páginas) para el Tribunal: qué sirve, qué falta y capturas de cada pantalla |
| `informe.html` / `informe-simple.html` | Fuentes editables de ambos informes |
| `capturas/01..10-*.png` | Pantallas del sistema (login, admin, estudiantes, resultados, integridad, reportes, tribunal, registro, JRV y urna) |
| `evidencias/01..08-*.png` | Ciclo completo de un votante |
| `evidencias/3pc-01..09-*.png` | Ciclo completo de tres terminales |
| `evidencias/e2e-resultado.json` | Resultado de la simulación E2E con tiempos |
| `evidencias/carga-votos.txt` | Resumen de la prueba de carga |

## Reproducir

```bash
# 1. Verificación de configuración
php artisan app:check

# 2. Entorno de estrés (base de datos aparte)
cp database/database.sqlite database/stress.sqlite
DB_DATABASE="$(pwd)/database/stress.sqlite" php artisan stress:seed 300 --open
DB_DATABASE="$(pwd)/database/stress.sqlite" VOTE_THROTTLE=100000 \
  PHP_CLI_SERVER_WORKERS=4 php artisan serve --port=8124
php artisan reverb:start --host=127.0.0.1 --port=8080

# 3. Carga de votos
./scripts/stress-vote.sh http://127.0.0.1:8124 300 20 STRESS

# 4. Ciclo de un votante (una terminal)
NODE_PATH="$(npm root -g)" node scripts/e2e-voter.mjs http://127.0.0.1:8124

# 5. Ciclo de tres terminales (PC1 registro → PC2 JRV → PC3 urna)
NODE_PATH="$(npm root -g)" node scripts/e2e-tres-terminales.mjs http://127.0.0.1:8124

# 6. Regenerar el PDF
cd docs/produccion && weasyprint informe.html informe-produccion.pdf
```

## Ciclo de tres terminales

| Terminal | Ruta | Función |
|---|---|---|
| PC1 (servidor + registro) | `/registro` | Identifica al votante y crea la solicitud |
| PC2 (JRV) | `/jrv` | Recibe la cola, aprueba y asigna urna |
| PC3 (urna) | `/kiosko` | Activa la urna, muestra la papeleta y registra el voto |

La comunicación en vivo usa **Laravel Reverb** (WebSocket local) con canales privados
`mesa.{id}` y `urna.{id}`; si el WebSocket no está disponible, el polling de 2–3 s
mantiene el flujo operativo.

## HTTPS local

```bash
# Certificado autofirmado para la IP del servidor (una vez)
openssl req -x509 -newkey rsa:2048 -nodes \
  -keyout storage/certs/server.key -out storage/certs/server.crt \
  -days 825 -subj "/CN=192.168.1.222" \
  -addext "subjectAltName=IP:192.168.1.222,IP:127.0.0.1,DNS:localhost"

# Proxy TLS hacia el servidor de la aplicación
socat OPENSSL-LISTEN:8443,cert=storage/certs/server.crt,key=storage/certs/server.key,verify=0,fork TCP:127.0.0.1:8000
```

Con `APP_URL=https://IP:8443` la aplicación fuerza URLs HTTPS y envía HSTS.
El certificado debe instalarse como confiable en las tres computadoras.

## RLS y PostgreSQL

El aislamiento por mesa está probado con **PostgreSQL 16 + RLS**:

| Rol | Estudiantes | Urnas | Votos | Solicitudes |
|---|---|---|---|---|
| JRV mesa 1 | 4 | 1 | 0 | 1 |
| JRV mesa 2 | 4 | 1 | 0 | 0 |
| Admin | 8 | 3 | 1 | 1 |

Aplicar `database/rls-postgres.sql` como superusuario. El script incluye
`FORCE ROW LEVEL SECURITY`, políticas por mesa y la función `urna_por_codigo`
para activar la terminal antes del login.

En el host sin driver:

```bash
sudo apt install postgresql php8.4-pgsql
sudo systemctl enable --now postgresql
```

Guía completa: `docs/despliegue-postgres-rls.md`.

## Veredicto resumido

Apto funcionalmente y con capacidad de sobra (~35 votos/s medidos para una
necesidad de ~0,05 votos/s). No apto aún para producción sin: MySQL/PostgreSQL,
HTTPS, `APP_DEBUG=false`, credenciales por defecto eliminadas y servidor
Nginx + PHP-FPM. La lista completa está en el informe.
