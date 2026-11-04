<div id="app-splash" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center gap-2 bg-surface transition-opacity duration-500">
    <img src="/img/logo.png" alt="CTP AIRA" class="h-20 w-20 rounded-full border border-outline-variant object-cover shadow-sm">
    <p class="mt-2 text-lg font-bold text-primary">Sistema Electoral</p>
    <p class="text-xs text-on-surface-variant">CTP AIRA · Pedregoso</p>

    <div class="mt-3 h-1.5 w-44 overflow-hidden rounded-full bg-outline-variant/50">
        <div class="splash-bar h-full w-1/3 rounded-full bg-primary"></div>
    </div>

    <p class="mt-3 text-[11px] text-on-surface-variant/80">Jefferson G. · Sahir M.</p>
</div>
<style>
    @keyframes splash-slide {
        0% { transform: translateX(-120%); }
        100% { transform: translateX(320%); }
    }
    .splash-bar { animation: splash-slide 1.1s ease-in-out infinite; }
</style>
<script>
(function () {
    const ocultar = () => {
        const splash = document.getElementById('app-splash');
        if (!splash) return;
        splash.style.opacity = '0';
        setTimeout(() => splash.remove(), 500);
    };

    window.addEventListener('load', () => setTimeout(ocultar, 450));
    setTimeout(ocultar, 4000);
})();
</script>
