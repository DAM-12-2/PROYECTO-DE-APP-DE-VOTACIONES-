# 01 — Base de datos legada

Motor: MySQL 5.1, todas las tablas `MyISAM`, charset `latin1`.
No hay claves foráneas declaradas: la integridad se sostiene en PHP.

## Tablas

| Tabla | Propósito | Columnas clave |
|---|---|---|
| `testudiantes` | Padrón | `id`, `identificacion`, `nombre`, `apellidos`, `seccion`, `foto` (BLOB), `huella` (BLOB), `voto` (0/1), `idUrna`, `estado` |
| `tpartidos` | Partidos | `id`, `siglas`, `nombre`, `fotopresidente`, `bandera`, `estado` (1 activo / 2 eliminado), `conteo` |
| `tmesas` | Mesas | `id`, `numero`, `estado` (1 activa / 2 eliminada) |
| `tseccionesmesa` | Sección → mesa | `seccion`, `id_mesa` |
| `tpuestos` | Puestos de elección | `id`, `nombre`, `estado` |
| `tmiembrospartido` | Candidatos por partido y puesto | `idestudiante`, `idpartido`, `idpuesto` |
| `tmiembrosjrv` | Miembros de JRV | `id_estudiante`, `id_partido`, `id_mesa`, `puesto` (0 miembro, 1 fiscal, 2 auxiliar, 3 presidente), `estado` |
| `tmiembrostee` | Tribunal Electoral Estudiantil | `idestudiante`, `idpuesto` |
| `tusuarios` | Cuentas | `usuario`, `password` (SHA-1), `estado` (0 desact., 1 activo, 2 cambiar), `tipousuario` (0 admin, 1 TEE, 2 JRV), `idmesa`, `codigo` |
| `tusuariomesa` | Usuario ↔ mesa | `idmesa`, `idusuario` |
| `turnas` | Urnas | `id`, `horaactivacion`, `estado` (0 eliminada, 1 espera, 2 activa), `id_mesa`, `id_estudiante` |
| `tvotos` | Votos emitidos | `idpartido` (cifrado), `idmesa` |
| `tincidentes` | Incidentes | `idmesa`, `idusuario`, `detalle` |
| `tregistro` | Bitácora | `xw23` (usuario), `xw24` (fecha cifrada), `xw25` (detalle cifrado) |
| `tinstitucion` | Datos del centro | `nombre`, `escudo` |
| `tajustes` | Conteo por mesa/partido | `keyFrame`, `value`, `param` (todo Base64 ofuscado) |
| `tcomp` | Copia de verificación del conteo | mismos campos que `tajustes` |
| `tparametros` | Parámetros electorales | `id`, `nombre`, `detalle` |

## Parámetros sembrados (`tparametros`)

| id | Nombre | Valor | Significado |
|---|---|---|---|
| 1 | `TiempoVotacion` | 90 | Segundos de sesión de voto |
| 2 | `IniciarElecciones` | 0 | 1 = elecciones abiertas |
| 3 | `BloqueoLista` | 0 | Bloqueo de padrón durante votación |
| 4 | `PorcVotoAfirmativo` | 20 | Umbral de voto afirmativo |
| 5 | `RequisitoGanar` | 1 | Regla de victoria |
| 6 | `VotosTotales` | 1 | Contabilizar totales |
| 7 | `SumarPorcAfirm` | 0 | Sumar porcentaje afirmativo |

## Observaciones estructurales

- `tvotos` no tiene PK ni columna de estudiante: **el doble voto solo se evita por
  el flag `testudiantes.voto`**, sin restricción de base de datos.
- `turnas` admite `id = 0` como valor por defecto y usa `id_estudiante = 0`.
- `tajustes` y `tcomp` duplican el conteo como mecanismo de verificación
  (comparación de dos copias), no como fuente de verdad normalizada.
- Todo el contenido sensible (`tvotos.idpartido`, `tajustes.value`, `tregistro`)
  se guarda cifrado con una clave fija en el código.
- Datos por defecto: institución `LICEO LAS ESPERANZAS`, usuario `admin` con
  SHA-1 de `admin` (`d033e22ae348aeb5660fc2140aec35850c4da997`).
