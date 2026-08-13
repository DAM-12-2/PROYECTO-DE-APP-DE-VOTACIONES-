const jrv = {
    csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    },

    async buscarEstudiante(identificacion) {
        try {
            const res = await fetch('/jrv/api/buscar?identificacion=' + encodeURIComponent(identificacion));
            return await res.json();
        } catch (error) {
            return { success: false, message: 'No se pudo conectar con el servidor' };
        }
    },

    async activarUrna(idUrna) {
        try {
            const res = await fetch('/jrv/api/activar-urna', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken() },
                body: JSON.stringify({ id: idUrna }),
            });
            return await res.json();
        } catch (error) {
            return { success: false, message: 'No se pudo conectar con el servidor' };
        }
    },

    async desactivarUrna(idUrna) {
        try {
            const res = await fetch('/jrv/api/desactivar-urna', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken() },
                body: JSON.stringify({ id: idUrna }),
            });
            return await res.json();
        } catch (error) {
            return { success: false, message: 'No se pudo conectar con el servidor' };
        }
    },

    async registrarVoto(identificacion, partyId) {
        try {
            const res = await fetch('/jrv/api/votar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken() },
                body: JSON.stringify({ identificacion, party_id: partyId }),
            });
            return await res.json();
        } catch (error) {
            return { success: false, message: 'No se pudo conectar con el servidor' };
        }
    },
};

window.jrv = jrv;
