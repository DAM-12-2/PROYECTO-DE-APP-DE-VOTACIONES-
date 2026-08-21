document.addEventListener('DOMContentLoaded', () => {

    const studentForm =
        document.getElementById('student-form');

    const identificacionInput =
        document.getElementById('identificacion');

    const mensajeIdentificacion =
        document.getElementById('mensaje-identificacion');

    const mensajeVotacion =
        document.getElementById('mensaje-votacion');

    const listaCandidatos =
        document.getElementById('lista-candidatos');

    const opcionConfirmacion =
        document.getElementById('opcion-confirmacion');

    const btnConfirmar =
        document.getElementById('btn-confirmar');

    const btnVolver =
        document.getElementById('btn-volver');

    const btnEnviarVoto =
        document.getElementById('btn-enviar-voto');

    const pantallaIdentificacion =
        document.getElementById('pantalla-identificacion');

    const pantallaVotacion =
        document.getElementById('pantalla-votacion');

    const pantallaConfirmacion =
        document.getElementById('pantalla-confirmacion');

    const pantallaExito =
        document.getElementById('pantalla-exito');

    let identificacionActual = '';
    let partidoSeleccionado = null;


    async function cargarPartidos() {
    try {
        const response = await fetch(
            '/jrv/api/partidos',
            {
                headers: {'Accept': 'application/json'}
            }
        );
        const data = await response.json();
        if (!response.ok || !data.success) {
            throw new Error(data.message ||'No fue posible cargar los partidos.');
        }
        return data.data;
    } catch (error) {
        console.error('Error al cargar partidos:', error);
        return [];
    }
}
    async function buscarEstudiante(identificacion) {
    try {
        const response = await fetch(
            '/jrv/api/buscar?identificacion=' +
            encodeURIComponent(identificacion),
            {headers: {'Accept': 'application/json'}
            }
        );
        const data = await response.json();
        if (!response.ok || !data.success) {
            return {
                success: false,
                message:
                    data.message ||
                    'No fue posible encontrar al estudiante.'
            };
        }
        return {
            success: true,
            estudiante: data.data[0]
        };

    } catch (error) {
        console.error('Error al buscar estudiante:',error);
        return {
            success: false,
            message:
                'No se pudo conectar con el servidor.'
        };
    }
}

    function mostrarMensaje(
        elemento,
        mensaje,
        tipo = 'error'
    ) {

        elemento.textContent = mensaje;

        elemento.classList.remove(
            'hidden',
            'bg-red-900',
            'text-red-300',
            'bg-green-900',
            'text-green-300',
            'bg-yellow-900',
            'text-yellow-300'
        );


        if (tipo === 'success') {

            elemento.classList.add(
                'bg-green-900',
                'text-green-300'
            );

        } else if (tipo === 'warning') {

            elemento.classList.add(
                'bg-yellow-900',
                'text-yellow-300'
            );

        } else {

            elemento.classList.add(
                'bg-red-900',
                'text-red-300'
            );
        }
    }
    function ocultarMensaje(elemento) {

        elemento.classList.add('hidden');

        elemento.textContent = '';
    }

    function mostrarPantalla(pantalla) {

        pantallaIdentificacion.classList.add('hidden');

        pantallaVotacion.classList.add('hidden');

        pantallaConfirmacion.classList.add('hidden');

        pantallaExito.classList.add('hidden');


        pantalla.classList.remove('hidden');
    }

    function mostrarPartidos(partidos) {
    listaCandidatos.innerHTML = '';

    partidos.forEach((partido) => {

        const opcion =
            document.createElement('label');
        opcion.className =
            'block bg-gray-700 border border-gray-600 rounded-xl p-5 cursor-pointer hover:border-blue-500 transition';
        opcion.innerHTML = `
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-lg">
                        ${partido.nombre}
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                        ${partido.siglas ?? ''}
                    </p>
                </div>
                <input
                    type="radio"
                    name="partido"
                    value="${partido.id}"
                    data-nombre="${partido.nombre}"
                    class="w-5 h-5"
                >
            </div>
        `;
        listaCandidatos.appendChild(opcion);
    });
}

    studentForm.addEventListener(
    'submit',
    async (event) => {
        event.preventDefault();
        const identificacion =
            identificacionInput.value.trim();

        if (identificacion === '') {
            mostrarMensaje(
                mensajeIdentificacion,
                'Debe ingresar su número de identificación.',
                'error'
            );
            identificacionInput.focus();
            return;
        }
        if (identificacion.length < 5) {
            mostrarMensaje(
                mensajeIdentificacion,
                'La identificación ingresada no parece válida.',
                'error'
            );
            identificacionInput.focus();
            return;
        }
        ocultarMensaje(mensajeIdentificacion);

        const resultado =
            await buscarEstudiante(identificacion);
        if (!resultado.success) {
            mostrarMensaje(
                mensajeIdentificacion,
                resultado.message,
                'error'
            );
            return;
        }
        const estudiante =
            resultado.estudiante;
        if (estudiante.voto) {
            mostrarMensaje(
                mensajeIdentificacion,
                'Este estudiante ya realizó su voto.',
                'warning'
            );
            return;
        }
        identificacionActual =
            estudiante.identificacion;
        const partidos =
            await cargarPartidos();
        if (partidos.length === 0) {
            mostrarMensaje(
                mensajeIdentificacion,
                'No hay partidos disponibles para votar.',
                'warning'
            );
            return;
        }
        mostrarPartidos(partidos);
        mostrarPantalla(pantallaVotacion);
    }
);
    btnConfirmar.addEventListener(
        'click',
        () => {
            const radioSeleccionado =
                document.querySelector(
                    'input[name="partido"]:checked'
                );

            if (!radioSeleccionado) {
                mostrarMensaje(
                    mensajeVotacion,
                    'Debe seleccionar un partido antes de continuar.',
                    'error'
                );
                return;
            }
            ocultarMensaje(
                mensajeVotacion
            );
            // Guardamos el partido elegido.
            partidoSeleccionado = {

                id: Number(
                    radioSeleccionado.value
                ),

                nombre:
                    radioSeleccionado.dataset.nombre

            };
            // Mostramos al usuario
            // qué opción seleccionó.
            opcionConfirmacion.innerHTML = `
                <p class="text-gray-400 text-sm">
                    Usted seleccionó:
                </p>
                <p class="text-xl font-bold mt-2">
                    ${partidoSeleccionado.nombre}
                </p>
            `;
            mostrarPantalla(
                pantallaConfirmacion
            );
        }
    );
    btnVolver.addEventListener(
        'click',
        () => {
            mostrarPantalla(
                pantallaVotacion
            );
        }
    );
    btnEnviarVoto.addEventListener(
        'click',
        async () => {
            if (!partidoSeleccionado) {
                mostrarPantalla(
                    pantallaVotacion
                );
                mostrarMensaje(
                    mensajeVotacion,
                    'Debe seleccionar un partido.',
                    'error'
                );
                return;
            }
            btnEnviarVoto.disabled = true;
            btnEnviarVoto.textContent =
                'Registrando voto...';
            try {
                await registrarVoto();
            } finally {
                btnEnviarVoto.disabled = false;
                btnEnviarVoto.textContent = 'Confirmar voto';
            }
        }
    );
    async function registrarVoto() {
        const csrfMeta =
            document.querySelector(
                'meta[name="csrf-token"]'
            );
        if (!csrfMeta) {
            alert(
                'No se encontró el token de seguridad.'
            );
            return;
        }
        const csrfToken =
            csrfMeta.getAttribute('content');
        try {
            const response = await fetch(
                '/jrv/api/votar',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type':
                            'application/json',
                        'Accept':
                            'application/json',
                        'X-CSRF-TOKEN':
                            csrfToken
                    },
                    body: JSON.stringify({
                        identificacion:
                            identificacionActual,
                        party_id:
                            partidoSeleccionado.id
                    })
                }
            );
            let data;
            try {
                data = await response.json();
            } catch {
                throw new Error(
                    'El servidor devolvió una respuesta inválida.'
                );
            }
            if (response.status === 201) {
                mostrarPantalla(
                    pantallaExito
                );
                setTimeout(
                    reiniciarKiosco,
                    4000
                );
                return;
            }

            if (response.status === 404) {
                mostrarPantalla(
                    pantallaIdentificacion
                );
                mostrarMensaje(
                    mensajeIdentificacion,
                    data.message ||
                        'Estudiante no encontrado.',
                    'error'
                );
                return;
            }
            if (response.status === 409) {

                mostrarPantalla(
                    pantallaIdentificacion
                );
                mostrarMensaje(
                    mensajeIdentificacion,
                    data.message ||
                        'Este estudiante ya realizó su voto.',
                    'warning'
                );
                return;
            }
            if (response.status === 400) {
                mostrarPantalla(
                    pantallaIdentificacion);
                mostrarMensaje(
                    mensajeIdentificacion,
                    data.message ||
                        'No hay una urna activa.',
                    'warning' );
                return;
            }
            if (response.status === 422) {
                let mensaje =
                    data.message ||
                    'Los datos enviados no son válidos.';
                if (data.errors) {
                    const errores =
                        Object.values(
                            data.errors
                        ).flat();

                    if (errores.length > 0) {

                        mensaje = errores[0];
                    }
                }
                mostrarPantalla(
                    pantallaIdentificacion
                );
                mostrarMensaje(
                    mensajeIdentificacion,
                    mensaje,
                    'error'
                );
                return;
            }
            mostrarPantalla(
                pantallaIdentificacion
            );
            mostrarMensaje(
                mensajeIdentificacion,
                data.message ||
                    'No fue posible registrar el voto.',
                'error'
            );
        } catch (error) {
            console.error(
                'Error al registrar voto:',
                error
            );
            mostrarPantalla(
                pantallaIdentificacion
            );
            mostrarMensaje(
                mensajeIdentificacion,
                'No se pudo conectar con el servidor.',
                'error'
            );
        }
    }
    function reiniciarKiosco() {
        identificacionActual = '';
        partidoSeleccionado = null;
        studentForm.reset();
        listaCandidatos.innerHTML = '';
        opcionConfirmacion.innerHTML = '';
        ocultarMensaje(
            mensajeIdentificacion
        );
        ocultarMensaje(
            mensajeVotacion
        );
        mostrarPantalla(
            pantallaIdentificacion
        );
        identificacionInput.focus();
    }
});