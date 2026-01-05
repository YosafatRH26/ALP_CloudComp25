@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'mb-6 rounded-xl border border-emerald-500/40 bg-emerald-900/20 px-4 py-3 text-sm text-emerald-100']) }}>
        {{ $status }}
    </div>
@endif