const jrv = {
    csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    },

    estudianteSeleccionado: null,
    urnasActuales: [],

    async request(url, options = {}) {
        try {
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken(),
                },
                ...options,
            });
            return await res.json();
        } catch (error) {
            return { success: false, message: 'No se pudo conectar con el servidor' };
        }
    },

    async buscar(identificacion) {
        return this.request('/jrv/api/buscar?identificacion=' + encodeURIComponent(identificacion));
    },

    async urnas() {
        return this.request('/jrv/api/urnas');
    },

    async cola() {
        return this.request('/jrv/api/cola');
    },

    async aprobar(id, urnaId) {
        return this.request(`/jrv/api/solicitud/${id}/aprobar`, {
            method: 'POST',
            body: JSON.stringify({ urna_id: urnaId }),
        });
    },

    async rechazar(id) {
        return this.request(`/jrv/api/solicitud/${id}/rechazar`, { method: 'POST' });
    },

    async asignar(urnaId, identificacion) {
        return this.request('/jrv/api/asignar', {
            method: 'POST',
            body: JSON.stringify({ urna_id: urnaId, identificacion }),
        });
    },

    async liberar(urnaId) {
        return this.request('/jrv/api/liberar', {
            method: 'POST',
            body: JSON.stringify({ urna_id: urnaId }),
        });
    },

    mensaje(texto, tipo = 'error') {
        const contenedor = document.getElementById('jrv-mensaje');
        if (!contenedor) return;
        contenedor.textContent = texto;
        contenedor.className = 'rounded-lg border px-4 py-3 text-sm ' + (
            tipo === 'success'
                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                : tipo === 'warning'
                    ? 'border-amber-200 bg-amber-50 text-amber-800'
                    : 'border-red-200 bg-red-50 text-red-700'
        );
        contenedor.classList.remove('hidden');
        setTimeout(() => contenedor.classList.add('hidden'), 5000);
    },

    renderEstudiante(estudiante) {
        const contenedor = document.getElementById('resultado-estudiante');
        if (!contenedor) return;

        if (!estudiante) {
            contenedor.innerHTML = '';
            contenedor.classList.add('hidden');
            return;
        }

        contenedor.classList.remove('hidden');
        contenedor.innerHTML = `
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Estudiante</p>
                    <p class="font-headline-sm text-headline-sm text-primary">${estudiante.nombre} ${estudiante.apellidos ?? ''}</p>
                    <p class="font-body-md text-body-md text-on-surface-variant">${estudiante.identificacion} · ${estudiante.seccion ?? ''}</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold ${estudiante.voto ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'}">
                    ${estudiante.voto ? 'Ya votó' : 'Habilitado'}
                </span>
            </div>
        `;
    },

    renderUrnas(urnas) {
        const contenedor = document.getElementById('lista-urnas');
        if (!contenedor) return;

        this.urnasActuales = urnas;

        if (!urnas.length) {
            contenedor.innerHTML = '<p class="text-sm text-on-surface-variant">No hay urnas asignadas a esta mesa.</p>';
            return;
        }

        contenedor.innerHTML = urnas.map((urna) => {
            const ocupada = !!urna.estudiante;
            return `
                <div class="rounded-xl border-2 ${ocupada ? 'border-amber-300 bg-amber-50/40' : 'border-outline-variant bg-surface-container-lowest'} p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-headline-sm text-headline-sm text-primary">${urna.codigo}</p>
                            <p class="text-xs font-semibold uppercase tracking-wide ${ocupada ? 'text-amber-700' : 'text-emerald-700'}">
                                ${ocupada ? 'Ocupada' : 'Disponible'}
                            </p>
                        </div>
                        <span class="material-symbols-outlined ${ocupada ? 'text-amber-600' : 'text-emerald-600'}">
                            ${ocupada ? 'person' : 'check_circle'}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-on-surface-variant min-h-[20px]">
                        ${ocupada ? urna.estudiante.nombre + ' · ' + urna.estudiante.identificacion : 'Sin estudiante asignado'}
                    </p>
                    <div class="mt-3 flex gap-2">
                        ${ocupada
                            ? `<button type="button" data-liberar="${urna.id}" class="w-full rounded-lg border border-outline px-3 py-2 text-sm font-semibold hover:border-red-400 hover:text-red-600">Liberar</button>`
                            : `<button type="button" data-asignar="${urna.id}" class="w-full rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-on-primary hover:bg-primary-container">Asignar estudiante</button>`
                        }
                    </div>
                </div>
            `;
        }).join('');

        contenedor.querySelectorAll('[data-asignar]').forEach((boton) => {
            boton.addEventListener('click', () => jrv.asignarSeleccionado(Number(boton.dataset.asignar)));
        });

        contenedor.querySelectorAll('[data-liberar]').forEach((boton) => {
            boton.addEventListener('click', () => jrv.liberarUrna(Number(boton.dataset.liberar)));
        });
    },

    renderCola(solicitudes) {
        const contenedor = document.getElementById('cola-solicitudes');
        if (!contenedor) return;

        const libre = this.urnasActuales.find(u => !u.estudiante);

        if (!solicitudes.length) {
            contenedor.innerHTML = '<p class="text-sm text-on-surface-variant">No hay solicitudes pendientes.</p>';
            return;
        }

        contenedor.innerHTML = solicitudes.map((s) => `
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-primary">${s.estudiante.nombre}</p>
                        <p class="text-xs text-on-surface-variant">${s.estudiante.identificacion} · ${s.estudiante.seccion ?? ''}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold ${s.estado === 'aprobada' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'}">
                        ${s.estado}
                    </span>
                </div>
                ${s.estado === 'pendiente' ? `
                    <div class="mt-3 flex gap-2">
                        <button type="button" data-aprobar="${s.id}"
                            class="flex-1 rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-on-primary hover:bg-primary-container ${libre ? '' : 'opacity-50 cursor-not-allowed'}"
                            ${libre ? '' : 'disabled'}>
                            ${libre ? `Aprobar en ${libre.codigo}` : 'Sin urna libre'}
                        </button>
                        <button type="button" data-rechazar="${s.id}"
                            class="rounded-lg border border-outline px-3 py-2 text-sm font-semibold hover:border-red-400 hover:text-red-600">
                            Rechazar
                        </button>
                    </div>
                ` : `
                    <p class="mt-3 text-sm text-emerald-700">Aprobada · urna ${s.urna?.codigo ?? ''}</p>
                `}
            </div>
        `).join('');

        contenedor.querySelectorAll('[data-aprobar]').forEach((boton) => {
            boton.addEventListener('click', () => jrv.aprobarSolicitud(Number(boton.dataset.aprobar)));
        });

        contenedor.querySelectorAll('[data-rechazar]').forEach((boton) => {
            boton.addEventListener('click', () => jrv.rechazarSolicitud(Number(boton.dataset.rechazar)));
        });
    },

    async aprobarSolicitud(id) {
        const libre = this.urnasActuales.find(u => !u.estudiante);
        if (!libre) {
            this.mensaje('No hay urnas libres.', 'warning');
            return;
        }

        const resultado = await this.aprobar(id, libre.id);
        this.mensaje(resultado.message || 'Solicitud procesada.', resultado.success ? 'success' : 'error');
        await this.cargarTodo();
    },

    async rechazarSolicitud(id) {
        const resultado = await this.rechazar(id);
        this.mensaje(resultado.message || 'Solicitud rechazada.', resultado.success ? 'success' : 'error');
        await this.cargarTodo();
    },

    async asignarSeleccionado(urnaId) {
        if (!this.estudianteSeleccionado) {
            this.mensaje('Primero busque y seleccione un estudiante.', 'warning');
            return;
        }

        const resultado = await this.asignar(urnaId, this.estudianteSeleccionado.identificacion);

        if (resultado.success) {
            this.mensaje(resultado.message, 'success');
            this.estudianteSeleccionado = null;
            this.renderEstudiante(null);
        } else {
            this.mensaje(resultado.message || 'No se pudo asignar el estudiante.');
        }

        await this.cargarTodo();
    },

    async liberarUrna(urnaId) {
        const resultado = await this.liberar(urnaId);
        this.mensaje(resultado.message || 'Urna liberada.', resultado.success ? 'success' : 'error');
        await this.cargarTodo();
    },

    async cargarUrnas() {
        const resultado = await this.urnas();
        if (resultado.success) {
            this.renderUrnas(resultado.data);
        }
    },

    async cargarTodo() {
        const urnas = await this.urnas();
        if (urnas.success) {
            this.renderUrnas(urnas.data);
        }

        const cola = await this.cola();
        if (cola.success) {
            this.renderCola(cola.data);
        }
    },

    async seleccionar(identificacion) {
        const resultado = await this.buscar(identificacion);

        if (!resultado.success) {
            this.estudianteSeleccionado = null;
            this.renderEstudiante(null);
            this.mensaje(resultado.message || 'Estudiante no encontrado.');
            return;
        }

        this.estudianteSeleccionado = resultado.data[0];
        this.renderEstudiante(this.estudianteSeleccionado);
    },
};

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('buscar-form');
    const input = document.getElementById('buscar-input');

    if (form) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const valor = input.value.trim();
            if (valor.length < 5) {
                jrv.mensaje('Ingrese una identificación válida.', 'warning');
                return;
            }
            jrv.seleccionar(valor);
        });
    }

    if (document.getElementById('lista-urnas')) {
        jrv.cargarTodo();
        setInterval(() => jrv.cargarTodo(), 3000);
    }

    document.addEventListener('rea:solicitud-creada', () => jrv.cargarTodo());
    document.addEventListener('rea:solicitud-aprobada', () => jrv.cargarTodo());
    document.addEventListener('rea:voto-emitido', () => jrv.cargarTodo());
});

window.jrv = jrv;
