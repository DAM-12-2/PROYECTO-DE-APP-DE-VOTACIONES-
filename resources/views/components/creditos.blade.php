<div {{ $attributes->merge(['class' => 'text-center text-[11px] leading-relaxed text-on-surface-variant/80']) }}>
    Desarrollado por
    <span class="font-semibold text-on-surface-variant">Jefferson G.</span>
    y
    <span class="font-semibold text-on-surface-variant">Sahir M.</span>
    <span class="mx-1 opacity-60">·</span>
    CTP AIRA {{ date('Y') }}
    <span class="mx-1 opacity-60">·</span>
    <a href="{{ route('acerca') }}" class="underline decoration-dotted underline-offset-2 hover:text-primary">Acerca de</a>
</div>
