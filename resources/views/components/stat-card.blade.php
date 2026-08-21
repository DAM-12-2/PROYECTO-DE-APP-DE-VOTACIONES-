@props([
    'title',
    'value',
    'icon',
])

<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:border-primary transition-colors">
    <div class="w-12 h-12 rounded-full bg-primary-container/10 flex items-center justify-center text-primary-container mb-2">
        <span class="material-symbols-outlined">{{ $icon }}</span>
    </div>
    <div>
        <p class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider">{{ $title }}</p>
        <h3 class="font-headline-lg text-headline-lg text-primary">{{ $value }}</h3>
    </div>
    <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
        <span class="material-symbols-outlined" style="font-size: 120px;">{{ $icon }}</span>
    </div>
</div>
