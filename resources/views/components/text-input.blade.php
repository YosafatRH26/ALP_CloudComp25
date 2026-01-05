@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full rounded-lg border border-slate-600 bg-slate-950/50 px-4 py-3 text-slate-100 placeholder-slate-500 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed']) !!}>
