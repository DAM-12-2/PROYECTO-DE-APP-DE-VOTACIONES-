@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
])

<div class="space-y-1">
    <label class="block font-label-lg text-label-lg text-on-surface mb-1" for="{{ $name }}">
        {{ $label }}
    </label>
    <input
        class="w-full px-3 py-2 bg-surface rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-on-surface transition-all"
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
    />
    @error($name)
        <p class="text-xs text-error mt-1">{{ $message }}</p>
    @enderror
</div>
