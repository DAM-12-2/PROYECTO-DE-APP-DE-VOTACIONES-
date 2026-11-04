(function () {
    const meta = (name) => {
        const el = document.querySelector(`meta[name="${name}"]`);
        return el ? el.content : '';
    };

    const key = meta('reverb-key');

    if (!key || typeof Echo === 'undefined' || typeof Pusher === 'undefined') {
        return;
    }

    const host = meta('reverb-host') || window.location.hostname;
    const port = Number(meta('reverb-port') || 8080);
    const scheme = meta('reverb-scheme') || 'http';

    try {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key,
            cluster: 'mt1',
            wsHost: host,
            wsPort: port,
            wssPort: port,
            forceTLS: scheme === 'https',
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': meta('csrf-token'),
                },
            },
        });

        const emitir = (nombre, data) => {
            document.dispatchEvent(new CustomEvent(nombre, { detail: data }));
        };

        const mesa = meta('rea-mesa-id');
        if (mesa) {
            window.Echo.private(`mesa.${mesa}`)
                .listen('.solicitud.creada', (e) => emitir('rea:solicitud-creada', e))
                .listen('.solicitud.aprobada', (e) => emitir('rea:solicitud-aprobada', e))
                .listen('.voto.emitido', (e) => emitir('rea:voto-emitido', e));
        }

        const urna = meta('rea-urna-id');
        if (urna) {
            window.Echo.private(`urna.${urna}`)
                .listen('.solicitud.aprobada', (e) => emitir('rea:solicitud-aprobada', e));
        }
    } catch (error) {
        console.warn('WebSocket no disponible; se usará polling.', error);
    }
})();
