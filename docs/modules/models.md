---
title: Modelos de Datos
keywords: datos, esquemas, sqlite, mysql
size: 1.5KB
---

# Modelos de Datos

No hay backend ni base de datos implementada. Los modelos propuestos seg\u00fan la UI:

## Estudiante
- C\u00e9dula (ID \u00fanico)
- Nombre completo
- Secci\u00f3n (ej: 7-1, 10-2, Plan Nacional A)

## Tribunal Estudiantil
- Presidente(a) (ID)
- Secretario(a) (ID)
- Vocal 1 (ID)
- Vocal 2 (ID)

## Partido / Agrupaci\u00f3n
- Nombre
- Siglas
- Bandera/Foto
- Presidente(a) (ID)
- Candidatos por puesto

## JRV (Mesa)
- ID de mesa
- 4 urnas electr\u00f3nicas (ID \u00fanico correlativo)
- Secciones asignadas

## Votaci\u00f3n
- Estado (abierta/cerrada)
- Rango horario permitido
- Votos emitidos

## Estructura Acad\u00e9mica
- Tercer Ciclo: 7-1 a 9-4
- Educaci\u00f3n Diversificada: 10-1 a 12-4
- Programas Especiales: Plan Nacional A, Plan Nacional B
