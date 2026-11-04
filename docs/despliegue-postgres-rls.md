# Despliegue con PostgreSQL y RLS

Este documento describe cómo activar el aislamiento por fila (Row Level Security)
en la base de datos. En el entorno de desarrollo actual no se pudo probar porque
el host no tiene el driver `pdo_pgsql`; el control por mesa ya funciona en la
aplicación, pero RLS añade una segunda capa a nivel de motor.

## Requisitos

```bash
sudo apt install postgresql php8.4-pgsql
sudo systemctl enable --now postgresql
```

## 1. Base de datos

```sql
CREATE USER votaciones WITH PASSWORD 'clave-larga-y-unica';
CREATE DATABASE votaciones OWNER votaciones;
```

## 2. Configuración de Laravel

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=votaciones
DB_USERNAME=votaciones
DB_PASSWORD=clave-larga-y-unica
```

```bash
php artisan migrate --force
psql -U votaciones -d votaciones -f database/rls-postgres.sql
```

## 3. Contexto por petición

Añadir un middleware que establezca las variables de sesión dentro de una
transacción. Ejemplo:

```php
DB::transaction(function () use ($request, $next) {
    DB::selectOne("SELECT set_config('app.role', ?, true)", [$request->user()?->role ?? 'anon']);
    DB::selectOne("SELECT set_config('app.user_id', ?, true)", [(string) ($request->user()?->id ?? 0)]);
    DB::selectOne("SELECT set_config('app.mesa_id', ?, true)", [(string) ($request->user()?->mesa_id ?? 0)]);

    return $next($request);
});
```

> `SET LOCAL` no admite parámetros; por eso se usa `set_config(..., true)`.

La conexión debe usar el mismo usuario de base de datos para toda la aplicación.
Si se usa un usuario `SUPERUSER`, PostgreSQL ignora RLS: crear un rol sin
`BYPASSRLS`.

## 4. Verificación

```sql
-- Con app.role = 'jrv' y app.mesa_id = 1
SELECT count(*) FROM students;        -- solo los de la mesa 1
SELECT count(*) FROM votes;           -- 0 (solo admin/TEE leen votos)
SELECT count(*) FROM urnas;           -- solo las de la mesa 1
```

## 5. Pruebas

Con RLS activo, ejecutar la suite:

```bash
php artisan test
```

Los tests de acceso cruzado (`SeguridadLocalTest`) deben seguir en verde y,
además, cualquier consulta directa con `DB::table()` queda limitada por el motor.

## 6. Limitaciones

- RLS no cifra la base; para cifrado en reposo usar el cifrado de disco o
  `pgcrypto` para columnas concretas.
- Los respaldos siguen conteniendo datos personales: deben cifrarse.
- El usuario de la aplicación no debe ser superusuario.
