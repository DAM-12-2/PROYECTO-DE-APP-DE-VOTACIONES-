# Guía sencilla: cómo funciona la conexión entre JavaScript y PHP

## 1. Analogía básica: como en phpMyAdmin, pero con una API

Piensa en phpMyAdmin como una interfaz visual para trabajar con una base de datos. Allí tú abres una URL, eliges una tabla y realizas acciones como ver, insertar o eliminar datos.

En una aplicación web moderna, el flujo es parecido, pero con una arquitectura más organizada:

- JavaScript (frontend) hace una petición a una URL específica.
- Esa URL apunta a un endpoint del backend.
- PHP recibe esa petición y decide qué hacer.
- PHP finalmente interactúa con la base de datos.

En otras palabras:

- JavaScript = la interfaz que ve el usuario.
- PHP = el encargado de procesar la lógica y acceder a la base de datos.
- Base de datos = donde se almacenan los registros.

## 2. ¿Cómo se llaman los archivos PHP desde JavaScript?

Desde JavaScript, normalmente se usan URLs para llamar a archivos o rutas del backend. En Laravel, estas rutas suelen estar definidas en archivos como `routes/web.php` o `routes/api.php`.

La forma más común de hacerlo es con `fetch`:

```javascript
fetch('/api/estudiantes', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    nombre: 'Ana',
    apellido: 'Pérez'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### ¿Qué pasa aquí?

- `fetch()` envía una petición HTTP a una URL.
- El navegador envía los datos al backend.
- PHP recibe esa solicitud y responde.

## 3. ¿Cómo se pasan los datos entre ambos entornos?

Los datos se envían normalmente en formato JSON.

### JSON

JSON es un formato ligero de intercambio de datos. Por ejemplo:

```json
{
  "nombre": "Ana",
  "apellido": "Pérez",
  "identificacion": "12345678"
}
```

### Cómo lo recibe PHP

En PHP, el framework Laravel permite leer esos datos con `$request->input()` o `$request->json()->all()`.

Ejemplo:

```php
public function store(Request $request)
{
    $data = $request->all();
    dd($data);
}
```

O usando JSON explícitamente:

```php
public function store(Request $request)
{
    $data = $request->json()->all();
    return response()->json($data);
}
```

## 4. Ejemplo práctico real: agregar un estudiante

Supongamos que desde el frontend quieres agregar un estudiante a la base de datos.

### Flujo completo

1. El usuario llena un formulario en JavaScript.
2. El código JavaScript prepara los datos.
3. Se envía una petición `POST` a una URL como `/api/estudiantes`.
4. Laravel recibe la petición en un controlador.
5. El controlador guarda los datos con Eloquent.
6. Laravel devuelve una respuesta JSON al frontend.

---

### Ejemplo en JavaScript (Frontend)

```javascript
const estudiante = {
  identificacion: '20240001',
  nombre: 'Carlos',
  apellidos: 'López',
  seccion: 'A',
  voto: false,
  estado: true
};

fetch('/api/estudiantes', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify(estudiante)
})
.then(async response => {
  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.message || 'Error al guardar');
  }

  console.log('Estudiante guardado:', data);
})
.catch(error => {
  console.error(error);
});
```

---

### Ejemplo en PHP (Backend con Laravel)

En Laravel, normalmente este flujo se maneja en un controlador. Por ejemplo:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'identificacion' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'seccion' => 'nullable|string|max:50',
            'voto' => 'nullable|boolean',
            'estado' => 'nullable|boolean',
        ]);

        $estudiante = Student::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Estudiante creado correctamente',
            'data' => $estudiante
        ], 201);
    }
}
```

---

### Ruta en Laravel

La ruta que conecta el frontend con el controlador se define en `routes/api.php` o `routes/web.php`:

```php
use App\Http\Controllers\StudentController;

Route::post('/estudiantes', [StudentController::class, 'store']);
```

---

## 5. Resumen rápido

- JavaScript envía datos a una URL mediante `fetch`.
- Esa URL corresponde a una ruta definida en Laravel.
- PHP recibe la petición en un controlador.
- PHP procesa los datos y los guarda en la base de datos usando Eloquent.
- PHP devuelve una respuesta JSON al frontend.

## 6. Idea clave

La comunicación entre frontend y backend funciona como un intercambio de mensajes:

```text
JavaScript -> URL/Endpoint -> PHP -> Base de datos -> PHP -> JSON -> JavaScript
```

Este patrón es la base de casi todas las aplicaciones web modernas.
