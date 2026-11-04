@extends('layouts.voting')

@section('content')

<div class="bg-gray-800 rounded-2xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <div class="text-5xl mb-4">🗳️</div>
        <h1 class="text-3xl font-bold"> Votación Estudiantil</h1>
        <p class="text-gray-400 mt-2">Sistema de votación estudiantil</p>
    </div>
    <section id="pantalla-identificacion">
        <h2 class="text-xl font-semibold mb-2">Identificación del estudiante</h2>
        <p class="text-gray-400 mb-6">Ingrese su número de identificación para continuar.</p>
        <form id="student-form">
            <div class="mb-5">
                <label for="identificacion" class="block text-sm font-medium mb-2">Número de identificación</label>
                <input
                    type="text"
                    id="identificacion"
                    name="identificacion"
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                    placeholder="Ingrese su identificación"
                    autocomplete="off">
            </div>
            <div
                id="mensaje-identificacion"
                class="hidden mb-5 p-3 rounded-lg text-sm">
            </div>
            <button
                type="submit"
                id="btn-continuar"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">
                Continuar
            </button>
        </form>
    </section>
    <section
        id="pantalla-votacion"
        class="hidden">
        <h2 class="text-2xl font-bold mb-2">
            Seleccione su opción</h2>
        <p class="text-gray-400 mb-6"> Seleccione una de las opciones disponibles.</p>
       <div
            id="lista-candidatos"
                class="space-y-4"
        >
                <!-- JavaScript colocará aquí los partidos -->
        </div>
        <div
            id="mensaje-votacion"
            class="hidden mt-5 p-3 rounded-lg text-sm">
        </div>
        <button
            type="button"
            id="btn-confirmar"
            class="w-full mt-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold transition">
            Confirmar voto
        </button>
    </section>
    <section
        id="pantalla-confirmacion"
        class="hidden">
        <div class="text-center">
            <div class="text-5xl mb-4">⚠️</div>
            <h2 class="text-2xl font-bold mb-4">Confirmar voto</h2>
            <p class="text-gray-400 mb-6">
                Está a punto de registrar su voto.
                Esta acción no podrá deshacerse.
            </p>
            <div
                id="opcion-confirmacion"
                class="bg-gray-700 rounded-lg p-4 mb-6">
            </div>
            <div class="flex gap-4">
                <button
                    type="button"
                    id="btn-volver"
                    class="w-1/2 py-3 bg-gray-600 hover:bg-gray-500 rounded-lg font-semibold transition">Volver</button>
                <button
                    type="button"
                    id="btn-enviar-voto"
                    class="w-1/2 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold transition">Confirmar voto</button>
            </div>
        </div>
    </section>
    <section id="pantalla-exito" class="hidden">
        <div class="text-center">
            <div class="text-6xl mb-4">✅</div>
            <h2 class="text-3xl font-bold mb-4">¡Voto registrado!</h2>
            <p class="text-gray-400">Su voto ha sido registrado correctamente.</p>
            <p class="text-gray-500 text-sm mt-4">Gracias por participar en la votación.</p>
        </div>
    </section>
</div>
@endsection