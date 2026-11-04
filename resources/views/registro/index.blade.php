@extends('layouts.registro')

@section('content')
<div class="rounded-2xl border border-outline bg-white p-6 shadow-sm sm:p-10">

    <section id="pantalla-identificacion" class="pantalla">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-container text-white">
                <span class="material-symbols-outlined text-3xl">badge</span>
            </div>
            <h1 class="text-2xl font-bold sm:text-3xl">Registro de votante</h1>
            <p class="mt-2 text-on-surface-variant">Ingrese su identificación para solicitar el voto.</p>
        </div>

        <form id="registro-form" class="mx-auto max-w-md" novalidate>
            <label for="identificacion" class="mb-2 block text-sm font-semibold">Número de identificación</label>
            <input id="identificacion" name="identificacion" inputmode="numeric" autocomplete="off" autofocus
                placeholder="Ej. 1000001"
                class="w-full rounded-xl border-2 border-outline bg-surface px-4 py-4 text-center text-2xl font-semibold tracking-wider outline-none transition focus:border-primary focus:bg-white">

            <div id="mensaje" class="mt-4 hidden rounded-lg border px-4 py-3 text-sm"></div>

            <button type="submit" id="btn-enviar"
                class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-4 text-lg font-semibold text-white transition hover:bg-primary-container">
                Solicitar voto
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </form>
    </section>

    <section id="pantalla-espera" class="hidden pantalla">
        <div class="mx-auto max-w-md py-6 text-center">
            <div class="relative mx-auto mb-6 flex h-20 w-20 items-center justify-center">
                <span class="pulse-ring absolute inset-0 rounded-full bg-amber-300"></span>
                <span class="relative flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                    <span class="material-symbols-outlined text-4xl">hourglass_top</span>
                </span>
            </div>
            <h2 class="text-2xl font-bold">Solicitud enviada</h2>
            <p id="espera-nombre" class="mt-2 text-on-surface-variant"></p>
            <p class="mt-4 text-sm text-on-surface-variant">La Junta Receptora de Votos está revisando su solicitud.</p>
        </div>
    </section>

    <section id="pantalla-aprobada" class="hidden pantalla">
        <div class="mx-auto max-w-md py-6 text-center">
            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <span class="material-symbols-outlined text-5xl">check_circle</span>
            </div>
            <h2 class="text-3xl font-bold">Solicitud aprobada</h2>
            <p id="aprobada-nombre" class="mt-2 text-on-surface-variant"></p>
            <div class="mt-6 rounded-xl border-2 border-emerald-200 bg-emerald-50 px-6 py-5">
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Pase a la urna</p>
                <p id="aprobada-urna" class="mt-1 text-4xl font-bold text-emerald-900">—</p>
            </div>
            <p class="mt-4 text-sm text-on-surface-variant">Preséntese con su identificación en la urna indicada.</p>
        </div>
    </section>

    <section id="pantalla-votada" class="hidden pantalla">
        <div class="mx-auto max-w-md py-6 text-center">
            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-primary/10 text-primary">
                <span class="material-symbols-outlined text-5xl">how_to_vote</span>
            </div>
            <h2 class="text-3xl font-bold">Voto registrado</h2>
            <p class="mt-2 text-on-surface-variant">Gracias por participar. Puede retirarse.</p>
            <button type="button" id="btn-siguiente"
                class="mt-8 rounded-xl bg-primary px-6 py-3 font-semibold text-white hover:bg-primary-container">
                Registrar siguiente votante
            </button>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registro-form');
    const input = document.getElementById('identificacion');
    const mensaje = document.getElementById('mensaje');
    const pantallas = ['pantalla-identificacion', 'pantalla-espera', 'pantalla-aprobada', 'pantalla-votada'].map(id => document.getElementById(id));

    let solicitudId = null;
    let intervalo = null;

    const mostrar = (id) => {
        pantallas.forEach(p => p.classList.add('hidden'));
        document.getElementById(id).classList.remove('hidden');
    };

    const aviso = (texto, tipo = 'error') => {
        mensaje.textContent = texto;
        mensaje.className = 'mt-4 rounded-lg border px-4 py-3 text-sm ' + (
            tipo === 'success'
                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                : 'border-red-200 bg-red-50 text-red-700'
        );
    };

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const identificacion = input.value.trim();

        if (identificacion.length < 5) {
            aviso('Ingrese una identificación válida.');
            return;
        }

        const boton = document.getElementById('btn-enviar');
        boton.disabled = true;

        try {
            const res = await fetch('/registro/solicitud', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({ identificacion }),
            });
            const data = await res.json();

            if (!data.success) {
                aviso(data.message || 'No se pudo registrar la solicitud.');
                return;
            }

            solicitudId = data.data.id;
            document.getElementById('espera-nombre').textContent = data.data.estudiante.nombre;
            mostrar('pantalla-espera');
            consultar();
            intervalo = setInterval(consultar, 2000);
        } catch (error) {
            aviso('No se pudo conectar con el servidor.');
        } finally {
            boton.disabled = false;
        }
    });

    async function consultar() {
        if (!solicitudId) return;

        try {
            const res = await fetch(`/registro/solicitud/${solicitudId}/estado`, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!data.success) return;

            const s = data.data;
            document.getElementById('aprobada-nombre').textContent = s.estudiante.nombre;

            if (s.estado === 'aprobada') {
                document.getElementById('aprobada-urna').textContent = s.urna?.codigo ?? '—';
                mostrar('pantalla-aprobada');
            } else if (s.estado === 'votada') {
                detener();
                mostrar('pantalla-votada');
            } else {
                mostrar('pantalla-espera');
            }
        } catch (error) {
            // reintento en el siguiente ciclo
        }
    }

    function detener() {
        if (intervalo) {
            clearInterval(intervalo);
            intervalo = null;
        }
    }

    document.getElementById('btn-siguiente').addEventListener('click', () => {
        detener();
        solicitudId = null;
        form.reset();
        mensaje.classList.add('hidden');
        mostrar('pantalla-identificacion');
        input.focus();
    });

    document.addEventListener('rea:solicitud-aprobada', () => consultar());
    document.addEventListener('rea:voto-emitido', () => consultar());
});
</script>
@endsection
