const jrv = {
    csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    },

    estudianteSeleccionado: null,

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

        await this.cargarUrnas();
    },

    async liberarUrna(urnaId) {
        const resultado = await this.liberar(urnaId);
        this.mensaje(resultado.message || 'Urna liberada.', resultado.success ? 'success' : 'error');
        await this.cargarUrnas();
    },

    async cargarUrnas() {
        const resultado = await this.urnas();
        if (resultado.success) {
            this.renderUrnas(resultado.data);
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
        jrv.cargarUrnas();
        setInterval(() => jrv.cargarUrnas(), 4000);
    }
});

window.jrv = jrv;
