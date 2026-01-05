@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-sky-400 text-start text-base font-medium text-sky-400 bg-sky-900/20 focus:outline-none focus:text-sky-300 focus:bg-sky-900/30 focus:border-sky-500 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800 hover:border-slate-600 focus:outline-none focus:text-slate-200 focus:bg-slate-800 focus:border-slate-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>