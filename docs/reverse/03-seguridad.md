# 03 — Hallazgos de seguridad

Evidencia sobre el código extraído en `src/`. Cada hallazgo indica archivo y línea.

## Críticos

### 1. Inyección SQL en todo el sistema
Concatenación directa de entrada del usuario, sin prepared statements:

- `admin/login.php:20` — `"... usuario like '".$user."' and password like '".sha1($pass)."'"`
- `index.php:9` — `"... FROM turnas WHERE id = '$idUrna'"`
- `realizarVoto.php:10` — `CALL spvotar('$idEstudiante','$idPartido','$idUrna';` (además con paréntesis desbalanceado)
- `server.php:31-42` — updates construidos con los campos del mensaje WebSocket

El login es explotable con `usuario = ' OR '1'='1`.

### 2. Credenciales hardcodeadas
- `sysi385x64vot.dll:178` — `$xyP = 'sisVOT2017Brunca@'`
- `sysi385x64vot.dll:192` — `$xyU = 'root'`
- `centrado.php:2` — `mysql_connect('10.0.0.22:7188', 'votos', 'votos')`
- `ip.php` / `puerto.php` — IP y puerto fijos del servidor

Cualquiera con acceso al ZIP obtiene la contraseña de `root` de MySQL.

### 3. Cifrado de votos con clave fija y IV derivado de la clave
`sysi385x64vot.dll:179-199`: Rijndael-256, clave `"Votaciones@."`,
IV = `md5(md5($key))`, modo CBC. Sin salt ni rotación. El mismo texto cifrado
siempre produce el mismo resultado (prefijo aleatorio decorativo, no criptográfico).

### 4. Sesión por cookies no firmadas
`admin/login.php:30-32` guarda el id de usuario y un `codigo` numérico en
cookies sin `HttpOnly`, sin `Secure` y sin firma. Adivinar o fijar `codigo`
equivale a suplantar la cuenta.

## Altos

### 5. Contraseñas con SHA-1 sin salt
`admin/login.php:20`, `admin/editarUsuario.php:25`, `admin/cambiarPassAdmin.php:11`.
El usuario `admin` sembrado usa SHA-1 de `admin`, rompible con cualquier rainbow table.

### 6. Sin autorización por recurso
`admin/conn/conn.php` valida que la sesión exista, pero cada script decide por su
cuenta si el rol alcanza. No hay verificación de que la mesa o el estudiante
pertenezcan al usuario autenticado.

### 7. Validación de voto solo en PHP
`server.php:76-79` comprueba `voto = 0` y luego llama a `spvotar`. Sin transacción
ni restricción única, dos mensajes simultáneos pueden emitir dos votos. `tvotos`
ni siquiera guarda el estudiante, así que no hay forma de auditar duplicados.

### 8. Verificación de conteo inoperante
`sysi385x64vot.dll:366` compara `$row['value']` contra sí mismo
(`$valor2 = cryOUT($row['value'])` en vez de `$row2['value']`), por lo que
`comprobarConteo()` siempre considera correcto el conteo.

## Medios

### 9. Errores de base de datos expuestos
`or die(mysql_error())` en casi todos los scripts de `admin/` y en `server.php`.

### 10. Respaldos dentro del webroot
`sysi385x64vot.dll:170` escribe `images/backups/backupTemp.sql` bajo el
document root; `admin/descargarRespaldo.php` lo sirve sin autenticación adicional.

### 11. Estado de urna con valores mágicos
`activarUrna.php` fija `id_estudiante = 3` como centinela; `estado = 10`
significa "ya votó" solo en `verificarUrna.php`.

### 12. Sin CSRF ni límite de intentos
Formularios y endpoints AJAX aceptan POST/GET sin token; el login no tiene
throttling.

## Inventario de riesgo por componente

| Componente | Riesgo |
|---|---|
| `admin/login.php` | Crítico (SQLi + cookies) |
| `server.php` | Crítico (SQLi + doble voto) |
| `sysi385x64vot.dll` | Crítico (secretos y cifrado) |
| `index.php` / `realizarVoto.php` | Alto (SQLi) |
| `admin/baseDatos*.php` | Alto (backup en webroot) |
| `centrado.php` | Alto (credenciales en claro) |
