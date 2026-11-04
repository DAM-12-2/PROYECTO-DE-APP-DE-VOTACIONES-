# Ingeniería inversa — sisVOT 2017

Análisis del sistema electoral legado que opera el CTP AIRA, usado como referencia
funcional para la reescritura en Laravel 12 de este repositorio.

## Fuente

| Dato | Valor |
|---|---|
| Artefacto | `/home/jeffer_gr/Descargas/INSTALADOR.zip` |
| SHA-256 del ZIP | `75fc31f4ed5ec5203734d50412b6ac56863dcc06180e65355bb1b70e1412f6b0` |
| Inventario REA | 316 nodos, 356 ocurrencias, 0 contradicciones de integridad |
| Código fuente | 74 archivos PHP + 1 archivo PHP con extensión `.dll`, ~20.870 líneas |
| Fecha del código | 2016-03 a 2017-05 |
| Instalador | `InstaladorVotaciones.exe` (PE32, Setup Factory) → `Tecnosoluciones_Brunca/` |
| Runtime original | Apache 2.4 (`sve3_http.exe`, launcher de Server2Go) + MySQL 5.1 + PHP 5 (`php5ts.dll`) portable, Windows |

El código extraído vive en `src/` y **no se versiona** (ver `.gitignore`).
Solo se versionan estos documentos y los scripts de análisis.

## Documentos

| Archivo | Contenido |
|---|---|
| [00-inventario.md](00-inventario.md) | Mapa de archivos y módulos del sistema legado |
| [01-base-datos.md](01-base-datos.md) | Las 18 tablas, relaciones y parámetros |
| [02-flujos.md](02-flujos.md) | Login, activación de urna, voto, conteo y cierre |
| [03-seguridad.md](03-seguridad.md) | Hallazgos con evidencia (`archivo:línea`) |
| [04-paridad.md](04-paridad.md) | Matriz original → Laravel: qué falta |
| [05-plan-mejora.md](05-plan-mejora.md) | Mejoras funcionales y rediseño de interfaz |

## Cómo se obtuvo

1. `unzip` selectivo de `INSTALADOR/codigo_fuente/` a `src/`.
2. Inventario del ZIP con REA (`inspect_artifact`, `detail: summary`) para hash e integridad.
3. Lectura estática dirigida de los PHP y del dump `votaciones.sql`.
4. `sysi385x64vot.dll` resultó ser **un script PHP renombrado**, no código nativo:
   no requiere desensamblador, se lee como fuente.
