-- Row Level Security para PostgreSQL
-- Aplicar después de `php artisan migrate` con DB_CONNECTION=pgsql.
-- El contexto se establece por transacción con set_config('app.role'|'app.user_id'|'app.mesa_id', ..., true).

ALTER TABLE students ENABLE ROW LEVEL SECURITY;
ALTER TABLE students FORCE ROW LEVEL SECURITY;
ALTER TABLE votes ENABLE ROW LEVEL SECURITY;
ALTER TABLE votes FORCE ROW LEVEL SECURITY;
ALTER TABLE urnas ENABLE ROW LEVEL SECURITY;
ALTER TABLE urnas FORCE ROW LEVEL SECURITY;
ALTER TABLE vote_requests ENABLE ROW LEVEL SECURITY;
ALTER TABLE vote_requests FORCE ROW LEVEL SECURITY;
ALTER TABLE bitacoras ENABLE ROW LEVEL SECURITY;
ALTER TABLE bitacoras FORCE ROW LEVEL SECURITY;

CREATE OR REPLACE FUNCTION app_role() RETURNS text LANGUAGE sql STABLE AS $$
  SELECT COALESCE(current_setting('app.role', true), 'anon')
$$;

CREATE OR REPLACE FUNCTION app_mesa() RETURNS int LANGUAGE sql STABLE AS $$
  SELECT COALESCE(NULLIF(current_setting('app.mesa_id', true), '')::int, 0)
$$;

CREATE OR REPLACE FUNCTION app_user() RETURNS bigint LANGUAGE sql STABLE AS $$
  SELECT COALESCE(NULLIF(current_setting('app.user_id', true), '')::bigint, 0)
$$;

-- Padrón: admin/TEE ven todo; JRV solo estudiantes de sus secciones.
CREATE POLICY students_select ON students FOR SELECT USING (
  app_role() IN ('admin', 'tee')
  OR EXISTS (
    SELECT 1 FROM secciones_mesa sm
    WHERE sm.seccion = students.seccion AND sm.mesa_id = app_mesa()
  )
);

CREATE POLICY students_write ON students FOR ALL USING (
  app_role() IN ('admin', 'tee')
) WITH CHECK (
  app_role() IN ('admin', 'tee')
);

-- Activación de urna: función acotada que solo busca por código.
-- Debe crearse como superusuario para que pueda leer a través de RLS.
CREATE OR REPLACE FUNCTION urna_por_codigo(p_codigo text)
RETURNS TABLE(id bigint, codigo text, estado int, id_mesa int)
LANGUAGE sql SECURITY DEFINER SET search_path = public
AS $$
  SELECT u.id, u.codigo, u.estado, u.id_mesa FROM urnas u WHERE u.codigo = p_codigo;
$$;
GRANT EXECUTE ON FUNCTION urna_por_codigo(text) TO votaciones;

-- La JRV puede marcar el voto de estudiantes de su mesa (solo la fila).
CREATE POLICY students_update_jrv ON students FOR UPDATE
USING (
  app_role() = 'jrv' AND EXISTS (
    SELECT 1 FROM secciones_mesa sm
    WHERE sm.seccion = students.seccion AND sm.mesa_id = app_mesa()
  )
)
WITH CHECK (
  app_role() = 'jrv' AND EXISTS (
    SELECT 1 FROM secciones_mesa sm
    WHERE sm.seccion = students.seccion AND sm.mesa_id = app_mesa()
  )
);

-- Votos: el voto es secreto; solo admin/TEE leen el conteo cifrado.
CREATE POLICY votes_select ON votes FOR SELECT USING (
  app_role() IN ('admin', 'tee')
);

CREATE POLICY votes_insert ON votes FOR INSERT WITH CHECK (
  app_role() = 'jrv'
);

-- Urnas: cada JRV solo su mesa.
CREATE POLICY urnas_mesa ON urnas FOR ALL USING (
  app_role() IN ('admin', 'tee') OR urnas.id_mesa = app_mesa()
) WITH CHECK (
  app_role() IN ('admin', 'tee') OR urnas.id_mesa = app_mesa()
);

-- Solicitudes: cada JRV solo las de su mesa.
CREATE POLICY vote_requests_mesa ON vote_requests FOR ALL USING (
  app_role() IN ('admin', 'tee') OR vote_requests.mesa_id = app_mesa()
) WITH CHECK (
  app_role() IN ('admin', 'tee') OR vote_requests.mesa_id = app_mesa()
);

-- Bitácora: lectura admin/TEE; escritura de cualquier usuario autenticado.
CREATE POLICY bitacoras_select ON bitacoras FOR SELECT USING (
  app_role() IN ('admin', 'tee')
);
CREATE POLICY bitacoras_insert ON bitacoras FOR INSERT WITH CHECK (
  app_role() <> 'anon'
);
