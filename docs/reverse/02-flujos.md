# 02 — Flujos funcionales

Todo el tiempo real viaja por un WebSocket casero (`server.php` +
`class.PHPWebSocket.php`, puerto fijo 7188). El protocolo son mensajes de texto
separados por `+`, donde el primer campo es el tipo.

| Tipo | Emisor | Efecto |
|---|---|---|
| `3+valor` | Admin | Abre (`1`) o cierra (`0`) elecciones. Actualiza `tparametros.id=2`, resetea `turnas` y suma `tparametros.id=3` |
| `4+idUrna+idEst+idMesa` | JRV | Identifica al estudiante en la urna: `estado=2` si hay estudiante, `1` si se libera |
| `5+idUrna+idEst+idMesa` | JRV | Devuelve la urna a espera (`estado=1`) |
| `6+idPartido+idEstudiante+idUrna` | Urna | **Emite el voto** (ver abajo) |

## 1. Login administrativo

`admin/login.php:20` consulta por usuario y `sha1($pass)`.
Si coincide, escribe tres cookies sin firmar (`admin/login.php:30-32`):
`x23dhscs` (id), `hdksne29` (código) y `swjd09sd` (tipo de usuario).
`admin/conn/conn.php` valida en cada request que `id` + `codigo` existan.

Roles: `0` admin, `1` TEE, `2` JRV. Un JRV sin mesa asignada no puede entrar.

## 2. Apertura de elecciones

El admin pulsa abrir/cerrar (`admin/abrirCerrar.php`), que emite el mensaje `3`.
El servidor pone `tparametros.IniciarElecciones`, reinicia todas las `turnas`
a `estado=1, id_estudiante=0` y lleva un contador de aperturas en
`tparametros.id=3` (`BloqueoLista`).

## 3. Activación de urna

1. El operador de JRV digita número de urna y contraseña (`index.php:5-25`).
2. Se busca la urna (`turnas.id`) y su mesa; se valida que exista un usuario
   `tipousuario=2` con esa mesa y ese `sha1(password)`.
3. La urna queda `estado=2` y asociada a un estudiante "centinela"
   (`activarUrna.php` usa literalmente `id_estudiante = 3`).
4. `verificarUrna.php` sondea: `2` activa, `10` cuando el estudiante ya votó.

## 4. Emisión del voto

1. El estudiante se identifica ante la JRV; se envía `4` y la urna pasa a `2`.
2. Se renderiza `mostrarPapeleta.php` con `tpartidos.estado = 1`.
3. Al pulsar votar, el cliente envía `6+partido+estudiante+urna`.
4. `server.php:70-96` valida `testudiantes.voto = 0`, cifra el partido con
   `cryIN()` y llama al procedimiento `spvotar`.
5. El procedimiento (no incluido en el dump) inserta en `tvotos` y marca
   `testudiantes.voto = 1`; queda comentado en el código el SQL equivalente.
6. La urna muestra "voto registrado" y se libera tras 3 s.

`realizarVoto.php` es una variante HTTP del mismo paso, con la misma validación
ausente de mesa/urna.

## 5. Conteo

`sysi385x64vot.dll` define tres capas:

| Función | Qué hace |
|---|---|
| `conteo()` | Cuenta votos por partido y mesa; guarda el resultado cifrado en `tajustes` |
| `comp()` | Repite el mismo cálculo y lo guarda en `tcomp` |
| `comprobarConteo()` | Compara `tajustes` contra `tcomp` y, si difieren o faltan partidos, vuelve a contar |

La comparación de `comprobarConteo()` tiene un bug: en la línea 366 asigna
`$valor2 = cryOUT($row['value'])` en lugar de `$row2['value']`, por lo que
compara una fila consigo misma y la verificación siempre pasa.

## 6. Cierre y reportes

Al cerrar elecciones se habilitan los reportes de resultados. Se generan:

- Acta de apertura, acta de cierre, acta oficial de resultados
- Padrón, padrón de firmas, padrón de votos
- Conteo cero (general y de consulta popular)
- Carteles por partido, instrucciones, incidentes

## 7. Bitácora

`registrarBitacora()` guarda `usuario`, fecha cifrada y detalle cifrado en
`tregistro`. No hay cadena de integridad ni sello de tiempo verificable.

## 8. Reglas de negocio (manual de usuario)

Fuente: `admin/userManual.pdf`.

- **Reloj de voto**: se inicia al asignar la urna al estudiante. Al agotarse, se
  registra la opción marcada en ese instante; sin selección = voto en blanco.
- **Tipos de voto**: blanco (nada seleccionado), válido (un partido), nulo
  (más de un partido).
- **Bloqueo de padrón**: abiertas las elecciones no se pueden modificar estudiantes.
- **Regla de victoria** configurable:
  - mayoría simple;
  - mayoría simple con porcentaje mínimo sobre empadronados o sobre votantes;
  - al cálculo se le puede sumar N estudiantes.
- **Consulta popular**: con un solo partido, se pregunta SÍ/NO y gana con el
  porcentaje de afirmativos definido por el TEE.
- **Límites operativos**: ~8 partidos por papeleta (800×600) y 1–2 urnas por JRV.
