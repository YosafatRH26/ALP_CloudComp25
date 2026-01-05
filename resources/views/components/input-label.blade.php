@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-slate-200 mb-2']) }}>
    {{ $value ?? $slot }}
</label>