# 00 — Inventario del sistema legado

Raíz: `src/` (extraído de `INSTALADOR/codigo_fuente/`). 318 archivos, 12 MB.

## Aplicación de urna (raíz de `src/`)

| Archivo | Rol |
|---|---|
| `index.php` | Pantalla de activación + papeleta. Valida `codigoUrna` y `passUrna` |
| `mostrarPapeleta.php` | Renderiza partidos activos y datos del estudiante |
| `realizarVoto.php` | Inserta el voto vía `CALL spvotar(...)` |
| `activarUrna.php` / `desactivarUrna.php` | Cambian el estado de la urna |
| `verificarUrna.php` | Sondea estado (1 espera, 2 activa, 10 voto emitido) |
| `server.php` | Servidor WebSocket propio (`class.PHPWebSocket.php`) para abrir/cerrar y sincronizar |
| `sysi385x64vot.dll` | **PHP renombrado**: conexión, cifrado, conteo, bitácora, respaldo |
| `ip.php` / `puerto.php` | IP y puerto fijos (`$ip`, `$puerto = '7188'`) |
| `centrado.php` | Script de prueba con credenciales hardcodeadas |
| `css/`, `js/`, `img/` | Estilos jQuery/2013 y recursos gráficos |
| `votaciones.sql` | Dump de estructura (18 tablas) |

## Panel administrativo (`admin/`)

| Grupo | Archivos |
|---|---|
| Sesión | `login.php`, `login/login.php`, `logout.php`, `conn/conn.php`, `cambiarPassAdmin.php`, `noAcceso.php`, `errorLogin.php` |
| Estudiantes | `estudiantes.php`, `agregarEstudiante.php`, `editarEstudiante.php`, `importarEstudiantes.php`, `Excel/reader.php` (2007) |
| Partidos | `partidos.php`, `nuevoPartido.php`, `editarPartido.php` |
| Mesas y secciones | `mesas.php`, `nuevaMesa.php`, `editarMesa.php`, `seccionMesa.php`, `seccionesJSON.php` |
| Puestos y candidatos | `puestos.php`, `buscarPuesto.php` |
| Usuarios y roles | `usuarios.php`, `nuevoUsuario.php`, `editarUsuario.php`, `tribunalElectoral.php`, `juntaReceptoraVotos.php` |
| Operación | `abrirCerrar.php`, `incidente.php`, `configuracion.php`, `parametros.php`, `revisarBitacora.php` |
| Respaldos | `baseDatos.php`, `baseDatos2.php`, `descargarRespaldo.php`, `zipper/pclzip.lib.php` |
| Reportes | `reportes/` (13 archivos, ver abajo) |
| Comunes | `funcionesAJAX.php`, `includes/` (`cipher.php`, `sidebar.php`, `mensajes.php`, `javascript.php`), `plantilla.php`, `ayuda.php` |

## Reportes existentes (`admin/reportes/`)

`acta_apertura.php`, `acta_cierre.php`, `acta_cierre_consulta.php`,
`acta_oficial_resultados.php`, `carteles.php`, `consulta_popular.php`,
`conteo_cero.php`, `conteo_cero_popular.php`, `incidentes.php`,
`instrucciones.php`, `padron.php`, `padron_firmas.php`, `padron_votos.php`.

## Tamaño

| Métrica | Valor |
|---|---|
| Archivos PHP/DLL | 74 |
| Líneas PHP/DLL | ~20.870 |
| Tablas | 18 |
| Librerías de terceros | jQuery 1.8.2, Fancybox, jQuery UI 2013, Excel reader 2007, PHPWebSocket, pclzip |
