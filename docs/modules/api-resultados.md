# API de Resultados y Votos Finales

## Resumen

Implementación completa de endpoints REST para consultar resultados electorales, votos por mesa/sección, y exportar a CSV.

---

## Endpoints

### Base URL
```
/api/resultados
```

### Autenticación
- **Tipo**: Session-based (cookies Laravel)
- **Middleware**: `auth` + `role:admin,tee`
- **Roles permitidos**: `admin`, `tee`
- **Rol denegado**: `jrv` (403 Forbidden)

### Bloqueo por Elección Abierta
Si `Setting eleccion_abierta = '1'` → **403 Forbidden** en todos los endpoints:
```json
{
  "error": "Resultados no disponibles mientras la elección está abierta. Cierre las votaciones primero."
}
```

---

## GET `/api/resultados`

**Resultado completo con totales, partidos y ganador**

### Response 200
```json
{
  "total_votos": 150,
  "blancos": 5,
  "nulos": 3,
  "votos_validos": 142,
  "partidos": [
    {"siglas": "PVA", "nombre": "Partido Verde Estudiantil", "votos": 80, "porcentaje": 56.3},
    {"siglas": "PRA", "nombre": "Partido Rojo Académico", "votos": 62, "porcentaje": 43.7}
  ],
  "ganador": {
    "siglas": "PVA",
    "nombre": "Partido Verde Estudiantil",
    "votos": 80,
    "porcentaje": 56.3,
    "mayoria_absoluta": true
  },
  "es_consulta_popular": false
}
```

### Consulta Popular (cuando `tipo_eleccion = consulta_popular`)
```json
{
  "total_votos": 200,
  "blancos": 2,
  "nulos": 1,
  "votos_validos": 197,
  "partidos": [
    {"siglas": "SÍ", "nombre": "Sí", "votos": 120, "porcentaje": 60.9},
    {"siglas": "NO", "nombre": "No", "votos": 77, "porcentaje": 39.1}
  ],
  "ganador": {"opcion": "SÍ", "votos": 120, "porcentaje": 60.9},
  "es_consulta_popular": true
}
```

---

## GET `/api/resultados/por-mesa`

**Resultados agrupados por mesa**

### Response 200
```json
[
  {
    "mesa_id": 1,
    "mesa_nombre": "Mesa 1",
    "mesa_numero": 1,
    "total": 50,
    "blancos": 2,
    "nulos": 1,
    "votos_validos": 47,
    "partidos": [
      {"siglas": "PVA", "nombre": "Partido Verde Estudiantil", "votos": 30, "porcentaje": 63.8},
      {"siglas": "PRA", "nombre": "Partido Rojo Académico", "votos": 17, "porcentaje": 36.2}
    ]
  },
  {
    "mesa_id": 2,
    "mesa_nombre": "Mesa 2",
    "mesa_numero": 2,
    "total": 50,
    "blancos": 1,
    "nulos": 1,
    "votos_validos": 48,
    "partidos": [...]
  }
]
```

---

## GET `/api/resultados/por-seccion`

**Resultados agrupados por sección académica**

### Response 200
```json
[
  {
    "seccion": "11-1",
    "mesa_ids": [1],
    "total": 25,
    "blancos": 1,
    "nulos": 0,
    "votos_validos": 24,
    "partidos": [...]
  },
  {
    "seccion": "11-2",
    "mesa_ids": [1],
    "total": 25,
    "blancos": 1,
    "nulos": 1,
    "votos_validos": 23,
    "partidos": [...]
  }
]
```

---

## GET `/api/resultados/resumen`

**Resumen ligero para dashboards**

### Response 200
```json
{
  "total_votos": 150,
  "votos_validos": 142,
  "blancos": 5,
  "nulos": 3,
  "total_electores": 200,
  "porcentaje_participacion": 75.0,
  "es_consulta_popular": false
}
```

---

## GET `/api/resultados/ganador`

**Verificación de ganador con lógica de mayoría absoluta**

### Response 200
```json
{
  "hay_ganador": true,
  "ganador": {
    "siglas": "PVA",
    "nombre": "Partido Verde Estudiantil",
    "votos": 80,
    "porcentaje": 56.3,
    "mayoria_absoluta": true
  },
  "es_consulta_popular": false
}
```

**Lógica:**
- **Partidos**: Mayoría absoluta (>50% votos válidos). Si no hay, retorna el primero sin mayoría.
- **Consulta Popular**: Gana SÍ si > NO, NO si > SÍ, EMPATE si iguales.

---

## GET `/api/resultados/exportar`

**Descarga CSV con resultados**

### Response
- **Content-Type**: `text/csv; charset=UTF-8`
- **Content-Disposition**: `attachment; filename="resultados_2026-08-16_10-30-00.csv"`

### Formato CSV (Partidos)
```csv
Partido,Siglas,Votos,Porcentaje
Partido Verde Estudiantil,PVA,80,56.3%
Partido Rojo Académico,PRA,62,43.7%

Total Votos,150
Blancos,5
Nulos,3
Votos Válidos,142
```

### Formato CSV (Consulta Popular)
```csv
Opción,Votos,Porcentaje
Sí,120,60.9%
No,77,39.1%

Total Votos,200
Blancos,2
Nulos,1
Votos Válidos,197
```

---

## Códigos de Error

| Código | Causa |
|--------|-------|
| 401 | No autenticado (redirige a /login) |
| 403 | Rol no autorizado (solo admin, tee) O elección abierta |
| 500 | Error interno del servidor |

---

## Testing con cURL

### Login previo (obtener sesión)
```bash
# Obtener CSRF token
curl -c cookies.txt -X GET http://localhost:8000/login

# Login
curl -b cookies.txt -c cookies.txt -X POST http://localhost:8000/login \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=TOKEN_DE_CSRF&username=admin&password=admin"
```

### Probar endpoints
```bash
# Resultados completos
curl -b cookies.txt http://localhost:8000/api/resultados

# Por mesa
curl -b cookies.txt http://localhost:8000/api/resultados/por-mesa

# Por sección
curl -b cookies.txt http://localhost:8000/api/resultados/por-seccion

# Resumen
curl -b cookies.txt http://localhost:8000/api/resultados/resumen

# Ganador
curl -b cookies.txt http://localhost:8000/api/resultados/ganador

# Exportar CSV (guarda archivo)
curl -b cookies.txt -o resultados.csv http://localhost:8000/api/resultados/exportar
```

### Probar bloqueo (elección abierta)
```bash
# Activar elección (como admin en panel web o via BD)
# sqlite3 database/database.sqlite "UPDATE settings SET detalle='1' WHERE nombre='eleccion_abierta';"

# Probar - debe devolver 403
curl -b cookies.txt http://localhost:8000/api/resultados
# {"error":"Resultados no disponibles mientras la elección está abierta..."}
```

---

## Implementación Técnica

### Conteo de Votos (Loop)
```php
foreach ($votes as $vote) {
    try {
        $decrypted = Crypt::decryptString($vote->encrypted_party);
    } catch (\Exception $e) {
        $nulos++; continue;
    }
    
    if ($isConsultaPopular) {
        $decrypted === '1' ? $siCount++ : ($decrypted === '0' ? $noCount++ : $nulos++);
    } else {
        in_array($decrypted, $validPartyIds) ? $partyVotes[$decrypted]++ 
            : ($decrypted === '' ? $blancos++ : $nulos++);
    }
}
```

### Archivos Modificados
| Archivo | Cambios |
|---------|---------|
| `routes/api.php` | 6 nuevas rutas con middleware `auth`, `role:admin,tee` |
| `app/Services/VoteTallyService.php` | `tallyVotes()`, `tallyVotesByMesa()`, `tallyVotesBySeccion()`, `formatPartyResults()` |
| `app/Http/Controllers/ResultController.php` | 6 métodos API + helper `checkElectionClosed()` |
| `app/Services/ElectionService.php` | `isElectionOpen()`, `isConsultaPopular()`, `isPadronBloqueado()`, `getWinnerThreshold()`, `toggle()` |

### Detección Automática Consulta Popular
```php
$tipo = Setting::where('nombre', 'tipo_eleccion')->value('detalle');
$isConsultaPopular = $tipo === 'consulta_popular';
```
- Si `tipo_eleccion = 'consulta_popular'` → votos se guardan como `'1'` (SÍ) / `'0'` (NO)
- Sino → votos se guardan como `party_id` cifrado

---

## Verificación Manual

### Checklist
- [ ] `GET /api/resultados` → 200 JSON estructura correcta
- [ ] `GET /api/resultados/por-mesa` → array con mesas y conteos
- [ ] `GET /api/resultados/por-seccion` → array con secciones y conteos
- [ ] `GET /api/resultados/resumen` → totales ligeros
- [ ] `GET /api/resultados/ganador` → ganador o null
- [ ] `GET /api/resultados/exportar` → descarga CSV válido
- [ ] Con `eleccion_abierta=1` → 403 en todos
- [ ] Sin login → 401/redirect login
- [ ] Con rol `jrv` → 403
- [ ] Con rol `tee` → 200 OK
- [ ] Con rol `admin` → 200 OK

### Comando rápido para probar
```bash
php artisan serve --port=8000
# En otra terminal:
# 1. Login y obtener cookies
# 2. Probar cada endpoint
```

---

## Notas

- **Cifrado**: Votos usan `Crypt::decryptString()` (Laravel encryption)
- **Performance**: Loop en PHP sobre `Vote::select(...)->get()`. Para >10k votos considerar `chunk()` o query SQL raw.
- **Cache**: No implementado. Recomendado `Cache::remember('tally_results', 60, ...)` para producción.
- **Migración**: No requiere migraciones nuevas.