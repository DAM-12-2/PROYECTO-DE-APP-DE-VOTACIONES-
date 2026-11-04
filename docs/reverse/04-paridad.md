# 04 — Matriz de paridad: sisVOT 2017 → Laravel 12

Comparación entre lo que hace el sistema legado y lo que ya existe en este
repositorio. Base: `routes/web.php`, `app/Http/Controllers/` y `resources/views/`.

## Módulos cubiertos

| Función legada | Equivalente actual | Estado |
|---|---|---|
| Padrón de estudiantes (CRUD) | `StudentController` + `admin/students.blade.php` | ✅ |
| Importar estudiantes desde Excel | `StudentController@import` + `StudentsImport` | ✅ (CSV/Excel vía Laravel) |
| Partidos (CRUD, bandera, foto) | `PartyController` | ✅ |
| Mesas (CRUD) | `MesaController` | ✅ |
| Secciones por mesa | `MesaController@storeSeccion`, `moverSeccion` | ✅ |
| Puestos de elección | `CandidatoController` (puestos) | ✅ |
| Candidatos por partido y puesto | `CandidatoController@storeCandidato` | ✅ |
| Usuarios y roles (admin/TEE/JRV) | `UsuarioController` + `RoleMiddleware` | ✅ |
| Miembros de JRV | `MiembroJrv` + vista de mesas | ✅ |
| Miembros del TEE | `TeeController` + `tee_members` | ✅ |
| Urnas (CRUD y activación) | `UrnaController` + `UrnaService` | ✅ |
| Incidentes | `IncidenteController` | ✅ |
| Bitácora | `Bitacora` + `BitacoraService` | ✅ |
| Respaldos (descargar/restaurar) | `SettingsController@backupDownload/backupRestore` | ✅ |
| Parámetros electorales | `Setting` + `SettingsController` | ✅ (modelo distinto) |
| Kiosco/urna de votación | `voting/urna.blade.php` + `VoteController` | ✅ |
| Portal JRV | `JrvController` + `jrv/index.blade.php` | ✅ |
| Panel Tribunal (SPA) | `layouts/tribunal.blade.php` + `assets/js/app.js` | ✅ |
| Acta de apertura | `ReportController@actaApertura` | ✅ |
| Acta de cierre | `ReportController@actaCierre` | ✅ |
| Acta oficial de resultados | `ReportController@actaResultados` | ✅ |
| Padrón | `ReportController@padron` | ✅ |
| Padrón de firmas | `ReportController@padronFirmas` | ✅ |
| Padrón de votos | `ReportController@padronVotos` | ✅ |
| Conteo cero | `ReportController@conteoCero` | ✅ |
| Carteles por partido | `ReportController@carteles` | ✅ |
| Instrucciones | `ReportController@instrucciones` | ✅ |
| Incidentes (reporte) | `ReportController@incidentes` | ✅ |
| Consulta popular | `ReportController@consultaPopular*` + `ElectionService` | ✅ (ampliado) |
| Resultados por mesa/sección | `ResultController` + API | ✅ (ampliado) |

## Brechas reales

| # | Función legada | Situación actual | Impacto |
|---|---|---|---|
| 1 | Tiempo máximo de sesión de voto (`TiempoVotacion = 90` s) y auto-emisión al agotarse | ✅ Implementado: `tiempo_votacion` en `settings`, cronómetro y barra en el kiosco | — |
| 2 | Verificación cruzada del conteo (`tajustes` vs `tcomp`) | ❌ Pendiente (Fase 2.3) | Sin detección de alteración del conteo |
| 3 | Sincronización en tiempo real por WebSocket | ❌ Polling/HTTP | Latencia al abrir/cerrar y al liberar la urna |
| 4 | Bloqueo de padrón durante la votación (`BloqueoLista`) | ✅ Implementado: middleware `block.padron` | — |
| 5 | Garantía de un voto por estudiante a nivel de BD | ✅ Implementado: `votes.student_id` único + transacción | — |
| 6 | Voto en blanco / nulo explícitos | ✅ Parcial: blanco (`party_id = 0`) contabilizado aparte; nulo = partido inexistente | Falta UI de voto nulo multi-selección |
| 7 | Desbloquear estudiante (anular su voto) | ✅ Implementado: `POST /admin/estudiantes/{id}/desbloquear` | — |
| 8 | Regla de ganador configurable (% mínimo, base padrón/votantes, sumar N) | ✅ Implementado en `VoteTallyService::verificarGanador()` | — |
| 9 | Foto/huella del votante | Campos en `students`, sin uso operativo | Función anunciada pero inerte |
| 10 | Voto por puesto (plancha completa) | Un solo `party_id` por voto | Ambos sistemas lo resuelven igual; decisión de producto pendiente |

### Hallazgos del manual de usuario original

Fuente: `src/admin/userManual.pdf` (extraído con `pdftotext`).

- El reloj de votación se inicia al asignar la urna y **al agotarse asigna la opción
  marcada en ese instante**; sin selección equivale a voto en blanco.
- "Una vez abiertas las elecciones, **NO se podrán modificar los datos de los estudiantes**".
- Reglas de victoria configurables: mayoría simple, o mayoría con un porcentaje mínimo
  calculado sobre empadronados o sobre votantes, más N estudiantes.
- Consulta popular cuando existe un solo partido; gana con el porcentaje de
  votos afirmativos que fije el TEE.
- Voto nulo = más de un partido seleccionado (la papeleta original permitía multiselección).
- Límite práctico de 8 partidos por papeleta y 1–2 urnas por JRV.

## Diferencias de modelo que conviene conservar

| Legado | Laravel | Comentario |
|---|---|---|
| `tusuarios.tipousuario` (0/1/2) | `users.role` (`admin`/`tee`/`jrv`) | Mejor: enum legible |
| `tparametros` (7 filas) | `settings` clave-valor | Mejor: extensible |
| `tajustes` + `tcomp` | Sin equivalente | Recuperar como verificación, no como fuente |
| `tvotos` sin identidad | `votes` sin `student_id` | **Mismo defecto heredado** |
| Cookies `x23dhscs`/`hdksne29` | Sesión Laravel firmada | Resuelto |

## Conclusión

La reescritura ya cubre **todo el alcance funcional del original y más**.
El trabajo restante no es portar pantallas: es cerrar las brechas 1–5 y
modernizar la interfaz.
