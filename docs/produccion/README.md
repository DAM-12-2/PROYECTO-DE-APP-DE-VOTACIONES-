# Validación para producción

Evidencias y resultados de la validación del sistema para una prueba real en el
CTP AIRA de Pedregoso.

## Documentos

| Archivo | Contenido |
|---|---|
| `informe-produccion.pdf` | Informe completo (10 páginas): veredicto, configuración, casos límite, carga, recursos, capturas y checklist |
| `informe.html` | Fuente del informe (editable) |
| `evidencias/01..08-*.png` | Ciclo completo de un votante real |
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

## Veredicto resumido

Apto funcionalmente y con capacidad de sobra (~35 votos/s medidos para una
necesidad de ~0,05 votos/s). No apto aún para producción sin: MySQL/PostgreSQL,
HTTPS, `APP_DEBUG=false`, credenciales por defecto eliminadas y servidor
Nginx + PHP-FPM. La lista completa está en el informe.
